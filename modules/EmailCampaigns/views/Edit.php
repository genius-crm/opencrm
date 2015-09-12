<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class EmailCampaigns_Edit_View extends Vtiger_Edit_View {
	
	public function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		
		if ($mode) {
			$this->$mode($request);
		} else {
			$this->step1($request);
		}
	}
	
	public function subscriberslist(Vtiger_Request $request) {
	
		$record = $request->get('record');
		$module = $request->get('module');
		$viewer = $this->getViewer($request);
		
		$viewer->assign('MODULE', $module);
		$viewer->assign('RECORDID', $record);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('schedulelater.tpl', $module);
	
	}
	
	public function schedulelater(Vtiger_Request $request) {
		$record = $request->get('record');
		$module = $request->get('module');
		$viewer = $this->getViewer($request);
		
		$viewer->assign('MODULE', $module);
		$viewer->assign('RECORDID', $record);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('schedulelater.tpl', $module);
	
	}
	public function preProcess(Vtiger_Request $request) {
		parent::preProcess($request);
		$viewer = $this->getViewer($request);

		 $recordId = $request->get('record');
		
		$viewer->assign('RECORDID', $recordId);
		if($recordId) {
			$moduleName = $request->getModule();
			$record = $request->get('record');
			$recordModel = $this->record?$this->record:Vtiger_Record_Model::getInstanceById($record, $moduleName);
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
			$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
			
		}
		$viewer->assign('RECORD_MODE', $request->getMode());
		//$viewer->view('EditHeader.tpl', $request->getModule(false));
	}	

	public function Step4(Vtiger_Request $request) {
		
		global $site_URL; 
		$recordId = $request->get('record');
		$moduleName = $request->getModule();

		if(!$this->record){
		$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$recordModel = $this->record->getRecord();
		$recordStrucure = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_DETAIL);
		$structuredValues = $recordStrucure->getStructure();
		// New line add for step4 display dynamic contents
		$emailtrack = '';
		if($structuredValues['Email Traking']['track_opens']->fieldvalue==1){  $emailtrack = 'Opens ';	}
		if($structuredValues['Email Traking']['track_clicks']->fieldvalue==1){	$emailtrack .= 'Clicks ';	}
		if($structuredValues['Email Traking']['track_bounces']->fieldvalue==1){  $emailtrack .= 'Bounces ';	}
		if($structuredValues['Email Traking']['track_complaints']->fieldvalue==1){  $emailtrack .= 'Complaints '; }
		//echo $emailtrack;
		/*$emailtracks = str_replace(" ",",",$emailtrack);*/
		$emailtracks = preg_replace('#\s+#',',',trim($emailtrack));
		
		$moduleModel = $recordModel->getModule();
        $viewer = $this->getViewer($request);
		$viewer->assign('RECORD', $recordModel);
		$viewer->assign('SITEURL', $site_URL);
		$viewer->assign('RECORD_STRUCTURE', $structuredValues);
		$viewer->assign('CAMPAIGN_NAME',	$structuredValues['Campaign Details']['campaign_name']->fieldvalue);
		
		$subscribers_list = $structuredValues['List Information']['subscribers_list']->fieldvalue;
		if($subscribers_list!=''){
			$SubscribersList = Vtiger_DetailView_Model::getInstance('SubscribersList', $subscribers_list);
			$recordModel_SubscribersList = $SubscribersList->getRecord();
			$list_name = $recordModel_SubscribersList->get('list_name');
			$list_name_url = $recordModel_SubscribersList->getDetailViewUrl();
			$subscribers_list_name = "<a href='$list_name_url'>$list_name</a>";
			$viewer->assign('SUBSCRIBERLIST_NAME',	$subscribers_list_name);
		}
		$viewer->assign('EMAIL_TRACKS',	$emailtracks);
		$viewer->assign('EMAIL_TEMP_SUBJECT',	$structuredValues['Email Template']['subject']->fieldvalue);
		$viewer->assign('CAMPAIGN_FROM_EMAIL',	$structuredValues['Campaign Details']['from_email']->fieldvalue);
		$viewer->assign('CAMPAIGN_NAME',	$structuredValues['Campaign Details']['campaign_name']->fieldvalue);
// new line finish

		$viewer->assign('RECORD_ID', $recordId);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('MODULE_NAME', $moduleName);
		//$viewer->assign('IS_AJAX_ENABLED', $this->isAjaxEnabled($recordModel));

		//return $viewer->view('DetailViewFullContents.tpl',$moduleName,true);
		$viewer->view('Step4.tpl', $moduleName);
	}
	
	public function Step3(Vtiger_Request $request) {
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
		
		$assigned_user_id = $request->get('assigned_user_id');
		$viewer->assign('assigned_user_id', $assigned_user_id);
		
		$batch = $request->get('batch');
		$viewer->assign('batch', $batch);
		
		$emailcamp_interval = $request->get('emailcamp_interval');
		$viewer->assign('emailcamp_interval', $emailcamp_interval);
		
		$conditions = $request->get('conditions');
		$viewer->assign('conditions', $conditions);
		
		$searchmodulelist = $request->get('searchmodulelist');
		$viewer->assign('searchmodulelist', $searchmodulelist);
		
		$campaign_name = $request->get('campaign_name');
		$viewer->assign('campaign_name', $campaign_name);
		
		$from_name = $request->get('from_name');
		$viewer->assign('from_name', $from_name);
		
		$from_email = $request->get('from_email');
		$viewer->assign('from_email', $from_email);
		
		$related_recorts = $request->get('related_recorts');
		$viewer->assign('related_recorts', $related_recorts);
	
		$reply_to = $request->get('reply_to');
		$viewer->assign('reply_to', $reply_to);
		
		$unsubscribe_option = $request->get('unsubscribe_option');
		$viewer->assign('unsubscribe_option', $unsubscribe_option);
		
		$subscribers_list = $request->get('subscribers_list');
		$viewer->assign('subscribers_list', $subscribers_list);
		
		$select_option = $request->get('select_option');
		$viewer->assign('select_option', $select_option);
		
		$whopenemail = $request->get('whopenemail');
		$viewer->assign('whopenemail', $whopenemail);
		
		$emailclicklink = $request->get('emailclicklink');
		$viewer->assign('emailclicklink', $emailclicklink);
		
		$parent_emailcampaignsid = $request->get('parent_emailcampaignsid');
		$viewer->assign('parent_emailcampaignsid', $parent_emailcampaignsid);
		
		$allFiledsOptions = $this->record->getEmailTemplateFields();
		$viewer->assign('ALL_FIELDS', $allFiledsOptions);
		
		$viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Zend_Json::encode($picklistDependencyDatasource));
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$emailTemplateModuleModel = Settings_Vtiger_Module_Model::getInstance('Settings:EmailTemplates');
		$emailTemplateListURL = $emailTemplateModuleModel->getListViewUrl();
		$viewer->assign('EMAIL_TEMPLATE_URL', $emailTemplateListURL);
		$viewer->view('Step3.tpl', $moduleName);
	}
	
	public function step2(Vtiger_Request $request) {
		global $adb;
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$record = $request->get('record');
		if(!empty($record)) {
		    $select_option_query = $adb->pquery("select select_option from vtiger_emailcampaigns where emailcampaignsid=?",array($record));
		    $select_option_value = $adb->query_result($select_option_query,0,'select_option');
		    
		    $recordModel = $this->record?$this->record:Vtiger_Record_Model::getInstanceById($record, $moduleName);
		    $viewer->assign('RECORD_ID', $record);
		    $viewer->assign('select_option', $select_option_value);
		    $viewer->assign('MODE', 'edit');
		} else {
		    $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
		    $viewer->assign('MODE', '');
		}
		if(!$this->record){
		    $this->record = $recordModel;
		}
		$assigned_user_id = $request->get('assigned_user_id');
		$viewer->assign('assigned_user_id', $assigned_user_id);
		
		$from_email = $request->get('from_email');
		$viewer->assign('from_email', $from_email);
		
		$from_name = $request->get('from_name');
		$viewer->assign('from_name', $from_name);
		
		$reply_to = $request->get('reply_to');
		$viewer->assign('reply_to', $reply_to);
		
		$campaign_name = $request->get('campaign_name');
		$viewer->assign('campaign_name', $campaign_name);
		
		$batch = $request->get('batch');
		$viewer->assign('batch', $batch);
		
		$emailcamp_interval = $request->get('emailcamp_interval');
		$viewer->assign('emailcamp_interval', $emailcamp_interval);
		
		$unsubscribe_option = $request->get('unsubscribe_option');
		$viewer->assign('unsubscribe_option', $unsubscribe_option);
		 
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
		
		
		
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('SEARCHABLE_MODULES', EmailCampaigns_Module_Model::getSearchableModules());
		//$viewer->assign('SOURCE_MODULE', '');
		//filter
		$source_module = $request->get('source_module');
		if($source_module == '' && $request->get('subscribers_list1')==''){
			$source_module = $recordModel->get('module_name');
		}else{
				$subscribers_list1 = $request->get('subscribers_list1');
				if($subscribers_list1 != '')
				{		
					$db = PearDatabase::getInstance();
					$query = "select vtiger_subscriberslist.*,vtiger_crmentity.crmid FROM vtiger_subscriberslist
						INNER JOIN vtiger_crmentity 
						ON vtiger_subscriberslist.subscriberslistid  = vtiger_crmentity.crmid
						WHERE vtiger_crmentity.deleted = 0 and vtiger_subscriberslist.subscriberslistid = $subscribers_list1 
						";
					$result = $db->pquery($query, array());
					$count = $db->num_rows($result);
					if($count > 0){
						$subscriberslistid = $db->query_result($result, 0,'crmid');
						$subscriberslist_name = $db->query_result($result, 0,'list_name');
						$subscriberssource_module = $db->query_result($result, 0,'module_name');
						$subscriberslistconditions  = $db->query_result($result, 0,'conditions');
						$source_module = $subscriberssource_module;
						$recordModel->set('subscribers_list', $subscriberslistid);
						
						}
				}		
		}
		$viewer->assign('SOURCE_MODULE', $source_module);
		
		if($source_module !=''){
			
			$customView = new CustomView();
			$viewid = $customView->getViewId($source_module);
				
			
			$customViewModel = new CustomView_Record_Model();
			$customViewModel->setModule($source_module);
			$viewer->assign('MODE', '');
			
			$sourcemoduleModel = Vtiger_Module_Model::getInstance($source_module);
			$sourcerecordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($sourcemoduleModel);

			$viewer->assign('SEARCHABLE_MODULES', EmailCampaigns_Module_Model::getSearchableModules());
			$viewer->assign('CUSTOMVIEW_MODEL', $customViewModel);
	
			if($recordModel->get('conditions') && $request->get('subscribers_list1')=='') {
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
			}else if($request->get('subscribers_list1')!=''){
				$subscribers_list1 = $request->get('subscribers_list1');
				
				if($subscribers_list1 != '')
				{		
					
						$conditions  = $subscriberslistconditions;
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
				}
				
			} else{
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
			
		}
		
		$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($moduleName);

		$viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Zend_Json::encode($picklistDependencyDatasource));
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		
		
		//end change
		$viewer->view('Step2.tpl', $moduleName);
	}
	
	public function step1(Vtiger_Request $request) {
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
		
		// $allFiledsOptions = EmailCampaigns_Record_Model::getAllModuleEmailTemplateFields();
		
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
		
		//$viewer->assign('SCHEDULEDREPORTS', $recordModel->getScheduledReport());
		$viewer->view('Step1.tpl', $moduleName);
	}
	
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Vtiger.resources.Edit',
			"modules.$moduleName.resources.Edit",
			"modules.$moduleName.resources.Popup",
			"modules.$moduleName.resources.Edit1",
			"modules.$moduleName.resources.Edit2",
			"modules.$moduleName.resources.Edit3",
			"modules.$moduleName.resources.Edit4",
			"modules.$moduleName.resources.AdvanceFilter",
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