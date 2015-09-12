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

class VGSWebmail_LoadEmailTemplateContents_Action extends Vtiger_BasicAjax_Action {

    function __construct() {
        parent::__construct();
    }

    function process(Vtiger_Request $request) {
        global $adb;

        $templateId = $request->get('template_id');


        $result = $adb->pquery("SELECT  subject,  body FROM vtiger_emailtemplates
                                WHERE deleted =0
                                AND templateid = ?", array($templateId));


        if ($adb->num_rows($result) > 0) {

            $templateInformation['content'] = decode_html($adb->query_result($result, 0, 'body'));
            $templateInformation['subject'] = $adb->query_result($result, 0, 'subject');
        }

        $response = new Vtiger_Response();
        $response->setResult($templateInformation);
        $response->emit();
    }

}

?>