<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

chdir(dirname(__FILE__) . '/../..');
include_once 'vtlib/Vtiger/Module.php';
include_once 'includes/main/WebUI.php';

include_once 'include/Webservices/Utils.php';

class Vtiger_Tools_Console_Controller {
	const PROMPT_ANY      = 1;
	const PROMPT_OPTIONAL = 2;
	const PROMPT_NUMERIC  = 3;
	const PROMPT_ALPHANUMERIC = 4;
	const PROMPT_NAME     = 5;
	const PROMPT_LABEL    = 6;

	protected function __construct() { }

	protected function handle() {
		$this->welcome();
		$this->options();
	}

	protected function welcome() {
		echo "Welcome to Vtiger CRM Creator.\n";
		echo "This tool will enable you to get started with developing extensions with ease.\n";
		echo "Have a good time. Press CTRL+C to \"quit\".\n";
	}

	protected function options() {
		echo "Choose the options below:\n";
		echo "1. Create New Module.\n";
		echo "2. Create New Layout.\n";
		echo "3. Create New Language Pack.\n";
		echo "4. Create Test Language Pack.\n";
		$option = $this->prompt("Enter your choice: ", self::PROMPT_NUMERIC);

		try {
			switch (intval($option)) {
				case 1: $this->handleCreateModule(); break;
				case 2: $this->handleCreateLayout(); break;
				case 3: $this->handleCreateLanguage(); break;
				case 4: $this->handleCreateTestLanguage(); break;
			}
		} catch (Exception $e) {
			echo "ERROR: " .$e->getMessage() . "\n";
			echo $e->getTraceAsString();
			echo "\n";
		}
	}

	protected function prompt($msg='', $type=self::PROMPT_ANY) {
		do {
			if ($msg) echo $msg;
			$value = trim(fgets(STDIN));

			if (!$value && $type == self::PROMPT_OPTIONAL) {
				return $value;

			} else if ($value) {

				switch ($type) {
					case self::PROMPT_NUMERIC:
						if (is_numeric($value)) {
							return $value;
						}
						break;
					case self::PROMPT_ALPHANUMERIC:
						if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
							return $value;
						}
						break;
					case self::PROMPT_NAME:
						if (!preg_match("/^[a-zA-Z][^a-zA-Z0-9_ ]*$/", $value)) {
							return $value;
						}
						break;
					case self::PROMPT_LABEL:
						if (!preg_match("/^[a-zA-Z0-9_ ]+$/", $value)) {
							return $value;
						}
						break;
					default:
						return $value;
				}
			}
		} while (true);
	}

	protected function toAlphaNumeric($value) {
		return preg_replace("/[^a-zA-Z0-9_]/", "", $value);
	}

	protected function findFiles($dir, $file_pattern, &$files) {
		$items = glob($dir . '/*', GLOB_NOSORT);
		foreach ($items as $item) {
			if (is_file($item)) {
				if (!$file_pattern || preg_match("/$file_pattern/", $item)) {
					$files[] = $item;
				}
			} else if (is_dir($item) && ($dir != $item)) {
				$this->findFiles($item, $file_pattern, $files);
			}
		}
	}

	// Option Handlers
	protected function handleCreateModule() {
		$controller = new Vtiger_Tools_Console_ModuleController();
		$controller->handle();
	}

	protected function handleCreateLanguage() {
		$controller = new Vtiger_Tools_Console_LanguageController();
		$controller->handle();
	}

	protected function handleCreateTestLanguage() {
		$controller = new Vtiger_Tools_Console_TestLanguageController();
		$controller->handle();
	}

	protected function handleCreateLayout() {
		$controller = new Vtiger_Tools_Console_LayoutController();
		$controller->handle();
	}

	// Static
	public static function run() {
		$singleton = new self();
		$singleton->handle();
	}
}

class Vtiger_Tools_Console_ModuleController extends Vtiger_Tools_Console_Controller {

