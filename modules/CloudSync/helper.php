<?php
class CloudSyncHelper	
{
	/**
	 * return config value from settings table
	 * @param string $configName 
	 * @return string $configValue
	 */
	public function getConfigValue($configName)	{
		global $adb, $current_user;
		$getConfigValue = $adb->pquery('select config_value from vtiger_cloudysyncsettings where config_name = ? and user_id = ?', array($configName, $current_user->id));
		$configValue = $adb->query_result($getConfigValue, 0, 'config_value');
		return $configValue;
	}

	/**
	 * check whether dropbox files created already in vtiger and mapped
	 * @param @string $service_id dropbox unique id
	 * @return string boolean
	 */
	public function checkIdAlreadyMapped($service_id)	{
		global $adb, $current_user;
		$crm_id = false;
		$get_crm_id = $adb->pquery('select crmid from vtiger_cloudsync_id_mapping where serviceid = ? and user_id = ?', array($service_id, $current_user->id));
		$crm_id = $adb->query_result($get_crm_id, 0, 'crmid');
		// check crmid status
		if(!empty($crm_id))		{
			$get_deleted = $adb->pquery('select crmid from vtiger_crmentity where crmid = ? and deleted = 0', array($crm_id));
			$count = $adb->num_rows($get_deleted);
			if($count >= 1)
				return false;
			else
				return $crm_id;

		}
		return false;
	}

	/**
	 * return common config value from settings table
	 * @param string $configName
	 * @return string $configValue
	 */
	public function getCommonConfigValue($configName)	{
		global $adb;
		$getConfigValue = $adb->pquery('select config_value from vtiger_cloudysyncsettings where config_name = ?', array($configName));
                $configValue = $adb->query_result($getConfigValue, 0, 'config_value');
                return $configValue;
	}

	/**
	 * return tabid for given modules
	 * @param string $moduleName
	 * @param integer $tabId 
 	 */
	public function getTabId($moduleName)	{
		global $adb;
		$getTabId = $adb->pquery('select tabid from vtiger_tab where name = ?', array($moduleName));
		$tabId = $adb->query_result($getTabId, 0, 'tabid');
		return $tabId;
	}

	/**
	 * return document folder name  
	 * @param integer $folder_id - folder id
	 * @return string $folder_name - document folder name
	 */
	public function getDocumentFolderName($folder_id)	{
		global $adb;
		$getFolderName = $adb->pquery('select foldername from vtiger_attachmentsfolder where folderid = ?', array($folder_id));
		$folder_name = $adb->query_result($getFolderName, 0, 'foldername');
		return $folder_name;
	}


	/**
	 * return document folders
	 * @return array $folders - Documents Module folders
	 */
	public function getDocumentFolders()	{
		$fieldObj = new Vtiger_Field_Model();
		$folders = $fieldObj->getDocumentFolders();
		return $folders;
	}

	/**
	 * add mapping id
	 * @param string $service service name (dropbox, googledrive etc)
	 * @param string $serviceId unique id from service response
	 * @param integer $crmId vtiger unique id
	 */
	public function addIdMapping($service, $serviceId, $crmId)	{
		global $adb, $current_user;
		$adb->pquery('insert into vtiger_cloudsync_id_mapping (service, serviceid, crmid, user_id) values (?, ?, ?, ?)', array($service, $serviceId, $crmId, $current_user->id));
	}

	/**
	 * add cloudsync log
	 * @param array $data 
	 */
	public function addLog($data)	{
		global $current_user;
		require_once('modules/CloudSync/CloudSync.php');
		$log = new CloudSync();
		$log->id = null;
		$log->mode = 'create';
		$log->column_fields['name'] = $data['service'];
		$log->column_fields['message'] = $data['message'];
		$log->column_fields['status'] = $data['status'];
		$log->column_fields['flow'] = $data['flow'];
		$log->column_fields['assigned_user_id'] = $current_user->id;
		$log->save('CloudSync');
	}

