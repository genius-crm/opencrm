<?php /* Smarty version Smarty-3.1.7, created on 2015-08-14 13:15:01
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Accounting/EditView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:41518979755cde9d5449cf6-35639070%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4aac6cc15dbe4990975cfadc38b71a692ade3aa9' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Accounting/EditView.tpl',
      1 => 1438433156,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '41518979755cde9d5449cf6-35639070',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55cde9d54e818',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cde9d54e818')) {function content_55cde9d54e818($_smarty_tpl) {?>

<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("EditViewBlocks.tpl",'Accounting'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("LineItemsEdit.tpl",'Accounting'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("EditViewActions.tpl",'Vtiger'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>