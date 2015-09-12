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

class VGSRelModUpdates extends VTEventHandler {

    function handleEvent($eventName, $entityData) {

        if ($eventName == 'vtiger.entity.aftersave.final') {
            $moduleName = $entityData->getModuleName();
            $relModulesUpdates = VGSRelModUpdates_Module_Model::getModules();

            if (in_array($moduleName, $relModulesUpdates)) {

                $relModUpdateInstance = Vtiger_Module_Model::getInstance('VGSRelModUpdates');
                $fields = $relModUpdateInstance->getFieldUpdatesForModule($moduleName);
                foreach ($fields as $fieldToUpdate) {
                    if ($entityData->get($fieldToUpdate['sourcefieldname']) == $fieldToUpdate['sourcefieldvalue']) {

                        $relatedRecords = $relModUpdateInstance->getModulesRelatedField($moduleName, $fieldToUpdate['targetmodule'], $entityData->getId());

                        if (is_array($relatedRecords) && count($relatedRecords) > 0) {
                            foreach ($relatedRecords as $relatedRecord) {
                                $relModUpdateInstance->updateTargetRecords($fieldToUpdate['targetmodule'], $relatedRecord, $fieldToUpdate['targetfieldname'], $fieldToUpdate['targetfieldvalue']);
                            }
                        }
                    }
                }
            }
        }
    }
}
