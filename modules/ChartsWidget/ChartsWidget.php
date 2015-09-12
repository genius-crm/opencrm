<?php
class ChartsWidget {

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
                        $thisModuleInstance->addLink("HEADERSCRIPT", "ChartsWidgetDetail",
                                "modules/ChartsWidget/resources/Detail.js");
                        $thisModuleInstance->addLink("HEADERSCRIPT", "ChartsWidgetChartDetail",
                                "modules/ChartsWidget/resources/ChartDetail.js");
                }

				$thisModuleInstance->initWebservice();
				$thisModuleInstance->initTables();
				
				$block1 = new Vtiger_Block();
				$block1->label = 'ChartsWidget';
				$thisModuleInstance->addBlock($block1); //to create a new block

				$field1 = new Vtiger_Field();
				$field1->name = 'chart_id';
				$field1->label = 'chart_id';
				$field1->column = 'chart_id';
				$field1->columntype = 'int(11)';
				$field1->uitype = 7;
				$field1->typeofdata = 'V~M';
				$block1->addField($field1);

				$field2 = new Vtiger_Field();
				$field2->name = 'active';
				$field2->label = 'active';
				$field2->column = 'active';
				$field2->columntype = 'int(1)';
				$field2->uitype = 7;
				$field2->typeofdata = 'V~M';
				$block1->addField($field2);

				$filter1 = new Vtiger_Filter();
				$filter1->name = 'All';
				$filter1->isdefault = true;
				$thisModuleInstance->addFilter($filter1);
				// Add fields to the filter created
				$filter1->addField($field1, 1);
				$filter1->addField($field2, 2);

				/** Set sharing access of this module */
				$thisModuleInstance->setDefaultSharing('Private'); 
				/** Enable and Disable available tools */
				$thisModuleInstance->disableTools(Array('Import', 'Export', 'Merge'));

				Vtiger_Utils::ExecuteQuery("ALTER TABLE vtiger_chartswidget MODIFY COLUMN chartswidgetid INT PRIMARY KEY AUTO_INCREMENT;");
				
        }

        protected function _deregisterLinks($moduleName) {
                $thisModuleInstance = Vtiger_Module::getInstance($moduleName);
                if ($thisModuleInstance) {
                        $thisModuleInstance->deleteLink("HEADERSCRIPT", "ChartsWidgetDetail",
                                "modules/ChartsWidget/resources/Detail.js");
                        $thisModuleInstance->deleteLink("HEADERSCRIPT", "ChartsWidgetChartDetail",
                                "modules/ChartsWidget/resources/ChartDetail.js");
              }

				Vtiger_Utils::ExecuteQuery("DELETE FROM vtiger_field WHERE tablename = 'vtiger_chartswidget';");
				Vtiger_Utils::ExecuteQuery("DELETE FROM vtiger_blocks WHERE blocklabel = 'chartswidget';");
        }
}
?>