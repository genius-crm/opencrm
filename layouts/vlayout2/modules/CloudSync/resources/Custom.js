$(document).ready(function() {
	// tooltip
        $('[data-toggle="tooltip"]').tooltip();
});

/**
 * return dropbox files
 * @param string fileName
 * @return array fileList
 */
function showDropboxFilesSettings(fileName)     
{
        var params = {
                'script'        : 'index.php?module=CloudSync&view=GetServiceFilesSettings&service=dropbox',
                'root'          : '/',
        }

        // open modal before getting values from dropbox
        $('#DropboxFilesModal').modal();
        $('.filetree').fileTree(params, function(data) {

        });
}

function storeDropboxAppFolderPath(filePath)	{
	$('#dropboxappfolder').val(filePath);
	var parsedData = new Array();
	parsedData['type'] = 'success';
	parsedData['title'] = 'Dropbox Path Selected Successfully.';
	parsedData['delay'] = 10000;
	// show notification to user
	Vtiger_Helper_Js.showPnotify(parsedData);
}

function getRevId(filePath)	{
	var confirm_response = confirm('Are you sure you want to create file using this folder ?');
        if(!confirm_response)
                return false;

	var user_id = $('#assigned_user_id_modal').val();
        var params = {
                'module'        : 'CloudSync',
                'view'          : 'Sync',
                'service'       : 'dropbox',
                'flow'          : 'vtigertoservice',
                'user_id'       : user_id,
                'filePath'      : filePath,
        }
	
	if(confirm_response)    {

                $('#loadinggif_modalheader').show();
                // show progressindicator
                var loadingMessage = jQuery('.listViewLoadingMsg').text('Downloading folder from Dropbox...');
                var progressIndicatorElement = jQuery.progressIndicator({
                                                'message' : loadingMessage,
                                                'position' : 'html',
                                                'blockInfo' : {
                                                        'enabled' : true
                                                }
                                        });

                // call to download document and create doc on vtiger
                AppConnector.request(params).then(
                        function(data)  {
                                // hide progressindicator
                                progressIndicatorElement.progressIndicator({
                                        'mode' : 'hide'
                                })

                                $('#loadinggif_modalheader').hide();
                                var parsedData = jQuery.parseJSON(data);
                                if(parsedData.status == 'Success')
                                        parsedData['type'] = 'success';

                                parsedData['title'] = parsedData['message'];
                                parsedData['delay'] = 10000;
                                // show notification to user
                                Vtiger_Helper_Js.showPnotify(parsedData);
                        }
                );
        }
	return false;
}

function show_button_tree(id)	{
	$('#'+id+'_x').show();
}

function hide_button_tree(id)	{
	$('#'+id+'_x').hide();
}

function startAuthorisation(url)	{
	// open new window
	window.open(url);
	// show modal to get the dropbox token
	$('#getAccessToken').modal();
}

/**
 * revoke dropbox access token
 */
function revokeDropboxAccess()	{
	var params = {
                'module'                : 'CloudSync',
                'action'                : 'RevokeAccess',
                'ajax'                  : true,
        }

	var confirm_before_revoke = confirm('Revoke will remove token as well as all mapping between vtiger and dropbox. Are you sure you want to revoke ?');
	if(!confirm_before_revoke)
		return false;

        // show response
        AppConnector.request(params).then(
                function(data)  {
			window.location.href = "index.php?module=CloudSync&view=Settings";
		}
	);	
}

/**
 * return dropbox files
 * @param string fileName
 * @return array fileList
 */
function showDropboxFiles(fileName)	{
	// hide notfication area
	$('cloudsync_widget_notification').hide();
	// resetting count values
        $('#cloudsync_widget_notification_success').html(0);
        $('#cloudsync_widget_notification_failure').html(0);

	var params = {
		'script'	: 'index.php?module=CloudSync&view=GetServiceFiles&service=dropbox',
		'root'		: '/',
        }

	// open modal before getting values from dropbox 
	$('#myModal').modal();
	$('.filetree').fileTree(params, function(data) {

       	}); 
}

/**
 * save dropbox access token
 */
function saveDropboxAccessToken()	{
	var message, data;
	var dropbox_authcode = $('#dropbox_authcode').val();
	if(!dropbox_authcode.trim())	{
		$('#dropboxtoken_notification').show();
		$('#dropboxtoken_notification').html('Please enter dropbox code'+"<span style = 'float:right;' onclick='closeDiv(\"dropboxtoken_notification\")'> <i class = 'fa fa-times-circle'></i> </span>");
		$('#dropboxtoken_notification').css({'border-color' : '#C9302C'});
		return false;
	}

	var params = {
                'module'        	: 'CloudSync',
                'action'          	: 'SaveSettings',
                'dropbox_authcode'   	: dropbox_authcode,
		'ajax'			: true,
        }

	$('#loadinggif_modalheader').show();
	// show response 
        AppConnector.request(params).then(
        	function(data)  {
                        $('#loadinggif_modalheader').hide();
			$('#dropboxtoken_notification').show();
			if(!data.error)	{	
				message = data.message;
			}
			else	{
				message = data.error.code;
				data.color = '#C9302C';
			}

			$('#dropboxtoken_notification').html(message+"<span style = 'float:right;' onclick='closeDiv(\"dropboxtoken_notification\")'> <i class = 'fa fa-times-circle'></i> </span>");
                        $('#dropboxtoken_notification').css({'border-color' : data.color});

			if(data.status == 'success')
				window.location.href = 'index.php?module=CloudSync&view=Settings';
                }
	); 
}

