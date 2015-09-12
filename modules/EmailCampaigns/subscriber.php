 <?php  error_reporting(0);
 
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/
require_once('../../config.inc.php');
global $site_URL;
global $dbconfig,$root_directory;
$con=mysql_connect($dbconfig['db_server'],$dbconfig['db_username'],$dbconfig['db_password']) or die(mysql_error());
$database=mysql_select_db($dbconfig['db_name']);


$id=$_REQUEST['recordid'];
$modulename=$_REQUEST['modulename'];
$current_user_id=$_REQUEST['current_user_id'];

	if($modulename == 'Contacts'){
			$query2 = "UPDATE `vtiger_contactdetails` SET `emailcampaign_optout` = '0' WHERE `contactid` = '$id' ";
			$result = mysql_query($query2,$con);
	}else if($modulename == 'Accounts'){
		$query2 = "UPDATE `vtiger_account` SET `emailcampaign_optout` = '0' WHERE `accountid` = '$id' ";
		$result = mysql_query($query2,$con);
	}else if($modulename == 'Leads'){
		$query2 = "UPDATE `vtiger_leaddetails`  SET `emailcampaign_optout` = '0'  WHERE `leadid` = '$id' ";
		$result = mysql_query($query2,$con);
	}
?>
 <h2>You are successfully subscribed from our newsletter</h2>

