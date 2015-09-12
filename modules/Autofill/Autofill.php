<?php
class Autofill {

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
                        $thisModuleInstance->addLink("HEADERSCRIPT", "Autofill",
                                "modules/Autofill/js/Autofill.js");
                }

        }

        protected function _deregisterLinks($moduleName) {
                $thisModuleInstance = Vtiger_Module::getInstance($moduleName);
                if ($thisModuleInstance) {
                        $thisModuleInstance->deleteLink("HEADERSCRIPT", "Autofill",
                                "modules/Autofill/js/Autofill.js");
                }
        }
}
?>