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

class VGSDynamicBlocks_SettingList_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        $qualifiedModuleName = $request->getModule();
        $dynamicBlocksList = VGSDynamicBlocks_Module_Model::getList();
        $sourceModule = $request->get('sourceModule');
        
        $viewer = $this->getViewer($request);
        $viewer->assign('HIDDEN_BLOCKS_ARRAY', $dynamicBlocksList);
        $viewer->assign('JS_LANG', Vtiger_Language_Handler::export('VGSDynamicBlocks', 'jsLanguageStrings'));
        $viewer->view('SettingsList.tpl', $qualifiedModuleName);
    }

    function getPageTitle(Vtiger_Request $request) {
        return vtranslate('LBL_MODULE_NAME', $request->getModule());
    }

    /**
     * Function to get the list of Script models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            "layouts.vlayout.modules.VGSDynamicBlocks.resources.List",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }

}
