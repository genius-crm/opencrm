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

class Banche extends Vtiger_CRMEntity {
	var $table_name = 'green_banche';
	var $table_index= 'bancheid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('green_banchecf', 'bancheid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'green_banche', 'green_banchecf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'green_banche' => 'bancheid',
		'green_banchecf'=>'bancheid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Nomebanca' => Array('banche', 'nomebanca'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Nomebanca' => 'nomebanca',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'nomebanca';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Nomebanca' => Array('banche', 'nomebanca'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Nomebanca' => 'nomebanca',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('nomebanca');

	// For Alphabetical search
	var $def_basicsearch_col = 'nomebanca';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'nomebanca';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('nomebanca','assigned_user_id');

	var $default_order_by = 'nomebanca';
	var $default_sort_order='ASC';

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
 			// Set sequence number
 			$myCustomEntity = CRMEntity::getInstance($moduleName);
			$myCustomEntity->setModuleSeqNumber("configure",$moduleName,'BNK','1');
			// block module download
			$adb->query("UPDATE vtiger_tab SET customized=0 WHERE name='$moduleName'");
			// Add module to account
			$module = Vtiger_Module::getInstance('Accounts');
			$block = Vtiger_Block::getInstance('LBL_ACCOUNT_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
			// Add module to Contacts
			$module = Vtiger_Module::getInstance('Contacts');
			$block = Vtiger_Block::getInstance('LBL_CONTACT_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
			// Add module to Quotes
			$module = Vtiger_Module::getInstance('Quotes');
			$block = Vtiger_Block::getInstance('LBL_QUOTE_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
			// Add module to PO
			$module = Vtiger_Module::getInstance('PurchaseOrder');
			$block = Vtiger_Block::getInstance('LBL_PO_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
			// Add module to SO
			$module = Vtiger_Module::getInstance('SalesOrder');
			$block = Vtiger_Block::getInstance('LBL_SO_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
			// Add module to Invoice
			$module = Vtiger_Module::getInstance('Invoice');
			$block = Vtiger_Block::getInstance('LBL_INVOICE_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
			// Add module to Vendors
			$module = Vtiger_Module::getInstance('Vendors');
			$block = Vtiger_Block::getInstance('LBL_VENDOR_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
			// Add module to DDT
			$module = Vtiger_Module::getInstance('Ddt');
			$block = Vtiger_Block::getInstance('LBL_DDT_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Banca';
			$field->name = 'banca';
			$field->column = 'banca';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Banche'));
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
