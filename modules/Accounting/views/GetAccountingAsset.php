<?php

/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class Accounting_GetAccountingAsset_View extends Vtiger_BasicAjax_View {

    public function process(Vtiger_Request $request) {
        $current_user = $cu_model = Users_Record_Model::getCurrentUserModel();
        $currentLanguage = Vtiger_Language_Handler::getLanguage();
        
        $adb = PearDatabase::getInstance();
        $viewer = $this->getViewer($request);
        $record = $request->get('record');
        $module = getSalesEntityType($record);

        $viewer->assign('MODULE', $module);
        $viewer->assign('ID', $record);

        $relfocus = CRMEntity::getInstance($module);
        $relfocus->id = $record;
        $relfocus->retrieve_entity_info($relfocus->id, $module);
		//get related module information
		$module_info = $relfocus->column_fields;
		$relmodulename = $module_info[record_module];
	//	$debug = print_r($module_info); // debug 
		//variance add relation
		$currency_id = $current_user->currency_id;
		$currency_place = 0;
		$currencySymbolPlacement = $current_user->currency_symbol_placement;
		if($currencySymbolPlacement == '$1.0'){
			$currency_place = 1;
		}
		$sql_currency = "select * from vtiger_currency_info where id = $currency_id"; 
		$res_currency = $adb->query($sql_currency);
		$currency_symbol = $adb->query_result($res_currency,0,'currency_symbol');
		
			$sql_relation = "select vtiger_field.fieldname from vtiger_field
				INNER JOIN vtiger_fieldmodulerel
					ON vtiger_fieldmodulerel.fieldid = vtiger_field.fieldid
				where vtiger_fieldmodulerel.relmodule = '$module' and vtiger_fieldmodulerel.module = 'Accounting'"; 
			$res_lable_relation = $adb->query($sql_relation);
			if($adb->num_rows($res_lable_relation) > 0){
				$relation = $adb->query_result($res_lable_relation,0,'fieldname');
				$relation_module = "&".$relation."=".$record;
				// populate accounting_title and account_name with values from relatedmodule
				if($relmodulename == 'HelpDesk') {
					$ticket_no = $module_info[ticket_no];
					$accounting_title = $ticket_no." - ".date("d/m/y");
					$account_name = $module_info[parent_id];
					$relation_module = $relation_module."&accounting=".$accounting_title."&relation1=".$account_name;
				}
				if($relmodulename == 'Project') {
					$project_no = $module_info[project_no];
					$accounting_title = $project_no." - ".date("d/m/y");
					$account_name = $module_info[linktoaccountscontacts];
					$relation_module = $relation_module."&accounting=".$accounting_title."&relation1=".$account_name;
				}
				$viewer->assign("relation_module", $relation_module);
			}
		//variance end change for relation		
		//variance add for header parts
			$tabid= getTabid('Accounting');
			$table_name = 'green_accounting';
			$sql = "select vtiger_field.columnname,vtiger_field.fieldlabel from vtiger_field where tabid = $tabid and tablename='$table_name'"; 
			$res_lable = $adb->query($sql);
			$required_lable = array('accounting','total','accounting_no');
			$header = array();
				if($adb->num_rows($res_lable) > 0){
					for($j=0;$j<$adb->num_rows($res_lable);$j++){
						if(in_array($adb->query_result($res_lable,$j,'columnname'),$required_lable)){
							$header[$adb->query_result($res_lable,$j,'columnname')] = $adb->query_result($res_lable,$j,'fieldlabel');
						}
					}
					$header['assigned_user_id'] = 'Assigned To';
					//print_r($header);
					$viewer->assign("HEADER", $header);
				}
		//end change header parts
		//variance add for Expense Records
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
			$query = "SELECT case when (vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname end as user_name,
				green_accounting.*, vtiger_crmentity.crmid, vtiger_crmentity.smownerid,
				vtiger_crmentity.modifiedtime
				from green_accounting
				INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = green_accounting.accountingid
				INNER JOIN green_accountingcf ON green_accountingcf.accountingid = green_accounting.accountingid
				LEFT JOIN vtiger_groups ON vtiger_groups.groupid=vtiger_crmentity.smownerid
				LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
				WHERE vtiger_crmentity.deleted=0
				AND green_accounting.paymenttype='Expense'
				AND (green_accounting.relation1=$record or green_accounting.relation2=$record or green_accounting.relation3=$record)";
			$res = $adb->query($query);
			$Expense_Records = array();
			$Total_Expense = 0.00;
            if ($adb->num_rows($res) > 0){
				for($j=0;$j<$adb->num_rows($res);$j++){
					$total = '';
					$Expense_Records[$j]['ACCOUNTINGID'] = $adb->query_result($res,$j,'accountingid');
					$Expense_Records[$j]['assigned_user_id'] = $adb->query_result($res,$j,'user_name');
					$Expense_Records[$j]['accounting'] = $adb->query_result($res,$j,'accounting');
					$currency_id_item = $adb->query_result($res,$j,'currency_id');
					$conversionRate = $adb->query_result($res,$j,'conversion_rate');
					if($currency_id == 1){
						$total = round($adb->query_result($res,$j,'subtotal')/$conversionRate,2);
					} else if($currency_id == $currency_id_item){
						$total = round(CurrencyField::convertToUserFormat($adb->query_result($res,$j,'subtotal'), null, true, true),2);
					} else {
						$total = round(CurrencyField::convertToUserFormat($adb->query_result($res,$j,'subtotal'), null, false, true),2);
					}
					$Expense_Records[$j]['total'] = $total;
					$Expense_Records[$j]['accounting_no'] = $adb->query_result($res,$j,'accounting_no');
					$accountingstatus = $adb->query_result($res,$j,'accountingstatus');
					$duedate = $adb->query_result($res,$j,'duedate');
					$today = date('Y-m-d');
					if($accountingstatus=='Pending' && $duedate < $today){
						$Expense_Records[$j]['COLOR_CODE'] = '#FF0000';
					}else if($accountingstatus=='Paid'){
						$Expense_Records[$j]['COLOR_CODE'] = '#33CC00';
					}else{
						$Expense_Records[$j]['COLOR_CODE'] = '';
					}
					$Total_Expense += $total;
				}
			}
			$viewer->assign("EXPENSE_RECORDS", $Expense_Records);
			$viewer->assign("CURRENCY_SYMBOL", $currency_symbol);
			$viewer->assign("CURRENCY_PLACE", $currency_place);
			$viewer->assign("TOTAL_EXPENSE", $Total_Expense);
		//variance End Change for Expense Records
			
		//variance add for Income Records
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
			 $query1 = "SELECT case when (vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname end as user_name,
				green_accounting.*, vtiger_crmentity.crmid, vtiger_crmentity.smownerid,
				vtiger_crmentity.modifiedtime
				from green_accounting
				INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = green_accounting.accountingid
				INNER JOIN green_accountingcf ON green_accountingcf.accountingid = green_accounting.accountingid
				LEFT JOIN vtiger_groups ON vtiger_groups.groupid=vtiger_crmentity.smownerid
				LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
				WHERE vtiger_crmentity.deleted=0
				AND green_accounting.paymenttype='Income'
				AND (green_accounting.relation1=$record or green_accounting.relation2=$record or green_accounting.relation3=$record)";
			$res1 = $adb->query($query1);
			$Income_Records = array();
			$Total_Income = 0.00;
            if ($adb->num_rows($res1) > 0){
				for($j=0;$j<$adb->num_rows($res1);$j++){
					$total = '';
					$Income_Records[$j]['ACCOUNTINGID'] = $adb->query_result($res1,$j,'accountingid');
					$Income_Records[$j]['assigned_user_id'] = $adb->query_result($res1,$j,'user_name');
					$Income_Records[$j]['accounting'] = $adb->query_result($res1,$j,'accounting');
					$currency_id_item = $adb->query_result($res1,$j,'currency_id');
					$conversionRate_in = $adb->query_result($res1,$j,'conversion_rate');
					if($currency_id == 1){
						$total = round($adb->query_result($res1,$j,'subtotal')/$conversionRate_in,2);
					} else if($currency_id == $currency_id_item){
						$total = round(CurrencyField::convertToUserFormat($adb->query_result($res1,$j,'subtotal'), null, true, true),2);
					} else {
						$total = round(CurrencyField::convertToUserFormat($adb->query_result($res1,$j,'subtotal'), null, false, true),2);
					}
					$Income_Records[$j]['total'] = $total;
					$Income_Records[$j]['accounting_no'] = $adb->query_result($res1,$j,'accounting_no');
					$accountingstatus = $adb->query_result($res1,$j,'accountingstatus');
					$duedate = $adb->query_result($res1,$j,'duedate');
					$today = date('Y-m-d');
					if($accountingstatus=='Pending' && $duedate < $today){
						$Income_Records[$j]['COLOR_CODE'] = '#FF0000';
					}else if($accountingstatus=='Paid'){
						$Income_Records[$j]['COLOR_CODE'] = '#33CC00';
					}else{
						$Income_Records[$j]['COLOR_CODE'] = '';
					}
					$Total_Income += $total;
				}
				
			}
			
			$viewer->assign("INCOME_RECORDS", $Income_Records);
			$viewer->assign("TOTAL_INCOME", $Total_Income);
			//variance End Change for Income Records

        require('user_privileges/user_privileges_' . $current_user->id . '.php');

        $viewer->assign('CURRENT_LANGUAGE', $currentLanguage);
        $viewer->assign('IS_ADMIN', is_admin($current_user));
        $viewer->view("GetAccountingAsset.tpl", 'Accounting');
    }
}    
