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

class VGSDynamicBlocks_AddNew_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        $qualifiedModuleName = $request->getModule();
        $supportedModules = VGSDynamicBlocks_Module_Model::getSupportedModules();
        $sourceModule = $request->get('sourceModule');
        if ($sourceModule) {
            $defaultModule = $supportedModules[getTabid($sourceModule)];
        } else {
            $defaultModule = reset($supportedModules);
        }
        
        $viewer = $this->getViewer($request);
        $viewer->assign('SUPPORTED_MODULES', $supportedModules);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->view('AddNew.tpl', $qualifiedModuleName);
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
