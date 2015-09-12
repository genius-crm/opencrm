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
global $adb;
$entity = $_REQUEST["entity"];
$action = $_REQUEST["do"];
if ($action == "load") {
	$query= "select phone, email1, website from vtiger_account a inner join vtiger_accountscf acf on a.accountid = acf.accountid where a.accountid = ?";
	$result = $adb->pquery($query,array($entity));
	$phone = $adb->query_result($result, 0, "phone");
	$email = $adb->query_result($result, 0, "email1");
	$website = $adb->query_result($result, 0, "website");
	//$cf_805 = $adb->query_result($result, 0, "cf_805");
	echo $phone . "||" . $email . "||" . $website ;
}
?>