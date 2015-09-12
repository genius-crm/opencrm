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

include_once 'modules/Vtiger/CRMEntity.php';

class VGSWebmail extends Vtiger_CRMEntity
{
    
    /**
     * Invoked when special actions are performed on the module.
     * @param String Module name
     * @param String Event Type
     */
    function vtlib_handler($moduleName, $eventType) {
        global $adb;
        if ($eventType == 'module.postinstall') {
            
            // TODO Handle actions after this module is installed.
            
            
        } else if ($eventType == 'module.disabled') {
            
            // TODO Handle actions before this module is being uninstalled.
            
        } else if ($eventType == 'module.preuninstall') {
            
            // TODO Handle actions when this module is about to be deleted.
            
            
        } else if ($eventType == 'module.preupdate') {
            
            // TODO Handle actions before this module is update
            
            try {
                $adb->query('ALTER TABLE `vtiger_vgswebmail_usersettings` ADD `autosaveattachments` INT(5)  NULL  DEFAULT NULL  AFTER `displayname`;')
            }
            catch(Exception $e) {
            }
        } else if ($eventType == 'module.postupdate') {
            
            // TODO Handle actions after this module is updated.
            
        }
    }
}

