/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("SubscribersList_Edit_Js",{
instance : {}}
,{

	advanceFilterInstance : false,
	formElement : false,
	
	getForm : function() {
		if(this.formElement == false){
			this.setForm(jQuery('#EmailCampaigns_step2'));
		}
		return this.formElement;
	},
	
	registersearchModuleListEvent : function(){
		
		var thisInstance = this;
		jQuery('#searchModuleList').on('change', function(e) {
			var selectElement = jQuery(e.currentTarget);
			var source_module = selectElement.val();
			var record = jQuery('#record').val();
			
			var list_name = jQuery('#SubscribersList_editView_fieldName_list_name').val();
			var url = "index.php?module="+app.getModuleName()+"&mode1=selectsourmodule&view=Edit&module_name="+source_module+"&list_name="+list_name;
						
			if(record == ''){
				window.location.href=url;
			}else{
				window.location.href=url+'&record='+record;
			}	
		});
		
		jQuery('#customFilter').on('change', function(e) {
			
			var source_module =  $('select[name="searchmodulelist"]').val();
			var selectElement1 = jQuery(e.currentTarget);
			var viewid = selectElement1.val();
			var list_name = jQuery('#SubscribersList_editView_fieldName_list_name').val();

			var record = jQuery('#record').val();
			
			var url = "index.php?module="+app.getModuleName()+"&view=Edit&module_name="+source_module+"&viewid="+viewid+"&list_name="+list_name;
			if(record == ''){
				window.location.href=url;
			}else{
				window.location.href=url+'&record='+record;
			}	
		});
		
		jQuery('.backStep').on('click', function(e) {
			
			var record = jQuery('#record').val();
			
			var url = "index.php?module="+app.getModuleName()+"&view=Edit";
			if(record == ''){
				window.location.href=url;
			}else{
				window.location.href=url+'&record='+record;
			}	
		});
		
		
	},
	
	registerEnableFilterOption : function() {
		jQuery('[name="conditionstype"]').on('change',function(e) {
			var advanceFilterContainer = jQuery('#advanceFilterContainer');
			var currentRadioButtonElement = jQuery(e.currentTarget);
			if(currentRadioButtonElement.hasClass('recreate')){
				if(currentRadioButtonElement.is(':checked')){
					advanceFilterContainer.removeClass('zeroOpacity');
				}
			} else {
				advanceFilterContainer.addClass('zeroOpacity');
			}
		});
	},
	
	calculateValues : function(){
		//handled advanced filters saved values.
		jQuery('[name="filtersavedinnew"]').val("6");
		var advfilterlist = this.advanceFilterInstance.getValues();
		jQuery('#advanced_filter').val(JSON.stringify(advfilterlist));
		
	},
	
	registerSubmitEvent : function(){
	
            editViewForm = this.getForm();
            var thisInstance = this;
            editViewForm.submit(function(e){
					var advfilterlist = thisInstance.advanceFilterInstance.getValues();
					jQuery('#advanced_filter').val(JSON.stringify(advfilterlist));
                   
            });
	},
	 registerApplyFilterConditionsEvent: function() {
        var a = this;
        jQuery(".filterClick").off();
        jQuery(".filterClick").on("click", function(i) {
			jQuery('[name="filtersavedinnew"]').val("6");
			var advfilterlist = a.advanceFilterInstance.getValues();
			
			var selectElement = jQuery("#searchModuleList");
			var source_module = selectElement.val();
			
            var j = {
                'module': app.getModuleName(),
				'action' : "SaveAjax",
				'mode' : 'filter_subscribers',
				'module_name' : source_module,
                "filter": JSON.stringify(advfilterlist)
            };
            AppConnector.request(j).then(function(k) {
                var e = k.result.count;
                jQuery("#filter_1").html(e);
				
				var related_recorts = k.result.related_recorts;
				jQuery('[name="related_recorts"]').val(related_recorts);
            });
        });
    },
	
	registerEvents : function(){
		
		var opts = app.validationEngineOptions;
		// to prevent the page reload after the validation has completed
		opts['onValidationComplete'] = function(form,valid) {
            //returns the valid status
               return valid;
        };
		opts['promptPosition'] = "bottomRight";
		jQuery('#EmailCampaigns_step2').validationEngine(opts);
		
		 this.registerSubmitEvent();
		this.registersearchModuleListEvent();
		this.registerApplyFilterConditionsEvent();
		this.advanceFilterInstance = Vtiger_AdvanceFilter_Js.getInstance(jQuery('.filterContainer'));
		if(jQuery('[name="filtersavedinnew"]').val() == '5'){
			this.registerEnableFilterOption();
		}
		
	}
});



