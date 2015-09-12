/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
EmailCampaigns_Edit_Js("EmailCampaigns_Edit3_Js",{},{
	
	step3Container : false,
	ckEditorInstance : false,
	init : function() {
		this.initialize();
	},
	/**
	 * Function to get the container which holds all the reports step1 elements
	 * @return jQuery object
	 */
	getContainer : function() {
		return this.step3Container;
	},

	/**
	 * Function to set the reports step1 container
	 * @params : element - which represents the reports step1 container
	 * @return : current instance
	 */
	setContainer : function(element) {
		this.step3Container = element;
		return this;
	},
	
	/**
	 * Function  to intialize the reports step1
	 */
	initialize : function(container) {
		if(typeof container == 'undefined') {
			container = jQuery('#EmailCampaigns_step3');
		}
		if(container.is('#EmailCampaigns_step3')) {
			this.setContainer(container);
		}else{
			this.setContainer(jQuery('#EmailCampaigns_step3'));
		}
	},
	
	registerEventForCkEditor : function(){
		var form = this.getContainer();
		var noteContentElement = form.find('[name="description"]');
		if(noteContentElement.length > 0){
			var ckEditorInstance = new Vtiger_CkEditor_Js();
			ckEditorInstance.loadCkEditor(noteContentElement);
		}
		this.registerFillTemplateContentEvent();
	},
	
	registerFillTemplateContentEvent : function() {
		var container = this.getContainer();
		container.on('change','select[name="templateFields"]',function(e){
			var oEditor = CKEDITOR.instances.EmailCampaigns_editView_fieldName_description;
			var value = jQuery(e.currentTarget).val();
			oEditor.insertHtml(value);
		});
	},
	
	getckEditorInstance : function(){
		if(this.ckEditorInstance == false){
			this.ckEditorInstance = new Vtiger_CkEditor_Js();
			var form = this.getContainer();
				var noteContentElement = form.find('[name="description"]');
			this.ckEditorInstance.element =  noteContentElement;
		}
		return this.ckEditorInstance;
	},
	registerSendEmailTemplateEvent : function(){

		var thisInstance = this;
		jQuery('#selectEmailTemplate').on('click',function(e){
			var url = jQuery(e.currentTarget).data('url');
			var popupInstance = Vtiger_Popup_Js.getInstance();
			popupInstance.show(url,function(data){
				var responseData = JSON.parse(data);
				for(var id in responseData){
					var selectedName = responseData[id].name;
					var selectedTemplateBody = responseData[id].info;
				}
				
				var ckEditorInstance = thisInstance.getckEditorInstance();
				ckEditorInstance.loadContentsInCkeditor(selectedTemplateBody);
				jQuery('#EmailCampaigns_editView_fieldName_subject').val(selectedName);
			},'tempalteWindow');
		});
	},
	submit : function(){
		
		var conditions = jQuery("#EmailCampaigns_step2 input[name=conditions]").val();
		jQuery("#conditions1").val(conditions);
		
		var objEditor = CKEDITOR.instances["EmailCampaigns_editView_fieldName_description"];
		var q = objEditor.getData();
		jQuery('[name="description"]').val(q);
		
		var aDeferred = jQuery.Deferred();
		var form = this.getContainer();
		
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
				if(data.result) {
					var params = {
						module : app.getModuleName(),
						parent : app.getParentModuleName(),
						view : 'Edit',
						mode : 'Step4',
						record : data.result.id
					}
					AppConnector.request(params).then(function(data) {
						aDeferred.resolve(data);
					}); 
				}
				progressIndicatorElement.progressIndicator({
					'mode' : 'hide'
				})
			},
			function(error,err){

			}
		);
		return aDeferred.promise();
	},
	
	registerChangeEventForModule : function(){
		var thisInstance = this;
		var container = this.getContainer();
		container.on('change','select[name="modulename"]',function(e){
			thisInstance.loadFields();
		});
	},
	
	loadFields : function() {
		var moduleName = jQuery('select[name="modulename"]').val();
		var allFields = jQuery('[name="moduleFields"]').data('value');
		var fieldSelectElement = jQuery('select[name="templateFields"]');
		var options = '';
		for(var key in allFields) {
			//IE Browser consider the prototype properties also, it should consider has own properties only.
			if(allFields.hasOwnProperty(key) && key == moduleName) {
				var moduleSpecificFields = allFields[key];
				var len = moduleSpecificFields.length;
				for (var i = 0; i < len; i++) {
					var fieldName = moduleSpecificFields[i][0].split(':');
					options += '<option value="'+moduleSpecificFields[i][1]+'"';
					if(fieldName[0] == moduleName) {
						options += '>'+fieldName[1]+'</option>';
					} else {
						options += '>'+moduleSpecificFields[i][0]+'</option>';
					}
				}
			}
		}
		
		if(options == '')
			options = '<option value="">NONE</option>';
		
		fieldSelectElement.empty().html(options).trigger("liszt:updated");
		return fieldSelectElement;
	},
	
	
	registerEvents : function(){
		var opts = app.validationEngineOptions;
		// to prevent the page reload after the validation has completed
		opts['onValidationComplete'] = function(form,valid) {
            //returns the valid status
            return valid;
        };
		opts['promptPosition'] = "bottomRight";
		jQuery('#EmailCampaigns_step3').validationEngine(opts);
		var container = this.getContainer();
		app.changeSelectElementView(container);
		this.registerSendEmailTemplateEvent();
		this.registerEventForCkEditor();
		this.registerChangeEventForModule();
	}
});