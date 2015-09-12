<?php
require_once('modules/CloudSync/config.php');
require_once('modules/CloudSync/helper.php');
class CloudSync_SaveWidgetSettings_Action extends Vtiger_Action_Controller
{
	public function checkPermission(Vtiger_Request $request)        {

	}

	public function process(Vtiger_Request $request) {
		global $adb, $current_user;
		$cached_request = $_REQUEST;

		$vtFunctions = new Vtiger_Functions();
                $helper = new CloudSyncHelper();
		
		// get documents tabid
                $documentTabId = $vtFunctions->getModuleId('Documents');
		$docRelatedModules = $helper->getDocumentRelatedModules($documentTabId, $vtFunctions);
		foreach($docRelatedModules as $singleModule)	{
			if($cached_request[$singleModule['moduleName'] . '_status'] == 'on')	{
				// create widget
				$helper->createWidget($singleModule['moduleName']);
			}
			else	{
				// remove widget
				$helper->deleteWidget($singleModule['moduleName']);
			}
			$data[$singleModule['moduleName']] = isset($cached_request[$singleModule['moduleName'] . '_status']) ? $cached_request[$singleModule['moduleName'] . '_status'] : null;
			$data['documentfolders_' . $singleModule['moduleName']] = isset($cached_request['dropboxdocumentfolders_' . $singleModule['moduleName']]) ? $cached_request['dropboxdocumentfolders_' . $singleModule['moduleName']] : null;
		}
		$serialised_data = base64_encode(serialize($data));
		$adb->pquery('update vtiger_cloudysyncsettings set config_value = ? where config_name = ?', array($serialised_data ,'serialised_data'));
		header("Location: index.php?module=CloudSync&view=Settings");die;
	}
}