	/**
	 * create new document on vtiger
	 * @param string $filePath
	 * @param array $fileInfo
	 * @return integer $id
	 */
	public function createDoc($filePath, $fileInfo, $attachment_type = 'I', $user_id = false)	{
		global $current_user, $upload_badext, $adb;
		$response = false;

		if(empty($user_id))	
			$user_id = $current_user->id;

		// check whether user has permission to create/edit record
		$modePermission = Users_Privileges_Model::isPermitted('Documents', 'EditView');
                if($modePermission != true)     {
                        $response = getTranslatedString('LBL_PERMISSION_DENIED');
			$log_data['service'] = sanitizeUploadFileName($fileInfo['filename'], $upload_badext);
			$log_data['status'] = 'Failed';
                        $log_data['message'] = $response;
                        $log_data['flow'] = 'dropbox to vtiger';
                        $this->addLog($log_data);
			echo json_encode($log_data);die;
                }

		// getting request values using _SERVER
                $http_referer = $_SERVER['HTTP_REFERER'];
                $parsed_url = parse_url($http_referer);
                $exploded_parsed_url = explode('&', $parsed_url['query']);
                foreach($exploded_parsed_url as $single_parsed_url)     {
                        $exploded_single_parsed_url = explode('=', $single_parsed_url);
                        if($exploded_single_parsed_url[0] == 'module')
                                $source_module = $exploded_single_parsed_url[1];
                        else if($exploded_single_parsed_url[0] == 'record')
                                $source_record = $exploded_single_parsed_url[1];

                }

		// if attached type is internal, get size using filesize function
		if($attachment_type == 'I')
			$fileSize = filesize($filePath); 

		// figure out the mode and crm id
		$mode = 'create';
		$doc_id = $this->checkIdAlreadyMapped($fileInfo['rev']);
		if(!empty($doc_id))	{
			$mode = 'edit';
		}

		$folderid = $this->getDocumentFolderId($source_module);
		// In case folder id is empty, set foldername to Default
		if(empty($folderid) || $folderid == 0)
			$folderid = 1;

		// session to restrict the workflow
		$_SESSION['document_already_saved_nattyant'] = true;
		require_once('modules/Documents/Documents.php');
		$doc = new Documents();
		$doc->mode = $mode; $doc->id = $doc_id;
		$doc->column_fields['notes_title'] = sanitizeUploadFileName($fileInfo['filename'], $upload_badext);
		$doc->column_fields['filename'] = $fileInfo['basename'];
		$doc->column_fields['filetype'] = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;
		$doc->column_fields['filesize'] = $fileSize;
		$doc->column_fields['filelocationtype'] = $attachment_type;
		$doc->column_fields['filestatus'] = 1;
		$doc->column_fields['folderid'] = $folderid;
		$doc->column_fields['assigned_user_id'] = $user_id;
		$doc->save('Documents');
		
		// unsetting created session again
		$_SESSION['document_already_saved_nattyant'] = false;
                unset($_SESSION['document_already_saved_nattyant']);

		if($doc->id && $attachment_type == 'I')	{
			// add entry to map service and vtiger
                	$this->addIdMapping('dropbox', $fileInfo['rev'], $doc->id);

			$fileInfo['name'] = $fileInfo['basename'];
			$fileInfo['type'] = $fileInfo['extension'];
			$fileInfo['size'] = $fileSize;
			$fileInfo['tmp_name'] = $filePath;

			$response = $this->uploadAndSaveFile($doc->id, 'Documents', $fileInfo);
			if(!$response)	{
				$response = vtranslate('LBL_ERROR_UPLOADING', 'CloudSync');
				$log_data['service'] = sanitizeUploadFileName($fileInfo['filename'], $upload_badext);
				$log_data['status'] = 'Failed';
				$log_data['message'] = $response;
				$log_data['flow'] = 'dropbox to vtiger';
				$this->addLog($log_data);
				echo json_encode($log_data);die;		
			}
		}

		// check whether source module is Documents. If so, skip the relation
		if($doc->id && !empty($source_module) && $source_module != 'Documents')	{
			// relating record to module
                        $adb->pquery('insert into vtiger_senotesrel (crmid, notesid) values (?, ?)', array($source_record, $doc->id));
		}
		$response = array('mode' => $mode, 'id' => $doc->id);
		return $response;
	}

