<?php

/**
 * VGS Hide Ads Banner Module
 *
 *
 * @package        VGSHideAds Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */
class VGSHideAds_IndexVGSHideAds_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {

        $viewer = $this->getViewer($request);

        $viewer->assign('ENABLED', $this->VGSLinkExists());

        $viewer->view('IndexVGSHideAds.tpl', $request->getModule());
    }

    function VGSLinkExists() {
        include_once 'vtlib/Vtiger/Module.php';
        include_once 'vtlib/Vtiger/Link.php';
        $homeModel = Vtiger_Module::getInstance('Home');
        $homeId = $homeModel->getId();

        $linkInstance = Vtiger_Link::getAllByType($homeId, 'HEADERSCRIPT', array('linklabel' => 'VGSHideAdsActions'));
        if (count($linkInstance) > 0) {
            return '1';
        } else {
            return '0';
        }
    }

}
