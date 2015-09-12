<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Quotes Record Model Class
 */
class ModuleName_Record_Model extends Inventory_Record_Model {

	public function getCreateInvoiceUrl() {
		$invoiceModuleModel = Vtiger_Module_Model::getInstance('Invoice');

		return "index.php?module=".$invoiceModuleModel->getName()."&view=".$invoiceModuleModel->getEditViewName()."&<modulename>id=".$this->getId();
	}

	public function getCreateSalesOrderUrl() {
		$salesOrderModuleModel = Vtiger_Module_Model::getInstance('SalesOrder');

		return "index.php?module=".$salesOrderModuleModel->getName()."&view=".$salesOrderModuleModel->getEditViewName()."&<modulename>id=".$this->getId();
	}

	/**
	 * Function to get this record and details as PDF
	 */
	public function getPDF() {
		$recordId = $this->getId();
		$moduleName = $this-><getmodulename>();

		$controller = new Vtiger_QuotePDFController($moduleName);
		$controller->loadRecord($recordId);

		$fileName = $moduleName.'_'.getModuleSequenceNumber($moduleName, $recordId);
		$controller->Output($fileName.'.pdf', 'D');
	}

	/**
	 * This Function adds the specified product quantity to the Product Quantity in Stock
	 * @param type $recordId
	 */
	function addStockToProducts($recordId) {
		$db = PearDatabase::getInstance();

		$recordModel = Inventory_Record_Model::getInstanceById($recordId);
		$relatedProducts = $recordModel->getProducts();

		foreach ($relatedProducts as $key => $relatedProduct) {
			if($relatedProduct['qty'.$key]){
				$productId = $relatedProduct['hdnProductId'.$key];
				$result = $db->pquery("SELECT qtyinstock FROM vtiger_products WHERE productid=?", array($productId));
				$qty = $db->query_result($result,0,"qtyinstock");
				$stock = $qty + $relatedProduct['qty'.$key];
				$db->pquery("UPDATE vtiger_products SET qtyinstock=? WHERE productid=?", array($stock, $productId));
			}
		}
	}
	function getCurrencyInfo() {
		$moduleName = $this-><getmodulename>();
		$currencyInfo = $this->getInventoryCurrencyInfo($moduleName, $this->getId());
		return $currencyInfo;
	}
	function getInventoryCurrencyInfo($module, $id)	{
		global $log, $adb;

		$log->debug("Entering into function ModuleName_Record_Model getInventoryCurrencyInfo($module, $id).");

		$inv_table_array = Array('ModuleName'=>'vtiger_<modulename>');
		$inv_id_array = Array('ModuleName'=>'<modulename>id');

		$inventory_table = $inv_table_array[$module];
		$inventory_id = $inv_id_array[$module];
		$res = $adb->pquery("select currency_id, $inventory_table.conversion_rate as conv_rate, vtiger_currency_info.* from $inventory_table
							inner join vtiger_currency_info on $inventory_table.currency_id = vtiger_currency_info.id
							where $inventory_id=?", array($id));

		$currency_info = array();
		$currency_info['currency_id'] = $adb->query_result($res,0,'currency_id');
		$currency_info['conversion_rate'] = $adb->query_result($res,0,'conv_rate');
		$currency_info['currency_name'] = $adb->query_result($res,0,'currency_name');
		$currency_info['currency_code'] = $adb->query_result($res,0,'currency_code');
		$currency_info['currency_symbol'] = $adb->query_result($res,0,'currency_symbol');

		$log->debug("Exit from function ModuleName_Record_Model getInventoryCurrencyInfo($module, $id).");

		return $currency_info;
	}
	function getInventoryTaxType($module, $id)	{
		global $log, $adb;

		$log->debug("Entering into function getInventoryTaxType($module, $id).");

		$inv_table_array = Array('ModuleName'=>'vtiger_<modulename>');
		$inv_id_array = Array('ModuleName'=>'<modulename>id');

		$res = $adb->pquery("select taxtype from $inv_table_array[$module] where $inv_id_array[$module]=?", array($id));

		$taxtype = $adb->query_result($res,0,'taxtype');

		$log->debug("Exit from function getInventoryTaxType($module, $id).");

		return $taxtype;
	}
	function getProductTaxes() {
		$taxDetails = $this->get('taxDetails');
		if ($taxDetails) {
			return $taxDetails;
		}

		$record = $this->getId();
		if ($record) {
			$relatedProducts = $this->getAssociatedProducts($this-><getmodulename>(), $this->getEntity());
			$taxDetails = $relatedProducts[1]['final_details']['taxes'];
		} else {
			$taxDetails = getAllTaxes('available', '', $this->getEntity()->mode, $this->getId());
		}

		$this->set('taxDetails', $taxDetails);
		return $taxDetails;
	}
	function getShippingTaxes() {
		$shippingTaxDetails = $this->get('shippingTaxDetails');
		if ($shippingTaxDetails) {
			return $shippingTaxDetails;
		}

		$record = $this->getId();
		if ($record) {
			$relatedProducts = $this->getAssociatedProducts($this-><getmodulename>(), $this->getEntity());
			$shippingTaxDetails = $relatedProducts[1]['final_details']['sh_taxes'];
		} else {
			$shippingTaxDetails = getAllTaxes('available', 'sh', 'edit', $this->getId());
		}

		$this->set('shippingTaxDetails', $shippingTaxDetails);
		return $shippingTaxDetails;
	}
	function getProducts() {
		$relatedProducts = $this->getAssociatedProducts($this-><getmodulename>(), $this->getEntity());
		$productsCount = count($relatedProducts);

		//Updating Pre tax total
		$preTaxTotal = (float)$relatedProducts[1]['final_details']['hdnSubTotal']
						+ (float)$relatedProducts[1]['final_details']['shipping_handling_charge']
						- (float)$relatedProducts[1]['final_details']['discountTotal_final'];

		$relatedProducts[1]['final_details']['preTaxTotal'] = number_format($preTaxTotal, getCurrencyDecimalPlaces(),'.','');
		
		//Updating Total After Discount
		$totalAfterDiscount = (float)$relatedProducts[1]['final_details']['hdnSubTotal']
								- (float)$relatedProducts[1]['final_details']['discountTotal_final'];
		
		$relatedProducts[1]['final_details']['totalAfterDiscount'] = number_format($totalAfterDiscount, getCurrencyDecimalPlaces(),'.','');
		
		//Updating Tax details
		$taxtype = $relatedProducts[1]['final_details']['taxtype'];

		for ($i=1;$i<=$productsCount; $i++) {
			$product = $relatedProducts[$i];
			$productId = $product['hdnProductId'.$i];
			$totalAfterDiscount = $product['totalAfterDiscount'.$i];

			if ($taxtype == 'individual') {
				$taxDetails = getTaxDetailsForProduct($productId, 'all');
				$taxCount = count($taxDetails);
				$taxTotal = '0.00';

				for($j=0; $j<$taxCount; $j++) {
					$taxValue = $product['taxes'][$j]['percentage'];

					$taxAmount = $totalAfterDiscount * $taxValue / 100;
					$taxTotal = $taxTotal + $taxAmount;

					$relatedProducts[$i]['taxes'][$j]['amount'] = $taxAmount;
					$relatedProducts[$i]['taxTotal'.$i] = $taxTotal;
				}
				$netPrice = $totalAfterDiscount + $taxTotal;
				$relatedProducts[$i]['netPrice'.$i] = $netPrice;
			}
		}
		return $relatedProducts;
	}
	function getAssociatedProducts($module,$focus,$seid='')	{
		global $log;
		$log->debug("Entering ModuleName_Record_Model getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
		global $adb;
		$output = '';
		global $theme,$current_user;

		$no_of_decimal_places = getCurrencyDecimalPlaces();
		$theme_path="themes/".$theme."/";
		$image_path=$theme_path."images/";
		$product_Detail = Array();

		if($module == 'ModuleName') {
			$query="SELECT
						case when vtiger_products.productid != '' then vtiger_products.productname else vtiger_service.servicename end as productname,
						case when vtiger_products.productid != '' then vtiger_products.productcode else vtiger_service.service_no end as productcode,
						case when vtiger_products.productid != '' then vtiger_products.unit_price else vtiger_service.unit_price end as unit_price,
						case when vtiger_products.productid != '' then vtiger_products.qtyinstock else 'NA' end as qtyinstock,
						case when vtiger_products.productid != '' then 'Products' else 'Services' end as entitytype,
									vtiger_inventoryproductrel.listprice,
									vtiger_inventoryproductrel.description AS product_description,
									vtiger_inventoryproductrel.*,vtiger_crmentity.deleted
									FROM vtiger_inventoryproductrel
									LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_inventoryproductrel.productid
									LEFT JOIN vtiger_products
											ON vtiger_products.productid=vtiger_inventoryproductrel.productid
									LEFT JOIN vtiger_service
											ON vtiger_service.serviceid=vtiger_inventoryproductrel.productid
									WHERE id=?
									ORDER BY sequence_no";
				$params = array($focus->id);
		}elseif($module == 'Products')	{
			$query="SELECT
									vtiger_products.productid,
									vtiger_products.productcode,
									vtiger_products.productname,
									vtiger_products.unit_price,
									vtiger_products.qtyinstock,vtiger_crmentity.deleted,
									vtiger_crmentity.description AS product_description,
									'Products' AS entitytype
									FROM vtiger_products
									INNER JOIN vtiger_crmentity
											ON vtiger_crmentity.crmid=vtiger_products.productid
									WHERE vtiger_crmentity.deleted=0
											AND productid=?";
				$params = array($seid);
		}	elseif($module == 'Services')	{
			$query="SELECT
									vtiger_service.serviceid AS productid,
									'NA' AS productcode,
									vtiger_service.servicename AS productname,
									vtiger_service.unit_price AS unit_price,
									'NA' AS qtyinstock,vtiger_crmentity.deleted,
									vtiger_crmentity.description AS product_description,
									'Services' AS entitytype
									FROM vtiger_service
									INNER JOIN vtiger_crmentity
											ON vtiger_crmentity.crmid=vtiger_service.serviceid
									WHERE vtiger_crmentity.deleted=0
											AND serviceid=?";
				$params = array($seid);
		}

		$result = $adb->pquery($query, $params);
		$num_rows=$adb->num_rows($result);
		for($i=1;$i<=$num_rows;$i++)
		{
			$deleted = $adb->query_result($result,$i-1,'deleted');
			$hdnProductId = $adb->query_result($result,$i-1,'productid');
			$hdnProductcode = $adb->query_result($result,$i-1,'productcode');
			$productname=$adb->query_result($result,$i-1,'productname');
			$productdescription=$adb->query_result($result,$i-1,'product_description');
			$comment=$adb->query_result($result,$i-1,'comment');
			$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
			$qty=$adb->query_result($result,$i-1,'quantity');
			$unitprice=$adb->query_result($result,$i-1,'unit_price');
			$listprice=$adb->query_result($result,$i-1,'listprice');
			$entitytype=$adb->query_result($result,$i-1,'entitytype');
			
			if(($deleted) || (!isset($deleted))){
				$product_Detail[$i]['productDeleted'.$i] = true;
			}elseif(!$deleted){
				$product_Detail[$i]['productDeleted'.$i] = false;
			}
			
			if (!empty($entitytype)) {
				$product_Detail[$i]['entityType'.$i]=$entitytype;
			}

			if($listprice == '')
				$listprice = $unitprice;
			if($qty =='')
				$qty = 1;

			//calculate productTotal
			$productTotal = $qty*$listprice;

			//Delete link in First column
			if($i != 1)
			{
				$product_Detail[$i]['delRow'.$i]="Del";
			}
			if(empty($focus->mode) && $seid!=''){
				$sub_prod_query = $adb->pquery("SELECT crmid as prod_id from vtiger_seproductsrel WHERE productid=? AND setype='Products'",array($seid));
			} else {
				$sub_prod_query = $adb->pquery("SELECT productid as prod_id from vtiger_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
			}
			$subprodid_str='';
			$subprodname_str='';
			$subProductArray = array();
			if($adb->num_rows($sub_prod_query)>0){
				for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
					$sprod_id = $adb->query_result($sub_prod_query,$j,'prod_id');
					$sprod_name = $subProductArray[] = getProductName($sprod_id);
					$str_sep = "";
					if($j>0) $str_sep = ":";
					$subprodid_str .= $str_sep.$sprod_id;
					$subprodname_str .= $str_sep." - ".$sprod_name;
				}
			}

			$subprodname_str = str_replace(":","<br>",$subprodname_str);

			$product_Detail[$i]['subProductArray'.$i] = $subProductArray;
			$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
			$product_Detail[$i]['productName'.$i]= from_html($productname);
			/* Added to fix the issue Product Pop-up name display*/
			if($_REQUEST['action'] == 'CreateSOPDF' || $_REQUEST['action'] == 'CreatePDF' || $_REQUEST['action'] == 'SendPDFMail')
				$product_Detail[$i]['productName'.$i]= htmlspecialchars($product_Detail[$i]['productName'.$i]);
			$product_Detail[$i]['hdnProductcode'.$i] = $hdnProductcode;
			$product_Detail[$i]['productDescription'.$i]= from_html($productdescription);
			if($module == 'Potentials' || $module == 'Products' || $module == 'Services') {
				$product_Detail[$i]['comment'.$i]= $productdescription;
			}else {
				$product_Detail[$i]['comment'.$i]= $comment;
			}

			if($module != 'PurchaseOrder' && $focus->object_name != 'Order') {
				$product_Detail[$i]['qtyInStock'.$i]=decimalFormat($qtyinstock);
			}
			$listprice = number_format($listprice, $no_of_decimal_places,'.','');
			$product_Detail[$i]['qty'.$i]=decimalFormat($qty);
			$product_Detail[$i]['listPrice'.$i]=$listprice;
			$product_Detail[$i]['unitPrice'.$i]=number_format($unitprice, $no_of_decimal_places,'.','');
			$product_Detail[$i]['productTotal'.$i]=$productTotal;
			$product_Detail[$i]['subproduct_ids'.$i]=$subprodid_str;
			$product_Detail[$i]['subprod_names'.$i]=$subprodname_str;
			$discount_percent = decimalFormat($adb->query_result($result,$i-1,'discount_percent'));
			$discount_amount = $adb->query_result($result,$i-1,'discount_amount');
			$discount_amount = decimalFormat(number_format($discount_amount, $no_of_decimal_places,'.',''));
			$discountTotal = '0.00';
			//Based on the discount percent or amount we will show the discount details

			//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(for Each Product)
			$product_Detail[$i]['discount_percent'.$i] = 0;
			$product_Detail[$i]['discount_amount'.$i] = 0;

			if($discount_percent != 'NULL' && $discount_percent != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "percentage";
				$product_Detail[$i]['discount_percent'.$i] = $discount_percent;
				$product_Detail[$i]['checked_discount_percent'.$i] = ' checked';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:hidden"';
				$discountTotal = $productTotal*$discount_percent/100;
			}
			elseif($discount_amount != 'NULL' && $discount_amount != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "amount";
				$product_Detail[$i]['discount_amount'.$i] = $discount_amount;
				$product_Detail[$i]['checked_discount_amount'.$i] = ' checked';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:hidden"';
				$discountTotal = $discount_amount;
			}
			else
			{
				$product_Detail[$i]['checked_discount_zero'.$i] = ' checked';
			}
			$totalAfterDiscount = $productTotal-$discountTotal;
			$totalAfterDiscount = number_format($totalAfterDiscount, $no_of_decimal_places,'.','');
			$discountTotal = number_format($discountTotal, $no_of_decimal_places,'.','');
			$product_Detail[$i]['discountTotal'.$i] = $discountTotal;
			$product_Detail[$i]['totalAfterDiscount'.$i] = $totalAfterDiscount;

			$taxTotal = '0.00';
			$taxTotal = number_format($taxTotal, $no_of_decimal_places,'.','');
			$product_Detail[$i]['taxTotal'.$i] = $taxTotal;

			//Calculate netprice
			$netPrice = $totalAfterDiscount+$taxTotal;
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'ModuleName')
			{
				$taxtype = $this->getInventoryTaxType($module,$focus->id);
				if($taxtype == 'individual')
				{
					//Add the tax with product total and assign to netprice
					$netPrice = $netPrice+$taxTotal;
				}
			}
			$product_Detail[$i]['netPrice'.$i] = $netPrice;

			//First we will get all associated taxes as array
			$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
			//Now retrieve the tax values from the current query with the name
			for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			{
				$tax_name = $tax_details[$tax_count]['taxname'];
				$tax_label = $tax_details[$tax_count]['taxlabel'];
				$tax_value = '0.00';

				//condition to avoid this function call when create new PO/SO/Quotes/Invoice from Product module
				if($focus->id != '')
				{
					if($taxtype == 'individual')//if individual then show the entered tax percentage
						$tax_value = getInventoryProductTaxValue($focus->id, $hdnProductId, $tax_name);
					else//if group tax then we have to show the default value when change to individual tax
						$tax_value = $tax_details[$tax_count]['percentage'];
				}
				else//if the above function not called then assign the default associated value of the product
					$tax_value = $tax_details[$tax_count]['percentage'];

				$product_Detail[$i]['taxes'][$tax_count]['taxname'] = $tax_name;
				$product_Detail[$i]['taxes'][$tax_count]['taxlabel'] = $tax_label;
				$product_Detail[$i]['taxes'][$tax_count]['percentage'] = $tax_value;
			}

		}

		//set the taxtype
		$product_Detail[1]['final_details']['taxtype'] = $taxtype;

		//Get the Final Discount, S&H charge, Tax for S&H and Adjustment values
		//To set the Final Discount details
		$finalDiscount = '0.00';
		$product_Detail[1]['final_details']['discount_type_final'] = 'zero';

		$subTotal = ($focus->column_fields['hdnSubTotal'] != '')?$focus->column_fields['hdnSubTotal']:'0.00';
		$subTotal = number_format($subTotal, $no_of_decimal_places,'.','');

		$product_Detail[1]['final_details']['hdnSubTotal'] = $subTotal;
		$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
		$discountAmount = ($focus->column_fields['hdnDiscountAmount'] != '')?$focus->column_fields['hdnDiscountAmount']:'0.00';
		if($discountPercent != '0'){
			$discountAmount = ($product_Detail[1]['final_details']['hdnSubTotal'] * $discountPercent / 100);
		}

		//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(For Final Discount)
		$discount_amount_final = '0.00';
		$discount_amount_final = number_format($discount_amount_final, $no_of_decimal_places,'.','');
		$product_Detail[1]['final_details']['discount_percentage_final'] = 0;
		$product_Detail[1]['final_details']['discount_amount_final'] = $discount_amount_final;

		if($focus->column_fields['hdnDiscountPercent'] != '0')
		{
			$finalDiscount = ($subTotal*$discountPercent/100);
			$product_Detail[1]['final_details']['discount_type_final'] = 'percentage';
			$product_Detail[1]['final_details']['discount_percentage_final'] = $discountPercent;
			$product_Detail[1]['final_details']['checked_discount_percentage_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:hidden"';
		}
		elseif($focus->column_fields['hdnDiscountAmount'] != '0')
		{
			$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
			$product_Detail[1]['final_details']['discount_type_final'] = 'amount';
			$product_Detail[1]['final_details']['discount_amount_final'] = $discountAmount;
			$product_Detail[1]['final_details']['checked_discount_amount_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:hidden"';
		}
		$finalDiscount = number_format($finalDiscount, $no_of_decimal_places,'.','');
		$product_Detail[1]['final_details']['discountTotal_final'] = $finalDiscount;

		//To set the Final Tax values
		//we will get all taxes. if individual then show the product related taxes only else show all taxes
		//suppose user want to change individual to group or vice versa in edit time the we have to show all taxes. so that here we will store all the taxes and based on need we will show the corresponding taxes

		$taxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$tax_details = getAllTaxes('available','','edit',$focus->id);

		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];

			//if taxtype is individual and want to change to group during edit time then we have to show the all available taxes and their default values
			//Also taxtype is group and want to change to individual during edit time then we have to provide the asspciated taxes and their default tax values for individual products
			if($taxtype == 'group')
				$tax_percent = $adb->query_result($result,0,$tax_name);
			else
				$tax_percent = $tax_details[$tax_count]['percentage'];//$adb->query_result($result,0,$tax_name);

			if($tax_percent == '' || $tax_percent == 'NULL')
				$tax_percent = '0.00';
			$taxamount = ($subTotal-$finalDiscount)*$tax_percent/100;
			$taxamount = number_format($taxamount, $no_of_decimal_places,'.','');
			$taxtotal = $taxtotal + $taxamount;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxname'] = $tax_name;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxlabel'] = $tax_label;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['percentage'] = $tax_percent;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['amount'] = $taxamount;
		}
		$product_Detail[1]['final_details']['tax_totalamount'] = $taxtotal;

		//To set the Shipping & Handling charge
		$shCharge = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
		$shCharge = number_format($shCharge, $no_of_decimal_places,'.','');
		$product_Detail[1]['final_details']['shipping_handling_charge'] = $shCharge;

		//To set the Shipping & Handling tax values
		//calculate S&H tax
		$shtaxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$shtax_details = getAllTaxes('available','sh','edit',$focus->id);

		//if taxtype is group then the tax should be same for all products in vtiger_inventoryproductrel table
		for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
		{
			$shtax_name = $shtax_details[$shtax_count]['taxname'];
			$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
			$shtax_percent = '0.00';
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'ModuleName' )
			{
				$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
			}
			$shtaxamount = $shCharge*$shtax_percent/100;
			$shtaxtotal = $shtaxtotal + $shtaxamount;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxname'] = $shtax_name;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxlabel'] = $shtax_label;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['percentage'] = $shtax_percent;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['amount'] = $shtaxamount;
		}
		$shtaxtotal = number_format($shtaxtotal, $no_of_decimal_places,'.','');
		$product_Detail[1]['final_details']['shtax_totalamount'] = $shtaxtotal;

		//To set the Adjustment value
		$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
		$adjustment = number_format($adjustment, $no_of_decimal_places,'.','');
		$product_Detail[1]['final_details']['adjustment'] = $adjustment;

		//To set the grand total
		$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
		$grandTotal = number_format($grandTotal, $no_of_decimal_places,'.','');
		$product_Detail[1]['final_details']['grandTotal'] = $grandTotal;

		$log->debug("Exiting ModuleName_Record_Model getAssociatedProducts method ...");

		return $product_Detail;
		}

}
