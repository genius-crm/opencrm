<?php
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
require_once 'config.inc.php';

require_once('include/utils/utils.php');
//require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
	include_once 'includes/main/WebUI.php';
	$adb = PearDatabase::getInstance();
global $current_user,$adb;
$current_user = Users::getActiveAdminUser();


	$default_timezone = vglobal('default_timezone');
	$adminTimeZone = $current_user->time_zone;
	@date_default_timezone_set($adminTimeZone);
	$currentTimestamp  = date("Y-m-d H:i:s");
	@date_default_timezone_set($default_timezone);
	$result = $adb->pquery("SELECT vtiger_scheduleemailcampagine.emailcampaignsid FROM vtiger_scheduleemailcampagine inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_scheduleemailcampagine.emailcampaignsid WHERE vtiger_scheduleemailcampagine.status = 1 and vtiger_crmentity.deleted = 0", array());
  
	$scheduledReports = array();
	$noOfScheduledReports = $adb->num_rows($result);
	
	require_once('modules/EmailCampaigns/actions/SaveAjax.php');
	for ($i = 0; $i < $noOfScheduledReports; ++$i) {
		$recordId = $adb->query_result($result, $i, 'emailcampaignsid');
		$scheduledReports[] = $recordId;
	  EmailCampaigns_SaveAjax_Action::vtigercron($recordId);
		
		
	}
	
?>