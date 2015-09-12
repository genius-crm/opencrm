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

class VGSWebmail_RCLogin_Action extends Vtiger_BasicAjax_Action {

    function __construct() {
        parent::__construct();
    }

    function process(Vtiger_Request $request) {
        global $adb, $site_URL;

        include "modules/VGSWebmail/utils/RoundCubeLoginClass.php";

        global $current_user;

        // Set to TRUE if something doesn't work
        $debug = false;
        //$rcPath = $current_user->rc_path;
        $rcPath = $site_URL . 'modules/VGSWebmail/lib/roundcube/';

        // Pass the relative Roundcube path to the constructor
        $rcl = new RoundcubeLogin($rcPath, $debug);


        try {

            $user_settings = $this->getUserSettings($current_user->id);
            if (count($user_settings) == 0) {
               
                    echo '<script language="javascript">'; 
                    echo 'top.location.href = "index.php?module=VGSWebmail&view=SettingsStep1";'; 
                    echo '</script>'; 
                    exit();
            } else {
                

                $rcl->login($user_settings['username'], $user_settings['password'],$user_settings['host']);

                
                if ($rcl->isLoggedIn()) {
                    $rcl->redirect();
                } else {
                    echo '<script language="javascript">'; 
                    echo 'top.location.href = "index.php?module=VGSWebmail&view=SettingsStep2&issues=yes";'; 
                    echo '</script>'; 
                    exit(); 

                }
            }
        } catch (RoundcubeLoginException $ex) {
            // If anything goes wrong, an exception is thrown.        
            $status = "ERROR: " . $ex->getMessage();
        }

    }

    function getUserSettings($user_id) {
        global $adb;

        $result = $adb->query("SELECT * FROM vtiger_vgswebmail_usersettings WHERE userid=$user_id");
        if ($adb->num_rows($result) > 0) {
            $user_settings['username'] = $adb->query_result($result, 0, 'username');
            $user_settings['password'] = $adb->query_result($result, 0, 'password');
            $user_settings['host'] = $adb->query_result($result, 0, 'host');
            $user_settings['port'] = $adb->query_result($result, 0, 'port');
        }

        return $user_settings;
    }

}
?>