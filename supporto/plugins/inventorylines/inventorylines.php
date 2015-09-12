<?php

/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */



class InventorylinesPlugin { 

	var $plugin_label="Inventory Lines";
	var $plugin_description="This plugin allow you to show the products and services lines ( with quantities and totals) at the bottom of your Quotes,Invoices and Sales Orders";
	var $affectedmodules=array("QuotesWS","SalesOrderWS","Quotes","Invoice");
	var $pluginconfig;
	var $commonfields;
	var $commonfieldsassoc;
	var $require_vtapi=true;
	var $standardmodules = array("Home", "HelpDesk", "Faq", "Quotes", "Invoice", "Products", "Services", "Documents", "Contacts", "Accounts", "Project", "ProjectTask", "ProjectMilestone", "Assets","Events");
	
	function __construct(){
		if($this->require_vtapi && !Api::is_connected()) return false;
		$this->pluginconfig=require ROOT_PATH."/plugins/inventorylines/config.php";
		//$this->pluginconfig = ConfigEditor::read(ROOT_PATH."/plugins/inventorylines/config.php");
		$this->commonfields = array(
			"sequence_no" => "Position",
			"commonf_no" => "Product/Service No",
			"commonf_name" => "Product/Service Name",
			"commonf_namedesc" => "Product/Service Name and Description",
			"commonf_usageunit" => "Product/Service Usage Unit",
			"commonf_category" => "Product/Service Category",
			"id" => "Product/Service Record ID",
			"comment" => "Line Description",
			"quantity" => "Quantity",
			"listprice" => "List Price",
			"commonf_discount"=>"Discount Amount",
			"totalprice"=>"Total Price",
			"finaltotalprice"=>"Total Price with Discount",
		);
		
		$this->commonfieldsassoc = array(
			"Products" => array(
				"commonf_no" => "product_no",
				"commonf_name" => "productname",
				"commonf_usageunit" => "usageunit",
				"commonf_category" => "productcategory",
			),
			"Services" => array(
				"commonf_no" => "service_no",
				"commonf_name" => "servicename",
				"commonf_usageunit" => "service_usageunit",
				"commonf_category" => "servicecategory",
			),
		);
	}

	function settings(){
		if($this->require_vtapi && !Api::is_connected()) return false;
		global $mycwsapi;	
		
		$inventoryinfo['Products']=$mycwsapi->getModuleFields("Products");	
		$inventoryinfo['Services']=$mycwsapi->getModuleFields("Services");
		$inventoryinfo['LineItem']=$mycwsapi->getModuleFields("LineItem");
				
		$commonfields=$this->commonfields;
		$commonfieldsassoc = $this->commonfieldsassoc;
		
		foreach($inventoryinfo as $modulename => $moduleinfo){
			foreach($moduleinfo['fields'] as $fieldinfo){	
				
				if(!isset($commonfields[$fieldinfo['name']])) $plugindata['inventoryinfo'][$modulename]['fieldslabels'][$fieldinfo['name']]=$fieldinfo['label'];
			}
		}
		
		foreach($commonfieldsassoc as $modname => $fields){	
			foreach($fields as $cfieldname => $realfieldname){	
				unset($plugindata['inventoryinfo'][$modname]['fieldslabels'][$realfieldname]);
			}	
		}
		
		foreach($commonfields as $fieldname => $fieldlabel){	
			$plugindata['inventoryinfo']["Common"]['fieldslabels'][$fieldname]=$fieldlabel;
		}

		
		
		$commonfields2 = array_intersect_key($plugindata['inventoryinfo']['Services']['fieldslabels'], $plugindata['inventoryinfo']['Products']['fieldslabels']);
				
		
		foreach($commonfields2 as $fieldname => $fieldlabel){	
			$plugindata['inventoryinfo']["Common"]['fieldslabels'][$fieldname]="Product/Service ".$fieldlabel;
			unset($plugindata['inventoryinfo']['Services']['fieldslabels'][$fieldname]);	
			unset($plugindata['inventoryinfo']['Products']['fieldslabels'][$fieldname]);	
		}
		
		
		if(isset($_POST['settings'])){
			$newconfig = $_POST['settings'];
			
			$plugindata['altmess']=ConfigEditor::write(ROOT_PATH."/plugins/inventorylines/config.php", $newconfig);	
			if($plugindata['altmess']=="OK") $this->pluginconfig=$newconfig;
		}
			
		$plugindata['config']=$this->pluginconfig;	
		$plugindata['linemodules']=array("Products","Services","Common");
		foreach($this->affectedmodules as $am)
			if(isset($GLOBALS['api_modules'][$am]) || in_array($am, $this->standardmodules))
				$plugindata['affectedmodules'][]=$am; 
			
		return $plugindata;
	}

