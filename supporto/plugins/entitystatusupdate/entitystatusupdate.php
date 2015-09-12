<?php


class EntitystatusupdatePlugin { 

	var $plugin_label="Entity Status Update";
	var $plugin_name="entitystatusupdate";
	var $plugin_description="This plugin allow you to show the 'Approve Quotation/Invoice' button at the bottom of your Quotes and Invoices, when a customer will click this button the quote status will be set to the one you choose in the configuration and the button will be disabled!";
	var $affectedmodules=array("Quotes","Invoice","PortalBase");
	var $pluginconfig;
	var $require_vtapi=true;
	var $statusfields=array(
			"Quotes"=>"quotestage",
			"Invoice"=>"invoicestatus",
	);
	var $standardmodules = array("Quotes","Invoice");
	
	function __construct(){
		global $loaded_plugins;
		if($this->require_vtapi && !Api::is_connected()) return false;
		
		//$loaded_plugins['PaypalintegrationPaymentHandles']=new PaypalintegrationPaymentHandles;
		
		$this->pluginconfig=require ROOT_PATH."/plugins/".$this->plugin_name."/config.php";
		//$this->pluginconfig = ConfigEditor::read(ROOT_PATH."/plugins/inventorylines/config.php");
	}

	function settings(){
		if($this->require_vtapi && !Api::is_connected()) return false;
		global $mycwsapi;	
		
		$statusinfo['Quotes']=$mycwsapi->getModuleFields("Quotes");	
		$statusinfo['Invoice']=$mycwsapi->getModuleFields("Invoice");	
		
		
		
		$statusfields=$this->statusfields;
		
		foreach($statusinfo as $modulename => $moduleinfo){
			foreach($moduleinfo['fields'] as $modf){
				if(in_array($modf['name'], $statusfields)){
					$statusfields[$modulename]=$modf;
				}
			}
		}
		
		
		$plugindata['statusfields']=$statusfields;
		
		if(isset($_POST['settings'])){
			$newconfig = $_POST['settings'];
			$plugindata['altmess']=ConfigEditor::write(ROOT_PATH."/plugins/".$this->plugin_name."/config.php", $newconfig);	
			$this->pluginconfig=$newconfig;
		}
			
		$plugindata['config']=$this->pluginconfig;	
		
		foreach($this->affectedmodules as $am)
			if(isset($GLOBALS['api_modules'][$am]) || in_array($am, $this->standardmodules))
				$plugindata['affectedmodules'][]=$am;
			
		return $plugindata;
	}

	function preTemplateLoad($modulename,$action,$data){
		if($this->require_vtapi && !Api::is_connected()) return $data;
		$pluginconfig =  $this->pluginconfig;
		
		if($action=="base") $modulename=$_REQUEST['module'];
			
		
		if($pluginconfig[$modulename]['is_enabled']=="true" && $pluginconfig['is_enabled']=="true"){
		
		switch($action){
			case "base":{
			
				if(isset($_POST['update']) && $_POST['update']=="true" && isset($_REQUEST['id']) && isset($_REQUEST['module'])){
						
						  $re = "/^[\\d]*x[\\d]*$/"; 
						  
						  $sparams = array(
								'id' => $_REQUEST['id'], 
								'block'=>$_REQUEST['module'],
								'contactid'=>$_SESSION["loggeduser"]['id'],
								'sessionid'=>$_SESSION["loggeduser"]['sessionid']
						  );
								
						  if($_REQUEST['module']=="Invoice") $lmod = $GLOBALS["sclient"]->call('get_invoice_detail', $sparams);
						  else  $lmod = $GLOBALS["sclient"]->call('get_details', $sparams);
						  
						  if($lmod[0]=="#NOT AUTHORIZED#") {
							$GLOBALS['entitystatusupdate']['operationresult']="ERROR";
							break;  
						  }
						  
						  if(preg_match($re,$_REQUEST['id'])) $target=$_REQUEST['id'];
							else{
								$modid=Api::getModuleId($_REQUEST['module']);
								$target=$modid."x".$_REQUEST['id'];
						  }
						  
						  if(isset($_POST['reject']) && $_POST['reject']=='true') $newstatus=$pluginconfig[$_REQUEST['module']]['rejected_status'];
						  else $newstatus=$pluginconfig[$_REQUEST['module']]['approved_status'];
						  
					  	  $modinfo=array(
					  	  	$this->statusfields[$_REQUEST['module']]=>$newstatus,
					  	  );
					  	  
					  	  $newinfo=$this->reviseRecord($target,$modinfo);
					  	  $GLOBALS['entitystatusupdate']['operationresult']="SUCCESS";
				}
				break;
			}
			case "list":{
				break;
			}			
			case "detail":
			case "detail_api": {
				if(isset($GLOBALS['api_client']) && $GLOBALS['api_client']!="NOT_CONFIGURED" && $GLOBALS['api_client']!="API_LOGIN_FAILED"){

				  	$re = "/^[\\d]*x[\\d]*$/"; 
					if(preg_match($re,$data['targetid'])) $target=$data['targetid'];
					else{
						$modid=Api::getModuleId($modulename);
						$target=$modid."x".$data['targetid'];
					}	
					
					$entdet=$GLOBALS['api_client']->doRetrieve($target);
				  	
				  	if(isset($GLOBALS['entitystatusupdate']['operationresult'])){				  	  
				  	  $data['plugin_data']['entitystatusupdate']['operationresult']=$GLOBALS['entitystatusupdate']['operationresult'];
				  	  unset($GLOBALS['entitystatusupdate']['operationresult']);
				  	}
				  	
				  	$data['plugin_data']['entitystatusupdate']['config']=$pluginconfig;
					$data['plugin_data']['entitystatusupdate']['entstatus']=$entdet[$this->statusfields[$modulename]];
					$data['plugin_data']['modulename']=$modulename;
				  	
				}
				break;
			}
		}
		
		
		
		
		
		
		$data['plugin_data']['views']['header'][$this->plugin_name]="approvealert";
		$data['plugin_data']['views']['blocks'][$this->plugin_name]="approvebutton";
		$data['plugin_data']['views']['footer'][$this->plugin_name]="";
		}
		
		return $data;
		
	}
	
	function reviseRecord($recordid,$updatedinfo){
		global $mycwsapi;
		$updatedinfo['id']=$recordid;		
		$updatedrecord=json_encode($updatedinfo);
		
		$data = array('operation' => 'revise', 'sessionName' => $mycwsapi->session_data->sessionName,'element'=>$updatedrecord);
		$result=WSRequest::post($mycwsapi->vtiger_ws_url,$data);		
		if(!$result) return false;
		else return json_decode(json_encode($result),true);
		
	}
	
	/*
	function postTemplateLoad($modulename,$action,$data){
		Template::displayPlugin('inventorylines',$data,'postload');
	}
	*/

}


?>