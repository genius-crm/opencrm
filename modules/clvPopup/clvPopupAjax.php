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
function Composite($composite, $entity, $line, $action)
{		
	
	global $adb;
	$query= "select composite from vtiger_discount where entity_id = ? and line_id = ?;";
	$result = $adb->pquery($query, array($entity, $line));
	$row = $adb->query_result($result);
	if ($action == "save") {
		if ($composite != "") {
			if ($row) {
				$query= "update vtiger_discount set composite = ? where entity_id = ? and line_id = ?;";
				$adb->pquery($query, array($composite, $entity, $line));
			} else {
				$query= "insert into vtiger_discount (composite,entity_id,line_id) values (?, ?, ?);";
				$adb->pquery($query, array($composite, $entity, $line));
			}
		} else {
			$query= "delete from vtiger_discount where entity_id = ? and line_id = ?;";
			$adb->pquery($query, array($entity, $line));
		}
	} else {
		if ($action == "delete") {
			$query= "delete from vtiger_discount where entity_id = ? and line_id > ?;";
			$adb->pquery($query, array($entity, $line));
		} else {
			echo $row;
		}
	}
}
$composite = rawurldecode($_REQUEST["composite"]);
$entity = $_REQUEST["entity"];
$line = $_REQUEST["line"];
$action = $_REQUEST["do"];
if ($action != "check") {
	Composite($composite, $entity, $line, $action);
} else {
	$module = $_REQUEST["sourcemodule"];
	$view = $_REQUEST["view"];
	$record = $_REQUEST["record"];
	if ((($module=="Invoice")||($module=="Quotes"))&&($view=="Detail")) {
		global $adb;
		$query= "SELECT MAX(crmid) FROM vtiger_crmentity WHERE setype = ?;";
		$result = $adb->pquery($query, array($module));
		$row = $adb->query_result($result);		
		if ($row==$record){
			$query= "update vtiger_discount set entity_id = ? where entity_id = 0;";
			$adb->pquery($query, array($record));
		}
	}
	
}
?>
