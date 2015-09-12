<?php
vimport('~~/vtlib/Vtiger/Module.php');
/**
 * CloudSync Module Model Class
 */
class CloudSync_Module_Model extends Vtiger_Module_Model 
{
	/**
	 * Function to get the module is permitted to specific action
	 * @param <String> $actionName
	 * @return <boolean>
	 */
	public function isPermitted($actionName) {
		// restrict user from creating/updating/importing record
		if($actionName == 'EditView' || $actionName == 'Import')
			return false;

		return ($this->isActive() && Users_Privileges_Model::isPermitted($this->getName(), $actionName));
	}

	/**
	 * Function to get Settings links
	 * @return <Array>
	 */
	public function getSettingLinks(){
		if(!$this->isEntityModule()) {
			return array();
		}
		vimport('~~modules/com_vtiger_workflow/VTWorkflowUtils.php');

		$layoutEditorImagePath = Vtiger_Theme::getImagePath('LayoutEditor.gif');
		$editWorkflowsImagePath = Vtiger_Theme::getImagePath('EditWorkflows.png');
		$settingsLinks = array();

		$settingsLinks[] = array(
				'linktype' => 'LISTVIEWSETTING',
				'linklabel' => 'LBL_EDIT_FIELDS',
				'linkurl' => 'index.php?parent=Settings&module=LayoutEditor&sourceModule='.$this->getName(),
				'linkicon' => $layoutEditorImagePath
				);

		if(VTWorkflowUtils::checkModuleWorkflow($this->getName())) {
			$settingsLinks[] = array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_EDIT_WORKFLOWS',
					'linkurl' => 'index.php?parent=Settings&module=Workflows&view=List&sourceModule='.$this->getName(),
					'linkicon' => $editWorkflowsImagePath
					);
		}

		$settingsLinks[] = array(
				'linktype' => 'LISTVIEWSETTING',
				'linklabel' => 'LBL_EDIT_PICKLIST_VALUES',
				'linkurl' => 'index.php?parent=Settings&module=Picklist&view=Index&source_module='.$this->getName(),
				'linkicon' => ''
				);

		// no need to show settings link
		$settingsLinks = array();

		if($this->hasSequenceNumberField()) {
			$settingsLinks[] = array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_MODULE_SEQUENCE_NUMBERING',
					'linkurl' => 'index.php?parent=Settings&module=Vtiger&view=CustomRecordNumbering&sourceModule='.$this->getName(),
					'linkicon' => ''
					);
		}

		$webformSupportedModule = Settings_Webforms_Module_Model :: getSupportedModulesList();
		if(array_key_exists($this->getName(), $webformSupportedModule)){
			$settingsLinks[] =  array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_SETUP_WEBFORMS',
					'linkurl' => 'index.php?module=Webforms&parent=Settings&view=Edit&sourceModule='.$this->getName(),
					'linkicon' => '');
		}
		return $settingsLinks;
	}

	public function isCustomizable()	{
		return false;
    	}

	/**
	 * Function to identify if the module supports quick search or not
     	 */
    	public function isQuickSearchEnabled() {
        	return false;
    	}
}