	/**
	 * copy downloaded file from service and move it to storage directory
	 * @param interger $docId
 	 * @param string $module
	 * @param array $file_details
	 * return bool
 	 */
	public function uploadAndSaveFile($docId, $module, $file_details)	{
		global $adb, $current_user, $upload_badext;
		$date_var = date("Y-m-d H:i:s");

		$file_name = $file_details['name'];
		$binFile = sanitizeUploadFileName($file_name, $upload_badext);
		
		$ownerid = $current_user->id;
		$current_id = $adb->getUniqueID("vtiger_crmentity");

                $filename = ltrim(basename(" " . $binFile)); //allowed filename like UTF-8 characters
                $filetype = $file_details['type'];
                $filesize = $file_details['size'];
                $filetmp_name = $file_details['tmp_name'];

		//get the file path inwhich folder we want to upload the file
                $upload_file_path = decideFilePath();

		// copy the file to vtiger storage directory
                $upload_status = copy($filetmp_name, $upload_file_path . $current_id . "_" . $binFile);
		if($upload_status === true)	{
			$sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
                        $params1 = array($current_id, $current_user->id, $ownerid, $module . " Attachment", $this->column_fields['description'], $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
			$adb->pquery($sql1, $params1);

			$sql2 = "insert into vtiger_attachments(attachmentsid, name, description, type, path) values(?, ?, ?, ?, ?)";
                        $params2 = array($current_id, $filename, $description, $filetype, $upload_file_path);
                        $result = $adb->pquery($sql2, $params2);

			$query = "delete from vtiger_seattachmentsrel where crmid = ?";
			$qparams = array($id);
			$adb->pquery($query, $qparams);

			$sql3 = 'insert into vtiger_seattachmentsrel values(?,?)';
			$adb->pquery($sql3, array($docId, $current_id));
			return true;
		}
		return false;
	}

	/**
	 * check needle present in the haystact at the end
	 * @param string $haystack
	 * @param string $needle
	 * @return bool
	 */
	public function endsWith($haystack, $needle) {
    		// search forward starting from end minus needle length characters
    		return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
	}

	/** 
	 * check file exists remotely
	 * @param string $filePath
	 * @return bool $fileExists
	 */
	public function checkFileExistsRemotely($filePath)	{
		$fileExists = false;
		$ch = curl_init($filePath);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($retcode == 200)	
			$fileExists = true;

		return $fileExists;
	}

	/**
	 * return folder id for given module name
	 * @param string $module_name - module name
	 * @return interger $folder_id - documents folder id
	 */
	public function getDocumentFolderId($module_name)	{
		global $adb, $current_user; $data = array();
		// get settings value
                $serialised_data = $this->getCommonConfigValue('serialised_data');
                if(!empty($serialised_data))
                        $data = unserialize(base64_decode($serialised_data));

		return $data['documentfolders_' . $module_name];
	}

	/**
	 * return field value 
	 * @param integer $crmId vtiger unique id
	 * @param string $fieldName vtiger field name
	 * @return string $fieldValue vtiger field value
	 */
	public function getDocInfo($crmId, $fieldName)	{
		global $adb;
		$getFieldValue = $adb->pquery("select $fieldName from vtiger_notes where notesid = ?", array($crmId));
                $fieldValue = $adb->query_result($getFieldValue, 0, $fieldName);
                return $fieldValue;
	}

	/**
         * return folder path of dropbox to store the file
         * @param integer $crmId vtiger unquie id
         * @return string $folderPath folder path to upload the file
         */
        public function getFolderPath($crmId)   {
                global $adb;
                $getFolderPath = $adb->pquery('select note_no from vtiger_notes where notesid = ?', array($crmId));
                $folderPath = $adb->query_result($getFolderPath, 0, 'note_no');
                return $folderPath;
        }

	/**
	 * return attachment information
	 * @param integer $crmId vtiger unique id
	 * @return array $fileDetails attachment details
	 */
	public function getFileDetails($crmId)	{
		global $adb;
		$fileDetails = array();
		$result = $adb->pquery("SELECT * FROM vtiger_attachments INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid WHERE crmid = ?", array($crmId));
                if($adb->num_rows($result)) {
                	$fileDetails = $adb->query_result_rowdata($result);
                }
                return $fileDetails;
	}

	/**
         * creating new widget for DragNDrop in given module
         * @param string $module
         */
        public function createWidget($module)   {
                // creating widget on documents module
                $modInstance = Vtiger_Module::getInstance($module);
                if($modInstance)
                        $modInstance->addLink("DETAILVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=detail&mod=" . $module);

        }

        /**
         * deleting DragNDrop widget in given module
         * @param string $module
         */
        public function deleteWidget($module)   {
                // deleting widget on module
                $modInstance = Vtiger_Module::getInstance($module);
                if($modInstance)
                        $modInstance->deleteLink("DETAILVIEWSIDEBARWIDGET", "CloudSync", "module=CloudSync&view=Widget&viewtype=detail&mod=" . $module);

        }

        /**
         * get document related modules
         * @param integer $documentTabId
         * @param object $vtFunctions
         * @param array $docRelatedModules
         */
        public function getDocumentRelatedModules($documentTabId, $vtFunctions) {
                global $adb; $count = 0;
                $not_needed_modules = array('Emails');
                $docRelatedModules = array();
                $getDocRelatedModules = $adb->pquery('select tabid from vtiger_relatedlists where related_tabid = ?', array($documentTabId));
                $count = $adb->num_rows($getDocRelatedModules);
                for($i = 0; $i < $count; $i ++) {
                        $docRelatedModules[$i]['tabid'] = $adb->query_result($getDocRelatedModules, $i, 'tabid');
			$docRelatedModules[$i]['moduleName'] = $vtFunctions->getModuleName($docRelatedModules[$i]['tabid']);
                        $docRelatedModules[$i]['checked'] = null;
                        if(in_array($docRelatedModules[$i]['moduleName'], $not_needed_modules))
                                unset($docRelatedModules[$i]);

                }
		// merging documents module informations
		$docRelatedModules[$i]['tabid'] = $this->getTabId('Documents');
		$docRelatedModules[$i]['moduleName'] = 'Documents';
		$docRelatedModules[$i]['checked'] = null;
                return $docRelatedModules;
	}
}
