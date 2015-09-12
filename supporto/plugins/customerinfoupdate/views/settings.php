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
	   	<div class="form-group col-md-10">
												    <label for="enabled_fields">Editable Fields for <b><?php echo $afmodule ?> Module</b></label>
												    <p class="help-block">Choose which filelds you will make editable for your customer.</p>
												    <select name="settings[<?php echo $afmodule ?>][fields][]" id="settings_<?php echo $afmodule ?>_fields" class="form-control chosen-select chzn-sortable" multiple>
												    <?php foreach($pluginsettings['modfields'][$afmodule]['fieldslabels'] as $fieldname => $fieldlabel){
												    			$selected="";
												    			if(in_array($fieldname, $pluginsettings['config'][$afmodule]['fields'])) $selected=" selected";
												  
													    		echo "<option value='".$fieldname."' $selected>".$fieldlabel."</option>";
													    		}
												    	?>
												    													   
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

    <?php foreach($pluginsettings['affectedmodules'] as $afmodule) if(isset($pluginsettings['config'][$afmodule]['fields'])) echo "var listFields".$afmodule." = ".json_encode($pluginsettings['config'][$afmodule]['fields']).";  ChosenOrder.setSelectionOrder($('#settings_".$afmodule."_fields'), listFields".$afmodule.");"; ?>
    
    $('.chosen-select').addClass('chzn-sortable').chosenSortable();
    
    	
})


	   	
   	</script>