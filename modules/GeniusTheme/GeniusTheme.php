<?php
/**
* IT&M GeniusTheme
*
* @package        IT&M GeniusTheme
* @author         IT&M
* @license        Commercial
* @copyright      2014 IT&M - www.itempd.it
* @version        Release: 1.0
*/

Class GeniusTheme {
       
       function vtlib_handler($moduleName, $eventType) {
             $adb = PearDatabase::getInstance();
             if ($eventType == 'module.postinstall') {
                    $this->copyTemplate();
             } else if ($eventType == 'module.disabled') {
                    if (!copy('includes/runtime/Viewer_org.php', 'includes/runtime/Viewer.php')) {
                           $log->fatal('filed to copy<br />');
                    }      
                    rename('layouts/vlayout2', 'layouts/vlayout2_disabled');
             } else if ($eventType == 'module.enabled') {
                    if (!copy('includes/runtime/Viewer_rsp.php', 'includes/runtime/Viewer.php')) {
                           $log->fatal('filed to copy<br />');
                    }
                    rename('layouts/vlayout2_disabled','layouts/vlayout2');
                    $this->updateLayout2();
             } else if ($eventType == 'module.preuninstall') {
             } else if ($eventType == 'module.preupdate') {
             } else if ($eventType == 'module.postupdate') {
					$this->copyTemplate();
					$this->updateLayout2();
             }
       }
       
       private function copyTemplate() {
             global $root_directory, $log;
             $zip = new ZipArchive();
             $fileName = 'modules/GeniusTheme/torewrite/vlayout2.zip';
             if ($zip->open($fileName)) {
             
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                           $log->fatal('Filename: ' . $zip->getNameIndex($i) . '<br />');
                    }
                    if ($zip->extractTo($root_directory.'layouts')) {
                           $zip->close();
							if (!copy('includes/runtime/Viewer.php', 'includes/runtime/Viewer_org.php')) {
								$log->fatal('filed to copy<br />');
							}
							if (!copy('modules/GeniusTheme/torewrite/Viewer.php', 'includes/runtime/Viewer.php')) {
								$log->fatal('failed to copy!<br />');
							}
							if (!copy('includes/runtime/Viewer.php', 'includes/runtime/Viewer_rsp.php')) {
								$log->fatal('filed to copy<br />');
							}
                    } else {
                           $log->fatal('ERROR EXTRACTING MIGRATION ZIP FILE!<br />');
                    }
             }
       }
       
       private function updateLayout2() {
             
             $this->recurse_copy('layouts/vlayout/modules','layouts/vlayout2/modules');
             
                    
       }
       
       private function recurse_copy($src,$dst) {
                            
              $modules=array('layouts/vlayout/modules/Accounts','layouts/vlayout/modules/Calendar','layouts/vlayout/modules/Campaigns',
			  'layouts/vlayout/modules/Contacts','layouts/vlayout/modules/CustomView','layouts/vlayout/modules/Documents','layouts/vlayout/modules/Emails',
			  'layouts/vlayout/modules/EmailTemplates','layouts/vlayout/modules/Events','layouts/vlayout/modules/ExtensionStore','layouts/vlayout/modules/Google',
			  'layouts/vlayout/modules/HelpDesk','layouts/vlayout/modules/Home','layouts/vlayout/modules/Import','layouts/vlayout/modules/Install',
			  'layouts/vlayout/modules/Inventory','layouts/vlayout/modules/Invoice','layouts/vlayout/modules/Leads','layouts/vlayout/modules/MailManager',
			  'layouts/vlayout/modules/Migration','layouts/vlayout/modules/Mobile','layouts/vlayout/modules/PBXManager','layouts/vlayout/modules/Portal',
			  'layouts/vlayout/modules/Potentials','layouts/vlayout/modules/PriceBooks','layouts/vlayout/modules/Products','layouts/vlayout/modules/Project',
			  'layouts/vlayout/modules/PurchaseOrder','layouts/vlayout/modules/Quotes','layouts/vlayout/modules/RecycleBin',
              'layouts/vlayout/modules/Reports','layouts/vlayout/modules/Rss','layouts/vlayout/modules/SalesOrder','layouts/vlayout/modules/Services',
			  'layouts/vlayout/modules/SMSNotifier','layouts/vlayout/modules/Users','layouts/vlayout/modules/Vendors','layouts/vlayout/modules/Vtiger',
              'layouts/vlayout/modules/Settings/CronTasks','layouts/vlayout/modules/Settings/Currency','layouts/vlayout/modules/Settings/CustomerPortal',
			  'layouts/vlayout/modules/Settings/EmailTemplate','layouts/vlayout/modules/Settings/EmailTemplates',
              'layouts/vlayout/modules/Settings/ExtensionStore','layouts/vlayout/modules/Settings/Groups','layouts/vlayout/modules/Settings/LayoutEditor',
			  'layouts/vlayout/modules/Settings/Leads','layouts/vlayout/modules/Settings/LoginHistory','layouts/vlayout/modules/Settings/MailConverter',
              'layouts/vlayout/modules/Settings/MenuEditor','layouts/vlayout/modules/Settings/ModuleManager','layouts/vlayout/modules/Settings/PBXManager',
			  'layouts/vlayout/modules/Settings/Picklist','layouts/vlayout/modules/Settings/PickListDependency',
              'layouts/vlayout/modules/Settings/Profiles','layouts/vlayout/modules/Settings/Roles','layouts/vlayout/modules/Settings/SharingAccess',
			  'layouts/vlayout/modules/Settings/SMSNotifier','layouts/vlayout/modules/Settings/Vtiger','layouts/vlayout/modules/Settings/Webforms',
			  'layouts/vlayout/modules/Settings/Workflows');
           
			     if (!in_array($src, $modules)) {
                    $dir = opendir($src);
                    @mkdir($dst);
                    while(false !== ( $file = readdir($dir)) ) {
                           if (( $file != '.' ) && ( $file != '..' )) {
                                  if ( is_dir($src . '/' . $file) ) {
                                        $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                                  }
                                  else {
                                        copy($src . '/' . $file,$dst . '/' . $file);
                                  }
                           }
                    }
                    closedir($dir);
             }
            }

}
?>
