<?php
/*+***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 *************************************************************************************************************************************/
class TimeControlHandler extends VTEventHandler {
	function handleEvent($eventName, $data) {
		$moduleName = $data->getModuleName();
		$record_id = $data->getId();
		switch ($eventName) {
			case 'vtiger.entity.aftersave.final':
				if ($moduleName == 'GreenTimeControl') {
					$relatedToId = $data->get('related_to');
					$start = strtotime( $data->get('date_start').' '.$data->get('time_start') );
					$end = strtotime( $data->get('due_date').' '.$data->get('time_end') );
					$time = round(abs($end - $start) / 3600,2);
					$data->set('sum_time',$time);     
					$db = PearDatabase::getInstance();
					$db->pquery( "UPDATE vtiger_greentimecontrol SET sum_time = ? WHERE greentimecontrolid = ?;" , array($time,$record_id), true );
					GreenTimeControl_Record_Model::recalculateTimeControl($data);
					GreenTimeControl_Record_Model::recalculateTimeOldValues($record_id, $data);
				}
				if ($moduleName == 'HelpDesk') {
					GreenTimeControl_Record_Model::recalculateProject( $data->get('projectid') );
				}
				if ($moduleName == 'ProjectTask') {
					GreenTimeControl_Record_Model::recalculateProject( $data->get('projectid') );
				}
				if ($moduleName == 'Project') {
					GreenTimeControl_Record_Model::recalculateServiceContracts( $data->get('servicecontractsid') );
				}
				if ($moduleName == 'Quotes') {
					GreenTimeControl_Record_Model::recalculatePotentials( $data->get('potential_id') );
				}
				if ($moduleName == 'Calculations') {
					GreenTimeControl_Record_Model::recalculatePotentials( $data->get('potentialid') );
				}
			break;
			case 'vtiger.entity.afterunlink':
				if ($moduleName == 'GreenTimeControl') {
					GreenTimeControl_Record_Model::recalculateTimeOldValues($record_id, $data);
				}
			break;
			case 'vtiger.entity.afterdelete':
				if ($moduleName == 'GreenTimeControl') {
					$db = PearDatabase::getInstance();
					$db->pquery( "UPDATE vtiger_greentimecontrol SET deleted = ? WHERE greentimecontrolid = ?;", array( 1, $record_id ), true );
					GreenTimeControl_Record_Model::recalculateTimeControl($data);
				}
			break;
		}
	}
}