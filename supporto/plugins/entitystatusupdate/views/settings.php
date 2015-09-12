<script src="views/assets/jquery-chosen-sortable.min.js" type="text/javascript" charset="utf-8"></script> 
<script src="views/assets/chosen.order.min.js"></script> 
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

	   		<form role="form" method="post"  enctype="multipart/form-data">
	   		<input type="hidden" name="updateconfig" value="1">
	   		
	   		<div class="form-group col-md-8">	   		
		   		<div class="checkbox" style="margin-top:0px;">
					  <p><b>Enabled</b></p>
					    <label>
					      <input type="checkbox" value="true" name="settings[is_enabled]" <?php if($pluginsettings['config']['is_enabled']) echo "checked"?>> Check to enable this plugin for your Portal.
					    </label>
				</div>	   		
	   		</div>
	   		
	   		
	   		<div class="form-group col-md-4">	
	   			<label>License Status </label>		
		   		<div class="alert alert-success text-center"><b>PAID - UNLIMITED</b></div>
		   			   		
	   		</div>
	   		
	   		
	   		
<?php foreach($pluginsettings['affectedmodules'] as $afmodule): ?>	
	
			<div class="form-group col-md-12"><hr></div>
				
		<div class="form-group col-md-2">	   		
		   		<div class="checkbox" style="margin-top:0px;">
					  <p><b>Enabled for <?php echo $afmodule; ?></b></p>
					    <label>
					      <input type="checkbox" value="true" name="settings[<?php echo $afmodule ?>][is_enabled]" <?php if($pluginsettings['config'][$afmodule]['is_enabled']) echo "checked"?>> Check to enable this plugin  <?php echo $afmodule ?>  Module
					    </label>
				</div>	   		
	   	</div>
	   	<div class="form-group col-md-4">	   		
		   	<label for="paid_status"><?php echo $afmodule ?> Hide on Status</b></label>
												    <p class="help-block">Hide the Accept/Reject button for Status.</p>
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
	   	<div class="form-group col-md-3">	   		
		   	<label for="paid_status"><?php echo $afmodule ?> Approved Status</b></label>
												    <p class="help-block">Select your <?php echo $afmodule ?>  Status after a customer approve a <?php echo $afmodule ?>.</p>
												    <select name="settings[<?php echo $afmodule ?>][approved_status]" id="settings_<?php echo $afmodule ?>_fields" class="form-control chosen-select chzn-sortable" >
												    <?php foreach($pluginsettings['statusfields'][$afmodule]['type']['picklistValues'] as $statusval): ?>
												    
												    	<?php
												    			$selected="";
												    			if(isset($pluginsettings['config'][$afmodule]['approved_status']) && $statusval['value'] == $pluginsettings['config'][$afmodule]['approved_status']) $selected=" selected";
					
													    		echo "<option value='".$statusval['value']."' $selected>".$statusval['label']."</option>";
													    
													    $sc++;		
												    	?>
												    	<?php endforeach; ?>
												    </select>   		
	   	</div>
	   	<div class="form-group col-md-3">	   		
		   	<label for="paid_status"><?php echo $afmodule ?> Rejected Status</b></label>
												    <p class="help-block">Select your <?php echo $afmodule ?>  Status after a customer reject a <?php echo $afmodule ?>.</p>
												    <select name="settings[<?php echo $afmodule ?>][rejected_status]" id="settings_<?php echo $afmodule ?>_fields_r" class="form-control chosen-select chzn-sortable" >
												    <?php foreach($pluginsettings['statusfields'][$afmodule]['type']['picklistValues'] as $statusval): ?>
												    
												    	<?php
												    			$selected="";
												    			if(isset($pluginsettings['config'][$afmodule]['rejected_status']) && $statusval['value'] == $pluginsettings['config'][$afmodule]['rejected_status']) $selected=" selected";
					
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