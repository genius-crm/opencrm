<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * ChartsWidget ListView Model Class
 */
class ChartsWidget_ListView_Model extends Vtiger_ListView_Model {

	/**
	 * Function to get the list of listview links for the module
	 * @return <Array> - Associate array of Link Type to List of Vtiger_Link_Model instances
	 */
	/**
	 * Function to get the list of Mass actions for the module
	 * @param <Array> $linkParams
	 * @return <Array> - Associative array of Link type to List of  Vtiger_Link_Model instances for Mass Actions
	 */
	public function getListViewMassActions() {
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$massActionLinks = array();
		if($currentUserModel->hasModulePermission($this->getModule()->getId())) {
			$massActionLinks[] = array(
					'linktype' => 'LISTVIEWMASSACTION',
					'linklabel' => 'LBL_CW_ACTIVATE',
					'linkurl' => 'javascript:ChartsWidget_List_Js.massActive("index.php?module=ChartsWidget&action=MassActive");',
					'linkicon' => ''
			);

			$massActionLinks[] = array(
					'linktype' => 'LISTVIEWMASSACTION',
					'linklabel' => 'LBL_CW_INACTIVATE',
					'linkurl' => 'javascript:ChartsWidget_List_Js.massInactive("index.php?module=ChartsWidget&action=MassInactive");',
					'linkicon' => ''
			);
		}

		foreach($massActionLinks as $massActionLink) {
			$links[] = Vtiger_Link_Model::getInstanceFromValues($massActionLink);
		}

		return $links;
	}

	/**
	 * Function to get the list view header
	 * @return <Array> - List of Vtiger_Field_Model instances
	 */
	public function getListViewHeaders() {
		return array(
				'reportname'=>'Report Name',
				'description'=>'Description',
				'widgetstatus'=>'Status'
		);
	}

	/**
	 * Function to get the list view entries
	 * @param Vtiger_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Vtiger_Record_Model instance.
	 */
	public function getListViewEntries($pagingModel) {
		$reportFolderModel = ChartsWidget_Folder_Model::getInstance();
		$reportFolderModel->set('folderid', $this->get('folderid'));

		$orderBy = $this->get('orderby');
		if (!empty($orderBy) && $orderBy === 'smownerid') {
			$fieldModel = Vtiger_Field_Model::getInstance('assigned_user_id', $moduleModel);
			if ($fieldModel->getFieldDataType() == 'owner') {
				$orderBy = 'COALESCE(CONCAT(vtiger_users.first_name,vtiger_users.last_name),vtiger_groups.groupname)';
			}
		}
		if(!empty($orderBy)) {
			$reportFolderModel->set('orderby', $orderBy);
			$reportFolderModel->set('sortby', $this->get('sortorder'));
		}

		$reportRecordModels = $reportFolderModel->getReports($pagingModel);
		$pagingModel->calculatePageRange($reportRecordModels);
		return $reportRecordModels;
	}

	/**
	 * Function to get the list view entries count
	 * @return <Integer>
	 */
	public function getListViewCount() {
		$reportFolderModel = ChartsWidget_Folder_Model::getInstance();
		$reportFolderModel->set('folderid', $this->get('folderid'));
		return $reportFolderModel->getReportsCount();
	}

}
