<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class SubscribersList_Edit_View extends Vtiger_Edit_View {
	
	public function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		
		if ($mode) {
			$this->$mode($request);
		} else {
			$this->step2($request);
		}
	}
	
	public function step2(Vtiger_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$record = $request->get('record');
		
        if(!empty($record)) {
            $recordModel = $this->record?$this->record:Vtiger_Record_Model::getInstanceById($record, $moduleName);
            $viewer->assign('RECORD_ID', $record);
            $viewer->assign('MODE', 'edit');
        } else {
            $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
            $viewer->assign('MODE', '');
        }
        if(!$this->record){
            $this->record = $recordModel;
        }
		
		$moduleModel = $recordModel->getModule();
		
		$fieldList = $moduleModel->getFields();
		
		$requestFieldList = array_intersect_key($request->getAll(), $fieldList);

		foreach($requestFieldList as $fieldName=>$fieldValue){
			$fieldModel = $fieldList[$fieldName];
			$specialField = false;
			if($fieldModel->isEditable() || $specialField) {
				$recordModel->set($fieldName, $fieldModel->getDBInsertValue($fieldValue));
			}
		}
		
		$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($moduleName);

		$viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Zend_Json::encode($picklistDependencyDatasource));
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('SEARCHABLE_MODULES', SubscribersList_Module_Model::getSearchableModules());
		//$viewer->assign('SOURCE_MODULE', '');
		//filter
		
		if(!empty($record)) {
			$source_module = $recordModel->get('module_name');
		}else{
			$source_module = $request->get('module_name'); 
		}
			$viewer->assign('SOURCE_MODULE', $source_module);
		if($source_module !=''){
			if($request->get('viewid')) {
				$viewid = $request->get('viewid');
			}else{
				$customView = new CustomView();
				$viewid = $customView->getViewId($source_module);
			}
			
			$sourcemoduleModel = Vtiger_Module_Model::getInstance($source_module);
			$sourcerecordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($sourcemoduleModel);
			
			if(!empty($viewid)) {
				$customViewModel = CustomView_Record_Model::getInstanceById($viewid);
				$viewer->assign('MODE', 'edit');
			} else {
				$customViewModel = new CustomView_Record_Model();
				$customViewModel->setModule($source_module);
				$viewer->assign('MODE', '');
			}

			$viewer->assign('SEARCHABLE_MODULES', EmailCampaigns_Module_Model::getSearchableModules());
			$viewer->assign('CUSTOMVIEW_MODEL', $customViewModel);
		
			if($recordModel->get('conditions') && $request->get('viewid')=='' && $request->get('mode1')!='selectsourmodule') {
				$conditions = $recordModel->get('conditions');
				$conditions = str_replace('&quot;', '"', $conditions);
				$conditions = json_decode($conditions, true);
				$transformedConditions = array();
				if(!empty($conditions)) {
					foreach($conditions as $index => $info) {
						if(!($info['groupid'])) {
							$firstGroup[] = array('columnname' => $info['fieldname'], 'comparator' => $info['operation'], 'value' => $info['value'],
								'column_condition' => $info['joincondition'], 'valuetype' => $info['valuetype'], 'groupid' => $info['groupid']);
						} else {
							$secondGroup[] = array('columnname' => $info['fieldname'], 'comparator' => $info['operation'], 'value' => $info['value'],
								'column_condition' => $info['joincondition'], 'valuetype' => $info['valuetype'], 'groupid' => $info['groupid']);
						}
					}
				}
				$transformedConditions[1] = array('columns'=>$firstGroup);
				$transformedConditions[2] = array('columns'=>$secondGroup);
				$viewer->assign('ADVANCE_CRITERIA', $transformedConditions);
			}else{
				//print_r($customViewModel->transformToNewAdvancedFilter());
				$viewer->assign('ADVANCE_CRITERIA', $customViewModel->transformToNewAdvancedFilter());
			}
			
			
			$viewer->assign('CURRENTDATE', date('Y-n-j'));
			
			$advanceFilterOpsByFieldType = Vtiger_Field_Model::getAdvancedFilterOpsByFieldType();
			$viewer->assign('ADVANCED_FILTER_OPTIONS', Vtiger_Field_Model::getAdvancedFilterOptions());
			$viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', $advanceFilterOpsByFieldType);
			$dateFilters = Vtiger_Field_Model::getDateFilterTypes();
			foreach($dateFilters as $comparatorKey => $comparatorInfo) {
				$comparatorInfo['startdate'] = DateTimeField::convertToUserFormat($comparatorInfo['startdate']);
				$comparatorInfo['enddate'] = DateTimeField::convertToUserFormat($comparatorInfo['enddate']);
				$comparatorInfo['label'] = vtranslate($comparatorInfo['label'],$module);
				$dateFilters[$comparatorKey] = $comparatorInfo;
			}
			
			$viewer->assign('DATE_FILTERS', $dateFilters);
			$viewer->assign('RECORD_STRUCTURE1', $sourcerecordStructureInstance->getStructure());
			$viewer->assign('SOURCE_MODULE',$source_module);
			$viewer->assign('SOURCE_MODULE_MODEL', $sourcemoduleModel);
			//$viewer->assign('MODULE', $module);
			$viewer->assign('VIEWID', $viewid);
			$viewer->assign('RECORD_ID', $record);
			
			$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($source_module));
		}
		//end change
			//echo $moduleName;
			
		$viewer->view('Step2.tpl', $moduleName);
	}
	
	
	
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Vtiger.resources.Edit',
			"modules.Vtiger.resources.AdvanceFilter",
			'~libraries/jquery/ckeditor/ckeditor.js',
			"modules.Vtiger.resources.CkEditor",
			"libraries.jquery.ckeditor.ckeditor",
			"libraries.jquery.ckeditor.adapters.jquery",
			'~libraries/jquery/timepicker/jquery.timepicker.min.js',
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	
}
