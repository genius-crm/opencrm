<?php

/**
 * VGS Dynamic Blocks
 *
 * @package        VGS Dynamic Blocks
 * @author         Conrado Maggi
 * @license        Commercial
 * @copyright      2014 VGS Global - www.vgsglobal.com
 * @version        Release: 1.0
 */
class VGSDynamicBlocks_VGSDynamicBlocksAjax_Action extends Settings_Vtiger_Index_Action {

    public function process(Vtiger_Request $request) {
        switch ($request->get('mode')) {
            case 'changeModule':
                $picklistFields = VGSDynamicBlocks_Module_Model::getPicklistFieldsForModule($request->get('sourceModule'));

                $response = new Vtiger_Response();
                $response->setEmitType(Vtiger_Response::$EMIT_JSON);
                $response->setResult(array('options' => $picklistFields));
                $response->emit();
                break;
            case 'changePicklistField':

                $picklistValuesOptions = VGSDynamicBlocks_Module_Model::getPicklistFieldsValues($request->get('picklistField'), $request->get('sourceModule'));

                $response = new Vtiger_Response();
                $response->setEmitType(Vtiger_Response::$EMIT_JSON);
                $response->setResult(array('options' => $picklistValuesOptions));
                $response->emit();
                break;
            case 'changePicklistValue':
                $return = '<select data-placeholder="' . vtranslate('LBL_CHOOSE_FIELDS', 'VGSDynamicBlocks') . '" id="fieldsSelect" class="select2 span12" multiple>';
                $blocks = VGSDynamicBlocks_Module_Model::getBlocksForValue($request->get('sourceModule'), $request->get('picklistField'), $request->get('picklistFieldValue'));
                foreach ($blocks as $blockId => $blockData) {
                    $return .= '<option value="' . $blockId . '"';
                    if ($blockId && $blockData['selected'] == '1') {
                        $return .= ' selected';
                    }
                    $return .= '>' . vtranslate($blockData['label'], getTabname($request->get('sourceModule'))) . '</option>';
                }
                $return .= '</select>';
                $response = new Vtiger_Response();
                $response->setEmitType(Vtiger_Response::$EMIT_JSON);
                $response->setResult(array('caption' => $return));
                $response->emit();
                break;
            case 'saveColumnsForModule':
                $db = PearDatabase::getInstance();
                $db->pquery("DELETE FROM vtiger_vgsdynamicblocks WHERE moduleid=? AND fieldname = ? AND fieldvalue = ?", array($request->get('sourceModule'), $request->get('picklistField'), $request->get('picklistFieldValue')));
                foreach ($request->get('fields') as $fieldid) {
                    $db->pquery("INSERT INTO vtiger_vgsdynamicblocks (moduleid,fieldname,fieldvalue,blockid) VALUES (?,?,?,?)", array($request->get('sourceModule'), $request->get('picklistField'), $request->get('picklistFieldValue'), $fieldid));
                }
                $response = new Vtiger_Response();
                $response->setEmitType(Vtiger_Response::$EMIT_JSON);
                $response->setResult(array('caption' => vtranslate('Saved', 'VGSDynamicBlocks')));
                $response->emit();
                break;

            case 'deleteColumnsForModule':
                $db = PearDatabase::getInstance();
                $db->pquery("DELETE FROM vtiger_vgsdynamicblocks WHERE vgsdynamicblocksid=?", array($request->get('record_id')));

                $response = new Vtiger_Response();
                $response->setEmitType(Vtiger_Response::$EMIT_JSON);
                $response->setResult(array('caption' => vtranslate('JS_DELETED_SUCCESSFULLY_FOR', 'VGSDynamicBlocks')));
                $response->emit();
                break;
        }
    }

}
