<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class EmailCampaigns_ListRecordAjax_View extends Vtiger_Basic_View {

	public function __construct() {
		parent::__construct();
		$this->exposeMethod('getListofUniqueOpen');
		$this->exposeMethod('getSubscriberList');
		$this->exposeMethod('getUniqueLinkClick');
		$this->exposeMethod('getRecordsCountCampaign');
	}

	function checkPermission() { }

	public function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
		}
		return;
	}

	public function getListofUniqueOpen(Vtiger_Request $request) {
		
		$viewer = $this->getViewer($request);
		$db = PearDatabase::getInstance();
		$module = $request->getModule();
		$actionbtn = array();

		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		$page = $request->get('page_no');
		
		$related_modulename = EmailCampaigns_Record_Model::getEmailCampaignRecordRelatedModule($recordId);
		
		$this->initializeListViewContents($request, $viewer,$related_modulename);

		if($related_modulename == 'Leads') {
			$table = 'vtiger_leaddetails ON vtiger_leaddetails.leadid';
		} else if($related_modulename == 'Contacts') {
			$table = 'vtiger_contactdetails ON vtiger_contactdetails.contactid';
		} else if($related_modulename == 'Accounts') {
			$table = 'vtiger_account ON vtiger_account.accountid';
		}
		
		$query_total_count = "SELECT * FROM  vtiger_email_track 
			INNER JOIN vtiger_emailcamp_sendemail ON vtiger_emailcamp_sendemail.activityid  = vtiger_email_track.mailid 
			INNER JOIN  $table = vtiger_emailcamp_sendemail.related_id	
			where vtiger_emailcamp_sendemail.emailcampaignsid = ? 
			and vtiger_email_track.access_count > 0 group by vtiger_emailcamp_sendemail.related_id";
		
		$result_total_count = $db->pquery($query_total_count, array($recordId));
		$total_count = $db->num_rows($result_total_count);
		
		$configdetailmodel = Settings_Vtiger_ConfigModule_Model::getInstance();
		$record = $configdetailmodel->getViewableData();
		$noofdata = $record['list_max_entries_per_page'];
		if($noofdata <= 0){
			$noofdata = 10;
		}
		
		$max_page_limit = $page * $noofdata;
		$min_page_limit = $max_page_limit - $noofdata;
		$qry_limit = " LIMIT 0,$noofdata";
		if($page > 1)
			$qry_limit = " LIMIT $min_page_limit,$noofdata";

		$max_page = ceil($total_count/$noofdata);
		$query = $query_total_count.$qry_limit;
		$result_links = $db->pquery($query, array($recordId));
		$uniq_open = $db->num_rows($result_links);
		$previous_page = $page - 1;
		$next_page = $page + 1;
		$totalpage = 'Page '.$page.' of '.$max_page;
		if($page == 1) {
			$disablepre = 'disabled';
		}
		if($page == $max_page){
			$disablenxt = 'disabled';
		}

		for($i=0;$i<$uniq_open;$i++) {
			if($related_modulename == 'Leads' || $related_modulename == 'Contacts') {
				$firstname = $db->query_result($result_links,$i,'firstname');
				$lastname = $db->query_result($result_links,$i,'lastname');
				$name = $firstname.' '.$lastname;
				$email = $db->query_result($result_links,$i,'email');
				if($related_modulename == 'Contacts'){
					$id = $db->query_result($result_links,$i,'contactid');
					$link = "index.php?module=Contacts&view=Detail&record";
				}else if($related_modulename == 'Leads'){
					$id = $db->query_result($result_links,$i,'leadid');
					$link="index.php?module=Leads&view=Detail&record";
				}			
			} else if($related_modulename == 'Accounts') {
				$name = $db->query_result($result_links,$i,'accountname');
				$email = $db->query_result($result_links,$i,'email1');
				$id = $db->query_result($result_links,$i,'accountid');
				$link="index.php?module=Accounts&view=Detail&record";
			}
			$uniq = $db->query_result($result_links,$i,'access_count');
			$record_data[$i] = array($name, $email, $uniq,$id);
		}

		$viewer->assign('MODULE', $module);
		$viewer->assign('DISABLEPRE', $disablepre);
		$viewer->assign('DISABLENXT', $disablenxt);
		$viewer->assign('RELATED_MODULE', $related_modulename);
		$viewer->assign('DISPLAYTOTALPAGE', $totalpage);
		$viewer->assign('PAGE', $page);
		$viewer->assign('RECORD_DATA', $record_data);
		$viewer->assign('link', $link);
		echo $viewer->view('UniqueOpenList.tpl',$module, true);
	}
	
	public function getSubscriberList(Vtiger_Request $request) {
		
		$viewer = $this->getViewer($request);
		$db = PearDatabase::getInstance();
		$module = $request->getModule();
		
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		$page = $request->get('page_no');
		$optout = $request->get('optout');

		$related_modulename = EmailCampaigns_Record_Model::getEmailCampaignRecordRelatedModule($recordId);
		$subscriber = EmailCampaigns_Record_Model::getSubscribers($recordId, $optout);
		$total_count = $db->num_rows($subscriber);
		
		$configdetailmodel = Settings_Vtiger_ConfigModule_Model::getInstance();
		$record = $configdetailmodel->getViewableData();
		$noofdata = $record['list_max_entries_per_page'];
		if($noofdata <= 0){
			$noofdata = 10;
		}
		
		$max_page = ceil($total_count/$noofdata);
		$max_page_limit = $page * $noofdata;
		$min_page_limit = $max_page_limit - $noofdata;
		$qry_limit = " LIMIT 0,$noofdata";
		if($page > 1)
			$qry_limit = " LIMIT $min_page_limit,$noofdata";
		
		$subscriber_limited = EmailCampaigns_Record_Model::getSubscribers($recordId, $optout, $qry_limit);
		$subscriber_list = $db->num_rows($subscriber_limited);
		$previous_page = $page - 1;
		$next_page = $page + 1;
		$totalpage = 'Page '.$page.' of '.$max_page;
		if($page == 1) {
			$disablepre = 'disabled';
		}
		if($page == $max_page){
			$disablenxt = 'disabled';
		}

		for($i=0; $i<$subscriber_list; $i++){
			if($related_modulename == 'Leads' || $related_modulename == 'Contacts') {
				$firstname = $db->query_result($subscriber_limited,$i,'firstname');
				$lastname = $db->query_result($subscriber_limited,$i,'lastname');
				$name = $firstname.' '.$lastname;
				$email = $db->query_result($subscriber_limited,$i,'email');
				if($related_modulename == 'Contacts'){
					$id = $db->query_result($subscriber_limited,$i,'contactid');
					$link = "index.php?module=Contacts&view=Detail&record";
				}else if($related_modulename == 'Leads'){
					$id = $db->query_result($subscriber_limited,$i,'leadid');
					$link="index.php?module=Leads&view=Detail&record";
				}	
			} else if($related_modulename == 'Accounts') {
				$name = $db->query_result($subscriber_limited,$i,'accountname');
				$email = $db->query_result($subscriber_limited,$i,'email1');
				$id = $db->query_result($subscriber_limited,$i,'accountid');
				$link="index.php?module=Accounts&view=Detail&record";
			}
			$record_data[$i] = array($name, $email,$id);
		}

		$viewer->assign('MODULE', $module);
		$viewer->assign('DISABLEPRE', $disablepre);
		$viewer->assign('DISABLENXT', $disablenxt);
		$viewer->assign('RELATED_MODULE', $related_modulename);
		$viewer->assign('DISPLAYTOTALPAGE', $totalpage);
		$viewer->assign('PAGE', $page);
		$viewer->assign('LISTTYPE', $optout);
		$viewer->assign('RECORD_DATA', $record_data);
		$viewer->assign('link', $link);
		echo $viewer->view('SubscriberList.tpl',$module, true);
	}

	public function getUniqueLinkClick(Vtiger_Request $request) {
		
		$viewer = $this->getViewer($request);
		$db = PearDatabase::getInstance();
		$module = $request->getModule();
		
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		$page = $request->get('page_no');

		$related_modulename = EmailCampaigns_Record_Model::getEmailCampaignRecordRelatedModule($recordId);
		$linkclickrecords = EmailCampaigns_Record_Model::getUniqueLinkClickRecords($recordId);
		$total_count = $db->num_rows($linkclickrecords);
		$this->initializeListViewContents($request, $viewer,$related_modulename);
		$configdetailmodel = Settings_Vtiger_ConfigModule_Model::getInstance();
		$record = $configdetailmodel->getViewableData();
		$noofdata = $record['list_max_entries_per_page'];
		if($noofdata <= 0){
			$noofdata = 10;
		}
		
		$max_page = ceil($total_count/$noofdata);
		$max_page_limit = $page * $noofdata;
		$min_page_limit = $max_page_limit - $noofdata;
		$qry_limit = " LIMIT 0,$noofdata";
		if($page > 1)
			$qry_limit = " LIMIT $min_page_limit,$noofdata";
		
		$linkclickrecords_limited = EmailCampaigns_Record_Model::getUniqueLinkClickRecords($recordId, $qry_limit);
		$linkclickrecords_list = $db->num_rows($linkclickrecords_limited);
		$previous_page = $page - 1;
		$next_page = $page + 1;
		$totalpage = 'Page '.$page.' of '.$max_page;
		if($page == 1) {
			$disablepre = 'disabled';
		}
		if($page == $max_page){
			$disablenxt = 'disabled';
		}

		for($i=0; $i<$linkclickrecords_list; $i++){
			if($related_modulename == 'Leads' || $related_modulename == 'Contacts') {
				$firstname	= $db->query_result($linkclickrecords_limited,$i,'firstname');
				$lastname 	= $db->query_result($linkclickrecords_limited,$i,'lastname');
				$name = $firstname.' '.$lastname;
				$email 		= $db->query_result($linkclickrecords_limited,$i,'email');
				if($related_modulename == 'Contacts'){
					$id = $db->query_result($linkclickrecords_limited,$i,'contactid');
					$link1 = "index.php?module=Contacts&view=Detail&record";
				}else if($related_modulename == 'Leads'){
					$id = $db->query_result($linkclickrecords_limited,$i,'leadid');
					$link1="index.php?module=Leads&view=Detail&record";
				}	
			} else if($related_modulename == 'Accounts') {
				$name 	= $db->query_result($linkclickrecords_limited,$i,'accountname');
				$email 	= $db->query_result($linkclickrecords_limited,$i,'email1');
				$id = $db->query_result($linkclickrecords_limited,$i,'accountid');
				$link1="index.php?module=Accounts&view=Detail&record";
			}
			$link	= $db->query_result($linkclickrecords_limited,$i,'link');
			$url	= $db->query_result($linkclickrecords_limited,$i,'url');
			$click 	= $db->query_result($linkclickrecords_limited,$i,'click');
			$record_data[$i] = array($name, $email,$link,$url,$click,$id);
		}

		$viewer->assign('MODULE', $module);
		$viewer->assign('DISABLEPRE', $disablepre);
		$viewer->assign('DISABLENXT', $disablenxt);
		$viewer->assign('RELATED_MODULE', $related_modulename);
		$viewer->assign('DISPLAYTOTALPAGE', $totalpage);
		$viewer->assign('PAGE', $page);
		$viewer->assign('RECORD_DATA', $record_data);
		$viewer->assign('link', $link1);
		echo $viewer->view('UniqueLinkClick.tpl',$module, true);
	}
	/**
	 * Function returns the number of records for the current filter
	 * @param Vtiger_Request $request
	 */
	function getRecordsCountCampaign(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$cvId = $request->get('viewname');
		$count = $this->getListViewCountCampaign($request);

		$result = array();
		$result['module'] = $moduleName;
		$result['viewname'] = $cvId;
		$result['count'] = $count;

		$response = new Vtiger_Response();
		$response->setEmitType(Vtiger_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}

	function getListViewCountCampaign(Vtiger_Request $request){
		$campaignid = $request->get('record');
		$recordType = $request->get('recordType');
		$db = PearDatabase::getInstance();
		if($recordType == 'UniqueOpenList')
		{
			$query = "SELECT * FROM  vtiger_email_track 
			INNER JOIN vtiger_emailcamp_sendemail ON vtiger_emailcamp_sendemail.activityid  = vtiger_email_track.mailid 
			WHERE vtiger_emailcamp_sendemail.emailcampaignsid = ? AND vtiger_email_track.access_count > 0";	
		
			$result = $db->pquery($query, array($campaignid));
			return $db->num_rows($result);	
		}
		else
		{
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
			$countRecord = 0;
			foreach ($result as $key => $value) {
				$countRecord = $countRecord + $value[1];
			}
			return $countRecord;
		}
		
	}

	/*
	 * Function to initialize the required data in smarty to display the List View Contents
	 */
	public function initializeListViewContents(Vtiger_Request $request, Vtiger_Viewer $viewer, $related_modulename) {
		$moduleName = $request->getModule();
		//$cvId = $this->viewName;
		$cvId = 1;
		
		$listViewModel = Vtiger_ListView_Model::getInstance($moduleName, $cvId);
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'), 'CVID'=>$cvId);

		$editRecordPermission = Users_Privileges_Model::isPermitted($related_modulename, 'EditView', $record);

		if(!empty($editRecordPermission)) {
			$actionbtn['editRecordPermission'] = $editRecordPermission;
		}

		$calendarRecordPermission = Users_Privileges_Model::isPermitted($related_modulename, 'Calendar', $record);
		if(!empty($calendarRecordPermission)) {
			$actionbtn['calendarRecordPermission'] = $calendarRecordPermission;
		}
		$linkModels = $listViewModel->getListViewMassActions($linkParams);
		
		$pagingModel = new Vtiger_Paging_Model();
	
		$viewer->assign('actionbtn',$actionbtn);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('relatedModule', $related_modulename);
		
		if(!$this->listViewLinks){
			$this->listViewLinks = $listViewModel->getListViewLinks($linkParams);
		}
		
		$viewer->assign('LISTVIEW_LINKS', $this->listViewLinks);

		$viewer->assign('LISTVIEW_MASSACTIONS', $linkModels['LISTVIEWMASSACTION']);	
		
	}
}
