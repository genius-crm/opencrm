<?php
/**
 * helper class for dropbox
 */
class dropboxHelper	
{
	/**
	 * dropbox access token
	 */
	private $token;

	/**
	 * dropbox status
	 */
	private $status;

	/**
	 * service name
	 */
	private $service = 'dropbox';

	/**
	 * helper class
	 */
	public $helper;

	public function __construct()	{
		require_once('modules/CloudSync/config.php');
		require_once('modules/CloudSync/helper.php');
		require_once('modules/CloudSync/lib/Dropbox/autoload.php');	
		$this->helper = new CloudSyncHelper();
		$this->token = $this->helper->getConfigValue('dropboxtoken');
		$this->status = $this->helper->getConfigValue('dropboxstatus');
		if($this->token)
			$this->dbxClient = new Dropbox\Client($this->token, "CloudSync/1.0");

	}

	/**
	 * upload files to dropbox
	 * @param string $filePath
	 * @param string $fileName
	 * @param integer $crmId
	 * @return bool 
	 */	
	public function upload($filePath, $fileName, $crmId)	{
		global $adb; $logData = array();
		$logData['flow'] = 'vtiger to dropbox';
		$logData['service'] = $fileName;

		// check dropbox status
		if(empty($this->token) || $this->token == null)	{
			$logData['status'] = 'Failed';
			$logData['message'] = 'No Access token present in CloudSync Settings';
			$this->helper->addLog($logData);
			return json_encode($logData);
		}

		// get document type 
		$docType = $this->helper->getDocInfo($crmId, 'filelocationtype');
                if($docType == 'E')     {
			// if location type is external
                        $filePath = $this->helper->getDocInfo($crmId, 'filename');
			// getting file information 
			$fileInfo = pathinfo($filePath);
			// getting filename from the url
			$fileName = $fileInfo['basename'];
			if(empty($fileName))
	                        $fileName = $this->helper->getDocInfo($crmId, 'title');

			$logData['service'] = $fileName;
			// check whether file exists on remote server
			$fileExists = $this->helper->checkFileExistsRemotely($filePath);
                }
		else	{
			// check whether file exists on server
			$fileExists = file_exists($filePath);
		}

		// check dropbox status
		if($this->status != 'on')	{
			$logData['status'] = 'Failed';
			$logData['message'] = "Sync skipped. Please change the status in CloudSync settings and sync again";
			$this->helper->addLog($logData);
			return json_encode($logData);
		}

		// if file not exists return false
		if(!$fileExists)	{
                        $logData['status'] = 'Failed';
			$logData['message'] = 'File not exists in given location or files inaccessible due to safe mode restrictions. path - ' . $filePath;
                        $this->helper->addLog($logData);
			return json_encode($logData);
                }

		// get file mode - whether to create or update file
		$mode = $this->getFileMode($crmId);

		$fp = fopen($filePath, "rb");
		// get folder path to upload the file
		$dropboxLocation = $this->helper->getFolderPath($crmId);
		// get app folder location from CloudSync Settings
		$dropboxAppFolder = $this->helper->getConfigValue('dropboxappfolder');
		// file path should start with / when uploading file to dropbox
		$dropboxFilePath = $dropboxAppFolder . '/' . $dropboxLocation . '/' . $fileName;
		// upload document to dropbox
		$response = $this->dbxClient->uploadFile($dropboxFilePath, $mode['writemode'], $fp);
		if(!empty($response['rev']))	{
			// add entry to map service and vtiger
			if($mode['mode'] == 'create')
				$this->helper->addIdMapping($this->service, $response['rev'], $crmId);

			$logData['status'] = 'Success';
			$logData['message'] = 'Successfully uploaded file to Dropbox';
		}
		else	{
			$logData['status'] = 'Failed';
                        $logData['message'] = 'Error while uploading file to ' . $this->service . '. Filename - ' . $fileName;
		}
		$this->helper->addLog($logData);	
		return json_encode($logData);
	}

