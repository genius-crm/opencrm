<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Accounting_PaymentDetails_View extends Settings_Vtiger_Index_View {

	public function process(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedName = $request->getModule(false);
		
		$check = "select * from vtiger_tab where tablabel = '$moduleName' and vtiger_tab.presence = 0"; 
		$presence = $adb->query($check);
		if($adb->num_rows($presence) > 0){
			$sql = "select vtiger_fieldmodulerel.relmodule,vtiger_tab.tabid from vtiger_fieldmodulerel
				INNER JOIN vtiger_tab ON vtiger_fieldmodulerel.relmodule = vtiger_tab.name
				where vtiger_fieldmodulerel.module = 'Accounting' and vtiger_tab.presence = 0"; 
			$result = $adb->query($sql);
			
			if($adb->num_rows($result) > 0){
				for($j=0;$j<$adb->num_rows($result);$j++){
					$module[$adb->query_result($result,$j,'tabid')] = $adb->query_result($result,$j,'relmodule');
				}
			}
			
			$sql1 = "SELECT vtiger_links.tabid from vtiger_links where vtiger_links.linklabel = 'Accounting'";
			$result1 = $adb->query($sql1);
			$active_module = array();
			if($adb->num_rows($result1) > 0){
				for($i=0;$i<$adb->num_rows($result1);$i++){
					$active_module[$i] = $adb->query_result($result1,$i,'tabid');
				}
			}
			
			$viewer->assign('ALL_MODULES', $module);
			$viewer->assign('ACTIVE_MODULES', $active_module);
			$viewer->assign('MODULE', $moduleName);
			$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

			echo $viewer->view('ListContents.tpl', $qualifiedName,true);
		} else {
			echo "<b>You can not update data until your module is active or installed</b>";
		}
	}
}