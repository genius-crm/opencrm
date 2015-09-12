<?php /* Smarty version Smarty-3.1.7, created on 2015-09-08 05:29:37
         compiled from "D:\xampp\htdocs\demo38\includes\runtime/../../layouts/vlayout2\modules\Vtiger\uitypes\SalutationDetailView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3147055ee72412cc956-13549427%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4351d4c03feea1dbf410beb85460ff66a1c411f4' => 
    array (
      0 => 'D:\\xampp\\htdocs\\demo38\\includes\\runtime/../../layouts/vlayout2\\modules\\Vtiger\\uitypes\\SalutationDetailView.tpl',
      1 => 1441175264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3147055ee72412cc956-13549427',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'RECORD' => 0,
    'FIELD_MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55ee72412e405',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ee72412e405')) {function content_55ee72412e405($_smarty_tpl) {?>


<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getDisplayValue('salutationtype');?>


<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'),$_smarty_tpl->tpl_vars['RECORD']->value->getId(),$_smarty_tpl->tpl_vars['RECORD']->value);?>
<?php }} ?>