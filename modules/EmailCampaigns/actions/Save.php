<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class EmailCampaigns_Save_Action extends Vtiger_Save_Action {

	public function process(Vtiger_Request $request) {
		$adb = PearDatabase::getInstance();
		$sqlbatch = $adb->pquery("SELECT * FROM vtiger_emailcampaign_setting");
		$batch = $adb->query_result($sqlbatch, 0, 'batch');
		
		$sqlinterval = $adb->pquery("SELECT * FROM vtiger_cron_task WHERE name='ScheduleEmailCampaign'");
		$interval = $adb->query_result($sqlinterval, 0, 'frequency');
		$intervalmin = ($interval/60);
		
		$recordModel = Vtiger_Save_Action::saveRecord($request);
		$response = new Vtiger_Response();
		$relatedRecordId = $recordModel->getId();
		if($relatedRecordId){
			$conditions = $request->get('conditions1');
			$wfCondition = array();
			if(!empty($conditions)) {
				foreach($conditions as $index => $condition) {
					$columns = $condition['columns'];
					// if($index == '1' && empty($columns)) {
						// $wfCondition[] = array('fieldname'=>'', 'operation'=>'', 'value'=>'', 'valuetype'=>'', 
							// 'joincondition'=>'', 'groupid'=>'0');
					// }
					if(!empty($columns) && is_array($columns) && $index == '1') {
						foreach($columns as $column) {
							$wfCondition[] = array('fieldname'=>$column['columnname'], 'operation'=>$column['comparator'],
								'value'=>$column['value'], 'valuetype'=>$column['valuetype'], 'joincondition'=>$column['column_condition'],
								'groupjoin'=>$condition['condition'], 'groupid'=>'0');
						}
					}else if(!empty($columns) && is_array($columns) && $index == '2'){
						foreach($columns as $column) {
								$wfCondition[] = array('fieldname'=>$column['columnname'], 'operation'=>$column['comparator'],
									'value'=>$column['value'], 'valuetype'=>$column['valuetype'], 'joincondition'=>$column['column_condition'],
									'groupjoin'=>$condition['condition'], 'groupid'=>'1');
							}
					}
				}
			}
			$moduleName = $request->getModule();
			$recordModel = Vtiger_Record_Model::getInstanceById($relatedRecordId, $moduleName);
			$recordModel->set('id', $relatedRecordId);
			$recordModel->set('mode', 'edit');
			$recordModel->set('batch', $batch);
			$recordModel->set('emailcamp_interval', $intervalmin);
			$recordModel->set('conditions', Zend_Json::encode($wfCondition));
			// $related_recorts = $request->get('related_recorts');
			// $recordModel->set('related_recorts', Zend_Json::decode($related_recorts));
			$recordModel->save();
		} 
		$response->setResult(array('id' => $relatedRecordId));
		$response->emit();
	}
}
