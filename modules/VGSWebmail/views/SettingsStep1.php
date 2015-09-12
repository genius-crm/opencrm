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

class VGSWebmail_SettingsStep1_View extends Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        global $adb, $current_user;
        $result = $adb->query("DELETE FROM vtiger_vgswebmail_usersettings WHERE userid = $current_user->id");

        $viewer = $this->getViewer($request);
        $viewer->view('SettingsStep1.tpl', $request->getModule());
    }

}
