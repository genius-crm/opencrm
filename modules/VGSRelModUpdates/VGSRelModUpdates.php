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
include_once 'modules/Vtiger/CRMEntity.php';
include_once 'include/utils/utils.php';

class VGSRelModUpdates extends Vtiger_CRMEntity {

    public function __construct() {
        
    }

    /**
     * Invoked when special actions are performed on the module.
     * @param String Module name
     * @param String Event Type
     */
    function vtlib_handler($moduleName, $eventType) {
        $adb = PearDatabase::getInstance();
        if ($eventType == 'module.postinstall') {

            $otherSettingsBlock = $adb->pquery('SELECT * FROM vtiger_settings_blocks WHERE label=?', array('LBL_OTHER_SETTINGS'));
            $otherSettingsBlockCount = $adb->num_rows($otherSettingsBlock);

            if ($otherSettingsBlockCount > 0) {
                $blockid = $adb->query_result($otherSettingsBlock, 0, 'blockid');
                $sequenceResult = $adb->pquery("SELECT max(sequence) as sequence FROM vtiger_settings_blocks WHERE blockid=", array($blockid));
                if ($adb->num_rows($sequenceResult)) {
                    $sequence = $adb->query_result($sequenceResult, 0, 'sequence');
                }
            }

            $fieldid = $adb->getUniqueID('vtiger_settings_field');
            $adb->pquery("INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active) 
                        VALUES(?,?,?,?,?,?,?,?)", array($fieldid, $blockid, 'VGS Related Module Updates', '', 'VGSRelModUpdates Configuration', 'index.php?module=VGSRelModUpdates&view=IndexSettings&parent=Settings', $sequence++, 0));


            //Adding the Event

            require_once'vtlib/Vtiger/Module.php';
            require 'include/events/include.inc';
            $em = new VTEventsManager($adb);
            $em->registerHandler('vtiger.entity.aftersave.final', 'modules/VGSRelModUpdates/handler/VGSRelModUpdatesHandler.php', 'VGSRelModUpdates');
        } else if ($eventType == 'module.disabled') {

            require_once'vtlib/Vtiger/Module.php';
            require 'include/events/include.inc';
            $em = new VTEventsManager($adb);
            $em->setHandlerInActive('VGSRelModUpdates');
            $adb->pquery("UPDATE vtiger_settings_field SET active=1 WHERE vtiger_settings_field.name=?", array('VGS Related Module Updates'));
        } else if ($eventType == 'module.enabled') {

            require_once'vtlib/Vtiger/Module.php';
            require 'include/events/include.inc';
            $em = new VTEventsManager($adb);
            $em->setHandlerActive('VGSRelModUpdates');

            $adb->pquery("UPDATE vtiger_settings_field SET active=0 WHERE vtiger_settings_field.name=?", array('VGS Related Module Updates'));
        } else if ($eventType == 'module.preuninstall') {
            $adb->pquery("DELETE FROM vtiger_settings_field WHERE name=?", array('LBL_VGS_RELATEDFIELDS'));

            require_once'vtlib/Vtiger/Module.php';
            require 'include/events/include.inc';
            $em = new VTEventsManager($adb);
            $em->unregisterHandler('VGSRelModUpdates');
            $adb->pquery("UPDATE vtiger_settings_field SET active=1 WHERE name=?", array('VGS Related Module Updates'));
        } else if ($eventType == 'module.preupdate') {
            
        } else if ($eventType == 'module.postupdate') {
            
        }
    }

}
