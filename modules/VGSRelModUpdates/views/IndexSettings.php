<?php

/**
 * VGS Related Module Updates
 *
 *
 * @package        VGSRelModUpdates Module
 * @author         Conrado Maggi
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 * @version        Release: 1.0
 */

class VGSRelmodupdates_IndexSettings_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        
        $vgsRelUpdatesList = $this->getList();
        
        $viewer = $this->getViewer($request);
        $viewer->assign('RMU_FIELDS_ARRAY', $vgsRelUpdatesList);   
        $viewer->view('IndexSettings.tpl', $request->getModule());
    }
    
    function getPageTitle(Vtiger_Request $request) {
        return vtranslate('LBL_MODULE_NAME', $request->getModule());
    }
    
    function getList() {
        $db = PearDatabase::getInstance();
        $relModFieldList = Array();
        $sql = "SELECT vtiger_vgsrelmodupdates.*, f1.fieldlabel as sourcefieldlabel, f2.fieldlabel as targetfieldlabel FROM vtiger_vgsrelmodupdates 
                        INNER JOIN vtiger_field f1 ON vtiger_vgsrelmodupdates.`sourcefieldname` = f1.fieldname
                        INNER JOIN vtiger_field f2 ON vtiger_vgsrelmodupdates.`targetfieldname` = f2.fieldname
                       GROUP BY  vgsrelmodupdatesid";
        $result = $db->pquery($sql, array());
        $i = 0;
        while ($row = $db->fetchByAssoc($result)) {

            $relModFieldList[$i]['id'] = $row['vgsrelmodupdatesid'];
            $relModFieldList[$i]['source_module'] = vtranslate($row['sourcemodule']);
            $relModFieldList[$i]['source_field_name'] = vtranslate($row['sourcefieldlabel'],$row['sourcemodule']);
            $relModFieldList[$i]['source_field_value'] = vtranslate($row['sourcefieldvalue'],$row['sourcemodule']);
            $relModFieldList[$i]['target_module'] = vtranslate($row['targetmodule']);
            $relModFieldList[$i]['target_module_field'] = vtranslate($row['targetfieldlabel'],$row['sourcemodule']);
            $relModFieldList[$i]['target_module_value'] = vtranslate($row['targetfieldvalue'],$row['sourcemodule']);
            $i++;
        }
        return $relModFieldList;
    }
    
    /**
     * Function to get the list of Script models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);

        $jsFileNames = array(
            "layouts.vlayout.modules.VGSRelModUpdates.resources.VGSRelModUpdatesSettings",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}
