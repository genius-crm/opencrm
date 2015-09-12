<?php
require_once('modules/CloudSync/config.php');
class CloudSync_Sync_View extends Vtiger_Index_View 
{
	function __construct()	{
		parent::__construct();
	}

	function process(Vtiger_Request $request)	{
		global $adb, $root_directory;
		// when you made any change here do it on workflow.php file too
		$service = $request->get('service');
		$crmId = $request->get('record');
		$flow = $request->get('flow');
		$user_id = $request->get('user_id');

		require_once("modules/CloudSync/{$service}Helper.php");
		require_once("modules/CloudSync/helper.php");

		$helper = new CloudSyncHelper();
		$helperClass = $service . 'Helper';
		$serviceObj = new $helperClass();

		if($flow == 'vtigertoservice')	{
			// download file from document and create document in vtiger using the downloaded file
			$filePath = $request->get('filePath');
			// get file meta information
			$fileInfo = $serviceObj->getMetaData($filePath, 'full');
			// get setype
			$setype = Vtiger_Functions::getCRMRecordType($crmId);
			// get attachment type
			$attachment_type = $helper->getConfigValue('dropboxattachmenttype');
			// check whether passed path is directory. If so, share it
			if(empty($attachment_type) || $fileInfo['is_dir'] == 1)	{
				// by default attachment type is External
				$attachment_type = 'E';
			}

			if($attachment_type == 'E')	{
				$fileInfo = array();
				// no need to download the file. just share the link and store
				$shareable_link = $serviceObj->createShareableLink($filePath);
				$fileInfo = array_merge($fileInfo, pathinfo($filePath)); 
				$fileInfo['basename'] = $shareable_link;

				// create new record in vtiger using the downloaded file
				$response = $helper->createDoc($shareable_link, $fileInfo, $attachment_type, $user_id);
				$logData['service'] = $fileInfo['filename'];
				$logData['flow'] = 'dropbox to vtiger';
				if(!empty($response['id']))     {
					// mode text to show on notification and log
					$response_text = 'updated';
					if($response['mode'] == 'create')
						$response_text = 'created';

					$logData['status'] = 'Success';
					$logData['message'] = 'Successfully ' . $response_text . ' record in vtiger. filename -' . $fileName;

					$result['status'] = $logData['status'];
					$result['message'] = 'Successfully ' . $response_text . ' record in vtiger.';
				}
				else    {
					// mode text to show on notification and log
					$response_text = 'updating';
					if($response['mode'] == 'create')
						$response_text = 'creating';

					$logData['status'] = 'Failed';
					$logData['message'] = 'Error while ' . $response_text . ' record in vtiger. filename - ' . $fileName;

					$result['status'] = $logData['status'];
					$result['message'] = 'Error while ' . $response_text . ' record in vtiger.';
				}
				$helper->addLog($logData);
				echo json_encode($result);die;
			}
			$response = $serviceObj->download($filePath, $user_id);	
			echo $response;die;
		}

		// get attachment details using crmid
		$fileDetails = $serviceObj->helper->getFileDetails($crmId);
		$fileName = html_entity_decode($fileDetails['name'], ENT_QUOTES, vglobal('default_charset'));
		$savedFileName = $fileDetails['attachmentsid'] . '_' . $fileName;

		// filepath of attachment
		$filePath = $root_directory . $fileDetails['path'] . $savedFileName;

		// upload the attachment to service
		$response = $serviceObj->upload($filePath, $fileName, $crmId);
		echo $response;die;
	}
}
