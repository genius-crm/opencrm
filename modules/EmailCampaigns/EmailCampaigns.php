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

class EmailCampaigns extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_emailcampaigns';
	var $table_index= 'emailcampaignsid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_emailcampaignscf', 'emailcampaignsid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_emailcampaigns', 'vtiger_emailcampaignscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_emailcampaigns' => 'emailcampaignsid',
		'vtiger_emailcampaignscf'=>'emailcampaignsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Campaign Name' => Array('emailcampaigns', 'campaign_name'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Campaign Name' => 'campaign_name',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'campaign_name';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Campaign Name' => Array('emailcampaigns', 'campaign_name'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Campaign Name' => 'campaign_name',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('campaign_name');

	// For Alphabetical search
	var $def_basicsearch_col = 'campaign_name';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'campaign_name';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('campaign_name','assigned_user_id');

	var $default_order_by = 'campaign_name';
	var $default_sort_order='ASC';

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
		global $adb;
		$displayLabel = 'EmailCampaign';
 		if($eventType == 'module.postinstall') {
			$blockid = $adb->query_result(
				$adb->pquery("SELECT blockid FROM vtiger_settings_blocks WHERE label='LBL_OTHER_SETTINGS'",array()),0, 'blockid');
			$sequence = (int)$adb->query_result($adb->pquery("SELECT max(sequence) as sequence FROM vtiger_settings_field WHERE blockid=?",array($blockid)),
				0, 'sequence') + 1;
			$fieldid = $adb->getUniqueId('vtiger_settings_field');
			$adb->pquery("INSERT INTO vtiger_settings_field (fieldid,blockid,sequence,name,iconpath,description,linkto)
				VALUES (?,?,?,?,?,?,?)", array($fieldid, $blockid,$sequence,$displayLabel,'','', 'index.php?module=EmailCampaigns&parent=Settings&view=EmailCampaignDetails'));
			$leads_field_exists_result = $adb->query("SELECT 1 FROM vtiger_field WHERE columnname='emailcampaign_optout' and tablename ='vtiger_leaddetails'");
			if($adb->num_rows($leads_field_exists_result) == 0){
				$adb->query("INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`, `summaryfield`) 
						VALUES ('7', (SELECT `id` +1 FROM `vtiger_field_seq`), 'emailcampaign_optout', 'vtiger_leaddetails', '1', '56', 'emailcampaign_optout', 'Email Campaign Opt Out', '1', '0', '0', '10', (SELECT max(vtfld.sequence)+1 FROM `vtiger_field` as vtfld WHERE vtfld.tablename = 'vtiger_leaddetails' and vtfld.block = (SELECT blockid FROM `vtiger_blocks` WHERE blocklabel = 'LBL_LEAD_INFORMATION')), (SELECT `blockid` FROM `vtiger_blocks` WHERE blocklabel = 'LBL_LEAD_INFORMATION'), '1', 'C~O', '3', NULL, 'BAS', '0', NULL, '0')");
				$adb->query("ALTER TABLE `vtiger_leaddetails` ADD `emailcampaign_optout` VARCHAR(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 0 AFTER `emailoptout`");
				$adb->query("UPDATE `vtiger_field_seq` SET `id` = id+1");
			}
			$contact_field_exists = $adb->query("SELECT 1 FROM vtiger_field WHERE columnname='emailcampaign_optout' and tablename ='vtiger_contactdetails'");
			if($adb->num_rows($contact_field_exists) == 0){
				$adb->query("INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`, `summaryfield`) 
					VALUES ('4', (SELECT `id` +1 FROM `vtiger_field_seq`), 'emailcampaign_optout', 'vtiger_contactdetails', '1', '56', 'emailcampaign_optout', 'Email Campaign Opt Out', '1', '0', '0', '10', (SELECT max(vtfld.sequence)+1 FROM `vtiger_field` as vtfld WHERE vtfld.tablename = 'vtiger_contactdetails' and vtfld.block = (SELECT blockid FROM `vtiger_blocks` WHERE blocklabel = 'LBL_CONTACT_INFORMATION')), (SELECT `blockid` FROM `vtiger_blocks` WHERE blocklabel = 'LBL_CONTACT_INFORMATION'), '1', 'C~O', '3', NULL, 'BAS', '0', NULL, '0')");
				$adb->query("ALTER TABLE `vtiger_contactdetails` ADD `emailcampaign_optout` VARCHAR(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 0 AFTER `emailoptout`");
				$adb->query("UPDATE `vtiger_field_seq` SET `id` = id+1");
			}
			$account_field_exists = $adb->query("SELECT 1 FROM vtiger_field WHERE columnname='emailcampaign_optout' and tablename ='vtiger_account'");
			if($adb->num_rows($account_field_exists) == 0){
				$adb->query("INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`, `summaryfield`) 
					VALUES ('6', (SELECT `id` +1 FROM `vtiger_field_seq`), 'emailcampaign_optout', 'vtiger_account', '1', '56', 'emailcampaign_optout', 'Email Campaign Opt Out', '1', '0', '0', '10', (SELECT max(vtfld.sequence)+1 FROM `vtiger_field` as vtfld WHERE vtfld.tablename = 'vtiger_account' and vtfld.block = (SELECT blockid FROM `vtiger_blocks` WHERE blocklabel = 'LBL_ACCOUNT_INFORMATION')), (SELECT `blockid` FROM `vtiger_blocks` WHERE blocklabel = 'LBL_ACCOUNT_INFORMATION'), '1', 'C~O', '3', NULL, 'BAS', '0', NULL, '0')");
				$adb->query("ALTER TABLE `vtiger_account` ADD `emailcampaign_optout` VARCHAR( 3 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 0 AFTER `emailoptout`");
				$adb->query("UPDATE `vtiger_field_seq` SET `id` = id+1");
			}
			// TODO Handle actions after this module is installed.
		} else if($eventType == 'module.disabled') {
			$moduleInstance = Vtiger_Module::getInstance($moduleName);
			$moduleInstance->deleteLink('HEADERSCRIPT', 'emailcampaign', 'layouts/vlayout/Settings/EmailCampaigns/resources/EmailCampaigns.js');
			$adb->pquery("UPDATE vtiger_settings_field SET active = 1 WHERE name=?", array($displayLabel));
			// TODO Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.preuninstall') {
			$adb->pquery("DELETE FROM vtiger_settings_field WHERE name=?", array($displayLabel));
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}else if($eventType == 'module.enabled') {
			$moduleInstance = Vtiger_Module::getInstance($moduleName);
			$moduleInstance->addLink('HEADERSCRIPT', 'emailcampaign', 'layouts/vlayout/Settings/EmailCampaigns/resources/EmailCampaigns.js');
			$adb->pquery("UPDATE vtiger_settings_field SET active = 0 WHERE name=?", array($displayLabel));
			// TODO Handle actions when this module is enabled.
		} 		
 	}
	
	function get_emaillist_account($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);
		
		$result2 = $this->db->pquery("SELECT* FROM vtiger_emailcampaigns WHERE emailcampaignsid = ?", array($id));
		$related_recorts = $this->db->query_result($result2, 0, 'related_recorts');
	
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
		
		$query = "SELECT  vtiger_crmentity.*,vtiger_account.*,vtiger_accountbillads.* 
				,vtiger_accountshipads.*,vtiger_accountscf.*, case when 
			(vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname 
			end as user_name from vtiger_account 
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_account.accountid 
			INNER JOIN vtiger_accountbillads ON vtiger_accountbillads.accountaddressid = vtiger_account.accountid 
			INNER JOIN vtiger_accountshipads ON vtiger_accountshipads.accountaddressid = vtiger_account.accountid 
			INNER JOIN vtiger_accountscf ON vtiger_accountscf.accountid = vtiger_account.accountid 
			LEFT JOIN vtiger_groups	ON vtiger_groups.groupid = vtiger_crmentity.smownerid 
			LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id 
			WHERE vtiger_crmentity.deleted = 0";	

		if(!empty($related_recorts)) {
			$query .= " AND vtiger_account.accountid IN (".$related_recorts.")";
		}						
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}
	function get_emaillist_contact($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);
		
		
		$result2 = $this->db->pquery("SELECT* FROM vtiger_emailcampaigns WHERE emailcampaignsid = ?", array($id));
		$related_recorts = $this->db->query_result($result2, 0, 'related_recorts');
	
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
		$query = "SELECT  vtiger_crmentity.*,vtiger_contactdetails.* ,case when 
			(vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname 
			end as user_name from vtiger_contactdetails 
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid 
			LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_contactdetails.accountid 
			INNER JOIN vtiger_contactaddress ON vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid 
			INNER JOIN vtiger_contactsubdetails ON vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid 
			INNER JOIN vtiger_customerdetails ON vtiger_contactdetails.contactid = vtiger_customerdetails.customerid 
			INNER JOIN vtiger_contactscf ON vtiger_contactdetails.contactid = vtiger_contactscf.contactid 
			LEFT JOIN vtiger_groups	ON vtiger_groups.groupid = vtiger_crmentity.smownerid 
			LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id 
			WHERE vtiger_crmentity.deleted = 0";
								
		if(!empty($related_recorts)) {
			$query .= " AND vtiger_contactdetails.contactid IN (".$related_recorts.")";
		}
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}
	
	
	function get_emaillist_leads($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);
		
		$result2 = $this->db->pquery("SELECT* FROM vtiger_emailcampaigns WHERE emailcampaignsid = ?", array($id));
		$related_recorts = $this->db->query_result($result2, 0, 'related_recorts');
	
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
		$query = "SELECT  vtiger_crmentity.*,vtiger_leaddetails.* ,case when 
			(vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname 
			end as user_name from vtiger_leaddetails 
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_leaddetails.leadid 
			INNER JOIN vtiger_leadsubdetails ON vtiger_leadsubdetails.leadsubscriptionid = vtiger_leaddetails.leadid 
			INNER JOIN vtiger_leadaddress ON vtiger_leaddetails.leadid = vtiger_leadaddress.leadaddressid 
			INNER JOIN vtiger_leadscf ON vtiger_leaddetails.leadid = vtiger_leadscf.leadid 
			LEFT JOIN vtiger_groups	ON vtiger_groups.groupid = vtiger_crmentity.smownerid 
			LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id 
			WHERE vtiger_crmentity.deleted = 0";	
								
		if(!empty($related_recorts)) {
			$query .= " AND vtiger_leaddetails.leadid IN (".$related_recorts.")";
		}						
		
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}
	
	function get_emails($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_emails(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();
		$button = '';
		$result2 = $this->db->pquery("SELECT* FROM vtiger_emailcampaigns WHERE emailcampaignsid = ?", array($id));
		$related_recorts = $this->db->query_result($result2, 0, 'related_recorts');
		
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
		$query ="select case when (vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname end as user_name," .
				" vtiger_activity.activityid, vtiger_activity.subject, vtiger_activity.semodule, vtiger_activity.activitytype," .
				" vtiger_emaildetails.email_flag,".
				" vtiger_activity.date_start, vtiger_activity.status, vtiger_activity.priority, vtiger_crmentity.crmid," .
				" vtiger_crmentity.smownerid,vtiger_crmentity.modifiedtime, vtiger_users.user_name, vtiger_emailcamp_sendemail.related_id as parent_id " .
				" from vtiger_activity" .
				" inner join vtiger_emailcamp_sendemail on vtiger_emailcamp_sendemail.activityid=vtiger_activity.activityid" .
				" inner join vtiger_emaildetails on vtiger_emaildetails.emailid  = vtiger_emailcamp_sendemail.activityid".
				" inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid" .
				" left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid" .
				" left join vtiger_users on  vtiger_users.id=vtiger_crmentity.smownerid" .
				" where vtiger_activity.activitytype='Emails' and vtiger_emaildetails.email_flag = 'SENT' and vtiger_crmentity.deleted=0 and vtiger_emailcamp_sendemail.emailcampaignsid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}
}
