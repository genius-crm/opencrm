<?php
require_once('modules/CloudSync/config.php');
require_once('modules/CloudSync/helper.php');
class CloudSync_Settings_View extends Vtiger_Index_View 
{
	function __construct()	{
		parent::__construct();
	}

	public function preProcess(Vtiger_Request $request, $display = true)	{
		$moduleName = $request->getModule();
		// get sidebar link list
		$sideBarLinks = CloudSyncConfig::$sideBarLinks;
		$sideBarLinks[0]['class'] = 'selectedQuickLink';
		$sideBarLinks[0]['sequence'] = 0;
	
		$viewer = $this->getViewer($request);
		$viewer->assign('sideBarLinks', $sideBarLinks);
		$viewer->assign('moduleName', $moduleName);
		return parent::preProcess($request, $display);
	}

	function process(Vtiger_Request $request)	{
		global $adb, $root_directory, $current_user;
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);
		$mode = $request->get('mode');
		if(empty($mode))
			$mode = 'detail';

		// check whether cache directory created
		$helper = new CloudSyncHelper();
		$storageLocation = CloudSyncConfig::$storageLocation;
		$checkSlash = $helper->endsWith($root_directory, '/');
                if(!$checkSlash)
                        $slash = '/';

                $vtigerFilePath = $root_directory . $slash . $storageLocation;
		if(!file_exists($vtigerFilePath))	{
			if(!mkdir($vtigerFilePath,  0777, true))	{
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED_TO_CREATE_DIR', $moduleName));
			}
		}

		$moduleName = $request->getModule();
		$serviceList = CloudSyncConfig::$serviceList;

		foreach($serviceList as $singleService)	{
			$data = array();
			// getting service values from table
			$getServiceStatus = $adb->pquery("select config_value from vtiger_cloudysyncsettings where config_name = ? and user_id = ?", array("{$singleService}status", $current_user->id));
			$getServiceWorklow = $adb->pquery("select config_value from vtiger_cloudysyncsettings where config_name = ? and user_id = ?", array("{$singleService}workflow", $current_user->id));
			$getServiceAttachType = $adb->pquery("select config_value from vtiger_cloudysyncsettings where config_name = ? and user_id = ?", array("{$singleService}attachmenttype", $current_user->id));
			$getServiceAppFolder = $adb->pquery("select config_value from vtiger_cloudysyncsettings where config_name = ? and user_id = ?", array("{$singleService}appfolder", $current_user->id));

			// assigning service values to variable
			$data['status'] = $adb->query_result($getServiceStatus, 0, 'config_value');
			if(empty($data['status']))
				$data['status'] = 'LBL_NO';
			else if($data['status'] == 'on')
				$data['status'] = 'LBL_YES';

			$data['workflow'] = $adb->query_result($getServiceWorklow, 0, 'config_value');
			if(empty($data['workflow']))
				$data['workflow'] = 'LBL_NO';
			else if($data['workflow'] == 'on')
				$data['workflow'] = 'LBL_YES';

			$data['attachmenttype'] = $adb->query_result($getServiceAttachType, 0, 'config_value');
			$data['appfolder'] = $adb->query_result($getServiceAppFolder, 0, 'config_value');
	
			if(empty($data['attachmenttype']))
				$data['attachmenttype'] = 'E';

			// assigning values to smarty variable 
			$viewer->assign("{$singleService}status", $data['status']);	
			$viewer->assign("{$singleService}workflow", $data['workflow']);
			$viewer->assign("{$singleService}attachmenttype", $data['attachmenttype']);
			$viewer->assign("{$singleService}appfolder", $data['appfolder']);

			if($singleService == 'dropbox')	{
				$getServiceToken = $adb->pquery("select config_value from vtiger_cloudysyncsettings where config_name = ? and user_id = ?", array("{$singleService}token", $current_user->id));
				$data['token'] = $adb->query_result($getServiceToken, 0, 'config_value');
				$viewer->assign("{$singleService}token", $data['token']);

				// check service connected
				require_once("modules/CloudSync/{$singleService}Helper.php");
				// service helper class
				$helperClass = "{$singleService}Helper";
				// service helper object
				$serviceObj = new $helperClass();
				if(!empty($data['token']))	{
					$dbxClient = new Dropbox\Client($data['token'], "CloudSync/1.0");
					// get account info
					$accountInfo = $dbxClient->getAccountInfo();
				}
				// get authorisation url
				$authorizeURL = $serviceObj->getAuthorizeURL();

				// set connection status to show on settings page
				if(isset($accountInfo['error']) || empty($data['token']))	{
					$viewer->assign("{$singleService}_connectionstatus", 'LBL_NOT_CONNECTED');
					$viewer->assign("{$singleService}_connectionstatus_class", 'alert alert-warning');
					$viewer->assign("AUTHORIZE_URL", $authorizeURL);
				}
				else	{
					$viewer->assign("{$singleService}_connectionstatus", 'LBL_CONNECTED');
					$viewer->assign("{$singleService}_connectionstatus_class", 'alert alert-success');
				}
			}
		}

		$vtFunctions = new Vtiger_Functions();
		// get documents tabid
                $documentTabId = $vtFunctions->getModuleId('Documents');

                // get settings value
                $serialised_data = $helper->getCommonConfigValue('serialised_data');
                if(!empty($serialised_data))
                        $data = unserialize(base64_decode($serialised_data));

                $docRelatedModules = $helper->getDocumentRelatedModules($documentTabId, $vtFunctions);
                foreach($docRelatedModules as $key => $single_module)   {
                        if(isset($data[$single_module['moduleName']]) && $data[$single_module['moduleName']] == 'on')	{
                                $docRelatedModules[$key]['checked'] = 'checked';
			}
			$docRelatedModules[$key]['folder_id'] = $data['documentfolders_'.$single_module['moduleName']];
                }

		// get document modules folder
		$document_folders = $helper->getDocumentFolders();
		
		$is_permitted_for_widget = false;
		if($current_user->is_admin == 'on')
			$is_permitted_for_widget = true;

		$viewer->assign('HELPER', $helper);
		$viewer->assign('document_folders', $document_folders);
		$viewer->assign('data', $docRelatedModules);
		$viewer->assign('is_permitted_for_widget', $is_permitted_for_widget);
		$viewer->assign('version', CloudSyncConfig::$version);
		$viewer->assign('DDD_WIDGET_SETTINGS_TITLE', CloudSyncConfig::$widgetSettingsTitle);
		$viewer->assign('MODE', $mode);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('VIEW', $_REQUEST['view']);
		$viewer->assign('settingsTitle', CloudSyncConfig::$settingsTitle);
		$viewer->assign('dropboxHeaderTitle', CloudSyncConfig::$dropboxHeaderTitle);
		$viewer->assign('version', CloudSyncConfig::$version);
		$viewer->view('Settings.tpl', $moduleName);
	}

	/**
         * Function to get the list of Script models to be included
         * @param Vtiger_Request $request
         * @return <Array> - List of Vtiger_JsScript_Model instances
         */
        function getHeaderScripts(Vtiger_Request $request) {
                $headerScriptInstances = parent::getHeaderScripts($request);
                $moduleName = $request->getModule();

                $jsFileNames = array(
			//"modules.$moduleName.resources.Tooltip",
			"modules.$moduleName.resources.Custom",
                        'modules.Vtiger.resources.List',
			"modules.$moduleName.lib.jQueryFileTree.jqueryFileTree",
                        'modules.CustomView.resources.CustomView',
                );

                $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
                $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
                return $headerScriptInstances;
        }
}
