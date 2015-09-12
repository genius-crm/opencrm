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
 * Vtiger Entity Record Model Class
 */
class GreenTimeControl_Totaltime_View extends Vtiger_Index_View {
	
	public function process(Vtiger_Request $request) {
		global $log, $adb;
		$log->debug("Entering Potentials_Record_Model::getSummaryInfo() method ...");
		
		$moduleName = $request->get('module');
		$realtedmoduleName = strtolower($request->get('source_module'));
		$field_name = $realtedmoduleName.'id';
		
		$query ='SELECT SUM(sum_time) as sum FROM vtiger_greentimecontrol
				INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_greentimecontrol.greentimecontrolid
				WHERE vtiger_crmentity.deleted=0 AND  vtiger_greentimecontrol.'.$field_name.' = ? AND greentimecontrol_status = ?';
		$result = $adb->pquery($query, array($request->get('record'), 'Accepted'));
		
		$time = $adb->query_result($result, 0, 'sum');
		
		$hour = floor($time);
		$min = round(60*($time - $hour));
		$full_sum_time_all = $hour.vtranslate('LBL_HOURS').' '.$min.vtranslate('LBL_MINUTES');
		$short_sum_time_all = $hour.vtranslate('LBL_H').' '.$min.vtranslate('LBL_M');
		$total_time_lable = vtranslate('Total time',$moduleName);

		echo "<div class='row-fluid textAlignCenter roundedCorners'>
					<label class='font-x-small'>$total_time_lable</label>
					<label class='font-x-x-large'>$full_sum_time_all</label>
		</div>";
	}
}
