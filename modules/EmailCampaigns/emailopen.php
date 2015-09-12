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

$emailtrackingreportid=$_REQUEST['emailtrackingreportid'];$parent_id=$_REQUEST['parent_id'];if($emailtrackingreportid!='' && $parent_id!=''){	$query8 = "UPDATE `vtiger_emailtrackingreport` SET `email_open` = email_open + 1 WHERE `emailtrackingreportid` = '$emailtrackingreportid' and `parent_id` = '$parent_id' ";	$result = mysql_query($query8,$con);	}

ob_flush(); 
?>
 