	public function handle() {
		echo ">>> MODULE <<<\n";

		$moduleInformation = array();
		do {
			$moduleInformation['name'] = ucwords($this->prompt("Enter module name: ", self::PROMPT_NAME));
			$module = $this->find($moduleInformation['name']);
			if (!$module) {
				break;
			}
			echo "ERROR: " . $moduleInformation['name'] . " module already exists, try another.\n";
		} while (true);

		$moduleInformation['entityfieldlabel'] = 'Name';

		$entityfieldlabel = ucwords($this->prompt(sprintf("Entity field (%s): ",
				$moduleInformation['entityfieldlabel']), self::PROMPT_OPTIONAL));
		if ($entityfieldlabel) {
			$moduleInformation['entityfieldlabel'] = $entityfieldlabel;
		}

		echo "Creating ...";
		$this->create($moduleInformation);
		echo "DONE.\n";
	}

	public function find($name) {
		return Vtiger_Module::getInstance($name);
	}

	protected function create($moduleInformation) {
		$moduleInformation['entityfieldname']  = strtolower($this->toAlphaNumeric($moduleInformation['entityfieldlabel']));

		$module = new Vtiger_Module();
		$module->name = $moduleInformation['name'];
       		$module->parent= 'Inventoty';
		$module->save();

		$module->initTables();

		$block = new Vtiger_Block();
		$block->label = 'LBL_'. strtoupper($module->name) . '_INFORMATION';
		$module->addBlock($block);

		$blockcf = new Vtiger_Block();
		$blockcf->label = 'LBL_CUSTOM_INFORMATION';
		$module->addBlock($blockcf);

		$block2 = new Vtiger_Block();
		$block2->label = 'Informazioni indirizzo';
		$module->addBlock($block2);

		$block3 = new Vtiger_Block();
		$block3->label = 'Termini e condizioni';
		$module->addBlock($block3);

		$block4 = new Vtiger_Block();
		$block4->label = 'Informazioni descrizione';
		$module->addBlock($block4);

		$block5 = new Vtiger_Block();
		$block5->label = 'Spedizione e consegna';
		$module->addBlock($block5);


		$field1  = new Vtiger_Field();
		$field1->name = $moduleInformation['entityfieldname'];
		$field1->label= 'Soggetto';
		$field1->uitype= 2;
		$field1->column = $field1->name;
		$field1->columntype = 'VARCHAR(255)';
		$field1->typeofdata = 'V~M';
		$block->addField($field1);

		$module->setEntityIdentifier($field1);


		$field = new Vtiger_Field();
		$field->label = 'Description';
		$field->name = 'description';
		$field->column = 'description';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_crmentity';
		$field->uitype = 19;
		$field->typeofdata = 'V~O';
		$block4->addField($field);


		/** Common fields that should be in every module, linked to vtiger CRM core table */
		$mfield2 = new Vtiger_Field();
		$mfield2->name = 'assigned_user_id';
		$mfield2->label = 'Assigned To';
		$mfield2->table = 'vtiger_crmentity';
		$mfield2->column = 'smownerid';
		$mfield2->uitype = 53;
		$mfield2->typeofdata = 'V~M';
		$block->addField($mfield2);

		$mfield3 = new Vtiger_Field();
		$mfield3->name = 'createdtime';
		$mfield3->label= 'Created Time';
		$mfield3->table = 'vtiger_crmentity';
		$mfield3->column = 'createdtime';
		$mfield3->uitype = 70;
		$mfield3->typeofdata = 'T~O';
		$mfield3->displaytype= 2;
		$block->addField($mfield3);

		$mfield4 = new Vtiger_Field();
		$mfield4->name = 'modifiedtime';
		$mfield4->label= 'Modified Time';
		$mfield4->table = 'vtiger_crmentity';
		$mfield4->column = 'modifiedtime';
		$mfield4->uitype = 70;
		$mfield4->typeofdata = 'T~O';
		$mfield4->displaytype= 2;
		$block->addField($mfield4);


		$mfield5 = new Vtiger_Field();
		$mfield5->name = 'modifiedby';
		$mfield5->label= 'Last Modified By';
		$mfield5->table = 'vtiger_crmentity';
		$mfield5->column = 'modifiedby';
		$mfield5->uitype = 52;
		$mfield5->typeofdata = 'V~O';
		$mfield5->displaytype= 3;
		$block->addField($mfield5);

	

		$field2 = new Vtiger_Field();
		$field2->label = $moduleInformation['entityfieldname'].' No';
		$field2->name = $moduleInformation['entityfieldname'].'_no';
		$field2->column = $moduleInformation['entityfieldname'].'_no';
		$field2->columntype = 'VARCHAR(100)';
		$field2->uitype = 4;
		$field2->typeofdata = 'V~O';
		$block->addField($field2);
		$block->save($module);






		$field = new Vtiger_Field();
		$field->label = 'Potential Name';
		$field->name = 'potentialid';
		$field->column = 'potential_id';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 76;
		$field->typeofdata = 'I~O';
		$block->addField($field);
		$block->save($module);



		$field3 = new Vtiger_Field();
		$field3->label = 'Stato documento';
		$field3->name = $moduleInformation['entityfieldname'].'stage';
		$field3->column = $moduleInformation['entityfieldname'].'ddtstage';
		$field3->columntype = 'VARCHAR(100)';
		$field3->uitype = 15;
		$field3->typeofdata = 'V~M';
		$block->addField($field3);
		$block->save($module);
		$field3->setPicklistValues( Array ('-', 'Creato', 'Consegnato', 'Fatturato') );




		$field = new Vtiger_Field();
		$field->label = 'Data documento';
		$field->name = 'datadoc';
		$field->column = 'datadoc';
		$field->columntype = 'DATE';
		$field->uitype = 5;
		$field->typeofdata = 'D~O';
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Contact Name';
		$field->name = 'contact_id';
		$field->column = 'contactid';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 57;
		$field->typeofdata = 'V~O';
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Trasporto a mezzo';
		$field->name = 'trasporto';
		$field->column = 'trasporto';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 15;
		$field->typeofdata = 'V~O';
		$block5->addField($field);
		$block5->save($module);
		$field->setPicklistValues(Array('-','Interno','TNT','Bartolini'));



		$field = new Vtiger_Field();
		$field->label = 'Data ritiro';
		$field->name = 'dataritiro';
		$field->column = 'dataritiro';
		$field->columntype = 'DATE';
		$field->uitype = 5;
		$field->typeofdata = 'D~O';
		$block5->addField($field);
		$block5->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Vettore';
		$field->name = 'vettore';
		$field->column = 'vettore';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 19;
		$field->typeofdata = 'V~O';
		$block5->addField($field);



		$field = new Vtiger_Field();
		$field->label = 'Sub Total';
		$field->name = 'hdnSubTotal';
		$field->column = 'subtotal';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 72;
		$field->typeofdata = 'N~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);


		$field = new Vtiger_Field();
		$field->label = 'Shipping';
		$field->name = 'shipping';
		$field->column = 'shipping';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block->addField($field);
		$block->save($module);


		$field = new Vtiger_Field();
		$field->label = 'Responsabile magazzino';
		$field->name = 'assigned_user_id1';
		$field->column = 'inventorymanager';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 77;
		$field->typeofdata = 'I~O';
		$block5->addField($field);
		$block5->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Adjustment';
		$field->name = 'txtAdjustment';
		$field->column = 'adjustment';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 72;
		$field->typeofdata = 'NN~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);


		$field = new Vtiger_Field();
		$field->label = 'Total';
		$field->name = 'hdnGrandTotal';
		$field->column = 'total';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 72;
		$field->typeofdata = 'N~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Tax Type';
		$field->name = 'hdnTaxType';
		$field->column = 'taxtype';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 16;
		$field->typeofdata = 'V~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);



		$field->label = 'Discount Percent';
		$field->name = 'hdnDiscountPercent';
		$field->column = 'discount_percent';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'N~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);


		$field->label = 'Discount Amount';
		$field->name = 'hdnDiscountAmount';
		$field->column = 'discount_amount';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 72;
		$field->typeofdata = 'N~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'S&H Amount';
		$field->name = 'hdnS_H_Amount';
		$field->column = 's_h_amount';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 72;
		$field->typeofdata = 'N~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Account Name';
		$field->name = 'account_id';
		$field->column = 'accountid';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 73;
		$field->typeofdata = 'I~M';
		$block->addField($field);
		$block->save($module);




		$field = new Vtiger_Field();
		$field->label = 'Currency';
		$field->name = 'currency_id';
		$field->column = 'currency_id';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 117;
		$field->typeofdata = 'I~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Conversion Rate';
		$field->name = 'conversion_rate';
		$field->column = 'conversion_rate';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'N~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Billing Address';
		$field->name = 'bill_street';
		$field->column = 'bill_street';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 24;
		$field->typeofdata = 'V~M';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Shipping Address';
		$field->name = 'ship_street';
		$field->column = 'ship_street';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 24;
		$field->typeofdata = 'V~M';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Billing City';
		$field->name = 'bill_city';
		$field->column = 'bill_city';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Shipping City';
		$field->name = 'ship_city';
		$field->column = 'ship_city';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Billing State';
		$field->name = 'bill_state';
		$field->column = 'bill_state';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Shipping State';
		$field->name = 'ship_state';
		$field->column = 'ship_state';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Billing Code';
		$field->name = 'bill_code';
		$field->column = 'bill_code';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Shipping Code';
		$field->name = 'ship_code';
		$field->column = 'ship_code';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);




