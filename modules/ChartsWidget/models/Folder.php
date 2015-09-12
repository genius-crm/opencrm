<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class ChartsWidget_Folder_Model extends Vtiger_Base_Model {

	/**
	 * Function to get the id of the folder
	 * @return <Number>
	 */
	function getId() {
		return $this->get('folderid');
	}

	/**
	 * Function to set the if for the folder
	 * @param <Number>
	 */
	function setId($value) {
		$this->set('folderid', $value);
	}

	/**
	 * Function to get the name of the folder
	 * @return <String>
	 */
	function getName() {
		return $this->get('foldername');
	}
    
	/**
	 * Function returns the instance of Folder model
	 * @return <Reports_Folder_Model>
	 */
	public static function getInstance() {
		return new self();
	}

	/**
	 * Function returns Report Models for the folder
	 * @param <Vtiger_Paging_Model> $pagingModel
	 * @return <Reports_Record_Model>
	 */
	function getReports($pagingModel) {
		$paramsList = array(
						'startIndex'=>$pagingModel->getStartIndex(),
						'pageLimit'=>$pagingModel->getPageLimit(),
						'orderBy'=>$this->get('orderby'),
						'sortBy'=>$this->get('sortby'));

		//$reportClassInstance = Vtiger_Module_Model::getClassInstance('Reports');
		
		$fldrId = $this->getId ();
		if($fldrId == 'All') {
			$fldrId = false;
			$paramsList = array( 'startIndex'=>$pagingModel->getStartIndex(),
								 'pageLimit'=>$pagingModel->getPageLimit(),
								 'orderBy'=>$this->get('orderby'),
								 'sortBy'=>$this->get('sortby')
							);
		}
		
		$reportsList = $this->sgetRptsforFldr($fldrId, $paramsList);
		if(!$fldrId){
			foreach ($reportsList as $reportId => $reports) {
				$reportsCount += count($reports);
			}
		}else{
			$reportsCount = count($reportsList);
		}
		
		$pageLimit = $pagingModel->getPageLimit();
		if($reportsCount > $pageLimit){
			if(!$fldrId){
				$lastKey = end(array_keys($reportsList));
				array_pop($reportsList[$lastKey]);
			}else{
				array_pop($reportsList);
			}
			$pagingModel->set('nextPageExists', true);
		}else{
			$pagingModel->set('nextPageExists', false);
		}
		
		$reportModuleModel = Vtiger_Module_Model::getInstance('Reports');
		
		if($fldrId == false) {
			return $this->getAllReportModels($reportsList, $reportModuleModel);
		} else {
			$reportModels = array();
			for($i=0; $i < count($reportsList); $i++) {
				$reportModel = new Reports_Record_Model();

				$reportModel->setData($reportsList[$i])->setModuleFromInstance($reportModuleModel);
				$reportModels[] = $reportModel;
				unset($reportModel);
			}
			return $reportModels;
		}
	}

	/**
	 * Function to get the description of the folder
	 * @return <String>
	 */
	function getDescription() {
		return $this->get('description');
	}

	/**
	 * Function returns the instance of Folder model
	 * @param FolderId
	 * @return <Reports_Folder_Model>
	 */
	public static function getInstanceById($folderId) {
		$folderModel = Vtiger_Cache::get('reportsFolder',$folderId);
        if(!$folderModel){
            $db = PearDatabase::getInstance();
            $folderModel = Reports_Folder_Model::getInstance();

            $result = $db->pquery("SELECT * FROM vtiger_reportfolder WHERE folderid = ?", array($folderId));

            if ($db->num_rows($result) > 0) {
                $values = $db->query_result_rowdata($result, 0);
                $folderModel->setData($values);
            }
            Vtiger_Cache::set('reportsFolder',$folderId,$folderModel);
        }
        return $folderModel;
	}
    
    /**
     * Function returns the instance of Folder model
     * @return <Reports_Folder_Model>
     */
    public static function getAll() {
        $db = PearDatabase::getInstance();
        $folders = Vtiger_Cache::get('reports', 'folders');
        if (!$folders) {
            $folders = array();
            $result = $db->pquery("SELECT * FROM vtiger_reportfolder ORDER BY foldername ASC", array());
            $noOfFolders = $db->num_rows($result);
            if ($noOfFolders > 0) {
                for ($i = 0; $i < $noOfFolders; $i++) {
                    $folderModel = Reports_Folder_Model::getInstance();
                    $values = $db->query_result_rowdata($result, $i);
                    $folders[$values['folderid']] = $folderModel->setData($values);
                    Vtiger_Cache::set('reportsFolder',$values['folderid'],$folderModel);
                }
            }
            Vtiger_Cache::set('reports','folders',$folders);
        }
        return $folders;
    }

    /**
	 * Function returns duplicate record status of the module
	 * @return true if duplicate records exists else false
	 */
	function checkDuplicate() {
		$db = PearDatabase::getInstance();

		$query = 'SELECT 1 FROM vtiger_reportfolder WHERE foldername = ?';
		$params = array($this->getName());

		$folderId = $this->getId();
		if ($folderId) {
			$query .= ' AND folderid != ?';
			array_push($params, $folderId);
		}

		$result = $db->pquery($query, $params);

		if ($db->num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Function returns whether reports are exist or not in this folder
	 * @return true if exists else false
	 */
	function hasReports() {
		$db = PearDatabase::getInstance();

		$result = $db->pquery('SELECT 1 FROM vtiger_report WHERE folderid = ?', array($this->getId()));

		if ($db->num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Function returns whether folder is Default or not
	 * @return true if it is read only else false
	 */
	function isDefault() {
        if ($this->get('state') == 'SAVED') {
			return true;
		}
		return false;
	}
	
	/**
	 * Function to get info array while saving a folder
	 * @return Array  info array
	 */
	public function getInfoArray() {
		return array('folderId' => $this->getId(),
			'folderName' => $this->getName(),
			'editURL' => $this->getEditUrl(),
			'deleteURL' => $this->getDeleteUrl(),
			'isEditable' => $this->isEditable(),
			'isDeletable' => $this->isDeletable());
	}

	/**
	 * Function to check whether folder is editable or not
	 * @return <boolean>
	 */
	public function isEditable() {
		if ($this->isDefault()) {
			return false;
		}
		return true;
	}

	/**
	 * Function to get check whether folder is deletable or not
	 * @return <boolean>
	 */
	public function isDeletable() {
		if ($this->isDefault()) {
			return false;
		}
		return true;
	}

	/**
	 * Function to calculate number of reports in this folder
	 * @return <Integer>
	 */
	public function getReportsCount() {
		$db = PearDatabase::getInstance();
		$params = array();
		
        // To get the report ids which are permitted for the user
            $query = "SELECT reportmodulesid, primarymodule from vtiger_reportmodules";
            $result = $db->pquery($query, array());
            $noOfRows = $db->num_rows($result);
            $allowedReportIds = array();
            for($i=0;$i<$noOfRows;$i++){
                $primaryModule = $db->query_result($result,$i,'primarymodule');
                $reportid = $db->query_result($result,$i,'reportmodulesid');
                if(isPermitted($primaryModule,'index') == "yes"){
                    $allowedReportIds[] = $reportid;
                }
            }
        //End
		$sql = "SELECT count(*) AS count FROM vtiger_report
				INNER JOIN vtiger_reportfolder ON vtiger_reportfolder.folderid = vtiger_report.folderid AND 
                vtiger_report.reportid in (".implode(',',$allowedReportIds).")";
		$fldrId = $this->getId();
		if($fldrId == 'All') {
			$fldrId = false;
		}

		// If information is required only for specific report folder?
		if($fldrId !== false) {
			$sql .= " WHERE vtiger_reportfolder.folderid=? AND vtiger_report.reporttype='Chart'";
			array_push($params,$fldrId);
		}
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserModel->isAdminUser()) {
			$currentUserId = $currentUserModel->getId();
			
			$groupId = implode(',',$currentUserModel->get('groups'));
			if ($groupId) {
				$groupQuery = "(SELECT reportid from vtiger_reportsharing WHERE shareid IN ($groupId) AND setype = 'groups') OR ";
			}
			
			$sql .= " AND (vtiger_report.reportid IN (SELECT reportid from vtiger_reportsharing WHERE $groupQuery shareid = ? AND setype = 'users')
						OR vtiger_report.sharingtype = 'Public'
						OR vtiger_report.owner = ?
						OR vtiger_report.owner IN (SELECT vtiger_user2role.userid FROM vtiger_user2role
													INNER JOIN vtiger_users ON vtiger_users.id = vtiger_user2role.userid
													INNER JOIN vtiger_role ON vtiger_role.roleid = vtiger_user2role.roleid
													WHERE vtiger_role.parentrole LIKE ?))";

			$parentRoleSeq = $currentUserModel->get('parent_role_seq').'::%';
			array_push($params, $currentUserId, $currentUserId, $parentRoleSeq);
		}
		$result = $db->pquery($sql, $params);
		return $db->query_result($result, 0, 'count');
	}
	
    /**
	 * Function to get all Report Record Models
	 * @param <Array> $allReportsList
	 * @param <Vtiger_Module_Model> - Reports Module Model
	 * @return <Array> Reports Record Models
	 */
	public function getAllReportModels($allReportsList, $reportModuleModel){
		$allReportModels = array();
        $folders = self::getAll();
		foreach ($allReportsList as $key => $reportsList) {
			for($i=0; $i < count($reportsList); $i++) {
				$reportModel = new Reports_Record_Model();
				$reportModel->setData($reportsList[$i])->setModuleFromInstance($reportModuleModel);
				$reportModel->set('foldername', $folders[$key]->getName());
				$allReportModels[] = $reportModel;
				unset($reportModel);
			}
		}
		return $allReportModels;
	}
    
     /**
	 * Function which provides the records for the current view
	 * @param <Boolean> $skipRecords - List of the RecordIds to be skipped
	 * @return <Array> List of RecordsIds
	 */
	public function getRecordIds($skipRecords=false, $module) {
		$db = PearDatabase::getInstance();
        $baseTableName = "vtiger_report";
        $baseTableId = "reportid";
		$folderId = $this->getId();
		$listQuery = $this->getListViewQuery($folderId);

		if($skipRecords && !empty($skipRecords) && is_array($skipRecords) && count($skipRecords) > 0) {
			$listQuery .= ' AND '.$baseTableName.'.'.$baseTableId.' NOT IN ('. implode(',', $skipRecords) .')';
		}
		$result = $db->query($listQuery);
		$noOfRecords = $db->num_rows($result);
		$recordIds = array();
		for($i=0; $i<$noOfRecords; ++$i) {
			$recordIds[] = $db->query_result($result, $i, $baseTableId);
		}
		return $recordIds;
	}
    
    /**
	 * Function returns Report Models for the folder
	 * @return <Reports_Record_Model>
	 */
	function getListViewQuery($folderId) {
        $sql = "select vtiger_report.*, vtiger_reportmodules.*, vtiger_reportfolder.folderid from vtiger_report 
                inner join vtiger_reportfolder on vtiger_reportfolder.folderid = vtiger_report.folderid 
                inner join vtiger_reportmodules on vtiger_reportmodules.reportmodulesid = vtiger_report.reportid 
				where vtiger_report.reporttype='Chart' ";
        
        if($folderId != "All") {
                $sql = $sql." and vtiger_reportfolder.folderid = ".$folderId;
        }        
        return $sql;
	}

	function sgetRptsforFldr($rpt_fldr_id, $paramsList=false)
	{
		$srptdetails="";
		global $adb;
		global $log;
		global $mod_strings,$current_user;
		$returndata = Array();

		require_once('include/utils/UserInfoUtil.php');

		$sql = "select vtiger_report.*, vtiger_reportmodules.*, vtiger_reportfolder.folderid, ";
		$sql .= "   case when ifnull(vtiger_chartswidget.active,'0') = 1 then 'Active' else 'Inactive' end as widgetstatus ";
		$sql .= "from vtiger_report inner join vtiger_reportfolder on vtiger_reportfolder.folderid = vtiger_report.folderid ";
		$sql .= " inner join vtiger_reportmodules on vtiger_reportmodules.reportmodulesid = vtiger_report.reportid ";
		$sql .= " left outer join vtiger_chartswidget on vtiger_report.reportid = vtiger_chartswidget.chart_id ";

		$sql .= " where vtiger_report.reporttype = 'chart' ";
		
		$params = array();

		// If information is required only for specific report folder?
		if($rpt_fldr_id !== false) {
			$sql .= " and vtiger_reportfolder.folderid=?";
			$params[] = $rpt_fldr_id;
		}

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require_once('include/utils/GetUserGroups.php');
		$userGroups = new GetUserGroups();
		$userGroups->getAllUserGroups($current_user->id);
		$user_groups = $userGroups->user_groups;
		if(!empty($user_groups) && $is_admin==false){
			$user_group_query = " (shareid IN (".generateQuestionMarks($user_groups).") AND setype='groups') OR";
			array_push($params, $user_groups);
		}

		$non_admin_query = " vtiger_report.reportid IN (SELECT reportid from vtiger_reportsharing WHERE $user_group_query (shareid=? AND setype='users'))";
		if($is_admin==false){
			$sql .= " and ( (".$non_admin_query.") or vtiger_report.sharingtype='Public' or vtiger_report.owner = ? or vtiger_report.owner in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%'))";
			array_push($params, $current_user->id);
			array_push($params, $current_user->id);
		}
		if ($paramsList) {
			$startIndex = $paramsList['startIndex'];
			$pageLimit = $paramsList['pageLimit'];
			$orderBy = $paramsList['orderBy'];
			$sortBy = $paramsList['sortBy'];
			if ($orderBy) {
				$sql .= " ORDER BY $orderBy $sortBy";
			}
			$sql .= " LIMIT $startIndex,".($pageLimit+1);
		}
		$query = $adb->pquery("select userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%'",array());
		$subordinate_users = Array();
		for($i=0;$i<$adb->num_rows($query);$i++){
			$subordinate_users[] = $adb->query_result($query,$i,'userid');
		}
		$result = $adb->pquery($sql, $params);

		$report = $adb->fetch_array($result);
		if(count($report)>0)
		{
			do
			{
				$report_details = Array();
				$report_details ['customizable'] = $report["customizable"];
				$report_details ['reportid'] = $report["reportid"];
				$report_details ['primarymodule'] = $report["primarymodule"];
				$report_details ['secondarymodules'] = $report["secondarymodules"];
				$report_details ['state'] = $report["state"];
				$report_details ['widgetstatus'] = $report["widgetstatus"];
				$report_details ['description'] = $report["description"];
				$report_details ['reportname'] = $report["reportname"];
                $report_details ['reporttype'] = $report["reporttype"];
				$report_details ['sharingtype'] = $report["sharingtype"];
				if($is_admin==true || in_array($report["owner"],$subordinate_users) || $report["owner"]==$current_user->id)
					$report_details ['editable'] = 'true';
				else
					$report_details['editable'] = 'false';

				if(isPermitted($report["primarymodule"],'index') == "yes")
					$returndata [$report["folderid"]][] = $report_details;
			}while($report = $adb->fetch_array($result));
		}

		if($rpt_fldr_id !== false) {
			$returndata = $returndata[$rpt_fldr_id];
		}

		$log->info("Reports :: ListView->Successfully returned vtiger_report details HTML");
		return $returndata;
	}
	
}