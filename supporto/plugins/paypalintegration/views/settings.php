<script src="views/assets/jquery-chosen-sortable.min.js" type="text/javascript" charset="utf-8"></script> 
<script src="views/assets/chosen.order.min.js"></script> 
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

	   		<form role="form" method="post"  enctype="multipart/form-data">
	   		<input type="hidden" name="updateconfig" value="1">
	   		
	   		<div class="form-group col-md-4">	   		
		   		<div class="checkbox" style="margin-top:0px;">
					  <p><b>Enabled</b></p>
					    <label>
					      <input type="checkbox" value="true" name="settings[is_enabled]" <?php if($pluginsettings['config']['is_enabled']) echo "checked"?>> Check to enable this plugin for your Portal.
					    </label>
				</div>	   		
	   		</div>
	   		<div class="form-group  col-md-5">
		    </div>
	   		
	   		<div class="form-group col-md-3">	
	   			<label>License Status </label>		
		   		<div class="alert alert-success text-center"><b>PAID - UNLIMITED</b></div>
		   			   		
	   		</div>
	   		
	   		
	   		<div class="form-group col-md-12">
		   		<div class="alert alert-info col-md-12">These are the PayPal API credential that you need to enable payment for this plugin, for more information on how to obtain these credentials you can read the official tutorial on PayPal Website <a href="https://developer.paypal.com/docs/classic/api/apiCredentials" target="_blank">Creating and Managing Classic API Credentials</a>.
		   		</div>
	   		</div>
	   		
	   		<div class="form-group  col-md-3">
				    <label for="paypal_api_user">Paypal Api Username <span style="color:red">*</span></label>
				    <input type="text" class="form-control" id="paypal_api_user" name="settings[paypal_api_user]" value="<?php echo $pluginsettings['config']['paypal_api_user']; ?>" required >
		    </div>
	   		
	   		<div class="form-group  col-md-3">
				    <label for="paypal_api_pass">Paypal Api Password <span style="color:red">*</span></label>
				    <input type="text" class="form-control" id="paypal_api_pass" name="settings[paypal_api_pass]" value="<?php echo $pluginsettings['config']['paypal_api_pass']; ?>" required >
		    </div>
		    
		    <div class="form-group  col-md-6">
				    <label for="paypal_api_signature">Paypal Api Signature <span style="color:red">*</span></label>
				    <input type="text" class="form-control" id="paypal_api_signature" name="settings[paypal_api_signature]" value="<?php echo $pluginsettings['config']['paypal_api_signature']; ?>" required >
		    </div>
	   		
	   		
<?php foreach($pluginsettings['affectedmodules'] as $afmodule): ?>	
	
			<div class="form-group col-md-12"><hr></div>
				
		<div class="form-group col-md-4">	   		
		   		<div class="checkbox" style="margin-top:0px;">
					  <p><b>Enabled for <?php echo $afmodule; ?></b></p>
					    <label>
					      <input type="checkbox" value="true" name="settings[<?php echo $afmodule ?>][is_enabled]" <?php if($pluginsettings['config'][$afmodule]['is_enabled']) echo "checked"?>> Check to enable this plugin  <?php echo $afmodule ?>  Module
					    </label>
				</div>	   		
	   	</div>
	   	<div class="form-group col-md-4">	   		
		   	<label for="paid_status"><?php echo $afmodule ?> Hide on Status</b></label>
												    <p class="help-block">Hide the PayPal button for Status.</p>
												    <select name="settings[<?php echo $afmodule ?>][hide_on_status][]" id="settings_<?php echo $afmodule ?>_hideonstatus" class="form-control chosen-select chzn-sortable" multiple>
												    <?php foreach($pluginsettings['statusfields'][$afmodule]['type']['picklistValues'] as $statusval): ?>
												    
												    	<?php
												    	$selected="";
												    			if(in_array($statusval['value'],$pluginsettings['config'][$afmodule]['hide_on_status'])) $selected=" selected";
					
												
													    		echo "<option value='".$statusval['value']."' ".$selected.">".$statusval['label']."</option>";
													    
													    $sc++;		
												    	?>
												    	<?php endforeach; ?>
												    </select>   		
	   	</div>
	   	<div class="form-group col-md-4">	   		
		   	<label for="paid_status"><?php echo $afmodule ?> Paid Status</b></label>
												    <p class="help-block">Select your <?php echo $afmodule ?>  Status after a success Payment.</p>
												    <select name="settings[<?php echo $afmodule ?>][paid_status]" id="settings_<?php echo $afmodule ?>_fields" class="form-control chosen-select chzn-sortable" >
												    <?php foreach($pluginsettings['statusfields'][$afmodule]['type']['picklistValues'] as $statusval): ?>
												    
												    	<?php
												    			$selected="";
												    			if(isset($pluginsettings['config'][$afmodule]['paid_status']) && $statusval['value'] == $pluginsettings['config'][$afmodule]['paid_status']) $selected=" selected";
					
													    		echo "<option value='".$statusval['value']."' $selected>".$statusval['label']."</option>";
													    
													    $sc++;		
												    	?>
												    	<?php endforeach; ?>
												    </select>   		
	   	</div>
	   		

	   														   
	   	<?php endforeach; ?>
	   	
	   		
	   		
	
	
			   	
			   	<div class="col-md-12" style="padding-bottom:50px;">
			  <hr>
					    
					    <div class="row">
					    <div class="col-md-12 text-center"><button type="submit" class="btn btn-success btn-lg">Save Configuration</button></div>
						

			   	</div>
		   	</form>
	   	</div>
	   	</div>
   	</div>
   	
   	<script>
	   	



$(function(){

    $( ".chosen-select" ).chosen();
	//$(".chosen-select").chosen({disable_search_threshold: 10}).chosenSortable();		

    	
})


	   	
   	</script>