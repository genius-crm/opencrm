<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class ChartsWidget_MassActive_Action extends Vtiger_Mass_Action {

	public function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance('Reports');

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	function preProcess(Vtiger_Request $request) {
		return true;
	}

	function postProcess(Vtiger_Request $request) {
		return true;
	}

	public function process(Vtiger_Request $request) {
		global $adb;

		$parentModule = 'ChartsWidget';
		$recordIds = Reports_Record_Model::getRecordsListFromRequest($request);

		foreach($recordIds as $recordId) {

			$query= "select * from vtiger_chartswidget where chart_id = ?;";
			$result = $adb->pquery($query, array($recordId));
			$row = $adb->query_result($result);
			if ($row) {
				$query= "update vtiger_chartswidget set active = 1 where chart_id = ?;";
			} else {
				$query= "insert into vtiger_chartswidget (chart_id,active) values (?, 1);";
			}
			$adb->pquery($query, array($recordId));

			$detailViewModel = Reports_DetailView_Model::getInstance('Reports', $recordId);
			$reportModel = $detailViewModel->getRecord();
			
			$dashboardsArray = array("Home", "Leads", "Accounts", "Contacts", "Potentials", "HelpDesk");
			foreach ($dashboardsArray as $module) {
				$thisModuleInstance = Vtiger_Module::getInstance($module);
				if ($thisModuleInstance) {
					$thisModuleInstance->deleteLink("DASHBOARDWIDGET", $reportModel->get('reportname') . " - Report ".$recordId, "index.php?module=ChartsWidget&view=ShowWidget&name=ChartDetail&record=".$recordId);
					$thisModuleInstance->addLink("DASHBOARDWIDGET", $reportModel->get('reportname') . " - Report ".$recordId, "index.php?module=ChartsWidget&view=ShowWidget&name=ChartDetail&record=".$recordId);
					$thisModuleInstance->deleteLink("HEADERSCRIPT", "ChartsWidgetDetail", "modules/ChartsWidget/resources/Detail.js");
					$thisModuleInstance->addLink("HEADERSCRIPT", "ChartsWidgetDetail", "modules/ChartsWidget/resources/Detail.js");
					$thisModuleInstance->deleteLink("HEADERSCRIPT", "ChartsWidgetChartDetail", "modules/ChartsWidget/resources/ChartDetail.js");
					$thisModuleInstance->addLink("HEADERSCRIPT", "ChartsWidgetChartDetail", "modules/ChartsWidget/resources/ChartDetail.js");
				}
			}
			
		}
	
		$response = new Vtiger_Response();
		$response->setResult(array(vtranslate('LBL_CW_ACTIVE_SUCCESSFULLY', $parentModule)));

		$response->emit();
	}
}
