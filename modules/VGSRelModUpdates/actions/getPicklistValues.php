<?php

/**
 * VGS Related Module Updates
 *
 *
 * @package        VGSRelModUpdates Module
 * @author         Conrado Maggi
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 * @version        Release: 1.0
 */

class VGSRelModUpdates_getPicklistValues_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        global $current_user;

        if (!is_admin($current_user)) {
            throw new AppException('LBL_PERMISSION_DENIED');
        }
    }

    public function process(Vtiger_Request $request) {
        
        $picklistValues = Vtiger_Util_Helper::getPickListValues($request->get('picklist_name'));
        
        if (count($picklistValues) > 0) {
            $htmlOptions = $this->buildOptionsArray($picklistValues,$request->get('source_module'));
            $fieldsResponse['result'] = 'ok';
            $fieldsResponse['options'] = $htmlOptions;
        } else {
            $fieldsResponse['result'] = 'fail';
        }


        $response = new Vtiger_Response();
        $response->setResult($fieldsResponse);
        $response->emit();
    }

    function buildOptionsArray($picklistValues,$selectedModule) {
       
        foreach ($picklistValues as $picklistValue) {
        
            $optionArray[$picklistValue] = getTranslatedString($picklistValue,$selectedModule);
        }
        return $optionArray;
    }

}