/**
 * create new document
 * @param string filePath
 */
function createDoc(filePath)	{

	var user_id = $('#assigned_user_id_modal').val();
	var params = {
		'module'	: 'CloudSync',
		'view'		: 'Sync',
		'service'	: 'dropbox',
		'flow' 		: 'vtigertoservice',
		'user_id'	: user_id,
		'filePath'	: filePath,
	}

	var confirm_response = confirm('Are you sure you want to create record using this file ?');
	if(confirm_response)	{
		$('#loadinggif_modalheader').show();
		// show progressindicator
		var loadingMessage = jQuery('.listViewLoadingMsg').text('Downloading file from Dropbox ...');
		var progressIndicatorElement = jQuery.progressIndicator({
						'message' : loadingMessage,
						'position' : 'html',
						'blockInfo' : {
							'enabled' : true
						}
					});		

		// call to download document and create doc on vtiger
        	AppConnector.request(params).then(
	                function(data)  {
				// hide progressindicator
                        	progressIndicatorElement.progressIndicator({
                                	'mode' : 'hide'
                        	})

				$('#loadinggif_modalheader').hide();
				var parsedData = jQuery.parseJSON(data);
	                        if(parsedData.status == 'Success')
                        	        parsedData['type'] = 'success';

                	        parsedData['title'] = parsedData['message'];
        	                parsedData['delay'] = 10000;
	                        // show notification to user
                        	Vtiger_Helper_Js.showPnotify(parsedData);
                	}
        	);		
	}	
}

/**
 * hide div
 * @param string divid
 */
function closeDiv(divid)	{
	$('#'+divid).hide();
}

/**
 * validate settings form
 */
function validateSettingsForm(form)	{

}

/**
 * sync document to dropbox - DetailView
 * @param integer record
 */
function syncToDropboxDetailView(record)	{

	// show progressindicator
	var progressIndicatorElement = jQuery.progressIndicator({
		'position' : 'html',
		'blockInfo' : {
		'enabled' : true
		}
	});

	var params = {
		'module'	: 'CloudSync',
                'view' 		: 'Sync',
                'service' 	: 'dropbox',
		'flow'		: 'servicetovtiger',
		'record' 	: record
	}

	// call to upload document to dropbox
	AppConnector.request(params).then(
		function(data)	{
			// hide progressindicator
                        progressIndicatorElement.progressIndicator({
                                'mode' : 'hide'
                        })

                        var parsedData = jQuery.parseJSON(data);
			if(parsedData.status == 'Success')
				parsedData['type'] = 'success';

			parsedData['title'] = parsedData['message'];
                        parsedData['delay'] = 10000;
                        // show notification to user
                        Vtiger_Helper_Js.showPnotify(parsedData);
		}
	);
}

/**
 * sync selected documents to dropbox - ListView
 */
function syncToDropboxListView()	{
	// check whether records selected in list view
	var listInstance = Vtiger_List_Js.getInstance();
        var validationResult = listInstance.checkListRecordSelected();

        if(validationResult != true)	{
       		var selectedIdsJson = listInstance.readSelectedIds(true);
		var selectedIds = jQuery.parseJSON(selectedIdsJson);
		var selectedIdsCount = selectedIds.length;
	}
	else	{
		// no record selected. show alert notification to user
        	listInstance.noRecordSelectedAlert();
		return false;
        }

	// show progressindicator
	var loadingMessage = jQuery('.listViewLoadingMsg').text('Uploading document(s) to Dropbox');
	var progressIndicatorElement = jQuery.progressIndicator({
		'message' : loadingMessage,
		'position' : 'html',
		'blockInfo' : {
		'enabled' : true
		}
	});

	var j = 0;
	var notification_count = Array();
        notification_count['success'] = notification_count['failure'] = 0;
	// sync document to dropbox one by one
	for(var i = 0; i < selectedIdsCount; i ++)	{
		// call to upload document to dropbox
		$.ajax({
			type	      	: 'POST',
			url		: 'index.php',
			data		: { module : 'CloudSync', view : 'Sync', service : 'dropbox', flow : 'servicetovtiger', record : selectedIds[i] },
			success: function(data, textStatus, xhr)	{
				var parsedData = jQuery.parseJSON(data);
				if(parsedData.status == 'Success')	{
					notification_count['success'] = parseInt(notification_count['success']) + 1;
				}	
				else	{
					notification_count['failure'] = parseInt(notification_count['failure']) + 1;
				}		
			},
			error: function(xhr, textStatus, errorThrown)	{
				notification_count['failure'] = parseInt(notification_count['failure']) + 1;
                                console.log(textStatus);
			},
			complete: function(data)	{
				// increment variable to check agaist total count
				j = j + 1;
				// disable progressindicator when variable and total count matches
				if(j == selectedIdsCount)	{
					// disable progressindicator
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})

                                        notification_params = Array();
                                        notification_params['delay'] = 10000;
                                        notification_params['type'] = 'success';
                                        notification_params['title'] = 'Sync Completed. <br> <br> Success - '+notification_count['success']+' <br> Failed - '+notification_count['failure'] + '<br> <br> Logs <a href = "index.php?module=CloudSync&showLogs=true&view=List" target = "_blank"> here </a>';
                                        // show notification to user
                                        Vtiger_Helper_Js.showPnotify(notification_params);
				}
			}
		});
	}

}
