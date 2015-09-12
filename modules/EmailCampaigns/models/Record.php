<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class EmailCampaigns_Record_Model extends Vtiger_Record_Model {
	
	public function getDetailViewUrl() {
		$id = Vtiger_Record_Model::getId($moduleName);
		return 'index.php?module=EmailCampaigns&view=Detail&record='.$id;
	}
	public function getScheduledReport(){
		return EmailCampaigns_ScheduleEmailCampaigns_Model::getInstanceById($this->getId());
	}
	public function getAllModuleList(){
	
		$db = PearDatabase::getInstance();
		
		 $query = 'SELECT DISTINCT(name) AS modulename FROM vtiger_tab 
				  LEFT JOIN vtiger_field ON vtiger_field.tabid = vtiger_tab.tabid
				  WHERE vtiger_field.uitype = ? ';
		$result = $db->pquery($query, array(13));
		$num_rows = $db->num_rows($result);
		$moduleList = array();
		for($i=0; $i<$num_rows; $i++){
			$moduleList[] = $db->query_result($result, $i, 'modulename');
		}
		return $moduleList;
	}
	
	public function getEmailTemplateFields(){
		return $this->getModule()->getAllModuleEmailTemplateFields();
	}
	
	public function getRelatedFields($module, $currentUserModel) {
		$handler = vtws_getModuleHandlerFromName($module, $currentUserModel);
		$meta = $handler->getMeta();
		$moduleFields = $meta->getModuleFields();
		$returnData = array();
		foreach ($moduleFields as $key => $field) {
			$referencelist = array();
			$relatedField = $field->getReferenceList();
			if ($field->getFieldName() == 'assigned_user_id') {
				$relModule = 'Users';
				$referencelist = $this->getRelatedModuleFieldList($relModule, $currentUserModel);
			}
			if (!empty($relatedField)) {
				foreach ($relatedField as $ind => $relModule) {
					$referencelist = $this->getRelatedModuleFieldList($relModule, $currentUserModel);
				}
			}
			$returnData[] = array('module' => $module, 'fieldname' => $field->getFieldName(), 'columnname' => $field->getColumnName(), 'fieldlabel' => $field->getFieldLabelKey(), 'referencelist' => $referencelist);
		}
		return $returnData;
	}
	
	public function getRelatedModuleFieldList($relModule, $user) {
		$handler = vtws_getModuleHandlerFromName($relModule, $user);
		$relMeta = $handler->getMeta();
		if (!$relMeta->isModuleEntity()) {
			return null;
		}
		$relModuleFields = $relMeta->getModuleFields();
		$relModuleFieldList = array();
		foreach ($relModuleFields as $relind => $relModuleField) {
			if($relModule == 'Users') {
				if($relModuleField->getFieldDataType() == 'string' || $relModuleField->getFieldDataType() == 'email' || $relModuleField->getFieldDataType() == 'phone') {
					$skipFields = array(98,115,116,31,32);
					if(!in_array($relModuleField->getUIType(), $skipFields) && $relModuleField->getFieldName() != 'asterisk_extension'){
						$relModuleFieldList[] = array('module' => $relModule, 'fieldname' => $relModuleField->getFieldName(), 'columnname' => $relModuleField->getColumnName(), 'fieldlabel' => $relModuleField->getFieldLabelKey());
					}
				}
			} else {
				$relModuleFieldList[] = array('module' => $relModule, 'fieldname' => $relModuleField->getFieldName(), 'columnname' => $relModuleField->getColumnName(), 'fieldlabel' => $relModuleField->getFieldLabelKey());
			}
		}
		return $relModuleFieldList;
	}
	
	public function getEmailCampaignRecordRelatedModule($campaignid) {
		
		$db = PearDatabase::getInstance();
		$related_module_query = "SELECT * FROM vtiger_emailcamp_sendemail 
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid  = vtiger_emailcamp_sendemail.related_id 
			where vtiger_emailcamp_sendemail.emailcampaignsid = ?";
		$related_module_result = $db->pquery($related_module_query, array($campaignid));
		return $db->query_result($related_module_result,0,'setype');
	}
	
	public function getSubscribers($campaignid, $optout, $limit = null) {
		
		$db = PearDatabase::getInstance();
		$module = EmailCampaigns_Record_Model::getEmailCampaignRecordRelatedModule($campaignid);
		
		if($module == 'Leads') {
			$table = 'vtiger_leaddetails ON vtiger_leaddetails.leadid';
			$table1='vtiger_leaddetails.emailcampaign_optout';
		} else if($module == 'Contacts') {
			$table = 'vtiger_contactdetails ON vtiger_contactdetails.contactid';
			$table1='vtiger_contactdetails.emailcampaign_optout';
		} else if($module == 'Accounts') {
			$table = 'vtiger_account ON vtiger_account.accountid';
			$table1='vtiger_account.emailcampaign_optout';
		}
		
		 /* $query = "SELECT * FROM vtiger_emailcamp_sendemail 
			INNER JOIN $table = vtiger_emailcamp_sendemail.related_id 
			WHERE vtiger_emailcamp_sendemail.emailcampaignsid = ? and $table1=?"; */
		$query = "SELECT * FROM vtiger_emailcamp_sendemail 
			INNER JOIN $table = vtiger_emailcamp_sendemail.related_id 
			WHERE vtiger_emailcamp_sendemail.emailcampaignsid = ? and $table1=?";
			
		return $db->pquery($query, array($campaignid, $optout));
	}
	
	public function getUniqueLinkClickRecords($campaignid, $limit = null) {
		
		$db = PearDatabase::getInstance();
		$module = EmailCampaigns_Record_Model::getEmailCampaignRecordRelatedModule($campaignid);
		
		if($module == 'Leads') {
			$table = 'vtiger_leaddetails ON vtiger_leaddetails.leadid';
		} else if($module == 'Contacts') {
			$table = 'vtiger_contactdetails ON vtiger_contactdetails.contactid';
		} else if($module == 'Accounts') {
			$table = 'vtiger_account ON vtiger_account.accountid';
		}
		
		$query = "SELECT * FROM  vtiger_emailcamp_sendemail 
			INNER JOIN $table = vtiger_emailcamp_sendemail.related_id 
			INNER JOIN vtiger_link_tracker ON vtiger_link_tracker.emailid = vtiger_emailcamp_sendemail.activityid 
			where vtiger_emailcamp_sendemail.emailcampaignsid = ? order by vtiger_link_tracker.link ASC $limit";
		
		return $db->pquery($query, array($campaignid));
	}
	
	public function getTotalSentEmailCount($campaignid) {
	
		$db = PearDatabase::getInstance();
		$query = "SELECT * FROM  vtiger_emaildetails 
			INNER JOIN vtiger_emailcamp_sendemail ON vtiger_emaildetails.emailid  = vtiger_emailcamp_sendemail.activityid 
			WHERE vtiger_emailcamp_sendemail.emailcampaignsid=? AND vtiger_emaildetails.email_flag = 'SENT'";	
		
		$result = $db->pquery($query, array($campaignid));
		return $db->num_rows($result);
	}
	
	public function getUniqOpenCount($campaignid) {
	
		$db = PearDatabase::getInstance();
		$query = "SELECT * FROM  vtiger_email_track 
			INNER JOIN vtiger_emailcamp_sendemail ON vtiger_emailcamp_sendemail.activityid  = vtiger_email_track.mailid 
			WHERE vtiger_emailcamp_sendemail.emailcampaignsid = ? AND vtiger_email_track.access_count > 0";	
		
		$result = $db->pquery($query, array($campaignid));
		return $db->num_rows($result);
	}
	
	public function getLinkList($campaignid) {
		
		$db = PearDatabase::getInstance();
		$query_links = "SELECT vtiger_link_tracker.link,sum(click) as click FROM vtiger_link_tracker 
			INNER JOIN vtiger_emailcamp_sendemail ON vtiger_link_tracker.emailid = vtiger_emailcamp_sendemail.activityid	
			WHERE vtiger_emailcamp_sendemail.emailcampaignsid = ? GROUP BY vtiger_link_tracker.link";
		
		$result_links = $db->pquery($query_links, array($campaignid));
		$count = $db->num_rows($result_links);
		
		$query_open_count = "SELECT count(email) as open FROM vtiger_link_tracker 
			INNER JOIN vtiger_emailcamp_sendemail ON vtiger_link_tracker.emailid  = vtiger_emailcamp_sendemail.activityid
			INNER JOIN vtiger_leaddetails ON vtiger_leaddetails.leadid  = vtiger_emailcamp_sendemail.related_id		
			WHERE vtiger_emailcamp_sendemail.emailcampaignsid = ? GROUP BY vtiger_link_tracker.link";
		
		$result_open_count = $db->pquery($query_open_count, array($campaignid));
		
		for($i=0;$i<$count;$i++){
			$linkname		= $db->query_result($result_links, $i, 'link');
			$click			= $db->query_result($result_links, $i, 'click');
			$uniqe_open		= $db->query_result($result_open_count, $i, 'open');
			$result[$i+1] 	= array($linkname, $click, $uniqe_open);
		}
		return $result;
	}
}
