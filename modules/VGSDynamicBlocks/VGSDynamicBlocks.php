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
class VGSDynamicBlocks {

    function vtlib_handler($moduleName, $eventType) {
        $adb = PearDatabase::getInstance();


        if ($eventType == 'module.postinstall') {
            $this->updateSettings();
            $tabid = getTabId("VGSDynamicBlocks");
            Vtiger_Link::addLink($tabid, 'HEADERSCRIPT', 'VGSDynamicBlocks', 'layouts/vlayout/modules/VGSDynamicBlocks/resources/VGSDynamicBlocks.js', '', 0, '');
        } else if ($eventType == 'module.disabled') {
            $adb->pquery('UPDATE vtiger_settings_field SET active= 1  WHERE  name= ?', array('VGSDynamicBlocks'));
        } else if ($eventType == 'module.enabled') {
            $adb->pquery('UPDATE vtiger_settings_field SET active= 0  WHERE  name= ?', array('VGSDynamicBlocks'));
        } else if ($eventType == 'module.preuninstall') {
            $adb->pquery('DELETE FROM vtiger_settings_field WHERE  name= ?', array('VGSDynamicBlocks'));
            require_once('vtlib/Vtiger/Link.php');
            $tabid = getTabId("VGSDynamicBlocks");
            Vtiger_Link::deleteAll($tabid);
        } else if ($eventType == 'module.preupdate') {
            // TODO Handle actions before this module is updated.
            $tabid = getTabId("VGSDynamicBlocks");
            Vtiger_Link::deleteAll($tabid);
        } else if ($eventType == 'module.postupdate') {
            $this->updateSettings();
            $tabid = getTabId("VGSDynamicBlocks");
            Vtiger_Link::addLink($tabid, 'HEADERSCRIPT', 'VGSDynamicBlocks', 'layouts/vlayout/modules/VGSDynamicBlocks/resources/VGSDynamicBlocks.js', '', 0, '');
        }
    }

    private function updateSettings() {
        $adb = PearDatabase::getInstance();

        $fieldid = $adb->getUniqueID('vtiger_settings_field');
        $blockid = getSettingsBlockId('LBL_OTHER_SETTINGS');
        $seq_res = $adb->pquery("SELECT max(sequence) AS max_seq FROM vtiger_settings_field WHERE blockid = ?", array($blockid));
        if ($adb->num_rows($seq_res) > 0) {
            $cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
            if ($cur_seq != null)
                $seq = $cur_seq + 1;
        }

        $result = $adb->pquery('SELECT 1 FROM vtiger_settings_field WHERE name=?', array('VGSDynamicBlocks'));
        if (!$adb->num_rows($result)) {
            $adb->pquery('INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence)
		VALUES (?,?,?,?,?,?,?)', array($fieldid, $blockid, 'VGSDynamicBlocks', '', 'Dynamic Blocks', 'index.php?parent=Settings&module=VGSDynamicBlocks&view=SettingList', $seq));
        }
    }

}