		$field = new Vtiger_Field();
		$field->label = 'Billing Country';
		$field->name = 'bill_country';
		$field->column = 'bill_country';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Shipping Country';
		$field->name = 'ship_country';
		$field->column = 'ship_country';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);





		$field = new Vtiger_Field();
		$field->label = 'Billing Po Box';
		$field->name = 'bill_pobox';
		$field->column = 'bill_pobox';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Shipping Po Box';
		$field->name = 'ship_pobox';
		$field->column = 'ship_pobox';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 1;
		$field->typeofdata = 'V~O';
		$block2->addField($field);
		$block2->save($module);







		$field = new Vtiger_Field();
		$field->label = 'Terms & Conditions';
		$field->name = 'terms_conditions';
		$field->column = 'terms_conditions';
		$field->columntype = 'VARCHAR(255)';
		$field->uitype = 19;
		$field->typeofdata = 'V~O';
		$block3->addField($field);
		$block3->save($module);







		$field = new Vtiger_Field();
		$field->label = 'Item Name';
		$field->name = 'productid';
		$field->column = 'productid';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 10;
		$field->typeofdata = 'V~M';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);
		$field->setRelatedModules(Array('Products','Services'));





		$field = new Vtiger_Field();
		$field->label = 'Quantity';
		$field->name = 'quantity';
		$field->column = 'quantity';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 7;
		$field->typeofdata = 'N~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);





		$field = new Vtiger_Field();
		$field->label = 'List Price';
		$field->name = 'listprice';
		$field->column = 'listprice';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 71;
		$field->typeofdata = 'N~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);






		$field = new Vtiger_Field();
		$field->label = 'Item Comment';
		$field->name = 'comment';
		$field->column = 'comment';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 19;
		$field->typeofdata = 'V~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);




		$field = new Vtiger_Field();
		$field->label = 'Discount';
		$field->name = 'discount_amount';
		$field->column = 'discount_amount';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 71;
		$field->typeofdata = 'N~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);




		$field = new Vtiger_Field();
		$field->label = 'Item Discount Percent';
		$field->name = 'discount_percent';
		$field->column = 'discount_percent';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 7;
		$field->typeofdata = 'V~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Tax1';
		$field->name = 'tax1';
		$field->column = 'tax1';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 83;
		$field->typeofdata = 'V~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'Tax2';
		$field->name = 'tax2';
		$field->column = 'tax2';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 83;
		$field->typeofdata = 'V~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);


		$field = new Vtiger_Field();
		$field->label = 'Tax3';
		$field->name = 'tax3';
		$field->column = 'tax3';
		$field->columntype = 'VARCHAR(255)';
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = 83;
		$field->typeofdata = 'V~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);




		$field = new Vtiger_Field();
		$field->label = 'Pre Tax Total';
		$field->name = 'pre_tax_total';
		$field->column = 'pre_tax_total';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 72;
		$field->typeofdata = 'N~O';
		$field->displaytype = 3;
		$block->addField($field);
		$block->save($module);



		$field = new Vtiger_Field();
		$field->label = 'S&H Percent';
		$field->name = 'hdnS_H_Percent';
		$field->column = 's_h_percent';
		$field->columntype = 'VARCHAR(100)';
		$field->uitype = 1;
		$field->typeofdata = 'N~O';
		$field->displaytype = 5;
		$block->addField($field);
		$block->save($module);

		// Create default custom filter (mandatory)
		$filter1 = new Vtiger_Filter();
		$filter1->name = 'All';
		$filter1->isdefault = true;
		$module->addFilter($filter1);
		// Add fields to the filter created
		$filter1->addField($field1)->addField($field2, 1)->addField($field3, 2);

		// Set sharing access of this module
		$module->setDefaultSharing();

		// Enable and Disable available tools
		$module->enableTools(Array('Import', 'Export', 'Merge'));

		// Initialize Webservice support
		$module->initWebservice();

		// Create files
		$this->createFiles($module, $field1);
	}

	protected function createFiles(Vtiger_Module $module, Vtiger_Field $entityField) {
		$targetpath = 'modules/' . $module->name;

		if (!is_file($targetpath)) {
			mkdir($targetpath);
			mkdir($targetpath . '/actions');
			mkdir($targetpath . '/models');
			mkdir($targetpath . '/views');

			$templatepath = 'vtlib/ModuleDir/6.0.0.inventory';
			//ModuleName.php
			$moduleFileContents = file_get_contents($templatepath . '/ModuleName.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
				'<modulename>' => strtolower($module->name),
				'<entityfieldlabel>' => $entityField->label,
				'<entitycolumn>' => $entityField->column,
				'<entityfieldname>' => $entityField->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/'.$module->name.'.php', $moduleFileContents);

			//folder actions
			$moduleFileContents = file_get_contents($templatepath . '/actions/MassSave.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/actions/MassSave.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/actions/Save.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/actions/Save.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/actions/SaveAjax.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/actions/SaveAjax.php', $moduleFileContents);
			//folder models
			$moduleFileContents = file_get_contents($templatepath . '/models/DetailView.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/models/DetailView.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/models/EditRecordStructure.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/models/EditRecordStructure.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/models/ListView.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/models/ListView.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/models/MassEditRecordStructure.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/models/MassEditRecordStructure.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/models/Module.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/models/Module.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/models/Record.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
				'<modulename>' => strtolower($module->name),
				'<getmodulename>'   => 'getModuleName',
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/models/Record.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/models/RelationListView.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/models/RelationListView.php', $moduleFileContents);
			//folder views
			$moduleFileContents = file_get_contents($templatepath . '/views/Detail.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/Detail.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/Edit.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/Edit.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/List.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/List.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/ProductsPopup.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/ProductsPopup.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/ProductsPopupAjax.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/ProductsPopupAjax.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/SendEmail.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/SendEmail.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/ServicesPopup.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/ServicesPopup.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/ServicesPopupAjax.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/ServicesPopupAjax.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/SubProductsPopup.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/SubProductsPopup.php', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/views/SubProductsPopupAjax.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/views/SubProductsPopupAjax.php', $moduleFileContents);
		}
		// TEMPLATE -> /layout/vlayout
		$targetpath = 'layouts/vlayout/modules/' . $module->name;

		if (!is_file($targetpath)) {
			mkdir($targetpath);
			mkdir($targetpath . '/resources');
			mkdir($targetpath . '/uitypes');

			$templatepath = 'vtlib/ModuleDir/6.0.0.inventory/templates';
			//TEMPLATE
			$moduleFileContents = file_get_contents($templatepath . '/EditView.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/EditView.tpl', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/Hierarchy.php');
			file_put_contents($targetpath.'/Hierarchy.tpl', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/LineItemsContent.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/LineItemsContent.tpl', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/LineItemsEdit.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/LineItemsEdit.tpl', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/settings.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/settings.tpl', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/resources/Detail.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/resources/Detail.js', $moduleFileContents);
			$moduleFileContents = file_get_contents($templatepath . '/resources/Edit.php');
			$replacevars = array(
				'ModuleName'   => $module->name,
			);

			foreach ($replacevars as $key => $value) {
				$moduleFileContents = str_replace($key, $value, $moduleFileContents);
			}
			file_put_contents($targetpath.'/resources/Edit.js', $moduleFileContents);
		}
	}

}

class Vtiger_Tools_Console_LayoutController extends Vtiger_Tools_Console_Controller {

	// Similar grouped patterns to identify the line on which tpl filename is specified.
	const VIEWERREGEX = '/\$viewer->(view|fetch)[^\(]*\(([^\)]+)/';
	const RETURNTPLREGEX = '/(return)([ ]+[\'"]+[^;]+)/';

	const TPLREGEX    = '/[\'"]([^\'"]+)/';

	public function handle() {
		echo ">>> LAYOUT <<<\n";

		$layoutInformation = array();
		do {
			$layoutInformation['name'] = strtolower($this->prompt("Enter layout name: ", self::PROMPT_NAME));
			if (!file_exists( 'layouts/' . $layoutInformation['name'])) {
				break;
			}
			echo "ERROR: " . $layoutInformation['name'] . " already exists, try another.\n";
		} while (true);

		echo "Creating ...";
		$this->create($layoutInformation);
		echo "DONE.\n";
	}

	protected function create($layoutInformation) {
		$files = array();
		$this->findFiles( 'includes', '.php$', $files);
		$this->findFiles( 'modules', '.php$', $files);

		$layoutdir =  'layouts/' . $layoutInformation['name'] . '/';

		foreach ($files as $file) {
			$tplfolder = $layoutdir . "modules/Vtiger";
			if (preg_match("/modules\/([^\/]+)\/([^\/]+)\//", $file, $fmatch)) {
				$tplfolder = $layoutdir . "modules/" . $fmatch[1];
				if ($fmatch[1] == 'Settings') {
					$tplfolder .= '/' . $fmatch[2];
				}
			}

			$tpls = array();
			$this->findTemplateNames($file, $tpls);
			$tpls = array_unique($tpls);

			if ($tpls) {
				foreach ($tpls as $tpl) {
					$tplname = basename($tpl, true);
					// Fix sub-directory path
					$tplpath = $tplfolder . '/'. substr($tpl, 0, strpos($tpl, $tplname));
					if (!file_exists($tplpath)) {
						mkdir($tplpath, 0755, true);
					}
					if (!file_exists($tplpath.$tplname)) {
						$initialContent = "{* License Text *}\n";
						// Enable debug to make it easy to implement.
						$initialContent.= "{debug}{* REMOVE THIS LINE AFTER IMPLEMENTATION *}\n\n";
						file_put_contents($tplpath.$tplname, $initialContent);
					}
					file_put_contents($tplpath.$tplname, "{* $file *}\n", FILE_APPEND);
				}
			}
		}
	}

	protected function findTemplateNames($file, &$tpls, $inreturn=false) {
		$contents = file_get_contents($file);

		$regex = $inreturn ? self::RETURNTPLREGEX : self::VIEWERREGEX;
		if (preg_match_all($regex, $contents, $matches)) {
			foreach ($matches[2] as $match) {
				if (preg_match(self::TPLREGEX, $match, $matches2)) {
					if (stripos($matches2[1], '.tpl') !== false) {
						$tpls[] = $matches2[1];
					}
				}
			}
			// Viewer files can also have return tpl calls - find them.
			if (!$inreturn) {
				$this->findTemplateNames($file, $tpls, true);
			}
		}
	}
}

class Vtiger_Tools_Console_LanguageController extends Vtiger_Tools_Console_Controller {

	const BASE_LANG_PREFIX = 'en_us';

	public function handle() {
		echo ">>> LANGUAGE <<<\n";

		$languageInformation = array();
		do {
			$languageInformation['prefix'] = strtolower($this->prompt("Enter (languagecode_countrycode): ", self::PROMPT_NAME));
			if (!file_exists( 'languages/' . $languageInformation['prefix'])) {
				break;
			}
			echo "ERROR: " . $languageInformation['prefix'] . " already exists, try another.\n";
		} while (true);

		echo "Creating ...";
		$this->create($languageInformation);
		echo "DONE.\n";
	}

	protected function create($languageInformation) {
		$files = array();
		$this->findFiles( 'languages/'.self::BASE_LANG_PREFIX, '.php$', $files);

		foreach ($files as $file) {
			$filename = basename($file, true);
			$dir = substr($file, 0, strpos($file, $filename));
			$dir = str_replace('languages/'.self::BASE_LANG_PREFIX, 'languages/'.$languageInformation['prefix'], $dir);
			if (!file_exists($dir)) mkdir($dir);

			if (isset($languageInformation['prefix_value'])) {
				$contents = file_get_contents($file);
				$contents = preg_replace("/(=>[^'\"]+['\"])(.*)/", sprintf('$1%s$2', $languageInformation['prefix_value']), $contents);
				file_put_contents($dir.'/'.$filename, $contents);
			} else {
				copy($file, $dir.'/'.$filename);
			}
		}
	}

	protected function deploy($languageInformation) {
		if (!isset($languageInformation['label'])) {
			echo "Language label not specified.";
			return;
		}

		$db = PearDatabase::getInstance();
		$check = $db->pquery('SELECT 1 FROM vtiger_language WHERE prefix=?', $languageInformation['prefix']);
		if ($check && $db->num_rows($check)) {
			;
		} else {
			$db->pquery('INSERT INTO vtiger_language (id,name,prefix,label,lastupdated,isdefault,active) VALUES(?,?,?,?,?,?,?)',
					array($db->getUniqueId('vtiger_language'), $languageInformation['label'], $languageInformation['prefix'],
							$languageInformation['label'], date('Y-m-d H:i:s'), 0, 1));
		}
	}
}

class Vtiger_Tools_Console_TestLanguageController extends Vtiger_Tools_Console_LanguageController {

	public function handle() {
		echo ">>> TEST LANGUAGE <<<\n";

		$languageInformation = array('label' => 'TEST', 'prefix' => 'te_st', 'prefix_value' => 'âœ” ');

		echo "Creating ...";
		$this->create($languageInformation);
		echo "DONE\n";

		echo "Deploying ...";
		$this->deploy($languageInformation);
		echo "DONE.\n";
	}
}

if (php_sapi_name() == 'cli') {
	Vtiger_Tools_Console_Controller::run();
} else {
	echo "Usage: php -f vtlib/tools/creator.php";
}

