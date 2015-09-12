<?php


class PaypalintegrationPlugin { 

	var $plugin_label="Paypal Integration";
	var $plugin_name="paypalintegration";
	var $plugin_description="This plugin allow you to show the 'Pay with Paypal' button at the bottom of your Quotes and Invoices, when a success payment will be registered the entity status will be automatically set to the one selected during the configuration of the plugin.";
	var $affectedmodules=array("Quotes","Invoice","PortalBase");
	//var $affectedmodules=array("SalesOrderWS","Quotes","Invoice","PortalBase");
	var $pluginconfig;
	var $require_vtapi=true;
	var $statusfields=array(
			//"SalesOrderWS"=>"sostatus",
			"Quotes"=>"quotestage",
			"Invoice"=>"invoicestatus",
	);
	
	var $numberfields=array(
			//"SalesOrderWS"=>"salesorder_no",
			"Quotes"=>"quote_no",
			"Invoice"=>"invoice_no",
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
		//$statusinfo['SalesOrder']=$mycwsapi->getModuleFields("SalesOrder");
		$statusinfo['Invoice']=$mycwsapi->getModuleFields("Invoice");
		
		
		
		$statusfields=$this->statusfields;
		
		foreach($statusinfo as $modulename => $moduleinfo){
			foreach($moduleinfo['fields'] as $modf){
				if(in_array($modf['name'], $statusfields)){
					if($modulename=="SalesOrder") $modulename=$modulename."WS";
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
				if(isset($_REQUEST['paynow']) && isset($_REQUEST['id']) && isset($_REQUEST['module']) && !isset($_REQUEST['ps'])  && !isset($_REQUEST['token'])){
					require("lib/MYC_Paypal.php");
					

					$paypalconnector= new MYC_Paypal($pluginconfig['paypal_api_user'], $pluginconfig['paypal_api_pass'], $pluginconfig['paypal_api_signature']);
					
					if(preg_match("/^[\\d]*x[\\d]*$/",$_REQUEST['id'])) $target=$_REQUEST['id'];
					else $target=Api::getModuleId($_REQUEST['module'])."x".$_REQUEST['id'];
					
					$trecord = $GLOBALS['api_client']->doRetrieve($target);
					
					if($trecord){
						$currencydet=$GLOBALS['api_client']->doRetrieve($trecord['currency_id']);	
						$currency=$currencydet['currency_code'];	
								
						$order_details=array(
							"subject"=>$trecord[$this->numberfields[$modulename]]." - ".$trecord['subject'],
							"amount"=>number_format($trecord['hdnGrandTotal'],2,".",""),
							"currency"=>$currency
						);
						
						$paypalconnector->checkout($order_details);
					}					
					
				}
				if(isset($_REQUEST['ps']) && $_REQUEST['ps']=="success" && isset($_REQUEST['id']) && isset($_REQUEST['module']) && isset($_REQUEST['token'])){
						  
						  require("lib/MYC_Paypal.php");
						  $paypalconnector= new MYC_Paypal($pluginconfig['paypal_api_user'], $pluginconfig['paypal_api_pass'], $pluginconfig['paypal_api_signature']);
						  
						  if(preg_match("/^[\\d]*x[\\d]*$/",$_REQUEST['id'])) $target=$_REQUEST['id'];
						  else $target=Api::getModuleId($_REQUEST['module'])."x".$_REQUEST['id'];
						  
						  $trecord = $GLOBALS['api_client']->doRetrieve($target);
					
						  if($trecord){
								$currencydet=$GLOBALS['api_client']->doRetrieve($trecord['currency_id']);	
								$currency=$currencydet['currency_code'];	
										
								$order_details=array(
									"subject"=>$trecord[$this->numberfields[$modulename]]." - ".$trecord['subject'],
									"amount"=>number_format($trecord['hdnGrandTotal'],2,".",""),
									"currency"=>$currency
								);
								
								$operation_result=$paypalconnector->finalize($order_details,$_REQUEST['token']);
								if($operation_result=="SUCCESS"){
									  global $mycwsapi;
								  	  $modinfo=array(
								  	  	$this->statusfields[$_REQUEST['module']]=>$pluginconfig[$_REQUEST['module']]['paid_status'],
								  	  );
								  	  
								  	  $newinfo=$mycwsapi->updateRecord($target,$modinfo);
								  	  $_SESSION['flash_mem']['payment_status']="SUCCESS";
								}
								else{
									 $_SESSION['flash_mem']['payment_status']="ERROR";
									 $_SESSION['flash_mem']['operation_result']=$operation_result;
								}
						  }				  	  
					  	  
				}
			}
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
					
					if(isset($_REQUEST['pa']) && $_REQUEST['pa']=="new"){
						die("n");
					} 
					
					
					$entdet=$GLOBALS['api_client']->doRetrieve($target);
					$currencydet=$GLOBALS['api_client']->doRetrieve($entdet['currency_id']);
				  	$data['plugin_data']['payobject']=$entdet['subject'];
				  	$data['plugin_data']['paypalaccount']=$pluginconfig['paypalaccount'];
				  	$data['plugin_data']['total']=number_format($entdet['hdnGrandTotal'],2);
				  	$data['plugin_data']['curencycode']=$currencydet['currency_code'];	
				  	$data['plugin_data']['curencysymbol']=$currencydet['currency_symbol'];
				  	
				  	if($_SESSION['flash_mem']['payment_status'] && $_SESSION['flash_mem']['payment_status']=="SUCCESS"){				  	  
				  	  	$data['plugin_data']['paypalintegration']['paymentstatus']="SUCCESS";
				  	  	unset($_SESSION['flash_mem']['operation_result']);
				  	  	unset($_SESSION['flash_mem']['payment_status']);
				  	}
				  	else if(isset($_SESSION['flash_mem']['payment_status']) && $_SESSION['flash_mem']['payment_status']=="ERROR"){ 
				  		$data['plugin_data']['paypalintegration']['paymentstatus']="ERROR";
				  		$data['plugin_data']['paypalintegration']['paymenterror']=$_SESSION['flash_mem']['operation_result'];
				  		unset($_SESSION['flash_mem']['operation_result']);
				  		unset($_SESSION['flash_mem']['payment_status']);
				  	}
				  	else if(isset($_REQUEST['ps']) && $_REQUEST['ps']=="cancel"){ 
				  		$data['plugin_data']['paypalintegration']['paymentstatus']="CANCEL";
				  	}
				  					  	
				  	$data['plugin_data']['config']=$pluginconfig;
					$data['plugin_data']['entstatus']=$entdet[$this->statusfields[$modulename]];
					$data['plugin_data']['modulename']=$modulename;
				  	
				}
				break;
			}
		}
		
		
		
		
		
		
		$data['plugin_data']['views']['header'][$this->plugin_name]="paymentstatus";
		$data['plugin_data']['views']['blocks'][$this->plugin_name]="paypalbutton";
		$data['plugin_data']['views']['footer'][$this->plugin_name]="";
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