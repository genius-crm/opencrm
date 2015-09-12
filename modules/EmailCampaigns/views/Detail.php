<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class EmailCampaigns_Detail_View extends Vtiger_Detail_View {

	function process(Vtiger_Request $request) {
	$viewer = $this->getViewer($request);
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		if(!$this->record){
			$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		}
		
		parent::process($request);
	}

	function showRelatedList(Vtiger_Request $request) {
	    
		if($request->get('tab_label') == 'Report'){
			$moduleName = $request->getModule();
			$parentId = $request->get('record');
			$sent_mails = EmailCampaigns_Record_Model::getTotalSentEmailCount($parentId);
			$uniqopen = EmailCampaigns_Record_Model::getUniqOpenCount($parentId);
			$link_list = EmailCampaigns_Record_Model::getLinkList($parentId);

			if($sent_mails == 0) {
				$uniqopenpercentage = 0;
			} else {
				$uniqopenpercentage = ($uniqopen*100)/$sent_mails;
			}

			$db = PearDatabase::getInstance();
			$unsubscriber = EmailCampaigns_Record_Model::getSubscribers($parentId, 1);
			$unsubscriber_count = $db->num_rows($unsubscriber);
			$query = "select * from vtiger_emailcampaigns where emailcampaignsid=$parentId";
			$result1=$db->pquery($query, array());
			$related_recorts = $db->query_result($result1,0,'related_recorts');
			$count_sub =count(explode(',',$related_recorts));

			$viewer = $this->getViewer($request);
			$viewer->assign('MODULE', $moduleName);
			$viewer->assign('RECORDID', $parentId);
			$viewer->assign('SENTMAILS', $sent_mails);
			$viewer->assign('UNIQUEOPEN',$uniqopen);
			$viewer->assign('UNIQUEOPENPERCENT',$uniqopenpercentage);
			$viewer->assign('SUBSCRIBER',$count_sub);
			$viewer->assign('UNSUBSCRIBER',$unsubscriber_count);
			$viewer->assign('LINK_LIST',$link_list);
			$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

			return $viewer->view('Report.tpl', $moduleName, 'true');
		} else {
			$moduleName = $request->getModule();
			$relatedModuleName = $request->get('relatedModule');
			$parentId = $request->get('record');
			$label = $request->get('tab_label');
			$requestedPage = $request->get('page');
			if(empty($requestedPage)) {
				$requestedPage = 1;
			}

			$pagingModel = new Vtiger_Paging_Model();
			$pagingModel->set('page',$requestedPage);

			$parentRecordModel = Vtiger_Record_Model::getInstanceById($parentId, $moduleName);
			$relationListView = Vtiger_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName, $label);
			$orderBy = $request->get('orderby');
			$sortOrder = $request->get('sortorder');
			if($sortOrder == 'ASC') {
				$nextSortOrder = 'DESC';
				$sortImage = 'icon-chevron-down';
			} else {
				$nextSortOrder = 'ASC';
				$sortImage = 'icon-chevron-up';
			}
			if(!empty($orderBy)) {
				$relationListView->set('orderby', $orderBy);
				$relationListView->set('sortorder',$sortOrder);
			}
			$models = $relationListView->getEntries($pagingModel);
			$links = $relationListView->getLinks();
			$header = $relationListView->getHeaders();
			$noOfEntries = count($models);

			$relationModel = $relationListView->getRelationModel();
			$relatedModuleModel = $relationModel->getRelationModuleModel();
			$relationField = $relationModel->getRelationField();

			$viewer = $this->getViewer($request);
			$viewer->assign('RELATED_RECORDS' , $models);
			$viewer->assign('PARENT_RECORD', $parentRecordModel);
			$viewer->assign('RELATED_LIST_LINKS', $links);
			$viewer->assign('RELATED_HEADERS', $header);
			$viewer->assign('RELATED_MODULE', $relatedModuleModel);
			$viewer->assign('RELATED_ENTIRES_COUNT', $noOfEntries);
			$viewer->assign('RELATION_FIELD', $relationField);

			if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
				$totalCount = $relationListView->getRelatedEntriesCount();
				$pageLimit = $pagingModel->getPageLimit();
				$pageCount = ceil((int) $totalCount / (int) $pageLimit);

				if($pageCount == 0){
					$pageCount = 1;
				}
				$viewer->assign('PAGE_COUNT', $pageCount);
				$viewer->assign('TOTAL_ENTRIES', $totalCount);
				$viewer->assign('PERFORMANCE', true);
			}

			$viewer->assign('MODULE', $moduleName);
			$viewer->assign('PAGING', $pagingModel);

			$viewer->assign('ORDER_BY',$orderBy);
			$viewer->assign('SORT_ORDER',$sortOrder);
			$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
			$viewer->assign('SORT_IMAGE',$sortImage);
			$viewer->assign('COLUMN_NAME',$orderBy);

			$viewer->assign('IS_EDITABLE', $relationModel->isEditable());
			$viewer->assign('IS_DELETABLE', $relationModel->isDeletable());
			$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
			$viewer->assign('VIEW', $request->get('view'));

			if($request->get('tab_label') == 'emails') {
			  $tplfilename = 'EmailRelatedList.tpl';
			} else {
			  $tplfilename = 'RelatedList.tpl';
			}

			return $viewer->view($tplfilename, $moduleName, 'true');
		}
	}
}