	/**
	 * download file from dropbox
	 * @param string $filePath
	 * @param int $user_id
	 * @return array $fileMetaData
	 */
	public function download($filePath, $user_id)	{
		global $root_directory;
		$slash = null; $time = time(); $logData = array();

		// getting filename from filePath
		$fileInfo = pathinfo($filePath);
		$fileName = $fileInfo['basename'];

		$storageLocation = CloudSyncConfig::$storageLocation;
		$checkSlash = $this->helper->endsWith($root_directory, '/');
		if(!$checkSlash)
			$slash = '/';

		// creating temporary filename
		$vtigerFilePath = tempnam($root_directory . $slash . $storageLocation, 'cloudsync_');
		$fp = fopen($vtigerFilePath, "w+b");
		$fileMetaData = $this->dbxClient->getFile($filePath, $fp);
		fclose($fp);
		chmod($vtigerFilePath, 0777);

		if(!empty($fileMetaData['rev']))        {
			$fileInfo['rev'] = $fileMetaData['rev'];
			// create new record in vtiger using the downloaded file
			$response = $this->helper->createDoc($vtigerFilePath, $fileInfo, 'I', $user_id);
			$logData['service'] = $fileName;
			$logData['flow'] = 'dropbox to vtiger';

			if(!empty($response['id']))	{
				// mode text to show on notification and log 
				$response_text = 'updated';
				if($response['mode'] == 'create')
					$response_text = 'created';

				$logData['status'] = 'Success';
                        	$logData['message'] = 'Successfully ' . $response_text . ' record in vtiger. filename -' . $fileName;

				$result['status'] = $logData['status'];
                        	$result['message'] = 'Successfully ' . $response_text . ' record in vtiger.';
			}
			else	{
				// mode text to show on notification and log 
				$response_text = 'updating';
				if($response['mode'] == 'create')
					$response_text = 'creating';

				$logData['status'] = 'Failed';
                        	$logData['message'] = 'Error while ' . $response_text . ' record in vtiger. filename - ' . $fileName;

				$result['status'] = $logData['status'];
                        	$result['message'] = 'Error while ' . $response_text . ' record in vtiger.';
			}
			$this->helper->addLog($logData);
		}
		else	{
			$result['status'] = 'Failed';
			$result['message'] = 'Error while creating record in vtiger.';
		}
		// unlinking the file from storage directory
		unlink($vtigerFilePath);
		return json_encode($result);
	}

	/**
	 * create shareable link for given path
	 * @param string $filePath
	 * @param string $shareable_link
	 */
	public function createShareableLink($filePath)	{
		$shareable_link = $this->dbxClient->createShareableLink($filePath);
		return $shareable_link;
	}

	/**
	 * get dropbox authorize url
	 * @return string $authorizeUrl
	 */
	public function getAuthorizeURL()	{
		$appInfo = Dropbox\AppInfo::loadFromJsonFile("modules/CloudSync/lib/Dropbox/key.php");
		$webAuth = new Dropbox\WebAuthNoRedirect($appInfo, "CloudSync/1.0");
		$authorizeUrl = $webAuth->start();
		return $authorizeUrl;
	}

	/**
	 * return file list
	 * @param string fileName
	 * @return array folderMetaData
	 */
	public function getMetaData($fileName, $info = false)	{
		$folderMetaData = $this->dbxClient->getMetadataWithChildren($fileName);
		if($info == 'full')
			return $folderMetaData;
		else
			return $folderMetaData['contents'];

	}

	/**
	 * return dropbox mode whether to update or add the file
	 * @param integer $crmId vtiger unqiue id
	 * @return array $mode writemode object and mode
	 */
	private function getFileMode($crmId)	{	
		global $adb, $current_user; $mode = null;
		$getServiceId = $adb->pquery('select serviceid from vtiger_cloudsync_id_mapping where crmid = ? and service = ? and user_id = ?', array($crmId, $this->service, $current_user->id));
		$recordCount = $adb->num_rows($getServiceId);
		if($recordCount > 0)	{
			$serviceid = $adb->query_result($getServiceId, 0, 'serviceid');
			if(!empty($serviceid))	{
				$mode['writemode'] = Dropbox\WriteMode::update($serviceid);
				$mode['mode'] = 'update';
			}
			else	{
				$mode['writemode'] = Dropbox\WriteMode::add();
				$mode['mode'] = 'create';
			}
		}
		else	{
			$mode['writemode'] = Dropbox\WriteMode::add();
			$mode['mode'] = 'create';
		}
		return $mode;
	}
}
