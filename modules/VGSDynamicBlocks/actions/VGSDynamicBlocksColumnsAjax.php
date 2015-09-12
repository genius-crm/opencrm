<?php

/**
 * VGS Dynamic Blocks
 *
 * @package        VGS Dynamic Blocks
 * @author         Conrado Maggi
 * @license        Commercial
 * @copyright      2014 VGS Global - www.vgsglobal.com
 * @version        Release: 1.0
 */

class VGSDynamicBlocks_VGSDynamicBlocksColumnsAjax_Action extends Settings_Vtiger_Index_Action {

    public function __construct() {
        parent::__construct();
        $this->exposeMethod('getInitialFields');
        $this->exposeMethod('getblocks');
        $this->exposeMethod('getDetailBlocks');
    }

    public function process(Vtiger_Request $request) {
        $mode = $request->getMode();
        if (!empty($mode)) {
            echo $this->invokeExposedMethod($mode, $request);
            return;
        }
    }

    public function checkPermission(Vtiger_Request $request) {
        return true;
    }

    public function getInitialFields(Vtiger_Request $request) {
        $hiddenBlocksDetails = $this->getHiddenBlocksDetail($request);
        
         if (count($hiddenBlocksDetails) > 0) {
            $msg['caption'] = 'OK';
            $msg['blocks']= $hiddenBlocksDetails;
            
        } else {
            $msg['caption'] = 'no-blocks';
        }
        
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult($msg);
        $response->emit();
    }
    
    
    function getHiddenBlocksDetail(Vtiger_Request $request){
        $moduleModel = Vtiger_Module_Model::getInstance($request->get('sourcemodule'));
        $blockModelList = $moduleModel->getBlocks();

        $db = PearDatabase::getInstance();
        $res = $db->pquery("SELECT * FROM vtiger_vgsdynamicblocks WHERE moduleid=?", array(getTabid($request->get('sourcemodule'))));
        if ($db->num_rows($res) > 0) {
            while ($row = $db->fetchByAssoc($res)) {
                foreach ($blockModelList as $blockLabel => $blockData) {
                    if ($row['blockid'] == $blockData->id) {
                        $row['blocklabel'] = vtranslate($blockLabel, $request->get('sourcemodule'));
                        $row['fieldvalue'] = html_entity_decode($row['fieldvalue'],ENT_QUOTES,'UTF-8');
                    }
                }
                $hiddenBlocksDetails[] = $row;
            }
        }    
        return $hiddenBlocksDetails;    
    }
    
    

    function getDetailBlocks(Vtiger_Request $request) {

        $hiddenBlocksDetails = $this->getHiddenBlocksDetail($request);
        
        if (count($hiddenBlocksDetails) > 0) {
            $msg['caption'] = 'OK';
            $msg['hiddenblocks']= $hiddenBlocksDetails;
            
        } else {
            $msg['caption'] = 'no-blocks';
        }
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult($msg);
        $response->emit();
    }

    function getblocks(Vtiger_Request $request) {

        $moduleModel = Vtiger_Module_Model::getInstance($request->get('sourcemodule'));
        $blockModelList = $moduleModel->getBlocks();

        $msg['hiddenblocks'] = array();

        $hiddenBlocksIds = $this->getHiddenBlocks($request);

        $msg['caption'] = 'OK';

        foreach ($blockModelList as $blockLabel => $blockModel) {
            if (count($hiddenBlocksIds) > 0 && in_array($blockModel->id, $hiddenBlocksIds) && $blockLabel !='') {
                $msg['hiddenblocks'][] = vtranslate($blockLabel, $request->get('sourcemodule'));
            } elseif($blockLabel !='' && !in_array($blockModel->id, $hiddenBlocksIds)) {
                $msg['visibleblocks'][] = vtranslate($blockLabel, $request->get('sourcemodule'));
            }
        }

        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult($msg);
        $response->emit();
    }

    function getHiddenBlocks($request) {
        $db = PearDatabase::getInstance();
        $moduleModel = Vtiger_Module_Model::getInstance($request->get('sourcemodule'));
        $blockModelList = $moduleModel->getBlocks();
        $res = $db->pquery("SELECT * FROM vtiger_vgsdynamicblocks WHERE moduleid=? AND fieldname=? AND fieldvalue=?", array(getTabid($request->get('sourcemodule')), $request->get('fieldname'), $request->get('fieldvalue')));
        if ($db->num_rows($res) > 0) {

            while ($row = $db->fetchByAssoc($res)) {
                foreach ($blockModelList as $blockLabel => $blockData) {
                    if ($row['blockid'] == $blockData->id) {
                        $row['blocklabel'] = vtranslate($blockLabel, $request->get('sourcemodule'));
                    }
                }
                $result[] = $row['blockid'];
            }
            return $result;
        } else {
            return array();
        }
    }

}
