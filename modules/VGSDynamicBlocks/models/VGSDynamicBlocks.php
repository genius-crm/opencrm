<?php

/* * *******************************************************************************
 * The content of this file is subject to the ITS4YouDuplicityCheck license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class ITS4YouDuplicityCheck_DuplicityCheck_Model extends Vtiger_Module_Model {

    /**
     * Function to get focus of this object
     * @return <type>
     */
    public function getFocus() {
        if (!$this->focus) {
            $this->focus = CRMEntity::getInstance($this->getName());
        }
        return $this->focus;
    }

    /**
     * Function to get Instance of this module
     * @param <String> $moduleName
     * @return <MultiCompany4you_CustomRecordNumbering_Model> $moduleModel
     */
    public static function getInstance($moduleName, $tabId = false) {
        $moduleModel = new self();
        $moduleModel->name = $moduleName;
        if ($tabId) {
            $moduleModel->id = $tabId;
        }
        return $moduleModel;
    }

    /**
     * Function to ger Supported modules for Custom record numbering
     * @return <Array> list of supported modules <Vtiger_Module_Model>
     */
    public static function getSupportedModules() {
        $db = PearDatabase::getInstance();
        $modulesModels = Array();
        $sql = "SELECT tabid, name FROM vtiger_tab WHERE isentitytype = ? AND presence = ?";
        $result = $db->pquery($sql, array(1, 0));
        while ($row = $db->fetchByAssoc($result)) {
            $modulesModels[$row['tabid']] = $row;
        }
        return $modulesModels;
    }
    
    public static function getFieldsForModule($tabid) {
		$db = PearDatabase::getInstance();
        $Fields = Array();
		$sql = "SELECT fieldid, fieldlabel, tablename FROM vtiger_field WHERE tabid=?";
		$res = $db->pquery($sql, array($tabid));
        while ($row = $db->fetchByAssoc($res)) {
            $Fields[$row['fieldid']] = $row;
        }
        $sel_sql = "SELECT fieldid FROM its4you_duplicitycheck WHERE moduleid=?";
        $sel_res = $db->pquery($sel_sql, array($tabid));
        while($sel_row = $db->fetchByAssoc($sel_res)){
			$Fields[$sel_row['fieldid']]['selected'] = '1';
		}
        return $Fields;
	}

}
