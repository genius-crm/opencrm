$('#fileupload').bind('fileuploaddone', function (e, data) 
{
	var progressIndicatorElement = jQuery.progressIndicator({
                'position' : 'html',
                'blockInfo' : {
                'enabled' : true
                }
        });

	// hide progressindicator
	progressIndicatorElement.progressIndicator({
		'mode' : 'hide'
	})

	var error_check = false;
	jQuery.each(data.messages, function(index, item) {
		if(item == data.result)	{
			error_check = true;
		}
     	});

	var data_msg = Array();
        data_msg[0] = 'Failed to resize image'; data_msg[1] = 'File upload aborted'; data_msg[2] = 'Image requires a minimum height'; data_msg[3] = 'Image requires a minimum height'; data_msg[4] = 'Image exceeds maximum height'; data_msg[5] = 'Image requires a minimum width'; data_msg[6] = 'Image exceeds maximum width'; data_msg[7] = 'Filetype not allowed'; data_msg[8] = 'File is too big'; data_msg[9] = 'The uploaded file exceeds the post_max_size directive in php.ini'; data_msg[10] = 'A PHP extension stopped the file upload'; data_msg[11] = 'Failed to write file to disk'; data_msg[12] = 'Missing a temporary folder'; data_msg[13] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        if(error_check === false)       {
                jQuery.each(data_msg, function(index_msg, item_msg) {
                        if(item_msg == data.result) {
                                error_check = true;
                        }
                });
        }

	var parsedData = Array();
	if(data.textStatus == 'success' && data.result != 'LBL_PERMISSION_DENIED' && error_check === false)
		parsedData['type'] = 'success';

	parsedData['title'] = data.result;
	parsedData['delay'] = 10000;
	// show notification to user
	Vtiger_Helper_Js.showPnotify(parsedData);
})

$('#fileupload').bind('fileuploadadd', function (e, data) 
{
	// show progressindicator
        var progressIndicatorElement = jQuery.progressIndicator({
                'position' : 'html',
                'blockInfo' : {
                'enabled' : true
                }
        });
})

