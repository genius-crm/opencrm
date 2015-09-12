<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
require_once('modules/CloudSync/config.php');
class CloudSync_Detail_View extends Vtiger_Index_View {
	protected $record = false;

	function __construct() {
		parent::__construct();
		$this->exposeMethod('showDetailViewByMode');
		$this->exposeMethod('showModuleDetailView');
		$this->exposeMethod('showModuleSummaryView');
		$this->exposeMethod('showModuleBasicView');
	}

	function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$recordPermission = Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $recordId);
		if(!$recordPermission) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
		return true;
	}

	function preProcess(Vtiger_Request $request, $display=true) {
		parent::preProcess($request, false);

		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		if(!$this->record){
			$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$recordModel = $this->record->getRecord();
		$recordStrucure = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_DETAIL);
		$summaryInfo = array();
		// Take first block information as summary information
		$stucturedValues = $recordStrucure->getStructure();
		foreach($stucturedValues as $blockLabel=>$fieldList) {
			$summaryInfo[$blockLabel] = $fieldList;
			break;
		}

		$detailViewLinkParams = array('MODULE'=>$moduleName,'RECORD'=>$recordId);

		$detailViewLinks = $this->record->getDetailViewLinks($detailViewLinkParams);
		$navigationInfo = ListViewSession::getListViewNavigation($recordId);

		// removing add button from basic action
                if($detailViewLinks)        {
                        foreach($detailViewLinks['DETAILVIEWBASIC'] as $single_basic_link => $singleBasicLink)        {
                                if($singleBasicLink->linklabel == 'LBL_EDIT')
                                        unset($detailViewLinks['DETAILVIEWBASIC'][$single_basic_link]);

                        }
                }
                // code ends here

		$viewer = $this->getViewer($request);
		$viewer->assign('RECORD', $recordModel);
		$viewer->assign('NAVIGATION', $navigationInfo);

		// get sidebar link list
                $sideBarLinks = CloudSyncConfig::$sideBarLinks;
                $sideBarLinks[1]['class'] = 'selectedQuickLink';
                $viewer->assign('sideBarLinks', $sideBarLinks);
                $viewer->assign('moduleName', $moduleName);

		//Intially make the prev and next records as null
		$prevRecordId = null;
		$nextRecordId = null;
		$found = false;
		if ($navigationInfo) {
			foreach($navigationInfo as $page=>$pageInfo) {
				foreach($pageInfo as $index=>$record) {
					//If record found then next record in the interation
					//will be next record
					if($found) {
						$nextRecordId = $record;
						break;
					}
					if($record == $recordId) {
						$found = true;
					}
					//If record not found then we are assiging previousRecordId
					//assuming next record will get matched
					if(!$found) {
						$prevRecordId = $record;
					}
				}
				//if record is found and next record is not calculated we need to perform iteration
				if($found && !empty($nextRecordId)) {
					break;
				}
			}
		}

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if(!empty($prevRecordId)) {
			$viewer->assign('PREVIOUS_RECORD_URL', $moduleModel->getDetailViewUrl($prevRecordId));
		}
		if(!empty($nextRecordId)) {
			$viewer->assign('NEXT_RECORD_URL', $moduleModel->getDetailViewUrl($nextRecordId));
		}

		$viewer->assign('MODULE_MODEL', $this->record->getModule());
		$viewer->assign('DETAILVIEW_LINKS', $detailViewLinks);

		$viewer->assign('IS_EDITABLE', $this->record->getRecord()->isEditable($moduleName));
		$viewer->assign('IS_DELETABLE', $this->record->getRecord()->isDeletable($moduleName));

		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
		$linkModels = $this->record->getSideBarLinks($linkParams);
		$viewer->assign('QUICK_LINKS', $linkModels);
        $viewer->assign('MODULE_NAME', $moduleName);

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$viewer->assign('DEFAULT_RECORD_VIEW', $currentUserModel->get('default_record_view'));

                $picklistDependencyDatasource=  Vtiger_DependencyPicklist::getPicklistDependencyDatasource($moduleName); 
                $viewer->assign('PICKLIST_DEPENDENCY_DATASOURCE',Zend_Json::encode($picklistDependencyDatasource));
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	function preProcessTplName(Vtiger_Request $request) {
		return 'DetailViewPreProcess.tpl';
	}

	function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}

		$currentUserModel = Users_Record_Model::getCurrentUserModel();


		if ($currentUserModel->get('default_record_view') === 'Summary') {
			echo $this->showModuleBasicView($request);
		} else {
			echo $this->showModuleDetailView($request);
		}
	}

	public function postProcess(Vtiger_Request $request) {
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if(!$this->record){
			$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$detailViewLinkParams = array('MODULE'=>$moduleName,'RECORD'=>$recordId);
		$detailViewLinks = $this->record->getDetailViewLinks($detailViewLinkParams);

		$selectedTabLabel = $request->get('tab_label');

		if(empty($selectedTabLabel)) {
            if($currentUserModel->get('default_record_view') === 'Detail') {
                $selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_DETAILS', $moduleName);
            } else{
                if($moduleModel->isSummaryViewSupported()) {
                    $selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_SUMMARY', $moduleName);
                } else {
                    $selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_DETAILS', $moduleName);
                }
            }
        }

		$viewer = $this->getViewer($request);

		$viewer->assign('SELECTED_TAB_LABEL', $selectedTabLabel);
		$viewer->assign('MODULE_MODEL', $this->record->getModule());
		$viewer->assign('DETAILVIEW_LINKS', $detailViewLinks);

		$viewer->view('DetailViewPostProcess.tpl', $moduleName);

		parent::postProcess($request);
	}


	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Vtiger.resources.Detail',
			"modules.$moduleName.resources.Detail",
			'modules.Vtiger.resources.RelatedList',
			"modules.$moduleName.resources.RelatedList",
			'libraries.jquery.jquery_windowmsg',
            		"libraries.jquery.ckeditor.ckeditor",
			"libraries.jquery.ckeditor.adapters.jquery",
			"modules.Emails.resources.MassEdit",
			"modules.Vtiger.resources.CkEditor",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	/**
	 * Function to get Ajax is enabled or not
	 * @param Vtiger_Record_Model record model
	 * @return <boolean> true/false
	 */
	function isAjaxEnabled($recordModel) {
		return $recordModel->isEditable();
	}

	function showDetailViewByMode($request) {
                $requestMode = $request->get('requestMode');
                if($requestMode == 'full') {
                        return $this->showModuleDetailView($request);
                }
                return $this->showModuleBasicView($request);
        }

        /**
         * Function shows the entire detail for the record
         * @param Vtiger_Request $request
         * @return <type>
         */
        function showModuleDetailView(Vtiger_Request $request) {
                $recordId = $request->get('record');
                $moduleName = $request->getModule();

                if(!$this->record){
                	$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
                }

                $recordModel = $this->record->getRecord();
                $recordStrucure = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_DETAIL);
                $structuredValues = $recordStrucure->getStructure();

        	$moduleModel = $recordModel->getModule();

                $viewer = $this->getViewer($request);
                $viewer->assign('RECORD', $recordModel);
                $viewer->assign('RECORD_STRUCTURE', $structuredValues);
        	$viewer->assign('BLOCK_LIST', $moduleModel->getBlocks());
                $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
                $viewer->assign('MODULE_NAME', $moduleName);
                $viewer->assign('IS_AJAX_ENABLED', $this->isAjaxEnabled($recordModel));

                return $viewer->view('DetailViewFullContents.tpl',$moduleName,true);
        }

	/**
         * Function shows basic detail for the record
         * @param <type> $request
         */
	function showModuleBasicView($request) {

                $recordId = $request->get('record');
                $moduleName = $request->getModule();

                if(!$this->record){
                        $this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
                }
                $recordModel = $this->record->getRecord();

                $detailViewLinkParams = array('MODULE'=>$moduleName,'RECORD'=>$recordId);
                $detailViewLinks = $this->record->getDetailViewLinks($detailViewLinkParams);

                $viewer = $this->getViewer($request);
                $viewer->assign('RECORD', $recordModel);
                $viewer->assign('MODULE_SUMMARY', $this->showModuleSummaryView($request));

                $viewer->assign('DETAILVIEW_LINKS', $detailViewLinks);
                $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
                $viewer->assign('IS_AJAX_ENABLED', $this->isAjaxEnabled($recordModel));
                $viewer->assign('MODULE_NAME', $moduleName);

                $recordStrucure = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_DETAIL);
                $structuredValues = $recordStrucure->getStructure();

        	$moduleModel = $recordModel->getModule();

                $viewer->assign('RECORD_STRUCTURE', $structuredValues);
        	$viewer->assign('BLOCK_LIST', $moduleModel->getBlocks());

                echo $viewer->view('DetailViewSummaryContents.tpl', $moduleName, true);
        }

	function showModuleSummaryView($request) {
                $recordId = $request->get('record');
                $moduleName = $request->getModule();

                if(!$this->record){
                        $this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
                }
                $recordModel = $this->record->getRecord();
                $recordStrucure = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_SUMMARY);

        	$moduleModel = $recordModel->getModule();
                $viewer = $this->getViewer($request);
                $viewer->assign('RECORD', $recordModel);
        	$viewer->assign('BLOCK_LIST', $moduleModel->getBlocks());
                $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

                $viewer->assign('MODULE_NAME', $moduleName);
                $viewer->assign('IS_AJAX_ENABLED', $this->isAjaxEnabled($recordModel));
                $viewer->assign('SUMMARY_RECORD_STRUCTURE', $recordStrucure->getStructure());

                return $viewer->view('ModuleSummaryView.tpl', $moduleName, true);
        }
}
