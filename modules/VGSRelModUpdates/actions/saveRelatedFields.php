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

class VGSRelModUpdates_saveRelatedFields_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        global $current_user;

        if (!is_admin($current_user)) {
            throw new AppException('LBL_PERMISSION_DENIED');
        }
    }

    public function process(Vtiger_Request $request) {
        $db = PearDatabase::getInstance();


        switch ($request->get('mode')) {
            case 'deleteRecord':
                $db->pquery("DELETE FROM vtiger_vgsrelmodupdates WHERE vgsrelmodupdatesid=?", array($request->get('record_id')));
                $fieldsResponse['result'] = 'ok';
                $response = new Vtiger_Response();
                $response->setResult($fieldsResponse);
                $response->emit();
                break;

            default:
                $params = Array(
                    $request->get('picklist1'),
                    $request->get('module1'),
                    $request->get('picklist_values1'),
                    $request->get('picklist2'),
                    $request->get('module2'),
                    $request->get('picklist_values2')
                );

                $result = $db->pquery("SELECT * FROM vtiger_vgsrelmodupdates WHERE 
                sourcefieldname=? 
                AND sourcemodule=? 
                AND sourcefieldvalue=? 
                AND targetfieldname=? 
                AND targetmodule=? 
                AND targetfieldvalue=? ", $params);

                if ($db->num_rows($result) > 0) {
                    $fieldsResponse['result'] = 'fail';
                    $fieldsResponse['message'] = vtranslate('ALREADY_EXISTS', 'VGSRelModUpdates');
                } else {
                    try {

                        $result = $db->pquery("INSERT INTO vtiger_vgsrelmodupdates (sourcefieldname,sourcemodule,sourcefieldvalue,targetfieldname,targetmodule,targetfieldvalue) 
                     VALUES (?,?,?,?,?,?)", $params);
                        if ($db->getAffectedRowCount($result) == 1) {
                            $fieldsResponse['result'] = 'ok';
                        } else {
                            $fieldsResponse['result'] = 'fail';
                            $fieldsResponse['message'] = vtranslate('LBL_DB_INSERT_FAIL','VGSRelModUpdates');
                        }
                    } catch (Exception $exc) {
                        $fieldsResponse['result'] = 'fail';
                        $fieldsResponse['message'] = $exc->message;
                    }
                }

                $response = new Vtiger_Response();
                $response->setResult($fieldsResponse);
                $response->emit();
                break;
        }
    }

}
