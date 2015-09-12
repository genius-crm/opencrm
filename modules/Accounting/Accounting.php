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

class Accounting extends Vtiger_CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity
	var $table_name = 'green_accounting';
	var $table_index= 'accountingid';
	var $entity_table = "vtiger_crmentity";
	var $column_fields = Array();
	var $sortby_fields = Array();

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('green_accountingcf', 'accountingid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'green_accounting', 'green_accountingcf', 'vtiger_inventoryproductrel');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'green_accounting' => 'accountingid',
		'green_accountingcf'=>'accountingid',
		'vtiger_inventoryproductrel'=>'id');
	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Payment ref' => Array('accounting', 'accounting'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Payment ref' => 'accounting',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'accounting';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Payment ref' => Array('accounting', 'accounting'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Payment ref' => 'accounting',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('accounting');

	// For Alphabetical search
	var $def_basicsearch_col = 'accounting';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'accounting';
	var $required_fields = Array('accounting'=>1);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('accounting','createdtime' ,'modifiedtime', 'assigned_user_id');

	var $default_order_by = 'accounting';
	var $default_sort_order='ASC';
	var $isLineItemUpdate = true;

	function Accounting() {
		$this->log =LoggerManager::getLogger('Accounting');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Accounting');
	}

	function save_module()
	{
		global $adb, $updateInventoryProductRel_deduct_stock;
		$updateInventoryProductRel_deduct_stock = false;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'AccountingAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW'
				&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates'
				&& $_REQUEST['action'] != 'SaveAjax' && $this->isLineItemUpdate != false) {

			$requestProductIdsList = $requestQuantitiesList = array();
			$totalNoOfProducts = $_REQUEST['totalProductCount'];
			for($i=1; $i<=$totalNoOfProducts; $i++) {
				$productId = $_REQUEST['hdnProductId'.$i];
				$requestProductIdsList[$productId] = $productId;
				$requestQuantitiesList[$productId] =  $_REQUEST['qty'.$i];
			}
			saveInventoryProductDetails($this, $module, $this->update_prod_stock);
			if ($this->mode != '') {
				$updateInventoryProductRel_deduct_stock = true;
			}
		}
	}

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
			// Module can't be downloaded from module manager
			$adb->query("UPDATE vtiger_tab SET customized=0 WHERE name='$moduleName'");
			//Add link to the module in the Setting Panel
			$fieldid = $adb->getUniqueID('vtiger_settings_field');
			$blockid = getSettingsBlockId('LBL_MODULE_MANAGER');
			
			$seq_res = $adb->query("SELECT max(sequence) AS max_seq FROM vtiger_settings_field WHERE blockid =1");
			$seq = 1;
			if ($adb->num_rows($seq_res) > 0)
			{
				$cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
				
				if ($cur_seq != null)
				{
					$seq = $cur_seq + 1;
				}
			}
				
			$adb->pquery
			(
				'INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active) VALUES (?,?,?,?,?,?,?,?)',
				array
				(
					$fieldid,
					4,
					'Payment Module Management',
					'layouts/vlayout/modules/Settings/'.$module_name.'/assets/images/'.$module_name.'.png',
					'Payment Module Management',
					'index.php?module=Accounting&parent=Settings&view=PaymentDetails',
					$seq,
					0
				)
			);
$commentsModule = Vtiger_Module::getInstance('ModComments');
$fieldInstance = Vtiger_Field::getInstance('related_to', $commentsModule);
$fieldInstance->setRelatedModules(array('Accounting'));

			// TODO Handle actions after this module is installed.
		} else if($eventType == 'module.disabled') {
			// TODO Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
$commentsModule = Vtiger_Module::getInstance('ModComments');
$fieldInstance = Vtiger_Field::getInstance('related_to', $commentsModule);

$fieldInstance->setRelatedModules(array('Accounting'));
			// TODO Handle actions after this module is updated.
		}
 	}

	function insertIntoEntityTable($table_name, $module, $fileid = '')  {
		//Ignore relation table insertions while saving of the record
		if($table_name == 'vtiger_inventoryproductrel') {
			return;
		}
		parent::insertIntoEntityTable($table_name, $module, $fileid);
	}
}
