<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class SubscribersList_TransferOwnership_Action extends Vtiger_Action_Controller {
	
	function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Save')) {
			throw new AppException(vtranslate($moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	public function process(Vtiger_Request $request) {
		$module = $request->getModule();
		$record = $request->get('record');
		
		if($record != '')
		{		
			$db = PearDatabase::getInstance();
			$query = "select vtiger_subscriberslist.*,vtiger_crmentity.crmid FROM vtiger_subscriberslist
				INNER JOIN vtiger_crmentity 
				ON vtiger_subscriberslist.subscriberslistid  = vtiger_crmentity.crmid
				WHERE vtiger_crmentity.deleted = 0 and vtiger_subscriberslist.subscriberslistid = $record 
				";
				$groupResults = array();
			$result = $db->pquery($query, array());
			$count = $db->num_rows($result);
			if($count > 0){
				$crmid = $db->query_result($result, 0,'crmid');
				$list_name = $db->query_result($result, 0,'list_name');
				$module_name = $db->query_result($result, 0,'module_name');
				$conditions  = $db->query_result($result, 0,'conditions');
			}
			
		}
			
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true, 'crmid' =>$crmid, 'list_name' =>$list_name, 'module_name' =>$module_name, 'conditions' =>Zend_Json::encode($conditions)));
		$response->emit();
			
		
	}
	
}
