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

class Modalitapagamento extends Vtiger_CRMEntity {
	var $table_name = 'green_modalitapagamento';
	var $table_index= 'modalitapagamentoid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('green_modalitapagamentocf', 'modalitapagamentoid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'green_modalitapagamento', 'green_modalitapagamentocf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'green_modalitapagamento' => 'modalitapagamentoid',
		'green_modalitapagamentocf'=>'modalitapagamentoid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Modalitapagamento' => Array('modalitapagamento', 'modalitapagamento'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Modalitapagamento' => 'modalitapagamento',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'modalitapagamento';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Modalitapagamento' => Array('modalitapagamento', 'modalitapagamento'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Modalitapagamento' => 'modalitapagamento',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('modalitapagamento');

	// For Alphabetical search
	var $def_basicsearch_col = 'modalitapagamento';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'modalitapagamento';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('modalitapagamento','assigned_user_id');

	var $default_order_by = 'modalitapagamento';
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
			$myCustomEntity->setModuleSeqNumber("configure",$moduleName,'PAY','1');
			// block module download
			$adb->query("UPDATE vtiger_tab SET customized=0 WHERE name='$moduleName'");
			// Add module to account
			$module = Vtiger_Module::getInstance('Accounts');
			$block = Vtiger_Block::getInstance('LBL_ACCOUNT_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
			// Add module to Contacts
			$module = Vtiger_Module::getInstance('Contacts');
			$block = Vtiger_Block::getInstance('LBL_CONTACT_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
			// Add module to Quotes
			$module = Vtiger_Module::getInstance('Quotes');
			$block = Vtiger_Block::getInstance('LBL_QUOTE_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
			// Add module to PO
			$module = Vtiger_Module::getInstance('PurchaseOrder');
			$block = Vtiger_Block::getInstance('LBL_PO_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
			// Add module to SO
			$module = Vtiger_Module::getInstance('SalesOrder');
			$block = Vtiger_Block::getInstance('LBL_SO_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
			// Add module to Invoice
			$module = Vtiger_Module::getInstance('Invoice');
			$block = Vtiger_Block::getInstance('LBL_INVOICE_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
			// Add module to Vendors
			$module = Vtiger_Module::getInstance('Vendors');
			$block = Vtiger_Block::getInstance('LBL_VENDOR_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
			// Add module to DDT
			$module = Vtiger_Module::getInstance('Ddt');
			$block = Vtiger_Block::getInstance('LBL_DDT_INFORMATION',$module);
			$field = new Vtiger_Field();
			$field->label = 'Modalitapagamento';
			$field->name = 'modalitapagamento';
			$field->column = 'modalitapagamento';
			$field->columntype = 'VARCHAR(255)';
			$field->uitype = 10;
			$field->typeofdata = 'V~O';
			$block->addField($field);
			$block->save($module);
			$field->setRelatedModules(Array('Modalitapagamento'));
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
