<?php
/*************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Ondemand Commercial
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class ChartsWidget_ChartDetail_Dashboard extends Vtiger_IndexAjax_View {

	function process(Vtiger_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$record = $request->get('record');

		$reportModel = Reports_Record_Model::getInstanceById($record);
		$reportChartModel = Reports_Chart_Model::getInstanceById($reportModel);

		$linkId = $request->get('linkid');
		$widget = Vtiger_Widget_Model::getInstance($linkId, $currentUser->getId());

		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('MODULE', $moduleName);

		$viewer->assign('RECORD_ID', $record);
		$viewer->assign('RECORD', $record);

		$scale = "0.8";
		$css_margin = "";
		if ($reportChartModel->getChartType()=="pieChart") {
			$scale = "0.55";
			$css_margin = "margin-left:90px;";
		}
		
		$viewer->assign('SCALE', $scale);
		$viewer->assign('CSS_MARGIN', $css_margin);

		$viewer->assign('WIDGET', $widget);

		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/ChartDetailContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/ChartDetail.tpl', $moduleName);
		}
	}

}
