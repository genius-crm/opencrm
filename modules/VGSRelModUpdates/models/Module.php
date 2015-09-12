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
class VGSRelModUpdates_Module_Model extends Vtiger_Module_Model {

    public static function getModules() {
        $RUModules = Vtiger_Cache::get('VGSRelModUpdates', 'active_modules');

        if (empty($RUModules) || count($RUModules) == 0) {
            $RUModules = array();
            $db = PearDatabase::getInstance();
            $result = $db->pquery("SELECT vtiger_vgsrelmodupdates.sourcemodule FROM vtiger_vgsrelmodupdates
                                        GROUP BY sourcemodule");
            if ($db->num_rows($result) > 0) {
                while ($row = $db->fetch_array($result)) {
                    array_push($RUModules, $row['sourcemodule']);
                }
            }

            //Vtiger_Cache::set('VGSRelModUpdates', 'active_modules',$RUModules);
        }

        return $RUModules;
    }

    function getFieldUpdatesForModule($sourceModule) {
        $fieldUpdates = Vtiger_Cache::get('VGSRelModUpdates', 'field_updates_' . $sourceModule);

        if (empty($fieldUpdates) || count($fieldUpdates[$sourceModule]) == 0) {
            $db = PearDatabase::getInstance();
            $result = $db->pquery("SELECT * FROM vtiger_vgsrelmodupdates WHERE sourcemodule = ?", array($sourceModule));

            if ($db->num_rows($result) > 0) {
                while ($row = $db->fetch_array($result)) {
                    $fieldUpdates[$row['vgsrelmodupdatesid']]['sourcefieldname'] = $row['sourcefieldname'];
                    $fieldUpdates[$row['vgsrelmodupdatesid']]['sourcefieldvalue'] = $row['sourcefieldvalue'];
                    $fieldUpdates[$row['vgsrelmodupdatesid']]['targetfieldname'] = $row['targetfieldname'];
                    $fieldUpdates[$row['vgsrelmodupdatesid']]['targetmodule'] = $row['targetmodule'];
                    $fieldUpdates[$row['vgsrelmodupdatesid']]['targetfieldvalue'] = $row['targetfieldvalue'];
                }
            }
            Vtiger_Cache::set('VGSRelModUpdates', 'field_updates_' . $sourceModule, $fieldUpdates);
        }

        return $fieldUpdates;
    }

    function getModulesRelatedField($sourceModule, $tartgetModule, $recordId) {
        $db = PearDatabase::getInstance();

        $tables = $this->getRelationTab($sourceModule, $tartgetModule);

        $targetModuleTableFields = reset($tables);
        $targetModuleTable = key($tables);

        $result = $db->pquery("SELECT $targetModuleTableFields[1] FROM $targetModuleTable 
                        INNER JOIN vtiger_crmentity ON $targetModuleTable.$targetModuleTableFields[1] = vtiger_crmentity.crmid "
                . "     WHERE deleted=0 AND $targetModuleTableFields[0]=?", array($recordId));

        if ($db->num_rows($result) > 0) {
            while ($row = $db->fetch_row($result)) {
                $relatedRecords[] = $row[0];
            }
        } else {
            return false;
        }

        return $relatedRecords;
    }

    function getRelationTab($sourceModule, $tartgetModule) {
        //let search for UIType and custom relationship first
        $tables = getRelationTables($sourceModule, $tartgetModule);
        $targetModuleTableFields = reset($tables);
        $targetModuleTable = key($tables);

        if ($targetModuleTable == 'vtiger_crmentityrel') {
            $tables = $this->getCustomRelationships($sourceModule, $tartgetModule);
        }

        return $tables;
    }

    function getCustomRelationships($sourceModule, $tartgetModule) {

        $rel_tables = array(
            'Quotes' => array(
                'SalesOrder' => array('vtiger_salesorder' => array('quotesid', 'quoteid')),
                'Accounts' => array('vtiger_quotes' => array('quotesid', 'accountid')),
                'Contacts' => array('vtiger_quotes' => array('quotesid', 'contactid')),
                'Potentials' => array('vtiger_quotes' => array('quotesid', 'potentialid')),
            ),
            'SalesOrder' => array(
                'Quotes' => array('vtiger_salesorder' => array('salesorderid', 'quoteid')),
                'Accounts' => array('vtiger_salesorder' => array('salesorderid', 'accountid')),
                'Contacts' => array('vtiger_salesorder' => array('salesorderid', 'contactid')),
                'Potentials' => array('vtiger_salesorder' => array('salesorderid', 'potentialid')),
                'Invoice' => array('vtiger_invoice' => array('invoiceid', 'salesorderid')),
            ),
            'Invoice' => array(
                'SalesOrder' => array('vtiger_invoice' => array('invoiceid', 'salesorderid')),
                'Accounts' => array('vtiger_invoice' => array('invoiceid', 'accountid')),
                'Contacts' => array('vtiger_invoice' => array('invoiceid', 'contactid')),
            ),
        );

        return $rel_tables[$sourceModule][$tartgetModule];
    }

    /**
     * For a given $targetModule returns the uitype in the source module field table
     */
    public static function getCustomUITypes() {
        $customUI = array(
            'Accounts' => '51',
            'Contacts' => '57',
            'Potentials' => '76',
            'Products' => '59',
            'Quotes' => '78',
            'SalesOrder' => '80',
            'Vendors' => '81',
        );

        return $customUI;
    }

    function updateTargetRecords($targetModule, $targetRecordId, $targetFieldName, $targetFieldValue) {
        global $log;

        if ($targetRecordId == '' || empty($targetRecordId) || $targetRecordId == 0) {
            return false;
        }

        try {
            if ($this->isPicklistValuePermitted($targetModule, $targetFieldName, $targetFieldValue)) {
                $record = Vtiger_Record_Model::getInstanceById($targetRecordId, $targetModule);
                $record->set('mode', 'edit');
                $record->set($targetFieldName, $targetFieldValue);
                $record->save();
            }
        } catch (Exception $exc) {
            $log->debug($exc->message);
        }
    }

    function isPicklistValuePermitted($targetModule, $targetFieldName, $targetFieldValue) {
        global $log;
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $fieldModel = Vtiger_Field_Model::getInstance($targetFieldName, Vtiger_Module_Model::getInstance($targetModule));


        // Check is the user can write in that field
        if (!$fieldModel->getPermissions('readwrite')) {
            $log->debug('User id: ' . $currentUser->id . ' is not permitted to edit the field:' . $targetFieldName);
            return false;
        }

        //Check if the user can choose that picklist value.

        if ($fieldModel->isRoleBased() && !$currentUser->isAdminUser()) {
            $picklistValues = Vtiger_Util_Helper::getRoleBasedPicklistValues($targetFieldName, $currentUser->get('roleid'));
        } else {
            $picklistValues = Vtiger_Util_Helper::getPickListValues($targetFieldName);
        }

        if (!in_array($targetFieldValue, $picklistValues)) {
            $log->debug('User id: ' . $currentUser->id . ' is not permitted to choose the value:' . $targetFieldValue . ' for picklist: ' . $targetFieldName);
            return false;
        }

        return true;
    }

}
