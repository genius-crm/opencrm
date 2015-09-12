<?php

/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */



class EventsPlugin { 

	var $plugin_label="Events Calendar";
	var $plugin_name="events";
	var $plugin_description="This plugin allow you to enable the event module in your Portal and show the event calendar";
	var $affectedmodules=array("PortalBase");
	var $pluginconfig;
	var $require_vtapi=true;
	
	function __construct(){
		if($this->require_vtapi && !Api::is_connected()) return false;
		$this->pluginconfig=require ROOT_PATH."/plugins/".$this->plugin_name."/config.php";
	}

	function settings(){
		if($this->require_vtapi && !Api::is_connected()) return false;
		global $mycwsapi;
		$eventfields=$mycwsapi->getModuleFields("Events");	
		$plugindata['eventmoduleinfo']=$eventfields;
		foreach($eventfields['fields'] as $field){
			$plugindata['eventfields'][$field['name']]=$field['label'];
		}
		
		
		
		if(isset($_POST['settings'])){
			$newconfig = $_POST['settings'];
			$plugindata['altmess']=ConfigEditor::write(ROOT_PATH."/plugins/".$this->plugin_name."/config.php", $newconfig);	
			$this->pluginconfig=$newconfig;
		}
			
		$plugindata['config']=$this->pluginconfig;	
		$plugindata['affectedmodules']=$this->affectedmodules;
			
		return $plugindata;
	}

	function preTemplateLoad($modulename,$action,$data){
		
		$pluginconfig =  $this->pluginconfig;
		
		if(isset($pluginconfig['is_enabled']) && $pluginconfig['is_enabled']=="true"){
		
			$GLOBALS['api_modules']["Events"]=array(
			    'action' => 'plugins',
			    'pn' => 'modulebuilder',
			    'm' => 'Events',
			    'list_fields' => $pluginconfig['list_fields'],
			    'detail_fields' => array (),
			    'is_enabled' => 'true',
			    'relation_fields' =>  array('contact_id'),
			    'download_pdf' => 'false',
			    'update_apimodule' => 'submit',
			);
			
			$GLOBALS['avmod'][]="Events";
		
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