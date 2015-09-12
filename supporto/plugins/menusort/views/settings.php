<?php

/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */
 
 
 ?>
<script src="views/assets/jquery-chosen-sortable.min.js" type="text/javascript" charset="utf-8"></script> 
<script src="views/assets/chosen.order.min.js"></script> 
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

	   		<form role="form" method="post"  enctype="multipart/form-data">
	   		<input type="hidden" name="updateconfig" value="1">
	   		
	   		<div class="form-group col-md-9">	   		
		   		<div class="checkbox" style="margin-top:0px;">
					  <p><b>Enabled</b></p>
					    <label>
					      <input type="checkbox" value="true" name="settings[is_enabled]" <?php if($pluginsettings['config']['is_enabled']) echo "checked"?>> Check to enable this plugin for your Portal.
					    </label>
				</div>	   		
	   		</div>
	   		
	   		<div class="form-group col-md-3">	
	   			<label>License Status </label>		
		   		<div class="alert alert-success text-center"><b>FREE - UNLIMITED</b></div>
		   			   		
	   		</div>
	   		
	
	   		<div class="form-group col-md-12">
	   		
												    <label for="list_fields">Enabled modules order for your portal </b></label>
												    <p class="help-block">Set the order to display the enabled Modules in your portal menu.</p>
												    <select name="settings[modules_order][]" id="modules_order" class="form-control chosen-select chzn-sortable" multiple>
												    	<?php foreach($pluginsettings['avmod'] as $modulename) {
												    			$selected=" selected";
												    							
													    		echo "<option value='".$modulename."' $selected>".Language::translate($modulename)."</option>";
													    		}
												    	?>
												    </select>
												  </div>
												  
												  			<div class="clearfix"></div>

	   		
	   		
	
	
			   	
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

    <?php echo "var listFields = ".json_encode($pluginsettings['config']['modules_order']).";  ChosenOrder.setSelectionOrder($('#modules_order'), listFields);"; ?>
    
    $('.chosen-select').addClass('chzn-sortable').chosenSortable();
    
    $('.search-choice-close').remove();
    $(".chosen-results").remove();
    
    $('.search-choice').attr("style","padding: 3px 5px 3px 5px;");
    	
})


	   	
   	</script>