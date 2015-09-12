<?php

/**
 * VGS Webmail Extension Module
 *
 *
 * @package        VGSWebmail Module
 * @author         Conrado Maggi - VGS Global - www.vgsglobal.com
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 */

class VGSWebmail_SettingsStep2_View extends Vtiger_Index_View {

    public function process(Vtiger_Request $request) {

        $viewer = $this->getViewer($request);
        if($request->get('issues')=='yes'){
            $viewer->assign('LOGIN_FAILED', true);

        }
        $viewer->assign('EMAIL_SETTINGS', $this->getSettingsArray());
        $viewer->view('SettingsStep2.tpl', $request->getModule());
    }

    function getSettingsArray() {
        global $adb, $current_user;
        $result = $adb->query("SELECT * FROM vtiger_vgswebmail_usersettings WHERE userid = $current_user->id");
        while ($result_set = $adb->fetch_array($result)) {
            $settings = $result_set;
        }

        return $settings;
    }

}