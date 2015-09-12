<?php
require_once('modules/CloudSync/config.php');
class CloudSync_Widget_View extends Vtiger_Index_View 
{
	function __construct()	{
		parent::__construct();
	}

	function process(Vtiger_Request $request)	{
		global $adb, $current_user;
		$viewer = $this->getViewer($request);
		$viewtype = $request->get('viewtype');
		$record = $request->get('record');

		// get users and group list
                $users_list = Users_Record_Model::getCurrentUserModel()->getAccessibleUsers('Documents');
                $groups_list = Users_Record_Model::getCurrentUserModel()->getAccessibleGroups('Documents');

                $assigned_user_id_html =  '<select class="chzn-select chzn-done" name = "assigned_user_id_modal" id = "assigned_user_id_modal">
                <optgroup label=' . vtranslate("LBL_USERS") . '>';
                foreach($users_list as $user_id => $user_name)  {
                        $selected = null;
                        if($current_user->id == $user_id)
                                $selected = 'selected';

                        $assigned_user_id_html .= "<option value = '{$user_id}' $selected> $user_name </option>";
                }
                $assigned_user_id_html .= "</optgroup>";

                if(!empty($groups_list))        {
                        $assigned_user_id_html .= "<optgroup label=" . vtranslate('LBL_GROUPS') . ">";
                        foreach($groups_list as $group_id => $group_name)       {
                                $assigned_user_id_html .= "<option value = '{$group_id}'> $group_name </option>";
                        }
                }
                $assigned_user_id_html .= "</optgroup> </select>";

		$moduleName = $request->getModule();
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('record', $record);
		$viewer->assign('source_module', $request->get('source_module'));

		require_once('modules/CloudSync/helper.php');
                require_once('modules/CloudSync/lib/Dropbox/autoload.php');
                $helper = new CloudSyncHelper();
                $token = $helper->getConfigValue('dropboxtoken');

		$viewer->assign('token_error_message', 'LBL_ACCESS_TOKEN_NOT_PRESENT');
		$viewer->assign('show_widget', true);
		if(empty($token))	
			$viewer->assign('show_widget', false);

		$viewer->assign('assigned_user_id_html', $assigned_user_id_html);
		$viewer->assign('SCRIPTS', $this->getHeaderScripts($request));	
		if($viewtype == 'detail')
			$viewer->view('DetailViewWidget.tpl', $moduleName);
		else
			$viewer->view('ListViewWidget.tpl', $moduleName);

	}

	public function getHeaderScripts(Vtiger_Request $request) {
                $headerScriptInstances = parent::getHeaderScripts($request);
		$headerScriptInstances = array();
                $moduleName = $request->getModule();

                $jsFileNames = array(
                        "modules.$moduleName.resources.Custom",
			"modules.$moduleName.lib.jQueryFileTree.jqueryFileTree",
                );

                $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
                $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
                return $headerScriptInstances;
        }
}
