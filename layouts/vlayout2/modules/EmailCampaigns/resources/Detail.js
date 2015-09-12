/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Detail_Js("EmailCampaigns_Detail_Js",{},{
	listInstance : false,
	massEditPreSave : 'Vtiger.MassEdit.PreSave', 
	
	registerverfyClickEvent : function(){
		var thisInstance = this;
		jQuery('.TestEmail').live('click',function(e){
			var aDeferred = jQuery.Deferred();
			var recordId = jQuery('#recordId').val();
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
		jQuery('.ScheduleLater').live('click',function(e){
			var aDeferred = jQuery.Deferred();
			var recordId = jQuery('#recordId').val();
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
					
						jQuery('.save').live('click',function(e){
						
						var startdate = jQuery('#startdate').val();
						var time = jQuery('#time').val();
						
						var recordId = jQuery('#recordId').val();
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
		
		jQuery('.SendNow').live('click',function(e){
				var aDeferred = jQuery.Deferred();
				var recordId = jQuery('#recordId').val();
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
									 progressIndicatorElement.progressIndicator({
										 'mode' : 'hide'
									 })
								
							}
						);
		})
	},
	
	getUniqueOpenList : function(){
		jQuery('#EmailCampaigns_detailView_fieldValue_unique_open').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var unique_open = jQuery(this).children(".value").html();

			if(unique_open > 0) {
				var params = {
						'module': app.getModuleName(),
						'view' : "ListRecordAjax",
						'mode' : 'getListofUniqueOpen',
						'record':recordId,
						'page_no':'1'
				}
				var progressIndicatorElement = jQuery.progressIndicator({
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
				});
						
				AppConnector.request(params).then(
					function(data) {
						//jQuery('#load_result').innerHTML = data;
						document.getElementById("load_result").innerHTML = data;
						progressIndicatorElement.progressIndicator({
							'mode' : 'hide'
						})	
					}
				);
			}
		})
		jQuery('#ReportlistViewNextPageButton').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var page_no = jQuery('#pageCount').val();
			var nxt_page = parseInt(page_no)+1;
			var params = {
					'module': app.getModuleName(),
					'view' : "ListRecordAjax",
					'mode' : 'getListofUniqueOpen',
					'record':recordId,
					'page_no':nxt_page
			}
			var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
			});
					
			AppConnector.request(params).then(
				function(data) {
					//jQuery('#load_result').innerHTML = data;
					document.getElementById("load_result").innerHTML = data;
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})	
				}
			);
		})
		jQuery('#ReportlistViewPreviousPageButton').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var page_no = jQuery('#pageCount').val();
			var pre_page = parseInt(page_no)-1;
			var params = {
					'module': app.getModuleName(),
					'view' : "ListRecordAjax",
					'mode' : 'getListofUniqueOpen',
					'record':recordId,
					'page_no':pre_page
			}
			var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
			});
					
			AppConnector.request(params).then(
				function(data) {
					//jQuery('#load_result').innerHTML = data;
					document.getElementById("load_result").innerHTML = data;
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})	
				}
			);
		})
	},
	
	getSubscriberList : function(){
		jQuery('#EmailCampaigns_detailView_fieldValue_subscribers').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var subscribers = jQuery(this).children(".value").html();

			if(subscribers > 0) {
				var params = {
						'module': app.getModuleName(),
						'view' : "ListRecordAjax",
						'mode' : 'getSubscriberList',
						'record':recordId,
						'optout':0,
						'page_no':'1'
				}
				var progressIndicatorElement = jQuery.progressIndicator({
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
				});
						
				AppConnector.request(params).then(
					function(data) {
						//jQuery('#load_result').innerHTML = data;
						document.getElementById("load_result").innerHTML = data;
						progressIndicatorElement.progressIndicator({
							'mode' : 'hide'
						})	
					}
				);
			}
		})
		jQuery('#EmailCampaigns_detailView_fieldValue_unsubscribers').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var unsubscribers = jQuery(this).children(".value").html();

			if(unsubscribers > 0) {
				var params = {
						'module': app.getModuleName(),
						'view' : "ListRecordAjax",
						'mode' : 'getSubscriberList',
						'record':recordId,
						'optout':1,
						'page_no':'1'
				}
				var progressIndicatorElement = jQuery.progressIndicator({
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
				});
						
				AppConnector.request(params).then(
					function(data) {
						//jQuery('#load_result').innerHTML = data;
						document.getElementById("load_result").innerHTML = data;
						progressIndicatorElement.progressIndicator({
							'mode' : 'hide'
						})	
					}
				);
			}
		})
		jQuery('#SubscriberlistViewNextPageButton').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var page_no = jQuery('#pageCount').val();
			var optout = jQuery('#listtype').val();
			var nxt_page = parseInt(page_no)+1;
			
			var params = {
					'module': app.getModuleName(),
					'view' : "ListRecordAjax",
					'mode' : 'getSubscriberList',
					'record':recordId,
					'optout':optout,
					'page_no':nxt_page
			}
			var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
			});
					
			AppConnector.request(params).then(
				function(data) {
					//jQuery('#load_result').innerHTML = data;
					document.getElementById("load_result").innerHTML = data;
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})	
				}
			);
		})
		jQuery('#SubscriberlistViewPreviousPageButton').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var page_no = jQuery('#pageCount').val();
			var optout = jQuery('#listtype').val();
			var pre_page = parseInt(page_no)-1;
			
			var params = {
					'module': app.getModuleName(),
					'view' : "ListRecordAjax",
					'mode' : 'getSubscriberList',
					'record':recordId,
					'optout':optout,
					'page_no':pre_page
			}
			var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
			});
					
			AppConnector.request(params).then(
				function(data) {
					//jQuery('#load_result').innerHTML = data;
					document.getElementById("load_result").innerHTML = data;
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})	
				}
			);
		})
		jQuery('#EmailCampaigns_listView_row_totalCount').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var UniqueOpenCount = jQuery(this).children(".listViewEntryValue").children(".value").html();

			if(UniqueOpenCount > 0) {
				var params = {
						'module': app.getModuleName(),
						'view' : "ListRecordAjax",
						'mode' : 'getUniqueLinkClick',
						'record':recordId,
						'page_no':'1'
				}
				var progressIndicatorElement = jQuery.progressIndicator({
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
				});
						
				AppConnector.request(params).then(
					function(data) {
						//jQuery('#load_result').innerHTML = data;
						document.getElementById("load_result").innerHTML = data;
						progressIndicatorElement.progressIndicator({
							'mode' : 'hide'
						})	
					}
				);
			}
		})
		jQuery('#UniqueOpenLinkListViewNextPageButton').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var page_no = jQuery('#pageCount').val();
			var nxt_page = parseInt(page_no)+1;

			var params = {
					'module': app.getModuleName(),
					'view' : "ListRecordAjax",
					'mode' : 'getUniqueLinkClick',
					'record':recordId,
					'page_no':nxt_page
			}
			var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
			});
					
			AppConnector.request(params).then(
				function(data) {
					//jQuery('#load_result').innerHTML = data;
					document.getElementById("load_result").innerHTML = data;
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})	
				}
			);
		})
		jQuery('#UniqueOpenLinkListViewPreviousPageButton').live('click',function(e){
			var recordId = jQuery('#recordId').val();
			var UniqueOpenCount = jQuery(this).children(".listViewEntryValue").children(".value").html();
			var page_no = jQuery('#pageCount').val();
			var pre_page = parseInt(page_no)-1;

			var params = {
					'module': app.getModuleName(),
					'view' : "ListRecordAjax",
					'mode' : 'getUniqueLinkClick',
					'record':recordId,
					'page_no':pre_page
			}
			var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
			});
					
			AppConnector.request(params).then(
				function(data) {
					//jQuery('#load_result').innerHTML = data;
					document.getElementById("load_result").innerHTML = data;
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})	
				}
			);
		})
	},
	/*
	 * Function to register the click event for list view main check box.
	 */
	
	registerMainCheckBoxClickEvent : function(){
		
		var listViewPageDiv = this.getListViewContainer();

		var thisInstance = this;
		jQuery(document).on('click','#listViewEntriesMainCheckBox',function(){
			var selectedIds = thisInstance.readSelectedIds();
			var excludedIds = thisInstance.readExcludedIds();
			if(jQuery('#listViewEntriesMainCheckBox').is(":checked")){
				var recordCountObj = thisInstance.getRecordsCount();
				
				recordCountObj.then(function(data){
					jQuery('#totalRecordsCount').text(data);
					if(jQuery("#deSelectAllMsgDiv").css('display') == 'none'){
						jQuery("#selectAllMsgDiv").show();
					}
				});

				jQuery('.listViewEntriesCheckBox').each( function(index,element) {
					jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
					if(selectedIds == 'all'){
						if((jQuery.inArray(jQuery(element).val(), excludedIds))!= -1){
							excludedIds.splice(jQuery.inArray(jQuery(element).val(),excludedIds),1);
						}
					} else if((jQuery.inArray(jQuery(element).val(), selectedIds)) == -1){
						selectedIds.push(jQuery(element).val());
					}
				});
			}else{
				jQuery("#selectAllMsgDiv").hide();
				jQuery('.listViewEntriesCheckBox').each( function(index,element) {
					jQuery(this).attr('checked', false).closest('tr').removeClass('highlightBackgroundColor');
					if(selectedIds == 'all'){
						excludedIds.push(jQuery(element).val());
						selectedIds = 'all';
					} else {
						selectedIds.splice( jQuery.inArray(jQuery(element).val(), selectedIds), 1 );
					}
				});
			}
			thisInstance.writeSelectedIds(selectedIds);
			thisInstance.writeExcludedIds(excludedIds);

		});
	},
	/*
	 * Function  to register click event for list view check box.
	 */
	registerCheckBoxClickEvent : function(){

		var listViewPageDiv = this.getListViewContainer();
		var thisInstance = this;
		jQuery(document).on('click','.listViewEntriesCheckBox',function(event){
		//listViewPageDiv.live('click','.listViewEntriesCheckBox',function(e){
			var selectedIds = thisInstance.readSelectedIds();
			var excludedIds = thisInstance.readExcludedIds();
			var elem = jQuery(event.currentTarget);
			if(elem.is(':checked')){
				elem.closest('tr').addClass('highlightBackgroundColor');
				if(selectedIds== 'all'){
					excludedIds.splice( jQuery.inArray(elem.val(), excludedIds), 1 );
				} else if((jQuery.inArray(elem.val(), selectedIds)) == -1) {
					selectedIds.push(elem.val());
				}
			} else {
				elem.closest('tr').removeClass('highlightBackgroundColor');
				if(selectedIds == 'all') {
					excludedIds.push(elem.val());
					selectedIds = 'all';
				} else {
					selectedIds.splice( jQuery.inArray(elem.val(), selectedIds), 1 );
				}
			}
			thisInstance.checkSelectAll();
			thisInstance.writeSelectedIds(selectedIds);
			thisInstance.writeExcludedIds(excludedIds);
		});
	},
	checkSelectAll : function(){
		var state = true;
		jQuery('.listViewEntriesCheckBox').each(function(index,element){
			if(jQuery(element).is(':checked')){
				state = true;
			}else{
				state = false;
				return false;
			}
		});
		if(state == true){
			jQuery('#listViewEntriesMainCheckBox').attr('checked',true);
		} else {
			jQuery('#listViewEntriesMainCheckBox').attr('checked', false);
		}
	},

	/*
	 * Function to register the click event for select all.
	 */
	registerSelectAllClickEvent :  function(){
		var listViewPageDiv = jQuery('#load_result');
		var thisInstance = this;
		listViewPageDiv.delegate('#selectAllMsg','click',function(){
			jQuery('#selectAllMsgDiv').hide();
			jQuery("#deSelectAllMsgDiv").show();
			jQuery('#listViewEntriesMainCheckBox').attr('checked',true);
			jQuery('.listViewEntriesCheckBox').each( function(index,element) {
				jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
			});
			thisInstance.writeSelectedIds('all');
		});
	},

	/*
	* Function to register the click event for deselect All.
	*/
	registerDeselectAllClickEvent : function(){
		var listViewPageDiv = jQuery('#load_result');
		var thisInstance = this;
		listViewPageDiv.delegate('#deSelectAllMsg','click',function(){
			jQuery('#deSelectAllMsgDiv').hide();
			jQuery('#listViewEntriesMainCheckBox').attr('checked',false);
			jQuery('.listViewEntriesCheckBox').each( function(index,element) {
				jQuery(this).attr('checked', false).closest('tr').removeClass('highlightBackgroundColor');
			});
			var excludedIds = new Array();
			var selectedIds = new Array();
			thisInstance.writeSelectedIds(selectedIds);
			thisInstance.writeExcludedIds(excludedIds);
		});
	},
	getListViewContainer : function() {
		this.listViewContainer = false;
		if(this.listViewContainer == false){
			this.listViewContainer = jQuery('div.resulttble');
		}
		return this.listViewContainer;
	},
	readSelectedIds : function(decode){
		var thisInstance  = this;
		var cvId = 1;
		var selectedIdsElement = jQuery('#selectedIds');
		var selectedIdsDataAttr = cvId+'Selectedids';
		var selectedIdsElementDataAttributes = selectedIdsElement.data();
		
		if (!(selectedIdsDataAttr in selectedIdsElementDataAttributes) ) {
			
			var selectedIds = new Array();
			thisInstance.writeSelectedIds(selectedIds);
		} else {
			selectedIds = selectedIdsElementDataAttributes[selectedIdsDataAttr];
		}
		if(decode == true){
			if(typeof selectedIds == 'object'){
				return JSON.stringify(selectedIds);
			}
		}
		return selectedIds;
	},
	readExcludedIds : function(decode){
		var thisInstance  = this;
		var cvId = 1;
		var exlcudedIdsElement = jQuery('#excludedIds');
		var excludedIdsDataAttr = cvId+'Excludedids';
		var excludedIdsElementDataAttributes = exlcudedIdsElement.data();
		if(!(excludedIdsDataAttr in excludedIdsElementDataAttributes)){
			var excludedIds = new Array();
			thisInstance.writeExcludedIds(excludedIds);
		}else{
			excludedIds = excludedIdsElementDataAttributes[excludedIdsDataAttr];
		}
		if(decode == true){
			if(typeof excludedIds == 'object') {
				return JSON.stringify(excludedIds);
			}
		}
		return excludedIds;
	},
	getRecordsCount : function(){
		var aDeferred = jQuery.Deferred();
		var recordCountVal = jQuery("#recordsCount").val();
		var recordType = jQuery('#recordType').val();
		if(recordCountVal != ''){
			aDeferred.resolve(recordCountVal);
		} else {
			var count = '';
			var cvId = this.getCurrentCvId();
			var module = app.getModuleName();
			//var module = jQuery("#relatedModule").val();
			var record = jQuery("#recordId").val();

			var postData = {
				"module": module,
				"view": "ListRecordAjax",
				"viewname": cvId,
				"mode": "getRecordsCountCampaign",
				"record" : record,
				'recordType' : recordType
			}

            /*var searchValue = this.getAlphabetSearchValue();
            if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
                postData['search_key'] = this.getAlphabetSearchField();
                postData['search_value'] = this.getAlphabetSearchValue();
                postData['operator'] = "s";
            }*/

            //postData.search_params = JSON.stringify(this.getListSearchParams());
			AppConnector.request(postData).then(
				function(data) {
					var response = JSON.parse(data);
					jQuery("#recordsCount").val(response['result']['count']);
					count =  response['result']['count'];
					aDeferred.resolve(count);
				},
				function(error,err){

				}
			);
		}

		return aDeferred.promise();
	},
	getAlphabetSearchField : function() {
		//return jQuery("#alphabetSearchKey").val();
		return '';
	},
	getAlphabetSearchValue : function() {
		//return jQuery("#alphabetValue").val();
		return '';
	},
	getListSearchParams : function(){
        var listViewPageDiv = this.getListViewContainer();
        var listViewTable = listViewPageDiv.find('.listViewEntriesTable');
        var searchParams = new Array();
        listViewTable.find('.listSearchContributor').each(function(index,domElement){
            var searchInfo = new Array();
            var searchContributorElement = jQuery(domElement);
            var fieldInfo = searchContributorElement.data('fieldinfo');
            var fieldName = searchContributorElement.attr('name');

            var searchValue = searchContributorElement.val();

            if(typeof searchValue == "object") {
                if(searchValue == null) {
                   searchValue = "";
                }else{
                    searchValue = searchValue.join(',');
                }
            }
            searchValue = searchValue.trim();
            if(searchValue.length <=0 ) {
                //continue
                return true;
            }
            var searchOperator = 'c';
            if(fieldInfo.type == "date" || fieldInfo.type == "datetime") {
                searchOperator = 'bw';
            }else if (fieldInfo.type == 'percentage' || fieldInfo.type == "double" || fieldInfo.type == "integer"
                || fieldInfo.type == 'currency' || fieldInfo.type == "number" || fieldInfo.type == "boolean" ||
                fieldInfo.type == "picklist") {
                searchOperator = 'e';
            }
            searchInfo.push(fieldName);
            searchInfo.push(searchOperator);
            searchInfo.push(searchValue);
            searchParams.push(searchInfo);
        });
        return new Array(searchParams);
    },

	writeExcludedIds : function(excludedIds){
		var cvId = this.getCurrentCvId();
		jQuery('#excludedIds').data(cvId+'Excludedids',excludedIds);
	},
	writeSelectedIds : function(selectedIds){
		var cvId = 1;
		jQuery('#selectedIds').data(cvId+'Selectedids',selectedIds);
	},
	getCurrentCvId : function(){
		//return jQuery('#customFilter').find('option:selected').data('id');
		return 1;
	},
	/*
	 * Function to check whether atleast one record is checked
	 */
	checkListRecordSelected : function(){
		var selectedIds = this.readSelectedIds();
		if(typeof selectedIds == 'object' && selectedIds.length <= 0) {
			return true;
		}
		return false;
	},
	actionCall : function(){
		var listViewPageDiv = jQuery('#resulttble')
		var thisInstance = this;
		jQuery(document).on('click','#massActionEdit',function(){
			var relatedModule =  jQuery('#relatedModule').val();
		//listViewPageDiv.live('click','#massActionEdit',function(){
			//thisInstance.triggerMassAction(massActionUrl,callBackFunction,beforeShowCb, css);
			var massEditUrl = 'index.php?module='+relatedModule+'&view=MassActionAjax&mode=showMassEditForm';
			thisInstance.triggerMassAction(massEditUrl, function(container){
			var massEditForm = container.find('#massEdit');
			massEditForm.validationEngine(app.validationEngineOptions);
			var listInstance = thisInstance.getInstance();
			thisInstance.inactiveFieldValidation(massEditForm);
			thisInstance.registerReferenceFieldsForValidation(massEditForm);
			thisInstance.registerFieldsForValidation(massEditForm);
			thisInstance.registerEventForTabClick(massEditForm);
			thisInstance.registerRecordAccessCheckEvent(massEditForm);
			//var editInstance = Vtiger_Edit_Js.getInstance();
			//editInstance.registerBasicEvents(massEditForm);
			//To remove the change happended for select elements due to picklist dependency
			container.find('select').trigger('change',{'forceDeSelect':true});
			thisInstance.postMassEdit(container);

			thisInstance.registerSlimScrollMassEdit();
		},{'width':'65%'});

		});
	},
	/**
	 * Function to inactive field for validation in a form
	 * this will remove data-validation-engine attr of all the elements
	 * @param Accepts form as a parameter
	 */
	inactiveFieldValidation : function(form){
        var massEditFieldList = jQuery('#massEditFieldsNameList').data('value');
		for(var fieldName in massEditFieldList){
            var fieldInfo = massEditFieldList[fieldName];

            var fieldElement = form.find('[name="'+fieldInfo.name+'"]');
            if(fieldInfo.type == "reference") {
                //get the element which will be shown which has "_display" appended to actual field name
                fieldElement = form.find('[name="'+fieldInfo.name+'_display"]');
            }else if(fieldInfo.type == "multipicklist") {
                fieldElement = form.find('[name="'+fieldInfo.name+'[]"]');
            }

            //Not all the fields will be enabled for mass edit
            if(fieldElement.length == 0 ) {
                continue;
            }

			var elemData = fieldElement.data();

            //Blank validation by default
            var validationVal = "validate[]"
            if('validationEngine' in elemData) {
                validationVal =  elemData.validationEngine;
                delete elemData.validationEngine;
            }
            fieldElement.data('invalidValidationEngine',validationVal);
			fieldElement.removeAttr('data-validation-engine');
		}
	},
	registerReferenceFieldsForValidation : function(form){
		var referenceField = form.find('.sourceField');
		form.find('.sourceField').on(Vtiger_Edit_Js.referenceSelectionEvent,function(e,params){
			var element = jQuery(e.currentTarget);
			var elementName = element.attr('name');
			var fieldDisplayName = elementName+"_display";
			var fieldDisplayElement = form.find('input[name="'+fieldDisplayName+'"]');
			if(params.selectedName == ""){
				return;
			}
			fieldDisplayElement.attr('data-validation-engine', fieldDisplayElement.data('invalidValidationEngine'));
            var parentTd = fieldDisplayElement.closest('td');
            if(!parentTd.hasClass('massEditActiveField')){
                parentTd.addClass('massEditActiveField');
            }
		})
		form.find('.clearReferenceSelection').on(Vtiger_Edit_Js.referenceDeSelectionEvent,function(e){
			var sourceField = form.find('.sourceField');
			var sourceFieldName = sourceField.attr('name');
			var fieldDisplayName = sourceFieldName+"_display";
			var fieldDisplayElement = form.find('input[name="'+fieldDisplayName+'"]').removeAttr('data-validation-engine');
            var parentTd = fieldDisplayElement.closest('td');
            if(parentTd.hasClass('massEditActiveField')){
                parentTd.removeClass('massEditActiveField');
            }
		})
	},
	/**
	 * function to register field for validation
	 * this will add the data-validation-engine attr of all the elements
	 * make the field available for validation
	 * @param Accepts form as a parameter
	 */
	registerFieldsForValidation : function(form){
		form.find('.fieldValue').on('change','input,select,textarea',function(e, params){
			if(typeof params == 'undefined'){
				params = {};
			}

			if(typeof params.forceDeSelect == 'undefined') {
				params.forceDeSelect = false;
			}
			var element = jQuery(e.currentTarget);
			var fieldValue = element.val();
			var parentTd = element.closest('td');
			if(((fieldValue == "" || fieldValue == null) && (typeof(element.attr('data-validation-engine')) != "undefined")) || params.forceDeSelect){
				if(parentTd.hasClass('massEditActiveField')){
					parentTd.removeClass('massEditActiveField');
				}
				element.removeAttr('data-validation-engine');
				element.validationEngine('hide');
				var invalidFields = form.data('jqv').InvalidFields;
				var response = jQuery.inArray(element.get(0),invalidFields);
				if(response != '-1'){
					invalidFields.splice(response,1);
				}
			} else if((fieldValue != "") && (typeof(element.attr('data-validation-engine')) == "undefined")){
				element.attr('data-validation-engine', element.data('invalidValidationEngine'));
				parentTd.addClass('massEditActiveField');
			}
		})
	},
	registerEventForTabClick : function(form){
		var ulContainer = form.find('.massEditTabs');
		ulContainer.on('click','a[data-toggle="tab"]',function(e){
			form.validationEngine('validate');
			var invalidFields = form.data('jqv').InvalidFields;
			if(invalidFields.length > 0){
				e.stopPropagation();
			}
		});
	},
	/*
	 * Function to check the view permission of a record after save
	 */
	registerRecordAccessCheckEvent : function(form) {
		var thisInstance = this;
		form.on(thisInstance.massEditPreSave, function(e) {
			var assignedToSelectElement = form.find('[name="assigned_user_id"][data-validation-engine]');
			if(assignedToSelectElement.length > 0){
				if(assignedToSelectElement.data('recordaccessconfirmation') == true) {
					return;
				}else{
					if(assignedToSelectElement.data('recordaccessconfirmationprogress') != true) {
						var recordAccess = assignedToSelectElement.find('option:selected').data('recordaccess');
						if(recordAccess == false) {
							var message = app.vtranslate('JS_NO_VIEW_PERMISSION_AFTER_SAVE');
							Vtiger_Helper_Js.showConfirmationBox({
								'message' : message
							}).then(
								function(e) {
									assignedToSelectElement.data('recordaccessconfirmation',true);
									assignedToSelectElement.removeData('recordaccessconfirmationprogress');
									form.submit();
								},
								function(error, err){
									assignedToSelectElement.removeData('recordaccessconfirmationprogress');
									e.preventDefault();
								});
							assignedToSelectElement.data('recordaccessconfirmationprogress',true);
						} else {
							return true;
						}
					}
				}
			} else{
				return true;
			}
			e.preventDefault();
		});
	},
	postMassEdit : function(massEditContainer) {
		var thisInstance = this;
		massEditContainer.find('form').on('submit', function(e){
			e.preventDefault();
			var form = jQuery(e.currentTarget);
			var invalidFields = form.data('jqv').InvalidFields;
			if(invalidFields.length == 0){
				form.find('[name="saveButton"]').attr('disabled',"disabled");
			}
			var invalidFields = form.data('jqv').InvalidFields;
			if(invalidFields.length > 0){
				return;
			}
			thisInstance.massActionSave(form, true).then(
				function(data) {
					thisInstance.getListViewRecords();
					thisInstance.clearList();
				},
				function(error,err){
				}
			)
		});
	},
	massActionSave : function(form, isMassEdit){
		if(typeof isMassEdit == 'undefined') {
			isMassEdit = false;
		}

		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var massActionUrl = form.serializeFormData();
		if(isMassEdit) {
			var fieldsChanged = false;
            var massEditFieldList = jQuery('#massEditFieldsNameList').data('value');
			for(var fieldName in massEditFieldList){
                var fieldInfo = massEditFieldList[fieldName];

                var fieldElement = form.find('[name="'+fieldInfo.name+'"]');
                if(fieldInfo.type == "reference") {
                    //get the element which will be shown which has "_display" appended to actual field name
                    fieldElement = form.find('[name="'+fieldInfo.name+'_display"]');
                }else if(fieldInfo.type == "multipicklist") {
                    fieldElement = form.find('[name="'+fieldInfo.name+'[]"]');
                }

                //Not all fields will be enabled for mass edit
                if(fieldElement.length == 0) {
                    continue;
                }

                var validationElement = fieldElement.filter('[data-validation-engine]');
                //check if you have element enabled has changed
                if(validationElement.length == 0){
                    if(fieldInfo.type == "multipicklist") {
                        fieldName = fieldName+"[]";
                    }
                    delete massActionUrl[fieldName];
                    if(fieldsChanged != true){
                        fieldsChanged = false;
                    }
                } else {
                    fieldsChanged = true;
                }
			}
			if(fieldsChanged == false){
				Vtiger_Helper_Js.showPnotify(app.vtranslate('NONE_OF_THE_FIELD_VALUES_ARE_CHANGED_IN_MASS_EDIT'));
				form.find('[name="saveButton"]').removeAttr('disabled');
				aDeferred.reject();
				return aDeferred.promise();
			}
			//on submit form trigger the massEditPreSave event
			var massEditPreSaveEvent = jQuery.Event(thisInstance.massEditPreSave);
			form.trigger(massEditPreSaveEvent);
			if(massEditPreSaveEvent.isDefaultPrevented()) {
				form.find('[name="saveButton"]').removeAttr('disabled');
				aDeferred.reject();
				return aDeferred.promise();
			}
		}
        var progressIndicatorElement = jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                'enabled' : true
            }
        });
		AppConnector.request(massActionUrl).then(
			function(data) {
                progressIndicatorElement.progressIndicator({
                    'mode' : 'hide'
                });
				app.hideModalWindow();
				aDeferred.resolve(data);
			},
			function(error,err){
				app.hideModalWindow();
				aDeferred.reject(error,err);
			}
		);
		return aDeferred.promise();
	},
	/*
	 * Function which will give you all the list view params
	 */
	getListViewRecords : function(urlParams) {
		var aDeferred = jQuery.Deferred();
		if(typeof urlParams == 'undefined') {
			urlParams = {};
		}

		var thisInstance = this;
		var loadingMessage = jQuery('.listViewLoadingMsg').text();
		var progressIndicatorElement = jQuery.progressIndicator({
			'message' : loadingMessage,
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});

		var defaultParams = this.getDefaultParams();
		var urlParams = jQuery.extend(defaultParams, urlParams);
		AppConnector.requestPjax(defaultParams).then(
			function(data){
				progressIndicatorElement.progressIndicator({
					'mode' : 'hide'
				})
                var listViewContentsContainer = jQuery('#listViewContents')
                listViewContentsContainer.html(data);
                app.showSelect2ElementView(listViewContentsContainer.find('select.select2'));
                app.changeSelectElementView(listViewContentsContainer);
                //thisInstance.registerTimeListSearch(listViewContentsContainer);

                //thisInstance.registerDateListSearch(listViewContentsContainer);
				thisInstance.calculatePages().then(function(data){
					//thisInstance.triggerDisplayTypeEvent();
					Vtiger_Helper_Js.showHorizontalTopScrollBar();

					var selectedIds = thisInstance.readSelectedIds();
					if(selectedIds != ''){
						if(selectedIds == 'all'){
							jQuery('.listViewEntriesCheckBox').each( function(index,element) {
								jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
							});
							jQuery('#deSelectAllMsgDiv').show();
							var excludedIds = thisInstance.readExcludedIds();
							if(excludedIds != ''){
								jQuery('#listViewEntriesMainCheckBox').attr('checked',false);
								jQuery('.listViewEntriesCheckBox').each( function(index,element) {
									if(jQuery.inArray(jQuery(element).val(),excludedIds) != -1){
										jQuery(element).attr('checked', false).closest('tr').removeClass('highlightBackgroundColor');
									}
								});
							}
						} else {
							jQuery('.listViewEntriesCheckBox').each( function(index,element) {
								if(jQuery.inArray(jQuery(element).val(),selectedIds) != -1){
									jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
								}
							});
						}
						thisInstance.checkSelectAll();
					}
					aDeferred.resolve(data);

					// Let listeners know about page state change.
					app.notifyPostAjaxReady();
				});
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},
	registerTimeListSearch1 : function(container) {
        app.registerEventForTimeFields(container,false);
	},
	getDefaultParams : function() {
		//var pageNumber = jQuery('#pageNumber').val();
		var pageNumber = 1;
		var module = app.getModuleName();
		var view = app.getViewName();
		var parent = app.getParentModuleName();
		var cvId = this.getCurrentCvId();
		var orderBy = jQuery('#orderBy').val();
		var sortOrder = jQuery("#sortOrder").val();
		var record = jQuery("#recordId").val();
		var relatedModule = jQuery("#relatedModule").val();
		var params = {
			/*'module': module,
			'parent' : parent,
			'page' : pageNumber,
			'view' : "List",
			'viewname' : cvId,
			'orderby' : orderBy,
			'sortorder' : sortOrder*/
			'module': module,
			'relatedModule' : relatedModule,
			'view' : view,
			'mode' : 'showRelatedList',
			'tab_label' : 'Report',
			'record' : record,
		}

        // var searchValue = this.getAlphabetSearchValue();

        // if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
        //     params['search_key'] = this.getAlphabetSearchField();
        //     params['search_value'] = searchValue;
        //     params['operator'] = "s";
        // }
        // params.search_params = JSON.stringify(this.getListSearchParams());
        return params;
	},
	/**
	 * Function to reload list
	 */
	clearList : function() {
		jQuery('#deSelectAllMsg').trigger('click');
		jQuery("#selectAllMsgDiv").hide();
	},
	registerSlimScrollMassEdit : function() {
		app.showScrollBar(jQuery('div[name="massEditContent"]'), {'height':'400px'});
	},
	/**
	 * Function to calculate number of pages
	 */
	calculatePages : function() {
		var aDeferred = jQuery.Deferred();
		var element = jQuery('#totalPageCount');
		var totalPageNumber = element.text();
		if(totalPageNumber == ""){
			var totalRecordCount = jQuery('#totalCount').val();
			if(totalRecordCount != '') {
				var pageLimit = jQuery('#pageLimit').val();
				if(pageLimit == '0') pageLimit = 1;
				pageCount = Math.ceil(totalRecordCount/pageLimit);
				if(pageCount == 0){
					pageCount = 1;
				}
				element.text(pageCount);
				aDeferred.resolve();
				return aDeferred.promise();
			}
			this.getPageCount().then(function(data){
				var pageCount = data['result']['page'];
				if(pageCount == 0){
					pageCount = 1;
				}
				element.text(pageCount);
				aDeferred.resolve();
			});
		} else {
			aDeferred.resolve();
		}
		return aDeferred.promise();
	},

	triggerMassAction : function(massActionUrl,callBackFunction,beforeShowCb, css) {
		//TODO : Make the paramters as an object
		if(typeof beforeShowCb == 'undefined') {
			beforeShowCb = function(){return true;};
		}

		if(typeof beforeShowCb == 'object') {
			css = beforeShowCb;
			beforeShowCb = function(){return true;};
		}

		var listInstance = this;
		var validationResult = this.checkListRecordSelected();
		if(validationResult != true){
			var progressIndicatorElement = jQuery.progressIndicator();
			// Compute selected ids, excluded ids values, along with cvid value and pass as url parameters
			var selectedIds = listInstance.readSelectedIds(true);
			var excludedIds = listInstance.readExcludedIds(true);
			var cvId = listInstance.getCurrentCvId();

			var postData = {
				"viewname" : cvId,
				"selected_ids":selectedIds,
				"excluded_ids" : excludedIds
			};

			var listViewInstance = this;
			var searchValue = listViewInstance.getAlphabetSearchValue();

			if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
				postData['search_key'] = listViewInstance.getAlphabetSearchField();
				postData['search_value'] = searchValue;
				postData['operator'] = "s";
			}

			postData.search_params = JSON.stringify(listInstance.getListSearchParams());

			var actionParams = {
				"type":"POST",
				"url":massActionUrl,
				"dataType":"html",
				"data" : postData
			};

			if(typeof css == 'undefined'){
				css = {};
			}
			var css = jQuery.extend({'text-align' : 'left'},css);

			AppConnector.request(actionParams).then(
				function(data) {
					progressIndicatorElement.progressIndicator({'mode' : 'hide'});
					if(data) {
						var result = beforeShowCb(data);
						if(!result) {
							return;
						}
						app.showModalWindow(data,function(data){
							if(typeof callBackFunction == 'function'){
								callBackFunction(data);
								//listInstance.triggerDisplayTypeEvent();
							}
						},css)
					}
				},
				function(error,err){
					progressIndicatorElement.progressIndicator({'mode' : 'hide'});
				}
			);
		} else {
			this.noRecordSelectedAlert();
		}
	},
	/*
	 * Function to return alerts if no records selected.
	 */
	noRecordSelectedAlert : function(){
		return alert(app.vtranslate('JS_PLEASE_SELECT_ONE_RECORD'));
	},

	getInstance: function(){
		if(this == false){
			var module = app.getModuleName();
			var parentModule = app.getParentModuleName();
			if(parentModule == 'Settings'){
				var moduleClassName = parentModule+"_"+module+"_List_Js";
				if(typeof window[moduleClassName] == 'undefined'){
					moduleClassName = module+"_List_Js";
				}
				var fallbackClassName = parentModule+"_Vtiger_List_Js";
				if(typeof window[fallbackClassName] == 'undefined') {
					fallbackClassName = "Vtiger_List_Js";
				}
			} else {
				moduleClassName = module+"_List_Js";
				fallbackClassName = "Vtiger_List_Js";
			}
			if(typeof window[moduleClassName] != 'undefined'){
				var instance = new window[moduleClassName]();
			}else{
				var instance = new window[fallbackClassName]();
			}
			Vtiger_List_Js.listInstance = instance;
			return instance;
		}
		return this.listInstance;
	},

	/**
	* Function to register event for adding related record for module
	*/
    registerEventForAddingRelatedTaskRecord : function(){
		var thisInstance = this;
		//var HeaderContentsHolder = jQuery('div.contentHeader');
		jQuery(document).on('click','#massActionTask',function(event){
			var element = jQuery(event.currentTarget);
			//var record = jQuery('[name="record"]').val();
			thisInstance.addRelatedTaskRecord(element);
		});
	},
	addRelatedTaskRecord : function(element , callback){
		var countSelectUsers = $('input:checkbox:checked.listViewEntriesCheckBox').map(function () {
		  return this.value;
		}).get()
		if (countSelectUsers.length != 1) {
			Vtiger_Helper_Js.showPnotify(app.vtranslate('Please select any single user.'));
			return false;
		}
		var relatedModule = jQuery('#relatedModule').val();
		var userId = countSelectUsers['0'];
		var aDeferred = jQuery.Deferred();
		var thisInstance = this;
		var	referenceModuleName = 'Calendar';
		var parentId = userId;
		var parentModule = relatedModule;
		var quickCreateParams = {};
		var relatedParams = {};
		
		if(relatedField == 'Contacts')
		{
			var relatedField = 'contact_id';
		}
		else{
			var relatedField = 'parent_id';	
		}
		
		var fullFormUrl = 'index.php?module=Calendar&view=Edit&sourceModule='+parentModule+'&sourceRecord='+parentId+'&relationOperation=true&'+relatedField+'='+parentId+'';
		relatedParams[relatedField] = parentId;
		var eliminatedKeys = new Array('view', 'module', 'mode', 'action');
		var preQuickCreateSave = function(data){
			var index,queryParam,queryParamComponents;
			//To handle switch to task tab when click on add task from related list of activities
			//As this is leading to events tab intially even clicked on add task
			if(typeof fullFormUrl != 'undefined' && fullFormUrl.indexOf('?')!== -1) {
				var urlSplit = fullFormUrl.split('?');
				var queryString = urlSplit[1];
				var queryParameters = queryString.split('&');
				for(index=0; index<queryParameters.length; index++) {
					queryParam = queryParameters[index];
					queryParamComponents = queryParam.split('=');
					if(queryParamComponents[0] == 'mode' && queryParamComponents[1] == 'Calendar'){
						data.find('a[data-tab-name="Task"]').trigger('click');
					}
				}
			}
			jQuery('<input type="hidden" name="sourceModule" value="'+parentModule+'" />').appendTo(data);
			jQuery('<input type="hidden" name="sourceRecord" value="'+parentId+'" />').appendTo(data);
			jQuery('<input type="hidden" name="relationOperation" value="true" />').appendTo(data);
			
			if(typeof relatedField != "undefined"){
				var field = data.find('[name="'+relatedField+'"]');
				//If their is no element with the relatedField name,we are adding hidden element with
				//name as relatedField name,for saving of record with relation to parent record
				if(field.length == 0){
					jQuery('<input type="hidden" name="'+relatedField+'" value="'+parentId+'" />').appendTo(data);
				}
			}
			for(index=0; index<queryParameters.length; index++) {
				queryParam = queryParameters[index];
				queryParamComponents = queryParam.split('=');
				if(jQuery.inArray(queryParamComponents[0], eliminatedKeys) == '-1' && data.find('[name="'+queryParamComponents[0]+'"]').length == 0) {
					jQuery('<input type="hidden" name="'+queryParamComponents[0]+'" value="'+queryParamComponents[1]+'" />').appendTo(data);
				}
			}
                        if(typeof callback !== 'undefined') {
                            callback();
                        }
		}
		var postQuickCreateSave  = function(data) {
			thisInstance.loadRelatedList().then(
				function(data){
					aDeferred.resolve(data);
				})
		}
		//If url contains params then seperate them and make them as relatedParams
		if(typeof fullFormUrl != 'undefined' && fullFormUrl.indexOf('?')!== -1) {
			var urlSplit = fullFormUrl.split('?');
			var queryString = urlSplit[1];
			var queryParameters = queryString.split('&');
			for(var index=0; index<queryParameters.length; index++) {
				var queryParam = queryParameters[index];
				var queryParamComponents = queryParam.split('=');
				if(jQuery.inArray(queryParamComponents[0], eliminatedKeys) == '-1') {
					relatedParams[queryParamComponents[0]] = queryParamComponents[1];
				}
			}
		}
		
		quickCreateParams['data'] = relatedParams;
		quickCreateParams['callbackFunction'] = postQuickCreateSave;
		quickCreateParams['callbackPostShown'] = preQuickCreateSave;
		quickCreateParams['noCache'] = true;
		var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="'+ referenceModuleName +'"]');
		
		if(quickCreateNode.length <= 0) {
			Vtiger_Helper_Js.showPnotify(app.vtranslate('JS_NO_CREATE_OR_NOT_QUICK_CREATE_ENABLED'))
		}
		quickCreateNode.trigger('click',quickCreateParams);
		return aDeferred.promise();
	},
/**
	 * Registered the events for this page
	 */
	registerEvents : function() {
		this.registerverfyClickEvent();
		this.getUniqueOpenList();
		this.getSubscriberList();
		this.registerMainCheckBoxClickEvent();
		this.registerSelectAllClickEvent();
		this.registerDeselectAllClickEvent();
		this.registerCheckBoxClickEvent();
		this.registerEventForAddingRelatedTaskRecord();
		this.actionCall();
		this._super();
	}
});