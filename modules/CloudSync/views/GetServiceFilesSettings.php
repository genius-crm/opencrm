<?php
require_once('modules/CloudSync/config.php');
class CloudSync_GetServiceFilesSettings_View extends Vtiger_Index_View 
{
	function __construct()	{
		parent::__construct();
	}

	function process(Vtiger_Request $request)	{
		global $adb, $root_directory;

		$service = $request->get('service');
		$fileName = $request->get('root');

		require_once("modules/CloudSync/{$service}Helper.php");
		$helperClass = $service . 'Helper';
		$serviceObj = new $helperClass();

		// get service files 
		$files = $serviceObj->getMetaData($fileName);
		if(is_array($files))	{
			echo '<ul class="jqueryFileTree">';
			// for all folders
			foreach($files as $file)	{
				if($file['is_dir'] == 1)	{
					$fname = $this->getFileName($file['path']);
					echo "<li class='directory collapsed'> 
						<a href='#' onMouseOver='show_button_tree(\"{$file['rev']}\")' onMouseOut='hide_button_tree(\"{$file['rev']}\")' id = '{$file['rev']}' rel='" . htmlentities($file['path']) . "' style = 'height: 30px; display: block; padding-top: 9px;'>" . $fname . "
							<i class = 'fa fa-link fa-2x' id = '{$file['rev']}_x' onclick = 'storeDropboxAppFolderPath(\"" . htmlentities($file['path']) . "\")' style = 'display:none; float:right; font-size: 1.5em;'> </i>
						</a>
					</li>";
				}
			}
	
			// for all files
			foreach($files as $file)	{
				if($file['is_dir'] != 1)	{
					$fname = $this->getFileName($file['path']);
					$ext = preg_replace('/^.*\./', '', $file['path']);
                                	echo "<li class='file ext_{$ext}'>
						<a href='#' rel=\"" . htmlentities($file['path']) . "\" style = 'height: 30px; display: block; padding-top: 9px;'>" . $fname . "</a>
					</li>";
				}
			}
			echo '</ul>';
		}
		die;
	}

	/**
	 * return filename from directory path
	 * @param string $filepath
	 * @return string $fname
	 */
	function getFileName($filepath)	{
		$pathInfo = pathinfo($filepath);
		if(!empty($pathInfo['extension']))
			$fname = $pathInfo['filename'] . '.' . $pathInfo['extension'];
		else
			$fname = $pathInfo['filename'];

		return $fname;
	}
}
