<?php
class Discount {

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
                        $thisModuleInstance->addLink("HEADERSCRIPT", "Discount",
                                "modules/Discount/js/Discount.js");
                }

				$thisModuleInstance->initWebservice();
				$thisModuleInstance->initTables();
				
				$block1 = new Vtiger_Block();
				$block1->label = 'Discount';
				$thisModuleInstance->addBlock($block1); //to create a new block

				$field1 = new Vtiger_Field();
				$field1->name = 'entity_id';
				$field1->label = 'entity_id';
				$field1->column = 'entity_id';
				$field1->columntype = 'int(11)';
				$field1->uitype = 7;
				$field1->typeofdata = 'V~M';
				$block1->addField($field1);

				$field2 = new Vtiger_Field();
				$field2->name = 'line_id';
				$field2->label = 'line_id';
				$field2->column = 'line_id';
				$field2->columntype = 'int(11)';
				$field2->uitype = 7;
				$field2->typeofdata = 'V~M';
				$block1->addField($field2);

				$field3 = new Vtiger_Field();
				$field3->name = 'composite';
				$field3->label = 'composite';
				$field3->column = 'composite';
				$field3->columntype = 'varchar(25)';
				$field3->uitype = 2;
				$field3->typeofdata = 'V~M';
				$block1->addField($field3);

				$filter1 = new Vtiger_Filter();
				$filter1->name = 'All';
				$filter1->isdefault = true;
				$thisModuleInstance->addFilter($filter1);
				// Add fields to the filter created
				$filter1->addField($field1, 1);
				$filter1->addField($field2, 2);
				$filter1->addField($field3, 3);

				/** Set sharing access of this module */
				$thisModuleInstance->setDefaultSharing('Private'); 
				/** Enable and Disable available tools */
				$thisModuleInstance->disableTools(Array('Import', 'Export', 'Merge'));

				Vtiger_Utils::ExecuteQuery("ALTER TABLE vtiger_discount MODIFY COLUMN discountid INT PRIMARY KEY AUTO_INCREMENT;");
				
				//PDF Maker Modifications

				@copy("modules/PDFMaker/models/PDFMaker.php", "modules/PDFMaker/models/PDFMaker.discount");

				$handle = fopen("modules/PDFMaker/models/PDFMaker.php", "r+") or exit("PDFMaker not available!");
				
				$insertPos=0;
				while (!feof($handle)) {
					$line=fgets($handle);
					if (strpos($line, '$body_html = $pdf_content["body"];')!==false) {
						$insertPos=ftell($handle);
						$newline = '        $Product_Fields["PRODUCTCOMPOSITEDISCOUNT"."$-$"."PRODUCTPOSITION"."$-$"] = vtranslate("LBL_DISCOUNT", "Discount");'.PHP_EOL;
						$newline = '        $compositeCount = substr_count($body_html, \'$PRODUCTCOMPOSITEDISCOUNT$\');'.PHP_EOL;
						$newline .= '	 	 for ($i = 1; $i <= $compositeCount; $i++) {'.PHP_EOL;
						$newline .= '			 $start = strpos($body_html, \'$PRODUCTCOMPOSITEDISCOUNT$-\')+strlen(\'$PRODUCTCOMPOSITEDISCOUNT$-\');'.PHP_EOL;
						$newline .= '			 $end = strpos($body_html, \'-$END$\', $start);'.PHP_EOL;
						$newline .= '			 $line = substr($body_html, $start, $end-$start);'.PHP_EOL;
						$newline .= '			 if ($line == \'$PRODUCTPOSITION$\') { $lineId = 0; } else { $lineId = $line; }'.PHP_EOL;
						$newline .= '			 $query= "select composite from vtiger_discount where entity_id = ? and line_id = ?;";'.PHP_EOL;
						$newline .= '			 $result = $this->db->pquery($query, array($record, $lineId));'.PHP_EOL;
						$newline .= '			 $row = $this->db->query_result($result);'.PHP_EOL;
						$newline .= '			 $body_html = str_replace(\'$PRODUCTCOMPOSITEDISCOUNT$-\'.$line.\'-$END$\', \'(\'.$row.\')&nbsp;&nbsp;&nbsp;\', $body_html);'.PHP_EOL;
						$newline .= '		 }';					
					} else {
						$newline.=$line;
					}
				} 
				
			    fseek($handle, $insertPos);
				fwrite($handle, $newline);

				fseek($handle, 0);
				
				$insertPos=0;
				while (!feof($handle)) {
					$line=fgets($handle);
					if (strpos($line, 'PRODUCTDISCOUNTPERCENT')!==false) {
						$insertPos=ftell($handle);
						$newline = '        $Product_Fields[\'PRODUCTCOMPOSITEDISCOUNT$-$PRODUCTPOSITION$-$END\'] = vtranslate("LBL_DISCOUNT", "Discount");'.PHP_EOL;
					} else {
						$newline.=$line;
					}
				} 
				
			    fseek($handle, $insertPos);
				fwrite($handle, $newline);

				fclose($handle);				
				
        }

        protected function _deregisterLinks($moduleName) {
                $thisModuleInstance = Vtiger_Module::getInstance($moduleName);
                if ($thisModuleInstance) {
                        $thisModuleInstance->deleteLink("HEADERSCRIPT", "Discount",
                                "modules/Discount/js/Discount.js");
                }

				@copy("modules/PDFMaker/models/PDFMaker.discount", "modules/PDFMaker/models/PDFMaker.php");

				Vtiger_Utils::ExecuteQuery("DELETE FROM vtiger_field WHERE tablename = 'vtiger_discount';");
				Vtiger_Utils::ExecuteQuery("DELETE FROM vtiger_blocks WHERE blocklabel = 'discount';");
        }
}
?>