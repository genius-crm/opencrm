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

class VGSWebmail_LoadEmailTemplates_Action extends Vtiger_BasicAjax_Action {

    function __construct() {
        parent::__construct();
    }

    function process(Vtiger_Request $request) {
        global $adb;

       
        $result=$adb->query("SELECT  templatename,  templateid FROM vtiger_emailtemplates
                                WHERE deleted =0");
        
        
        if($adb->num_rows($result)>0){
            $termsSelect ='<select id="template-select" ><option></option>';
            for ($i = 0; $i < $adb->num_rows($result); $i++) {
                $termsSelect .='<option value=' . $adb->query_result($result,$i,'templateid') . '>' . $adb->query_result($result,$i,'templatename') . '</option>';
            }
            $termsSelect .='</select>';
        }

        $response = new Vtiger_Response();
        $response->setResult($termsSelect);
        $response->emit();
    }

}



?>