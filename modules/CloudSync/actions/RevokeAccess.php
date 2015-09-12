<?php
class CloudSync_RevokeAccess_Action extends Vtiger_Action_Controller 
{
	public function checkPermission(Vtiger_Request $request)	{

        }	

	public function process(Vtiger_Request $request) {
		global $adb, $current_user;
		// clearing user settings like status, access token and workflow status
		$adb->pquery('update vtiger_cloudysyncsettings set config_value = null where user_id = ? and config_name = ?', array($current_user->id, 'dropboxtoken'));
		// clearing app folder 
		$adb->pquery('update vtiger_cloudysyncsettings set config_value = null where user_id = ? and config_name = ?', array($current_user->id, 'dropboxappfolder'));
		// clearing vtiger cloud mapping ids
		$adb->pquery('delete from vtiger_cloudsync_id_mapping where user_id = ?', array($current_user->id));
		die;
	}
}
