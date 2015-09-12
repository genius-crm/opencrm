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
class VGSDynamicBlocks_Module_Model extends Vtiger_Module_Model {

    function getList() {
        $db = PearDatabase::getInstance();
        $dynamicList = Array();
        $sql = "SELECT vgsdynamicblocksid, vtiger_vgsdynamicblocks.moduleid, vtiger_vgsdynamicblocks.fieldvalue, fieldlabel, blocklabel FROM vtiger_vgsdynamicblocks
                 INNER JOIN vtiger_field ON vtiger_vgsdynamicblocks.fieldname = vtiger_field.fieldname
                 INNER JOIN vtiger_blocks ON vtiger_vgsdynamicblocks.blockid = vtiger_blocks.blockid
                 GROUP BY moduleid, fieldvalue, fieldlabel, blocklabel ";
        $result = $db->pquery($sql, array());
        $i = 0;
        while ($row = $db->fetchByAssoc($result)) {

            $moduleName = getTabname($row['moduleid']);

            $dynamicList[$i]['id'] = $row['vgsdynamicblocksid'];
            $dynamicList[$i]['module_name'] = vtranslate($moduleName);
            $dynamicList[$i]['field_label'] = vtranslate($row['fieldlabel'], $moduleName);
            $dynamicList[$i]['field_value'] = vtranslate($row['fieldvalue'], $moduleName);
            $dynamicList[$i]['hidden_blocks'] = vtranslate($row['blocklabel'], $moduleName);
            $i++;
        }
        return $dynamicList;
    }

    /**
     * Function to ger Supported modules for Dynamics Blocks
     * @return <Array> list of supported modules <Vtiger_Module_Model>
     */
    public static function getSupportedModules() {
        $db = PearDatabase::getInstance();
        $modulesModels = Array();
        $sql = "SELECT tabid, name FROM vtiger_tab WHERE isentitytype = ? AND presence = ?";
        $result = $db->pquery($sql, array(1, 0));
        while ($row = $db->fetchByAssoc($result)) {
            $modulesModels[$row['tabid']] = $row;
        }
        return $modulesModels;
    }
    
    /** 
     * Function that return for given module and picklist value an array
     * of hidden and available blocks. Use in Settings tabs of the module
     * 
     * @param type $tabid
     * @param type $fieldName
     * @param type $fieldValue
     * @return string
     */

    public static function getBlocksForValue($tabid, $fieldName, $fieldValue) {
        $db = PearDatabase::getInstance();
        $blocks = Array();
        $sql = "SELECT blockid, blocklabel FROM vtiger_blocks WHERE tabid=?";
        $res = $db->pquery($sql, array($tabid));
        while ($row = $db->fetchByAssoc($res)) {
            $blocks[$row['blockid']]['label'] = $row['blocklabel'];
        }
        $sel_sql = "SELECT blockid FROM vtiger_vgsdynamicblocks WHERE moduleid=? AND fieldname = ? AND fieldvalue = ?";
        $sel_res = $db->pquery($sel_sql, array($tabid, $fieldName, $fieldValue));
        while ($sel_row = $db->fetchByAssoc($sel_res)) {
            $blocks[$sel_row['blockid']]['selected'] = '1';
        }
        return $blocks;
    }

    /**
     * Return an array of picklist fields for a given tabid
     * 
     * @param type $tabid
     * @return type
     */
    
    public function getPicklistFieldsForModule($tabid) {
        $db = PearDatabase::getInstance();
        $picklistFields = Array();
        $sql = "SELECT fieldname, fieldlabel FROM vtiger_field WHERE uitype IN ('15','16') AND presence IN (0,2) AND tabid=?";
        $res = $db->pquery($sql, array($tabid));
        while ($row = $db->fetchByAssoc($res)) {
            $picklistFields[$row['fieldname']] = vtranslate($row['fieldlabel'], getTabname($tabid));
        }

        return $picklistFields;
    }
    
    /**
     * Returns an array of picklist values
     * 
     * @param type $fieldName
     * @param type $tabid
     * @return type
     */

    public function getPicklistFieldsValues($fieldName, $tabid) {
        $picklistValues = Vtiger_Util_Helper::getPickListValues($fieldName);

        $picklistValuesOptions = Array();

        $picklistValuesOptions[''] = '';
        foreach ($picklistValues as $picklistValue) {
            $picklistValuesOptions[$picklistValue] = vtranslate($picklistValue, getTabname($tabid));
        }

        return $picklistValuesOptions;
    }

}
