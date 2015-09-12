<?php /* Smarty version Smarty-3.1.7, created on 2015-08-14 13:15:01
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Accounting/LineItemsEdit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:122372796355cde9d571b091-91078555%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee812a0b48084817c72ac8d61b3337e17d5e2686' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Accounting/LineItemsEdit.tpl',
      1 => 1438433155,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122372796355cde9d571b091-91078555',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'RELATED_PRODUCTS' => 0,
    'FINAL' => 0,
    'MODULE' => 0,
    'APP' => 0,
    'CURRENCINFO' => 0,
    'SELECTED_CURRENCY' => 0,
    'USER_MODEL' => 0,
    'CURRENCIES' => 0,
    'currency_details' => 0,
    'USER_CURRENCY_ID' => 0,
    'RECORD_STRUCTURE_MODEL' => 0,
    'RECORD_CURRENCY_RATE' => 0,
    'row_no' => 0,
    'data' => 0,
    'PRODUCT_ACTIVE' => 0,
    'SERVICE_ACTIVE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55cde9d5813f0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cde9d5813f0')) {function content_55cde9d5813f0($_smarty_tpl) {?>

<!--All final details are stored in the first element in the array with the index name as final_detailsso we will get that array, parse that array and fill the details--><?php $_smarty_tpl->tpl_vars["FINAL"] = new Smarty_variable($_smarty_tpl->tpl_vars['RELATED_PRODUCTS']->value[1]['final_details'], null, 0);?><?php $_smarty_tpl->tpl_vars["IS_INDIVIDUAL_TAX_TYPE"] = new Smarty_variable(false, null, 0);?><?php $_smarty_tpl->tpl_vars["IS_GROUP_TAX_TYPE"] = new Smarty_variable(true, null, 0);?><?php if ($_smarty_tpl->tpl_vars['FINAL']->value['taxtype']=='individual'){?><?php $_smarty_tpl->tpl_vars["IS_GROUP_TAX_TYPE"] = new Smarty_variable(false, null, 0);?><?php $_smarty_tpl->tpl_vars["IS_INDIVIDUAL_TAX_TYPE"] = new Smarty_variable(true, null, 0);?><?php }?><table class="table table-bordered blockContainer lineItemTable" id="lineItemTab"><tr><th colspan="4"><span class="inventoryLineItemHeader"><?php echo vtranslate('LBL_ITEM_DETAILS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></th><td colspan="2" class="chznDropDown"><b><?php echo $_smarty_tpl->tpl_vars['APP']->value['LBL_CURRENCY'];?>
</b>&nbsp;&nbsp;<?php $_smarty_tpl->tpl_vars['SELECTED_CURRENCY'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENCINFO']->value, null, 0);?><?php if ($_smarty_tpl->tpl_vars['SELECTED_CURRENCY']->value==''){?><?php $_smarty_tpl->tpl_vars['USER_CURRENCY_ID'] = new Smarty_variable($_smarty_tpl->tpl_vars['USER_MODEL']->value->get('currency_id'), null, 0);?><?php  $_smarty_tpl->tpl_vars['currency_details'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['currency_details']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['CURRENCIES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['currency_details']->key => $_smarty_tpl->tpl_vars['currency_details']->value){
$_smarty_tpl->tpl_vars['currency_details']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['currency_details']->value['curid']==$_smarty_tpl->tpl_vars['USER_CURRENCY_ID']->value){?><?php $_smarty_tpl->tpl_vars['SELECTED_CURRENCY'] = new Smarty_variable($_smarty_tpl->tpl_vars['currency_details']->value, null, 0);?><?php }?><?php } ?><?php }?><select class="chzn-select" id="currency_id" name="currency_id"><?php  $_smarty_tpl->tpl_vars['currency_details'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['currency_details']->_loop = false;
 $_smarty_tpl->tpl_vars['count'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENCIES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['currency_details']->key => $_smarty_tpl->tpl_vars['currency_details']->value){
$_smarty_tpl->tpl_vars['currency_details']->_loop = true;
 $_smarty_tpl->tpl_vars['count']->value = $_smarty_tpl->tpl_vars['currency_details']->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['currency_details']->value['curid'];?>
" class="textShadowNone" data-conversion-rate="<?php echo $_smarty_tpl->tpl_vars['currency_details']->value['conversionrate'];?>
" <?php if ($_smarty_tpl->tpl_vars['SELECTED_CURRENCY']->value['currency_id']==$_smarty_tpl->tpl_vars['currency_details']->value['curid']){?> selected <?php }?>><?php echo getTranslatedCurrencyString($_smarty_tpl->tpl_vars['currency_details']->value['currencylabel']);?>
 (<?php echo $_smarty_tpl->tpl_vars['currency_details']->value['currencysymbol'];?>
)</option><?php } ?></select><?php $_smarty_tpl->tpl_vars["RECORD_CURRENCY_RATE"] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD_STRUCTURE_MODEL']->value->getRecord()->get('conversion_rate'), null, 0);?><?php if ($_smarty_tpl->tpl_vars['RECORD_CURRENCY_RATE']->value==''){?><?php $_smarty_tpl->tpl_vars["RECORD_CURRENCY_RATE"] = new Smarty_variable($_smarty_tpl->tpl_vars['SELECTED_CURRENCY']->value['conversionrate'], null, 0);?><?php }?><input type="hidden" name="conversion_rate" id="conversion_rate" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_CURRENCY_RATE']->value;?>
" /><input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['SELECTED_CURRENCY']->value['currency_id'];?>
" id="prev_selected_currency_id" /><!-- TODO : To get default currency in even better way than depending on first element --><input type="hidden" id="default_currency_id" value="<?php echo $_smarty_tpl->tpl_vars['CURRENCIES']->value[0]['curid'];?>
" /></td></tr><tr><td><b><?php echo vtranslate('LBL_TOOLS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b></td><td><span class="redColor">*</span><b><?php echo vtranslate('LBL_ITEM_NAME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b></td><td><b><?php echo vtranslate('LBL_QTY',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b></td><td><b><?php echo vtranslate('LBL_LIST_PRICE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b></td><td><b class="pull-right"><?php echo vtranslate('LBL_TOTAL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b></td><td><b class="pull-right"><?php echo vtranslate('LBL_NET_PRICE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b></td></tr><tr id="row0" class="hide lineItemCloneCopy"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("LineItemsContent.tpl",'Accounting'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('row_no'=>0,'data'=>array()), 0);?>
</tr><?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['row_no'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['RELATED_PRODUCTS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['row_no']->value = $_smarty_tpl->tpl_vars['data']->key;
?><tr id="row<?php echo $_smarty_tpl->tpl_vars['row_no']->value;?>
" class="lineItemRow" <?php if ($_smarty_tpl->tpl_vars['data']->value["entityType".($_smarty_tpl->tpl_vars['row_no']->value)]=='Products'){?>data-quantity-in-stock=<?php echo $_smarty_tpl->tpl_vars['data']->value["qtyInStock".($_smarty_tpl->tpl_vars['row_no']->value)];?>
<?php }?>><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("LineItemsContent.tpl",'Accounting'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('row_no'=>$_smarty_tpl->tpl_vars['row_no']->value,'data'=>$_smarty_tpl->tpl_vars['data']->value), 0);?>
</tr><?php } ?><?php if (count($_smarty_tpl->tpl_vars['RELATED_PRODUCTS']->value)==0){?><tr id="row1" class="lineItemRow"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("LineItemsContent.tpl",'Accounting'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('row_no'=>1,'data'=>array()), 0);?>
</tr><?php }?></table><div class="row-fluid verticalBottomSpacing"><div><?php if ($_smarty_tpl->tpl_vars['PRODUCT_ACTIVE']->value=='true'&&$_smarty_tpl->tpl_vars['SERVICE_ACTIVE']->value=='true'){?><div class="btn-toolbar"><span class="btn-group"><button type="button" class="btn addButton" id="addProduct"><i class="icon-plus icon-white"></i><strong><?php echo vtranslate('LBL_ADD_PRODUCT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></span><span class="btn-group"><button type="button" class="btn addButton" id="addService"><i class="icon-plus icon-white"></i><strong><?php echo vtranslate('LBL_ADD_SERVICE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></span></div><?php }elseif($_smarty_tpl->tpl_vars['PRODUCT_ACTIVE']->value=='true'){?><div class="btn-group"><button type="button" class="btn addButton" id="addProduct"><i class="icon-plus icon-white"></i><strong> <?php echo vtranslate('LBL_ADD_PRODUCT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div><?php }elseif($_smarty_tpl->tpl_vars['SERVICE_ACTIVE']->value=='true'){?><div class="btn-group"><button type="button" class="btn addButton" id="addService"><i class="icon-plus icon-white"></i><strong> <?php echo vtranslate('LBL_ADD_SERVICE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div><?php }?></div></div><table class="table table-bordered blockContainer lineItemTable" id="lineItemResult"><tr><td  width="83%"><div class="pull-right"><strong><?php echo vtranslate('LBL_ITEMS_TOTAL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></div></td><td><div id="netTotal" class="pull-right netTotal"><?php if (!empty($_smarty_tpl->tpl_vars['FINAL']->value['hdnSubTotal'])){?><?php echo $_smarty_tpl->tpl_vars['FINAL']->value['hdnSubTotal'];?>
<?php }else{ ?>0.00<?php }?></div></td></tr></table><input type="hidden" name="totalProductCount" id="totalProductCount" value="<?php echo $_smarty_tpl->tpl_vars['row_no']->value;?>
" /><input type="hidden" name="subtotal" id="subtotal" value="" /><input type="hidden" name="total" id="total" value="" />
<?php }} ?>