<?php

/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */


class MenusortPlugin { 

	var $plugin_label="Menu Sorting";
	var $plugin_name="menusort";
	var $plugin_description="This plugin allow you to sort the portal main menu modules link to respect your order, it allow you to set a custom icon for your module.";
	var $affectedmodules=array("PortalBase");
	var $pluginconfig;
	
	function __construct(){
		$this->pluginconfig=require ROOT_PATH."/plugins/".$this->plugin_name."/config.php";
	}

	function settings(){
		
		if(isset($_POST['settings'])){
			$newconfig = $_POST['settings'];
			$plugindata['altmess']=ConfigEditor::write(ROOT_PATH."/plugins/".$this->plugin_name."/config.php", $newconfig);	
			$this->pluginconfig=$newconfig;
		}
		
		foreach($GLOBALS['api_modules'] as $modname => $modfields)
						if(isset($modfields["is_enabled"]) && $modfields["is_enabled"]=="true") $GLOBALS['avmod'][]=$modname;
		
		$standardmodules = array("Home", "HelpDesk", "Faq", "Quotes", "Invoice", "Products", "Services", "Documents", "Contacts", "Accounts", "Project", "ProjectTask", "ProjectMilestone", "Assets","Events");
		
		if(isset($GLOBALS['avmod']) && count($GLOBALS['avmod'])>0) $plugindata['avmod']=array_merge($standardmodules,$GLOBALS['avmod']);		
		else $plugindata['avmod']=$standardmodules;	

		$plugindata['config']=$this->pluginconfig;	
		$plugindata['affectedmodules']=$this->affectedmodules;
			
		return $plugindata;
	}

	function preTemplateLoad($modulename,$action,$data){
		
		$pluginconfig =  $this->pluginconfig;
		
		if(isset($pluginconfig['is_enabled']) && $pluginconfig['is_enabled']=="true"){
			
			foreach($pluginconfig['modules_order'] as $avm)
				if(in_array($avm, $GLOBALS['avmod']))
			    	$orderedmodules[] = $avm;
			
			foreach($GLOBALS['avmod'] as $avm)
				if(!in_array($avm, $orderedmodules))
					$orderedmodules[] = $avm;
			

			$GLOBALS['avmod']=$orderedmodules;
		
		}
		
		return $data;
		
	}
	
	/*
	function postTemplateLoad($modulename,$action,$data){
		Template::displayPlugin('inventorylines',$data,'postload');
	}
	*/

}
?>