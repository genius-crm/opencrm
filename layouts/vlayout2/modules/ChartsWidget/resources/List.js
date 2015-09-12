/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
Vtiger_List_Js("ChartsWidget_List_Js",{

	listInstance : false,

	massActive : function(url) {
		var listInstance = ChartsWidget_List_Js.listInstance;
		var validationResult = listInstance.checkListRecordSelected();
		if(validationResult != true){
			// Compute selected ids, excluded ids values, along with cvid value and pass as url parameters
			var selectedIds = listInstance.readSelectedIds(true);
			var excludedIds = listInstance.readExcludedIds(true);
			var cvId = listInstance.getCurrentCvId();

			var message = app.vtranslate('JS_ACTIVE_CONFIRMATION');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {
					var activeURL = url+'&viewname='+cvId+'&selected_ids='+selectedIds+'&excluded_ids='+excludedIds;
					var activeMessage = app.vtranslate('JS_RECORDS_ARE_GETTING_ACTIVATED');
					var progressIndicatorElement = jQuery.progressIndicator({
						'message' : activeMessage,
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
					});
					AppConnector.request(activeURL).then(
						function(data) {
							progressIndicatorElement.progressIndicator({
								'mode' : 'hide'
							})
							if(data){
								listInstance.massActionPostOperations(data,'Active');
							}
						});
				},
				function(error, err){
				}
			);
		} else {
			listInstance.noRecordSelectedAlert();
		}

	},

	massInactive : function(url) {
		var listInstance = ChartsWidget_List_Js.listInstance;
		var validationResult = listInstance.checkListRecordSelected();
		if(validationResult != true){
			// Compute selected ids, excluded ids values, along with cvid value and pass as url parameters
			var selectedIds = listInstance.readSelectedIds(true);
			var excludedIds = listInstance.readExcludedIds(true);
			var cvId = listInstance.getCurrentCvId();

			var message = app.vtranslate('JS_INACTIVE_CONFIRMATION');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {
					var inactiveURL = url+'&viewname='+cvId+'&selected_ids='+selectedIds+'&excluded_ids='+excludedIds;
					var inactiveMessage = app.vtranslate('JS_RECORDS_ARE_GETTING_INACTIVATED');
					var progressIndicatorElement = jQuery.progressIndicator({
						'message' : inactiveMessage,
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
					});
					AppConnector.request(inactiveURL).then(
						function(data) {
							progressIndicatorElement.progressIndicator({
								'mode' : 'hide'
							})
							if(data){
								listInstance.massActionPostOperations(data,'Inactive');
							}
						});
				},
				function(error, err){
				}
			);
		} else {
			listInstance.noRecordSelectedAlert();
		}

	}

},{

	init : function(){
		ChartsWidget_List_Js.listInstance = this;
	},

	/*
	 * Function to perform the operations after the mass action
	 */
	massActionPostOperations : function(data, action){
		var thisInstance = this;
		var cvId = this.getCurrentCvId();
		if(data.success){
			var module = app.getModuleName();
			AppConnector.request('index.php?module='+module+'&view=List&viewname='+cvId).then(
				function(data) {
					jQuery(".listViewEntriesCheckBox input:checkbox:checked").each(function(){
					  $(this).parents('tr').eq(3).html(action);
					});
					jQuery('#recordsCount').val('');
					jQuery('#totalPageCount').text('');
					app.hideModalWindow();
					var listViewContainer = thisInstance.getListViewContentContainer();
					listViewContainer.html(data);
					jQuery('#deSelectAllMsg').trigger('click');
					thisInstance.calculatePages().then(function(){
						thisInstance.updatePagination();
					});
				});
		} else {
			app.hideModalWindow();
			var params = {
				title : app.vtranslate('JS_LBL_PERMISSION'),
				text : data.error.message+ ' : ' + data.error.code
			}
			Vtiger_Helper_Js.showPnotify(params);
		}
	},

	registerEvents : function(){
		this._super();
	}
});
