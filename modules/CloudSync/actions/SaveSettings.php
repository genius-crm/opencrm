<?php
require_once('modules/CloudSync/config.php');
class CloudSync_SaveSettings_Action extends Vtiger_Action_Controller 
{
	public function checkPermission(Vtiger_Request $request)	{

        }	

	public function process(Vtiger_Request $request) {
		global $adb, $current_user;
		$serviceFieldList = CloudSyncConfig::$serviceFieldList;
		// insert new row for current user
		foreach($serviceFieldList as $singleField)	{
			$config_name = null;
			$get_config_name = $adb->pquery('select config_name from vtiger_cloudysyncsettings where config_name = ? and user_id = ?', array($singleField, $current_user->id));
			$config_name = $adb->query_result($get_config_name, 0, 'config_name');
			if(empty($config_name))	
				$adb->pquery('insert into vtiger_cloudysyncsettings (config_name, user_id) values (?, ?)', array($singleField, $current_user->id));

		}
		// if called from modal ajax
		if($request->get('ajax'))	{
			$authCode = $request->get('dropbox_authcode');
			// check whether dropbox access token is valid
			require_once('modules/CloudSync/lib/Dropbox/autoload.php');	
			$appInfo = Dropbox\AppInfo::loadFromJsonFile(CloudSyncConfig::$dropboxKeyFile);
			$webAuth = new Dropbox\WebAuthNoRedirect($appInfo, "CloudSync/1.0");
			list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
			if(empty($dropboxUserId))	{
				$response['color'] = '#C9302C';
				$response['message'] = "code doesn't exist or has expired";
				$response['status'] = 'failed';
			}
			else	{
				$adb->pquery('update vtiger_cloudysyncsettings set config_value = ? where config_name = ? and user_id = ?', array($accessToken, 'dropboxtoken', $current_user->id));
				$response['color'] = '#449D44';
				$response['message'] = 'Token Successfully stored';
				$response['status'] = 'success';
			}
			echo json_encode($response);die;
		}

		foreach($serviceFieldList as $singleField)	{
			$config_value = null;
			$config_value = $request->get($singleField);
			$adb->pquery('update vtiger_cloudysyncsettings set config_value = ? where config_name = ? and user_id = ?', array($config_value, $singleField, $current_user->id));
		}
		header("Location: index.php?module=CloudSync&view=Settings");die;
	}
}
