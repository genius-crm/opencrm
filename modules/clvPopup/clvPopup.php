<?php
class clvPopup {

        public function vtlib_handler($moduleName, $eventType) {
                if ($eventType == 'module.postinstall') {
                        $this->_registerLinks($moduleName);
                } else if ($eventType == 'module.enabled') {
                        $this->_registerLinks($moduleName);
                } else if ($eventType == 'module.disabled') {
                        $this->_deregisterLinks($moduleName);
                }
        }

        protected function _registerLinks($moduleName) {
                $thisModuleInstance = Vtiger_Module::getInstance($moduleName);
                if ($thisModuleInstance) {
                        $thisModuleInstance->addLink("HEADERSCRIPT", "clvPopup",
                                "modules/clvPopup/js/jquery.popupWindow.js");
                        $thisModuleInstance->addLink("HEADERSCRIPT", "clvPopup",
                                "modules/clvPopup/js/clvPopup.js");
                }

        }

        protected function _deregisterLinks($moduleName) {
                $thisModuleInstance = Vtiger_Module::getInstance($moduleName);
                if ($thisModuleInstance) {
                        $thisModuleInstance->deleteLink("HEADERSCRIPT", "clvPopup",
                                "modules/clvPopup/js/jquery.popupWindow.js");
                        $thisModuleInstance->deleteLink("HEADERSCRIPT", "clvPopup",
                                "modules/clvPopup/js/clvPopup.js");
                }
        }
}
?>