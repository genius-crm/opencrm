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

class VGSRelModUpdates_getRelatedModule_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        global $current_user;

        if (!is_admin($current_user)) {
            throw new AppException('LBL_PERMISSION_DENIED');
        }
    }

    public function process(Vtiger_Request $request) {
        $db = PearDatabase::getInstance();
        $tabId = Vtiger_Functions::getModuleId($request->get('source_module'));
        
        $sql = "SELECT tabid,vtiger_field.fieldid, fieldname,uitype, relmodule
               FROM vtiger_field LEFT JOIN vtiger_fieldmodulerel ON vtiger_field.`fieldid`= vtiger_fieldmodulerel.`fieldid`
               WHERE vtiger_field.tabid = ?
               AND uitype IN ('10','51','73','57','76','59','78','80','81') 
               AND vtiger_field.presence in (0,2)";
        
        $sourceModuleUIs = $this->getCustomUIs($request->get('source_module'));
        
        $sql .=" UNION 
                SELECT tabid,vtiger_field.fieldid, fieldname,uitype, relmodule
               FROM vtiger_field LEFT JOIN vtiger_fieldmodulerel ON vtiger_field.`fieldid`= vtiger_fieldmodulerel.`fieldid`
               WHERE (vtiger_fieldmodulerel.relmodule = ? 
               AND vtiger_field.presence in (0,2))";
        
        if($sourceModuleUIs !=''){
            $sql .=" OR ($sourceModuleUIs
               AND vtiger_field.presence in (0,2))";
        }
        
        $result = $db->pquery($sql, array($tabId,$request->get('source_module')));
        if ($db->num_rows($result) > 0) {
           
            $htmlOptions = $this->buildOptionsArray($result,$tabId,$request->get('source_module'));
            $fieldsResponse['result'] = 'ok';
            $fieldsResponse['options'] = $htmlOptions;
        } else {
            $fieldsResponse['result'] = 'fail';
        }


        $response = new Vtiger_Response();
        $response->setResult($fieldsResponse);
        $response->emit();
    }

    function buildOptionsArray($result,$tabId,$sourceModule) {
        $db = PearDatabase::getInstance();
        $customUITypes = VGSRelModUpdates_Module_Model::getCustomUITypes();
        $inventoryModules = array('Quotes','SalesOrder','Invoice','PurchaseOrder');
        
        for ($i = 0; $i < $db->num_rows($result); $i++) {
            
            $resTabId = $db->query_result($result, $i, 'tabid');
            $uiType = $db->query_result($result, $i, 'uitype');
            if($uiType == 10 && $resTabId == $tabId){
                $relatedModuleName = $db->query_result($result, $i, 'relmodule');
            }  elseif($resTabId == $tabId && $uiType != 10 && $uiType != 73) {
                $relatedModuleName = array_search($uiType,$customUITypes);
            } elseif ($resTabId != $tabId) {
                $relatedModuleName = Vtiger_Functions::getModuleName($resTabId);
            }elseif ($uiType == '73') {
                $relatedModuleName = 'Accounts';
            }
            if (in_array($sourceModule,$inventoryModules) && ($relatedModuleName == 'Products' OR $relatedModuleName == 'Services')) {
                continue; //Not showing relationship between products and inventory modules- Different updates process
                
            }
            
            if($relatedModuleName !=''){
                $optionArray[$relatedModuleName] = getTranslatedString($relatedModuleName);
            }
            
        }
        return $optionArray;
    }
    
    function getCustomUIs($sourceModule){
        $customsUIs = VGSRelModUpdates_Module_Model::getCustomUITypes();
        
        if($sourceModule == 'Accounts'){
            return 'uitype IN (73,51)';
        }elseif(array_key_exists($sourceModule,$customsUIs)){
            return 'uitype IN (' . $customsUIs[$sourceModule] . ')';
        }
        
        return '';
        
    }
    
    

}
