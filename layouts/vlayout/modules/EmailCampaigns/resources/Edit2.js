/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
EmailCampaigns_Edit_Js("EmailCampaigns_Edit2_Js",{},{
	
	step2Container : false,
	
	advanceFilterInstance : false,
	init : function() {
		this.initialize();
	},
	/**
	 * Function to get the container which holds all the reports step1 elements
	 * @return jQuery object
	 */
	getContainer : function() {
		return this.step2Container;
	},
	/**
	 * Function to set the reports step1 container
	 * @params : element - which represents the reports step1 container
	 * @return : current instance
	 */
	setContainer : function(element) {
		this.step2Container = element;
		return this;
	},
	
	/**
	 * Function  to intialize the reports step1
	 */
	initialize : function(container) {
		if(typeof container == 'undefined') {
			container = jQuery('#EmailCampaigns_step2');
		}
		if(container.is('#EmailCampaigns_step2')) {
			this.setContainer(container);
		}else{
			this.setContainer(jQuery('#EmailCampaigns_step2'));
		}
	},
	
	calculateValues : function(){
		//handled advanced filters saved values.
		jQuery('[name="filtersavedinnew"]').val("6");
		var advfilterlist = this.advanceFilterInstance.getValues();
		jQuery('#advanced_filter').val(JSON.stringify(advfilterlist));
		
	},
	
	submit : function(){
		var aDeferred = jQuery.Deferred();
		var form = this.getContainer();
		this.calculateValues();
		var formData = form.serializeFormData();
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		AppConnector.request(formData).then(
			function(data) {
				form.hide();
				progressIndicatorElement.progressIndicator({
					'mode' : 'hide'
				})
				aDeferred.resolve(data);
			},
			function(error,err){

			}
		);
		return aDeferred.promise();
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
	
	selectoptionshowhide : function() {
	    jQuery('[name="select_option"]').on('change',function(e) {
		    var selectvalue = jQuery('[name="select_option"]').val();
		    //alert(selectvalue);
		    if(selectvalue=='Start from past campaign'){
			    //jQuery('#EmailCampaigns_step2').reset();
			    jQuery('#filter_condition').hide();
			    jQuery('#subscriber_select').hide();
			    jQuery('#howto').hide();
			    jQuery('#past_emailcampaign').show();
			    jQuery('#emailclicklink').show();
			    jQuery('#whopenemail').show();
		    }
		    if(selectvalue=='Select a list'){
			    //jQuery('#EmailCampaigns_step2').reset();
			    jQuery('#filter_condition').show();
			    jQuery('#subscriber_select').show();
			    jQuery('#howto').show();
			    jQuery('#past_emailcampaign').hide();
			    jQuery('#emailclicklink').hide();
			    jQuery('#whopenemail').hide();
		    }
		    
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
			var parent_emailcampaignsidElement = jQuery("#parent_emailcampaignsid");
			var parent_emailcampaignsid = parent_emailcampaignsidElement.val();
			
			if ($("#EmailCampaigns_editView_fieldName_whopenemail").is(":checked"))
			{
			  var whopenemail = '1';
			}else{
				var whopenemail = '0';
			}
			
			if ($("#EmailCampaigns_editView_fieldName_emailclicklink").is(":checked"))
			{
				var emailclicklink = '1';
			}else{
				var emailclicklink = '0';
			}
			
            var j = {
                'module': app.getModuleName(),
				'action' : "SaveAjax",
				'mode' : 'filter_subscribers',
				'module_name' : source_module,
				'whopenemail' : whopenemail,
				'emailclicklink' : emailclicklink,
				'parent_emailcampaignsid' : parent_emailcampaignsid,
                "filter": JSON.stringify(advfilterlist)
            };
            AppConnector.request(j).then(function(k) {
                var e = k.result.count;
                jQuery("#filter_1").html(e);
				
				var related_recorts = k.result.related_recorts;
				jQuery('[name="related_recorts"]').val(related_recorts);
				
				var searchmodulelist = k.result.module_name;
				jQuery('[name="searchmodulelist"]').val(searchmodulelist);
				jQuery('[name="module_name"]').val(searchmodulelist);
            });
        });
    },
	
	
	
	getPopUpParams : function(container) {
	
        var params = {};
        var sourceModule = app.getModuleName();
		var popupReferenceModule = jQuery('input[name="popupReferenceModule"]',container).val();
        var sourceFieldElement = jQuery('input[class="sourceField"]',container);
		var sourceField = sourceFieldElement.attr('name');
		var sourceRecordElement = jQuery('input[name="record"]');
		var sourceRecordId = '';
		if(sourceRecordElement.length > 0) {
            sourceRecordId = sourceRecordElement.val();
        }

        var isMultiple = false;
        if(sourceFieldElement.data('multiple') == true){
            isMultiple = true;
        }

		var params = {
			'module' : popupReferenceModule,
			'src_module' : sourceModule,
			'src_field' : sourceField,
			'src_record' : sourceRecordId
		}

        if(isMultiple) {
            params.multi_select = true ;
        }
        return params;
    },

	
	openPopUp : function(e){
		var thisInstance = this;
		var parentElem = jQuery(e.target).closest('div').parent();
		var container = this.getContainer();
		var params = this.getPopUpParams(parentElem);

        var isMultiple = false;
        if(params.multi_select) {
            isMultiple = true;
        }

        var sourceFieldElement = jQuery('input[class="sourceField"]',parentElem);

        var prePopupOpenEvent = jQuery.Event(Vtiger_Edit_Js.preReferencePopUpOpenEvent);
        sourceFieldElement.trigger(prePopupOpenEvent);

        if(prePopupOpenEvent.isDefaultPrevented()) {
            return ;
        }

		var popupInstance =Vtiger_Popup_Js.getInstance();
		popupInstance.show(params,function(data){
				var responseData = JSON.parse(data);
                var dataList = new Array();
				for(var id in responseData){
					var data = {
						'name' : responseData[id].name,
						'id' : id
					}
                    dataList.push(data);
                    if(!isMultiple) {
                        thisInstance.setReferenceFieldValue(parentElem, data);
                    }
				}

                if(isMultiple) {
                    sourceFieldElement.trigger(Vtiger_Edit_Js.refrenceMultiSelectionEvent,{'data':dataList});
                }
			});
	},
	
	
	
	referenceModulePopupRegisterEvent : function(container){
		var thisInstance = this;
		container.find('.relatedPopup').on("click",function(e){
			thisInstance.openPopUp(e);
		});
		container.find('.referenceModulesList').chosen().change(function(e){
			var element = jQuery(e.currentTarget);
			var closestTD = element.closest('td').next();
			var popupReferenceModule = element.val();
			var referenceModuleElement = jQuery('input[name="popupReferenceModule"]', closestTD);
			var prevSelectedReferenceModule = referenceModuleElement.val();
			referenceModuleElement.val(popupReferenceModule);

			//If Reference module is changed then we should clear the previous value
			if(prevSelectedReferenceModule != popupReferenceModule) {
				closestTD.find('.clearReferenceSelection').trigger('click');
			}
		});
	},
	
	registerClearReferenceSelectionEvent : function(container) {
		container.find('.clearReferenceSelection').on('click', function(e){
			jQuery('#parent_emailcampaignsid_display').removeAttr('readonly').val('');
		})
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

		var container = this.getContainer();
		app.changeSelectElementView(container);
		this.advanceFilterInstance = Vtiger_AdvanceFilter_Js.getInstance(jQuery('.filterContainer',container));
		//this.getPopUp();
		if(jQuery('[name="filtersavedinnew"]',container).val() == '5'){
			this.registerEnableFilterOption();
		}
		this.selectoptionshowhide();
		this.registerApplyFilterConditionsEvent();
		this.referenceModulePopupRegisterEvent(container);
		this.registerClearReferenceSelectionEvent(container);

		
	}
});