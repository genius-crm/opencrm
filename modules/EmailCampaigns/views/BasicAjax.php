<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class EmailCampaigns_BasicAjax_View extends Vtiger_Basic_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('showAdvancedSearch');
		
	}

	function checkPermission() { }

	function preProcess(Vtiger_Request $request) {
		return true;
	}

	function postProcess(Vtiger_Request $request) {
		return true;
	}

	function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
		}
		return;
	}

	/**
	 * Function to display the UI for advance search on any of the module
	 * @param Vtiger_Request $request
	 */
	function showAdvancedSearch(Vtiger_Request $request) {
		//Modules for which search is excluded
		$excludedModuleForSearch = array('Vtiger');

		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		
		if($request->get('source_module')) {
			$moduleName = $request->get('source_module');
		}
		if($request->get('record')) {
			$record = $request->get('record');
		}else{
			$customView = new CustomView();
			$record = $customView->getViewId($moduleName);
		}
		 
		$saveFilterPermitted = true;
        $saveFilterexcludedModules =  array('ModComments','RSS','Portal','Integration','PBXManager','DashBoard');
        if(in_array($moduleName, $saveFilterexcludedModules)){
            $saveFilterPermitted = false;
        }
        
		//See if it is an excluded module, If so search in home module
		if(in_array($moduleName, $excludedModuleForSearch)) {
			$moduleName = 'Home';
		}
		$module = $request->getModule();

		if(!empty($record)) {
			$customViewModel = CustomView_Record_Model::getInstanceById($record);
			$viewer->assign('MODE', 'edit');
		} else {
			$customViewModel = new CustomView_Record_Model();
            $customViewModel->setModule($moduleName);
			$viewer->assign('MODE', '');
		}
		
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel);

		$viewer->assign('SEARCHABLE_MODULES', EmailCampaigns_Module_Model::getSearchableModules());
		$viewer->assign('CUSTOMVIEW_MODEL', $customViewModel);
		
		$viewer->assign('ADVANCE_CRITERIA', $customViewModel->transformToNewAdvancedFilter());
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		
		
		if($moduleName == 'Calendar'){
			$advanceFilterOpsByFieldType = Calendar_Field_Model::getAdvancedFilterOpsByFieldType();
		} else{
			$advanceFilterOpsByFieldType = Vtiger_Field_Model::getAdvancedFilterOpsByFieldType();
		}
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
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		$viewer->assign('SOURCE_MODULE',$moduleName);
        $viewer->assign('SOURCE_MODULE_MODEL', $moduleModel);
		$viewer->assign('MODULE', $module);
		$viewer->assign('VIEWID', $record);
		$viewer->assign('RECORD_ID', $record);
        $viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($moduleName));
        $viewer->assign('SAVE_FILTER_PERMITTED', $saveFilterPermitted);
		echo $viewer->view('AdvanceSearch.tpl',$module, true);
	}

}