	function preTemplateLoad($modulename,$action,$data){
	
		if($this->require_vtapi && !Api::is_connected()) return $data;
				
		$pluginconfig =  $this->pluginconfig;
		if($pluginconfig[$modulename]['is_enabled']=="true" && $pluginconfig['is_enabled']=="true"){
		
		switch($action){
			case "list":{
				break;
			}			
			case "detail":
			case "detail_api": {
				if(isset($GLOBALS['api_client']) && $GLOBALS['api_client']!="NOT_CONFIGURED" && $GLOBALS['api_client']!="API_LOGIN_FAILED"){
				  	//$apidata = $GLOBALS['api_client']->doQuery("SELECT * FROM ");
				  	//if($this->endsWith($modulename,"WS")) $modulename=substr($modulename, 0, -2);
				  	$re = "/^[\\d]*x[\\d]*$/"; 
					if(preg_match($re,$data['targetid'])) $target=$data['targetid'];
					else{
						$modid=Api::getModuleId($modulename);
						$target=$modid."x".$data['targetid'];
					}	
					$entdet=$GLOBALS['api_client']->doRetrieve($target);
					$currencydet=$GLOBALS['api_client']->doRetrieve($entdet['currency_id']);
				  	$lineitems = $entdet['LineItems'];
				  	$lc=0;	
				  	foreach($lineitems as $linep){
				  		$proddetails = $GLOBALS['api_client']->doRetrieve($linep['productid']);
				  		$lineitems[$lc]=array_merge($proddetails,$lineitems[$lc]);
					  	$lc++;
				  	}		  	
				  	global $mycwsapi;	
		
					$inventoryinfo['Products']=$mycwsapi->getModuleFields("Products");	
					$inventoryinfo['Services']=$mycwsapi->getModuleFields("Services");
					$inventoryinfo['LineItem']=$mycwsapi->getModuleFields("LineItem");
					
					foreach($inventoryinfo as $relmname => $relminfo){							
						foreach($relminfo['fields'] as $fieldinfo){				
							if(!isset($this->commonfields[$fieldinfo['name']])) $data['plugin_data']['inventorylines']['fieldslabels'][$fieldinfo['name']]=$fieldinfo['label'];
						}
					}
					
					foreach($this->commonfields as $cfname => $cflabel){	
						$data['plugin_data']['inventorylines']['fieldslabels'][$cfname]=$cflabel;	
					}
				  	$data['plugin_data']['data']['inventorylines']['fields']=$pluginconfig[$modulename]['fields'];
				  	$data['plugin_data']['data']['inventorylines']['lineitems']=$lineitems;
				  	$data['plugin_data']['entdetail']=$entdet;
				  	$data['plugin_data']['curencysymbol']=$currencydet['currency_symbol'];

				}
				break;
			}
		}
		
		$data['plugin_data']['commonfieldsassoc']=$this->commonfieldsassoc;
		$data['plugin_data']['commonfields']=$this->commonfields;
		
		$data['plugin_data']['views']['header']['inventorylines']="";
		$data['plugin_data']['views']['blocks']['inventorylines']="linesblock";
		$data['plugin_data']['views']['footer']['inventorylines']="";
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