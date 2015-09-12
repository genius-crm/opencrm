<?php

/* * *******************************************************************************
 * The content of this file is subject to the ITS4YouDuplicityCheck license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class ITS4YouDuplicityCheck_DuplicityCheckAjax_Action extends Settings_Vtiger_Index_Action {
    
	public function checkPermission(Vtiger_Request $request) {
    }

    public function process(Vtiger_Request $request) {
        switch($request->get('mode')){
			case 'changeModule':
				$return = '<select data-placeholder="'.vtranslate('LBL_CHOOSE_FIELDS','ITS4YouDuplicityCheck').'" id="fieldsSelect" class="select2 span12" multiple>';
				$fields = ITS4YouDuplicityCheck_DuplicityCheck_Model::getFieldsForModule($request->get('sourceModule'));
				foreach($fields as $fieldid=>$field_data){
					$return .= '<option value="'.$fieldid.'"';
					if($fieldid && $field_data['selected'] == '1'){
						$return .= ' selected';
					}
					$return .= '>'.vtranslate($field_data['fieldlabel'],getTabname($request->get('sourceModule'))).'</option>';
				}
				$return .= '</select>';
				$response = new Vtiger_Response();
		        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
		        $response->setResult(array('caption'=>$return));
		        $response->emit();
			break;
			case 'saveColumnsForModule':
				$db = PearDatabase::getInstance();
				$db->pquery("DELETE FROM its4you_duplicitycheck WHERE moduleid=?", array($request->get('sourceModule')));
				foreach($request->get('fields') as $fieldid){
					$db->pquery("INSERT INTO its4you_duplicitycheck VALUES (?,?)", array($request->get('sourceModule'),$fieldid));
				}
				$response = new Vtiger_Response();
		        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
		        $response->setResult(array('caption'=>vtranslate('JS_DUPLICITY_CHECK_SAVED_SUCCESSFULLY_FOR','ITS4YouDuplicityCheck')));
		        $response->emit();
			break;
		}
	}
	
}
