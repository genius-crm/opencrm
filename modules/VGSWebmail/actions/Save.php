<?php

/**
 * VGS Webmail Extension Module
 *
 *
 * @package        VGSWebmail Module
 * @author         Conrado Maggi - VGS Global - www.vgsglobal.com
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 */

class VGSWebmail_Save_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        global $current_user;
        $moduleName = $request->getModule();

    }

    public function process(Vtiger_Request $request) {

        $this->saveRecord($request);

        header("Location: index.php?module=VGSWebmail&view=List");
    }

    /**
     * Function to save record
     * @param <Vtiger_Request> $request - values of the record
     * @return <RecordModel> - record Model of saved record
     */
    public function saveRecord($request) {
      
        require_once 'config.inc.php';
        global $site_URL,$current_user; 
        
        if(substr($site_URL,-1)=='/'){
            $VTUrl = substr($site_URL, 0, strlen($site_URL)-1);
        }else{
            $VTUrl = $site_URL;
        }
        
        if($request->get('autosaveattachments') == 'on'){
            $attachmentsSave = 1;
        }  else {
            $attachmentsSave = 0;
        }
       
        $infoArray = array(
            $current_user->id,
            $request->get('username'),
            $request->get('password'),
            $request->get('host'),
            $request->get('port'),
            $request->get('smtp_server'),
            $request->get('smtp_port'),
            $request->get('smtp_user'),
            $request->get('smtp_pass'),
            $VTUrl,
            $request->get('webmail_language'),
            $request->get('displayname'),
            $attachmentsSave
        );

        $db = PearDatabase::getInstance();

        $db->pquery("DELETE FROM vtiger_vgswebmail_usersettings WHERE userid=?", array($current_user->id));

        $db->pquery("INSERT INTO vtiger_vgswebmail_usersettings(userid,username,password,host,port,smtp_server, smtp_port,smtp_user,smtp_pass,vtiger_url,language,displayname,autosaveattachments) 
                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)", $infoArray);
    }

}
