<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

include_once 'modules/Vtiger/CRMEntity.php';

class EmailTrackingReport extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_emailtrackingreport';
	var $table_index= 'emailtrackingreportid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_emailtrackingreportcf', 'emailtrackingreportid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_emailtrackingreport', 'vtiger_emailtrackingreportcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_emailtrackingreport' => 'emailtrackingreportid',
		'vtiger_emailtrackingreportcf'=>'emailtrackingreportid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Email Tracking Report ID' => Array('emailtrackingreport', 'emailtrackingreport1'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Email Tracking Report ID' => 'emailtrackingreport1',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'emailtrackingreport1';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Email Tracking Report ID' => Array('emailtrackingreport', 'emailtrackingreport1'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Email Tracking Report ID' => 'emailtrackingreport1',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('emailtrackingreport1');

	// For Alphabetical search
	var $def_basicsearch_col = 'emailtrackingreport1';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'emailtrackingreport1';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('emailtrackingreport1','assigned_user_id');

	var $default_order_by = 'emailtrackingreport1';
	var $default_sort_order='ASC';

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
			$leads_link_exists_result = $adb->query("SELECT 1 FROM vtiger_relatedlists WHERE label='Email Campaigns' and tabid ='7'");
			if($adb->num_rows($leads_link_exists_result) == 0){
				$adb->query("INSERT INTO `vtiger_relatedlists` (`relation_id`, `tabid`, `related_tabid`, `name`, `sequence`, `label`, `presence`, `actions`)
					VALUES ((SELECT `id` +3 FROM `vtiger_relatedlists_seq`),  7, (SELECT tabid FROM vtiger_tab WHERE name='EmailTrackingReport'), 'get_dependents_list', 3, 'Email Campaigns', 0, 'SELECT');");
				$adb->query("UPDATE `vtiger_relatedlists_seq` SET `id` = id+1");
			}
			$contact_link_exists = $adb->query("SELECT 1 FROM vtiger_relatedlists WHERE label='Email Campaigns' and tabid ='4'");
			if($adb->num_rows($contact_link_exists) == 0){
				$adb->query("INSERT INTO `vtiger_relatedlists` (`relation_id`, `tabid`, `related_tabid`, `name`, `sequence`, `label`, `presence`, `actions`) 
					VALUES ((SELECT `id` +1 FROM `vtiger_relatedlists_seq`),  4, (SELECT tabid FROM vtiger_tab WHERE name='EmailTrackingReport'), 'get_dependents_list', 1, 'Email Campaigns', 0, 'SELECT')");
				$adb->query("UPDATE `vtiger_relatedlists_seq` SET `id` = id+1");
			}
			$account_link_exists = $adb->query("SELECT 1 FROM vtiger_relatedlists WHERE label='Email Campaigns' and tabid ='6'");
			if($adb->num_rows($account_link_exists) == 0){
				$adb->query("INSERT INTO `vtiger_relatedlists` (`relation_id`, `tabid`, `related_tabid`, `name`, `sequence`, `label`, `presence`, `actions`)
					VALUES ((SELECT `id` +3 FROM `vtiger_relatedlists_seq`),  6, (SELECT tabid FROM vtiger_tab WHERE name='EmailTrackingReport'), 'get_dependents_list', 3, 'Email Campaigns', 0, 'SELECT')");
				$adb->query("UPDATE `vtiger_relatedlists_seq` SET `id` = id+1");
			}
			// TODO Handle actions after this module is installed.
		} else if($eventType == 'module.disabled') {
			// TODO Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
 	}
}
