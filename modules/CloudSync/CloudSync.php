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

class CloudSync extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_cloudsync';
	var $table_index= 'cloudsyncid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_cloudsynccf', 'cloudsyncid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_cloudsync', 'vtiger_cloudsynccf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_cloudsync' => 'cloudsyncid',
		'vtiger_cloudsynccf'=>'cloudsyncid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('cloudsync', 'name'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'name',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'name';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('cloudsync', 'name'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'name',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('name');

	// For Alphabetical search
	var $def_basicsearch_col = 'name';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'name';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('name','assigned_user_id');

	var $default_order_by = 'name';
	var $default_sort_order='ASC';

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
			require_once('modules/CloudSync/config.php');
			// create custom settings table
			$settingsTable = 'CREATE TABLE `vtiger_cloudysyncsettings` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `config_name` varchar(255) NOT NULL,
			  `config_value` longtext,
			  `user_id` int(10) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1';
			$adb->pquery($settingsTable, array());

			// create custom id mapping table
			$idMappingTable = 'CREATE TABLE `vtiger_cloudsync_id_mapping` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `service` varchar(100) NOT NULL,
			  `serviceid` varchar(150) NOT NULL,
			  `crmid` varchar(15) NOT NULL,
			  `user_id` int(10) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1';
			$adb->pquery($idMappingTable, array());

			$version = CloudSyncConfig::$version;
			$adb->pquery('insert into vtiger_cloudysyncsettings (config_name, config_value) values (?, ?)', array('version', $version));
			$adb->pquery('insert into vtiger_cloudysyncsettings (config_name) values (?)', array('serialised_data'));

			// creating widget on documents module
			$docInstance = Vtiger_Module::getInstance('Documents');
                	if($docInstance) {
        	                $docInstance->addLink("DETAILVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=detail");
				$docInstance->addLink("LISTVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=list");
	                }

			// create custom function for workflow
			require_once('include/utils/utils.php');
			require_once('modules/com_vtiger_workflow/VTEntityMethodManager.inc');
			require_once('modules/com_vtiger_workflow/VTWorkflowManager.inc');
			require_once('modules/com_vtiger_workflow/VTTaskManager.inc');
			$emm = new VTEntityMethodManager($adb); 
			$emm->addEntityMethod("Documents", "Upload Document - CloudSync", "modules/CloudSync/Workflow.php", "uploadDocs_CloudSync");

			// creating workflow to upload documents to service on first save
			$vtWorkFlow = new VTWorkflowManager($adb);
			$docWorkFlow = $vtWorkFlow->newWorkFlow("Documents");
			$docWorkFlow->test = '[]'; 
			$docWorkFlow->description = "Upload Document - CloudSync";
			$docWorkFlow->executionCondition = 2;
			$docWorkFlow->defaultworkflow = 1;
			$vtWorkFlow->save($docWorkFlow);

			$tm = new VTTaskManager($adb);
			$task = $tm->createTask('VTEntityMethodTask', $docWorkFlow->id);
			$task->summary = 'Upload Document - CloudSync';
			$task->active = true;
			$task->methodName = "Upload Document - CloudSync";
			$tm->saveTask($task);
		} 
		else if($eventType == 'module.enabled')	{
			// creating widget on documents module
                        $docInstance = Vtiger_Module::getInstance('Documents');
                        if($docInstance) {
                                $docInstance->addLink("DETAILVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=detail");
                                $docInstance->addLink("LISTVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=list");
                        }
		}
		else if($eventType == 'module.disabled') {
			// removing widget on documents module
                        $docInstance = Vtiger_Module::getInstance('Documents');
                        if($docInstance) {
                                $docInstance->deleteLink("DETAILVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=detail");
                                $docInstance->deleteLink("LISTVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=list");
                        }
		} 
		else if($eventType == 'module.preuninstall')	{
			// drop custom settings table
			$adb->pquery('drop table vtiger_cloudysyncsettings', array());
			$adb->pquery('drop table vtiger_cloudsync_id_mapping', array());

			// creating widget on documents module
                        $docInstance = Vtiger_Module::getInstance('Documents');
                        if($docInstance) {
                                $docInstance->deleteLink("DETAILVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=detail");
                                $docInstance->deleteLink("LISTVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=list");
                        }
		} 
		else if($eventType == 'module.preupdate')	{
			// TODO Handle actions before this module is updated.
		} 
		else if($eventType == 'module.postupdate')	{
			// TODO Handle actions after this module is updated.
		}
 	}
}
