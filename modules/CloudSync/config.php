<?php
/**
 * cloudsync config
 * @author Rajkumar Mohan 
 */
class CloudSyncConfig	{
	/**
	 * Settings page title
	 */
	public static $settingsTitle = 'LBL_CLOUDSYNC_SETTINGS';

	/**
	 * Dropbox header title
	 */
	public static $dropboxHeaderTitle = 'LBL_DROPBOX_SETTINGS';

	/**
	 * Dropbox Widget Settings title
	 */
	public static $widgetSettingsTitle = 'LBL_WIDGET_SETTINGS';

	/**
	 * CloudSync version
	 */
	public static $version = '1.3';

	/**
	 *
	 */
	public static $dropboxKeyFile = 'modules/CloudSync/lib/Dropbox/key.php';

	/**
 	 * Service List 
	 */
	public static $serviceList = array('dropbox');

	/**
 	 * Service fields
	 */
	public static $serviceFieldList = array('dropboxstatus', 'dropboxtoken', 'dropboxworkflow', 'dropboxattachmenttype', 'dropboxappfolder');

	/**
	 * Storage location
	 */
	public static $storageLocation = 'storage/cloudsync_cache';

	/**
	 * side bar link list
	 */
	public static $sideBarLinks = array(
					0 => array('name' => 'LBL_CLOUDSYNC_SETTINGS', 'link' => 'index.php?module=CloudSync&view=Settings', 'class' => 'unselectedQuickLink'), 
					1 => array('name' => 'LBL_CLOUDSYNC_LOGS', 'link' => 'index.php?module=CloudSync&view=List&showLogs=true', 'class' => 'unselectedQuickLink')
				);
}
