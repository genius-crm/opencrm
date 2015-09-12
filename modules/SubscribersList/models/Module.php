<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class SubscribersList_Module_Model extends Vtiger_Module_Model {
		
	public static function getSearchableModules() {
		$userPrivModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$entityModules = self::getEntityModules();

		$searchableModules = array();
		foreach ($entityModules as $tabid => $moduleModel) {
				$moduleName = $moduleModel->getName();
				if ($moduleName == 'Contacts' || $moduleName == 'Accounts' || $moduleName == 'Leads' ) 
				{
					if($userPrivModel->hasModuleActionPermission($moduleModel->getId(), 'DetailView')) {
							$searchableModules[$moduleName] = $moduleModel;
					}
				}
		}
		return $searchableModules;
	}
	
}