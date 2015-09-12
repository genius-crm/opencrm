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

$link_name=$_REQUEST['link_name'];
$emailid=$_REQUEST['emailid'];
$url=$_REQUEST['url'];
$current_user_id=$_REQUEST['current_user_id'];

$emailtrackingreportid=$_REQUEST['emailtrackingreportid'];
$parent_id=$_REQUEST['parent_id'];
if($emailtrackingreportid!='' && $parent_id!=''){
	$query8 = "UPDATE `vtiger_emailtrackingreport` SET `email_click` = email_click + 1 WHERE `emailtrackingreportid` = '$emailtrackingreportid' and `parent_id` = '$parent_id' ";
	$result = mysql_query($query8,$con);
	
}

$query = "select id,click  from vtiger_link_tracker where  link='$link_name' AND emailid='$emailid' AND url='$url'";
$result = mysql_query($query,$con);
$num_rows = mysql_num_rows($result);
if($num_rows>0){
	while($row = mysql_fetch_array($result)){
			$id= $row['id'];
			$click= $row['click'];
			$click += 1;
			$query2 = "UPDATE `vtiger_link_tracker` SET `click` = '$click' WHERE `id` = '$id' ";
			$result = mysql_query($query2,$con);
	}
	echo("<script>location.href = '$url';</script>");
	exit;
	 
}else{
	$query1 = "INSERT INTO `vtiger_link_tracker` ( `id` ,`emailid` ,`link` ,`url` ,`click`) VALUES (NULL, '$emailid', '$link_name', '$url', '1')";
	$result = mysql_query($query1,$con);
		echo("<script>location.href = '$url';</script>");
		exit;
     
}
  
ob_flush(); 
?>
 