<?php

class Settings_EmailCampaigns_EmailCampaignDetailSaveAjax_Action extends Settings_Vtiger_Basic_Action {

	public function process(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();
		$response = new Vtiger_Response();
		$qualifiedModuleName = $request->getModule(false);
		$status = array();
		$updatedFields = $request->get('updatedFields');
		$batch = $updatedFields['batch'];
				
		$query = "select * from vtiger_emailcampaign_setting where id = 1"; 
		$result = $adb->query($query);
		if($adb->num_rows($result) > 0){
			$sql_update = "UPDATE vtiger_emailcampaign_setting SET `batch` = $batch where `id` = 1";	
			$result_output = $adb->query($sql_update);
		} else {
			$sql_insert = "INSERT INTO vtiger_emailcampaign_setting (`id`,`batch`) VALUES ('1', $batch)";	
			$result_output = $adb->query($sql_insert);
		}
		
		if($result_output !== false) {
			$status['success'] = true;
			$status['result'] = array('true');
			$response->setResult(array($status));
		} else {
			$status['success'] = false;
			$status['result'] = array('Error in Updating data');
			$response->setResult(array($status));
		}
		$response->emit();
	}
}