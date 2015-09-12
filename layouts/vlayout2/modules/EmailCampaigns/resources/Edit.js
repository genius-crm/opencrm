/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("EmailCampaigns_Edit_Js",{
instance : {}}
,{

	currentInstance : false,
	workFlowsContainer : false,
	init : function() {
		this.initiate();
	},
	/**
	 * Function to get the container which holds all the workflow elements
	 * @return jQuery object
	 */
	getContainer : function() {
		return this.workFlowsContainer;
	},

	/**
	 * Function to set the reports container
	 * @params : element - which represents the workflow container
	 * @return : current instance
	 */
	setContainer : function(element) {
		this.workFlowsContainer = element;
		return this;
	},
		
	/*
	 * Function to return the instance based on the step of the Workflow
	 */
	getInstance : function(step) {
	//  alert(step);
		if(step in EmailCampaigns_Edit_Js.instance ){
		//  alert('if');
		  return EmailCampaigns_Edit_Js.instance[step];
		} else {
		//  alert('else');
			var moduleClassName = 'EmailCampaigns_Edit'+step+'_Js' ;
			//alert(moduleClassName)
			EmailCampaigns_Edit_Js.instance[step] =  new window[moduleClassName]();
			return EmailCampaigns_Edit_Js.instance[step]
		}
	},
	
	/*
	 * Function to get the value of the step 
	 * returns 1 or 2 or 3
	 */
	getStepValue : function(){
		var container = this.currentInstance.getContainer(); 
		return jQuery('.step',container).val();
	},
	
	/*
	 * Function to initiate the step 1 instance
	 */
	initiate : function(container){
		if(typeof container == 'undefined') {
			container = jQuery('.workFlowContents');
		}
		if(container.is('.workFlowContents')) {
			this.setContainer(container);
		}else{
			this.setContainer(jQuery('.workFlowContents',container));
		}
		this.initiateStep('1');
		this.currentInstance.registerEvents();
	},
	/*
	 * Function to initiate all the operations for a step
	 * @params step value
	 */
	initiateStep : function(stepVal) {
		var step = 'step'+stepVal;
		this.activateHeader(step);
		var currentInstance = this.getInstance(stepVal);
		this.currentInstance = currentInstance;
	},
	
	/*
	 * Function to activate the header based on the class
	 * @params class name
	 */
	activateHeader : function(step) {
		var headersContainer = jQuery('.crumbs ');
		headersContainer.find('.active').removeClass('active');
		jQuery('#'+step,headersContainer).addClass('active');
	},
	
	registerFormSubmitEvent : function(form) {
		var thisInstance = this;
		
		if(jQuery.isFunction(thisInstance.currentInstance.submit)){
			form.on('submit',function(e){
				var form = jQuery(e.currentTarget);
				
				var specialValidation = true;
				if(jQuery.isFunction(thisInstance.currentInstance.isFormValidate)){
					var specialValidation =  thisInstance.currentInstance.isFormValidate();
				}
				if ( form.validationEngine('validate') && specialValidation) {
						var stepVal = thisInstance.getStepValue();
						if(stepVal == '2'){
							var filter_1 = jQuery("#filter_1").html();
							if(filter_1 == '0'){
							 var g = {
								title: app.vtranslate("Warning"),
								type: "error",
								text: app.vtranslate("No Subscriber found. Please select another list or change conditions for the current filter. Then press apply button.")
								};
								Vtiger_Helper_Js.showPnotify(g);
								return false;
							}
							
						}
						thisInstance.currentInstance.submit().then(function(data){
							thisInstance.getContainer().append(data);
							var stepVal = thisInstance.getStepValue();
							var nextStepVal = parseInt(stepVal) + 1;
							thisInstance.initiateStep(nextStepVal);
							thisInstance.currentInstance.initialize();
							var container = thisInstance.currentInstance.getContainer();
							thisInstance.registerFormSubmitEvent(container);
							thisInstance.currentInstance.registerEvents();
						});
				}
				e.preventDefault();
			})		
		}
	},
	back : function(){
		var step = this.getStepValue();
		var prevStep = parseInt(step) - 1;
		this.currentInstance.initialize();
		var container = this.currentInstance.getContainer();
		var workflowRecordElement = jQuery('[name="record"]',container);
		var workFlowId = workflowRecordElement.val();
		
		container.remove();
		this.initiateStep(prevStep);
		var currentContainer = this.currentInstance.getContainer();
		currentContainer.show();
		jQuery('[name="record"]',currentContainer).val(workFlowId);
		var modulesList = jQuery('#moduleName',currentContainer);
		if(modulesList.length > 0 && workFlowId != '') {
			modulesList.attr('disabled','disabled').trigger('liszt:updated');
		}
	},
	
	registerBackStepClickEvent : function(){
		var thisInstance = this;
		var container = this.getContainer();
		container.on('click','.backStep',function(e){
			thisInstance.back();
		});
		container.on('click','.step',function(e){
			var stepid = this.id;
			var res = stepid.split("step");
			var step = jQuery('.step',container).val();
			var record = jQuery('#record').val();
			var finalstep = parseInt(step) + 1
			//alert(step);
					if(parseInt(finalstep) == parseInt(res[1]) && record!=''){
						//thisInstance.steplink(res[1]);
						thisInstance.currentInstance.submit().then(function(data){
							thisInstance.getContainer().append(data);
							var stepVal = thisInstance.getStepValue();
							var nextStepVal = parseInt(stepVal) + 1;
							thisInstance.initiateStep(nextStepVal);
							thisInstance.currentInstance.initialize();
							var container = thisInstance.currentInstance.getContainer();
							thisInstance.registerFormSubmitEvent(container);
							thisInstance.currentInstance.registerEvents();
						});
					}else if(parseInt(step) >= parseInt(res[1]) && record!=''){
						thisInstance.steplink(res[1]);
					}else if(parseInt(step) >= parseInt(res[1]) && record==''){
					  thisInstance.steplink(res[1]);
					}
		});
		
		
		container.on('click','.jumpToStep',function(e){
			var b = parseInt(jQuery(e.currentTarget).attr("data-value"));
			 if(b == 1){
				  thisInstance.back();
				  thisInstance.back();
				  thisInstance.back();
			  }else if(b == 2){
				thisInstance.back();
				thisInstance.back();
				//jQuery("#filter_1").html('0');
			  }else if(b == 3){
				thisInstance.back();
				
			  }
		});
		
	},
	
	steplink : function(stepid){
		var step = stepid;
		var prevStep = parseInt(step);
		
		this.currentInstance.initialize();
		var container = this.currentInstance.getContainer();
		var workflowRecordElement = jQuery('[name="record"]',container);
		var workFlowId = workflowRecordElement.val();
		container.hide();
		this.initiateStep(prevStep);
		var currentContainer = this.currentInstance.getContainer();
		currentContainer.show();
		jQuery('[name="record"]',currentContainer).val(workFlowId);
		var modulesList = jQuery('#moduleName',currentContainer);
		if(modulesList.length > 0 && workFlowId != '') {
			modulesList.attr('disabled','disabled').trigger('liszt:updated');
		}
	},
	
	loadallstep : function(){
		this.currentInstance.initialize();
		var container = this.currentInstance.getContainer();
		var workflowRecordElement = jQuery('[name="record"]',container);
		var workFlowId = workflowRecordElement.val();
		//container.hide();
		if(workFlowId){
			var i = 0;
			var len = 5;
			for (i = 1; i < len; i++) {
				var prevStep = i;
				this.initiateStep(prevStep);
				var currentContainer = this.currentInstance.getContainer();
				var aDeferred = jQuery.Deferred();
				var step = 'Step'+prevStep;
				var params = {
								module : app.getModuleName(),
								parent : app.getParentModuleName(),
								view : 'Edit',
								mode : step,
								record : workFlowId
							}
							AppConnector.request(params).then(function(data) {
							 jQuery( ".editContainer" ).append(data);
								var formid = "#EmailCampaigns_step"+step
								jQuery(formid).hide();
								
							});
						
			}
		}
	},
	
	
	
	
	
	registerReferenceSelectionEvent1 : function() {
		var thisInstance = this;
		var container = this.getContainer();
		container.on('click','.apply_list', function(e){
		
			var subscribers_list = jQuery("#subscribers_list").val();
			
			if(subscribers_list == ''){
			
					var g = {
								title: app.vtranslate("Warning"),
								type: "error",
								text: app.vtranslate("No Subscriber list found. Please select Subscribers List")
								};
								Vtiger_Helper_Js.showPnotify(g);
								return false;
			}
            thisInstance.back();
			var form = jQuery(e.currentTarget);
			jQuery('[name="subscribers_list1"]').val(subscribers_list);
			
			thisInstance.currentInstance.submit().then(function(data){
							thisInstance.getContainer().append(data);
							var stepVal = thisInstance.getStepValue();
							var nextStepVal = parseInt(stepVal) + 1;
							thisInstance.initiateStep(nextStepVal);
							thisInstance.currentInstance.initialize();
							var container = thisInstance.currentInstance.getContainer();
							thisInstance.registerFormSubmitEvent(container);
							thisInstance.currentInstance.registerEvents();
						});
        });
		
		
		container.on('click','.icon-remove-sign', function(e){
		
		//var subscribers_list = jQuery("#subscribers_list").val();
		jQuery('[name="subscribers_list"]').val('');
		jQuery('[name="subscribers_list_display"]').val('');
		
		
        });
		
		container.on('click','.icon-plus', function(e){
		var url = "index.php?module=SubscribersList&view=Edit";
		window.open(url,'_blank');
		//var subscribers_list = jQuery("#subscribers_list").val();
		//jQuery('[name="subscribers_list"]').val('');
		//jQuery('[name="subscribers_list_display"]').val('');
		
		
        });
		
		
		
	},
	
	
	/*emailfiledsvalidation : function(){
		var thisInstance = this;
		jQuery( "#EmailCampaigns_editView_fieldName_from_email" ).focusout(function() {
			var emailFilter = /^[_/a-zA-Z0-9]+([!"#$%&'()*+,./:;<=>?\^_`{|}~-]?[a-zA-Z0-9/_/-])*@[a-zA-Z0-9]+([\_\-\.]?[a-zA-Z0-9]+)*\.([\-\_]?[a-zA-Z0-9])+(\.?[a-zA-Z0-9]+)?$/;
			var fieldValue = jQuery('#EmailCampaigns_editView_fieldName_from_email').val();
			if (!emailFilter.test(fieldValue)) {
				var errorInfo = app.vtranslate('JS_PLEASE_ENTER_VALID_EMAIL_ADDRESS');
				jQuery('#EmailCampaigns_editView_fieldName_from_email').val("");
				jQuery('#emailverfy').hide();
				return false;
			}else{
				var aDeferred = jQuery.Deferred();
				var params = {
						'module': app.getModuleName(),
						'action' : "SaveAjax",
						'mode' : 'checkDuplicateemail',
						'email' : fieldValue
					}
					var progressIndicatorElement = jQuery.progressIndicator({
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
					});
					AppConnector.request(params).then(
						function(data) {
								progressIndicatorElement.progressIndicator({
									'mode' : 'hide'
								})
							if(data.result['success'] == true && data.result['verify'] =='0'){
								aDeferred.resolve(data);
								jQuery('#emailverfy').hide();
								jQuery('#emailverfyed').show();
								jQuery('#step1next').show();
								jQuery('#resendlink').hide();
								
							}else if(data.result['success'] == true && data.result['verify'] =='1'){
								jQuery('#emailverfy').hide();
								jQuery('#emailverfyed').hide();
								jQuery('#step1next').hide();
								jQuery('#resendlink').show();
							}else{
								jQuery('#emailverfy').show();
								jQuery('#emailverfyed').hide();
								jQuery('#step1next').hide();
								jQuery('#resendlink').hide();
								
							}
						}
					);
			}
		})
	},

	emailverfy: function(){
		var fieldValue = jQuery('#EmailCampaigns_editView_fieldName_from_email').val();
		//window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
		var url = "index.php?module=EmailCampaigns&view=Emailverify&email="+fieldValue;
		var aDeferred = jQuery.Deferred();
				var params = {
						'module': app.getModuleName(),
						'action' : "SaveAjax",
						'mode' : 'checkverfyemail',
						'email' : fieldValue
					}
					var progressIndicatorElement = jQuery.progressIndicator({
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
					});
					AppConnector.request(params).then(
						function(data) {
							progressIndicatorElement.progressIndicator({
									'mode' : 'hide'
								})
							if(data.result['emailstatus']=='1'){
								jQuery('#emailverfy').hide();
								jQuery('#emailverfyed').hide();
								jQuery('#resendlink').show();
								return false;
							}else{
							
							}
						}
					);
	},
	
	registerverfyClickEvent : function(){
		var thisInstance = this;
		var container = this.getContainer();
		container.on('click','.Verify',function(e){
			thisInstance.emailverfy();
		});
	},
	*/
	registerEvents : function(){
		var form = this.currentInstance.getContainer();
		this.registerFormSubmitEvent(form);
		this.registerBackStepClickEvent();
		//this.registerverfyClickEvent();
		
		
		//this.emailfiledsvalidation();
		//this.loadallstep();
		this.registerReferenceSelectionEvent1();
		
	}
});



