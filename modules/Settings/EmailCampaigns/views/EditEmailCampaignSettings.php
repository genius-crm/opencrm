<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_EmailCampaigns_EditEmailCampaignSettings_View extends Settings_Vtiger_Index_View {

	public function process(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedName = $request->getModule(false);
		$data = array();
		
		$query = "select * from vtiger_emailcampaign_setting where id = 1"; 
		$result = $adb->query($query);
		if($adb->num_rows($result) > 0){
			$batch = $adb->query_result($result,0,'batch');
			$interval = $adb->query_result($result,0,'interval');
			$data = array('batch' => $batch,'interval' => $interval);
			$viewer->assign('DATA', $data);
		} else {
			$viewer->assign('DATA', $data);
		}
		$detail_view_url = "?module=EmailCampaigns&parent=Settings&view=EmailCampaignDetails";
		$viewer->assign('DETAIL_VIEW_URL', $detail_view_url);
		$viewer->assign('VIEW', $action);
		$viewer->assign('MODULE', $moduleName);
		

		echo $viewer->view('EditContents.tpl', $qualifiedName,true);
	}
}