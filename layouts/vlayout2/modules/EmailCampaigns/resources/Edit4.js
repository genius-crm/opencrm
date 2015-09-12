/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
EmailCampaigns_Edit_Js("EmailCampaigns_Edit4_Js",{},{
	
	step4Container : false,
	ckEditorInstance : false,
	
	init : function() {
		this.initialize();
	},
	/**
	 * Function to get the container which holds all the reports step1 elements
	 * @return jQuery object
	 */
	getContainer : function() {
		return this.step4Container;
	},

	/**
	 * Function to set the reports step1 container
	 * @params : element - which represents the reports step1 container
	 * @return : current instance
	 */
	setContainer : function(element) {
		this.step4Container = element;
		return this;
	},
	
	/**
	 * Function  to intialize the reports step1
	 */
	initialize : function(container) {
		if(typeof container == 'undefined') {
			container = jQuery('#EmailCampaigns_step4');
		}
		if(container.is('#EmailCampaigns_step4')) {
			this.setContainer(container);
		}else{
			this.setContainer(jQuery('#EmailCampaigns_step4'));
		}
	},
	
	
	registerverfyClickbEvent : function(){
		var thisInstance = this;
		jQuery('.TestEmail').on('click',function(e){
		
			var aDeferred = jQuery.Deferred();
			var recordId = jQuery('.recordval').val();
				var params = {
						'module': app.getModuleName(),
						'action' : "SaveAjax",
						'mode' : 'sendetest',
						'record':recordId
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
								
					}
				);
			
		})
		jQuery('.ScheduleLater').on('click',function(e){
			var aDeferred = jQuery.Deferred();
			var recordId = jQuery('.recordval').val();
			var params = {
					'module': app.getModuleName(),
					'view' : "Edit",
					'mode' : 'schedulelater',
					'record':recordId
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
					app.showModalWindow(data, function(data){
					app.registerEventForTimeFields();
						jQuery('.save').on('click',function(e){
						
						var startdate = jQuery('#startdate').val();
						var time = jQuery('#time').val();
						
						if( startdate == '' || time == ''){
							 var g = {
								title: app.vtranslate("Warning"),
								type: "error",
								text: app.vtranslate("JS_CAMPAIGN_NAME_ALREADY_EXISTS.CHOOSE_A_DIFFERENT_NAME")
							};
							Vtiger_Helper_Js.showPnotify(g);
							return false;
						}

						var recordId = jQuery('.recordval').val();
							var params = {
								'module': app.getModuleName(),
								'action' : "SaveAjax",
								'mode' : 'schedulelatersave',
								'startdate' : startdate,
								'time' : time,
								'record':recordId
							}
							var progressIndicatorElement = jQuery.progressIndicator({
								'position' : 'html',
								'blockInfo' : {
									'enabled' : true
								}
							});
							
							AppConnector.request(params).then(
								function(data) {
										 window.location = "index.php?module=EmailCampaigns&view=Detail&record="+recordId;
										 progressIndicatorElement.progressIndicator({
											 'mode' : 'hide'
										 })
									
								}
							);
							
						})
							
						});
					
								
				}
			);
			
		})
		
		jQuery('.SendNow').on('click',function(e){
				var aDeferred = jQuery.Deferred();
				var recordId = jQuery('#saverecord').val();
				var params = {
						'module': app.getModuleName(),
						'action' : "SaveAjax",
						'mode' : 'sendenow',
						'record':recordId
						
					}
					
					var progressIndicatorElement = jQuery.progressIndicator({
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
					});
					
						AppConnector.request(params).then(
							function(data) {
									 window.location = "index.php?module=EmailCampaigns&view=Detail&record="+recordId;
									 progressIndicatorElement.progressIndicator({
										 'mode' : 'hide'
										
									 })
								
							}
						);
						//window.location = "index.php?module=EmailCampaigns&view=Detail&record="+recordId;
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
		jQuery('#EmailCampaigns_step4').validationEngine(opts);

		var container = this.getContainer();
		app.changeSelectElementView(container);
		this.registerverfyClickbEvent();
		
		
		
	}
});
