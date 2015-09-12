<?php if(isset($data['plugin_data']['paypalintegration']['paymentstatus']) && $data['plugin_data']['paypalintegration']['paymentstatus']=="SUCCESS"): ?>
	<div class="alert alert-success"><?php echo Language::translate("Thank you, your payment has been completed, we will check it as soon as possible!"); ?></div>
<?php elseif(isset($data['plugin_data']['paypalintegration']['paymentstatus']) && $data['plugin_data']['paypalintegration']['paymentstatus']=="CANCEL"): ?>
	<div class="alert alert-danger"><?php echo Language::translate("Something went wrong with your payment, please try again!"); ?></div>
<?php elseif(isset($data['plugin_data']['paypalintegration']['paymentstatus']) && $data['plugin_data']['paypalintegration']['paymentstatus']=="ERROR"): ?>
	<div class="alert alert-danger"><?php echo Language::translate("Something went wrong with your payment, please try again! "); ?>
	<?php 
	$paymentresult=$data['plugin_data']['paypalintegration']['paymenterror'];
	if(isset($paymentresult->Errors) && count($paymentresult->Errors)){
		echo "<br><b>".Language::translate("ERROR DETAILS").":</br>";
		foreach($paymentresult->Errors as $error)
			echo "<b>ERROR ".$error->ErrorCode."</b> - ".$error->LongMessage."<br>";			
	}	
	?>
	</div>
<?php endif;  ?>