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

class VGSHideAds_saveVGSHideAdsSettings_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        global $current_user;
        $moduleName = $request->getModule();

        if (!is_admin($current_user)) {
            throw new AppException('LBL_PERMISSION_DENIED');
        }
    }

    public function process(Vtiger_Request $request) {
        include_once 'vtlib/Vtiger/Module.php';
        include_once 'vtlib/Vtiger/Link.php';

        $settingsCheck = $request->get('checkbox');

        $homeModel = Vtiger_Module::getInstance('Home');
        $homeId = $homeModel->getId();

        try {
            if ($settingsCheck == 'true') {

                Vtiger_Link::addLink($homeId, 'HEADERSCRIPT', 'VGSHideAdsActions', 'layouts/vlayout/modules/VGSHideAds/resources/VGSHideAdsActions.js');
                $responseText = 'ok';
            } else {
                Vtiger_Link::deleteLink($homeId, 'HEADERSCRIPT', 'VGSHideAdsActions');
                $responseText = 'ok';
            }
        } catch (Exception $exc) {
                $responseText = 'fail';
        }

        $response = new Vtiger_Response();
        $response->setResult($responseText);
        $response->emit();
    }

}
