<?php

class Settings_Accounting_PaymentDetailSaveAjax_Action extends Settings_Vtiger_Basic_Action {

	public function process(Vtiger_Request $request) {
		$tabid = $request->get('forModule');
        $updateStatus = $request->get('updateStatus');
		$adb = PearDatabase::getInstance();
		$response = new Vtiger_Response();
		$qualifiedModuleName = $request->getModule(false);
		$status = array();

		if($updateStatus == 'true') {
			$sql = "select vtiger_links_seq.id from vtiger_links_seq"; 
			$result = $adb->query($sql);
			
			if($adb->num_rows($result) > 0) {
				$id = $adb->query_result($result,0,'id');
			}
			
			if($id != ''){
				$new_id = $id + 1;
				$linkurl = 'module=Accounting&view=GetAccountingActions&record=$RECORD$';
				
				$sql_insert = "INSERT INTO vtiger_links (linkid,tabid,linktype,linklabel,linkurl,linkicon,sequence,handler_path,handler_class,handler) VALUES ($new_id, $tabid, 'DETAILVIEWSIDEBARWIDGET', 'Accounting', '$linkurl', NULL, NULL, NULL, NULL, NULL)";	
				$result_output = $adb->query($sql_insert);

				if($result_output !== false) {
					$sql_update = "UPDATE vtiger_links_seq SET vtiger_links_seq.id = $new_id";	
					$result1 = $adb->query($sql_update);
					
					if($result1 !== false) {
						$status['success'] = true;
						$status['result'] = array('true');
						$response->setResult(array($status));
					} else {
						$status['success'] = false;
						$status['result'] = array('Error in Updating data');
						$response->setError(vtranslate($status, $qualifiedModuleName));
					}
				} else {
					$status['success'] = false;
					$status['result'] = array('Error in Inserting data');
					$response->setError(vtranslate($status, $qualifiedModuleName));
				}
			}
		
		} else {
			$sql = "delete from vtiger_links where vtiger_links.tabid = $tabid and vtiger_links.linklabel = 'Accounting'";
			$result = $adb->query($sql);
			
			if($result !== false) {
				$status['success'] = true;
				$status['result'] = array('true');
				$response->setResult(array($status));
			} else {
				$status['success'] = false;
				$status['result'] = array('Error in deleting widget data');
				$response->setError(vtranslate($status, $qualifiedModuleName));
			}
		}
		$response->emit();
	}
}