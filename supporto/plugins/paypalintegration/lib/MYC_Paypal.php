<?php

require("PPBootStrap.php");
class MYC_Paypal {
	
	var $paypal_service, $order_object;
	
	public function __construct($api_user, $api_pass, $api_signature){

		$paypal_config=array(
			'mode' => 'live', 
			'acct1.UserName' => $api_user, 
			'acct1.Password' => $api_pass, 
			'acct1.Signature' => $api_signature
		);
		$this->paypal_service = new PayPalAPIInterfaceServiceService($paypal_config);
	}
	
	public function checkout($order_details){
	
		$this->order_object = new PaymentDetailsType();
		$this->currency_code = $order_details['currency'];
			
		$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
		$current_url = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$returnUrl = $current_url."&ps=success";
		$cancelUrl = "$current_url&ps=cancel" ;
		
		$order_items = array();
		$order_items[0] = array('name'=>$order_details['subject'], 'amount'=>$order_details['amount'], 'quantity'=>1);
		
		$paymentDetails = $this->order_object;
		
		$this->add_products($order_items,$this->currency_code);
		
		$this->order_object->PaymentAction = "Order";
		$this->order_object->HandlingTotal = $handlingTotal;
		$this->order_object->InsuranceTotal = $insuranceTotal;
		$this->order_object->ShippingTotal = $shippingTotal;
	
		$setECReqDetails = new SetExpressCheckoutRequestDetailsType();
		$setECReqDetails->PaymentDetails[0] = $this->order_object;
		$setECReqDetails->CancelURL = $cancelUrl;
		$setECReqDetails->ReturnURL = $returnUrl;
		$setECReqDetails->NoShipping = 1;
		$setECReqDetails->AddressOverride = 0;
		$setECReqDetails->ReqConfirmShipping = 0;
		
		$setECReqType = new SetExpressCheckoutRequestType();
		$setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
		$setECReq = new SetExpressCheckoutReq();
		$setECReq->SetExpressCheckoutRequest = $setECReqType;
	
		// Perform request
	    $setECResponse = $this->paypal_service->SetExpressCheckout($setECReq);
	    
	    if(strtoupper($setECResponse->Ack) == 'SUCCESS') {
	    	$token = $setECResponse->Token;
			$payPalURL = 'https://www.paypal.com/webscr?cmd=_express-checkout&token='.$token;
	        header("Location: ".$payPalURL);
	    }
	    
	    else return $setECResponse;
		
	}
	
	public function finalize($order_details,$token){
		$getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
		$getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
		$getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;
		
		$getECResponse = $this->paypal_service->GetExpressCheckoutDetails($getExpressCheckoutReq);
		
		
		if(isset($getECResponse) && isset($getECResponse->Ack) && strtoupper($getECResponse->Ack)=="SUCCESS") {
			
			$Ack = $getECResponse->Ack;
			$Token = $getECResponse->GetExpressCheckoutDetailsResponseDetails->Token;
			$PayerID = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;
			$PayerStatus = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerStatus;
			$PaymentAction = "Sale";
			
			$this->order_object = new PaymentDetailsType();
			$this->currency_code = $order_details['currency'];
			
			$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
			$current_url = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
						
			$returnUrl = $current_url;
			$cancelUrl = "$current_url&ps=cancel" ;
			
			$order_items = array();
			$order_items[0] = array('name'=>$order_details['subject'], 'amount'=>$order_details['amount'], 'quantity'=>1);
			
			$paymentDetails = $this->order_object;
			
			$this->add_products($order_items,$this->currency_code);
			
			$DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
			$DoECRequestDetails->PayerID = $PayerID;
			$DoECRequestDetails->Token = $Token;
			$DoECRequestDetails->PaymentAction = $PaymentAction;
			$DoECRequestDetails->PaymentDetails[0] = $this->order_object;
			
			$DoECRequest = new DoExpressCheckoutPaymentRequestType();
			$DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;
			
			
			$DoECReq = new DoExpressCheckoutPaymentReq();
			$DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;
			
			$DoECResponse = $this->paypal_service->DoExpressCheckoutPayment($DoECReq);
	
			if(isset($DoECResponse)) {
				if(isset($DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo) && isset($DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->PaymentStatus) && strtoupper($DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->PaymentStatus)=="COMPLETED"){
					return "SUCCESS";
				}
				
				else return $DoECResponse;
			}
			
			else return $getECResponse;
	
		}
		
		else return $getECResponse;
	
	}
	

	
	
	function add_products($products_array,$currency_code){
	
		$itemTotalValue = 0;
		
		for($i=0; $i<count($products_array); $i++) {
			$itemAmount = new BasicAmountType($currency_code, $products_array[$i]['amount']);	
			$itemTotalValue += $products_array[$i]['amount'] * $products_array[$i]['quantity']; 
			
			$itemDetails = new PaymentDetailsItemType();
			$itemDetails->Name = $products_array[$i]['name'];
			$itemDetails->Amount = $itemAmount;
			$itemDetails->Quantity = $products_array[$i]['quantity'];
			$itemDetails->ItemCategory = "Physical";
			
			$this->order_object->PaymentDetailsItem[$i] = $itemDetails;	
		}
				
		//Payment details
		$this->order_object->ItemTotal = new BasicAmountType($currency_code, $itemTotalValue);
		$this->order_object->OrderTotal = new BasicAmountType($currency_code, $itemTotalValue);
		
		return $this->order_object;
	}
	
} 