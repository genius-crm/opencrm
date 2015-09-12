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
class VGSWebmail_List_View extends Vtiger_Index_View {

        public function process(Vtiger_Request $request) {
                $viewer = $this->getViewer($request);
                $viewer->view('List.tpl', $request->getModule());
        }

}
