<?php
/**
 * CloudSync ListView Model Class
 */
class CloudSync_ListView_Model extends Vtiger_ListView_Model	
{
	/**
         * function to give advance links of a module
         * @RETURN array of advanced links
         */
	public function getAdvancedLinks(){
		$moduleModel = $this->getModule();
		$advancedLinks = array();
		$exportPermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'Export');
		if($exportPermission) {
			$advancedLinks[] = array(
					'linktype' => 'LISTVIEW',
					'linklabel' => 'LBL_EXPORT',
					'linkurl' => 'javascript:Vtiger_List_Js.triggerExportAction("'.$this->getModule()->getExportUrl().'")',
					'linkicon' => ''
					);
		}

		$duplicatePermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'DuplicatesHandling');
		if($duplicatePermission) {
			$advancedLinks[] = array(
					'linktype' => 'LISTVIEWMASSACTION',
					'linklabel' => 'LBL_FIND_DUPLICATES',
					'linkurl' => 'Javascript:Vtiger_List_Js.showDuplicateSearchForm("index.php?module='.$moduleModel->getName().
						'&view=MassActionAjax&mode=showDuplicatesSearchForm")',
					'linkicon' => ''
					);
		}

		return $advancedLinks;
	}
}
