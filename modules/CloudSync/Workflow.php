<?php
/**
 * upload document to service
 * function will be triggered via workflow
 * @param string $data 
 */
function uploadDocs_CloudSync($data)	{
	// getting record id
	list($group, $crmId) = explode('x', $data->id);
	require_once('modules/CloudSync/config.php');
	$serviceList = CloudSyncConfig::$serviceList;
	foreach($serviceList as $service)	
	{
		require_once("modules/CloudSync/{$service}Helper.php");
		$helperClass = $service . 'Helper';
		$serviceObj = new $helperClass();

		$serviceWorkflowStatus = $serviceObj->helper->getConfigValue($service . 'workflow');
		if(empty($serviceWorkflowStatus))	
			continue;

		// get attachment details using crmid
		$fileDetails = $serviceObj->helper->getFileDetails($crmId);
		$fileName = html_entity_decode($fileDetails['name'], ENT_QUOTES, vglobal('default_charset'));
		$savedFileName = $fileDetails['attachmentsid'] . '_' . $fileName;

		// filepath of attachment
		$filePath = $root_directory . $fileDetails['path'] . $savedFileName;

		// upload the attachment to service
		$serviceObj->upload($filePath, $fileName, $crmId);
	}

}
