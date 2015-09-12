<?php /* Smarty version Smarty-3.1.7, created on 2015-09-08 05:29:45
         compiled from "D:\xampp\htdocs\demo38\includes\runtime/../../layouts/vlayout2\modules\Vtiger\EditView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1603555ee72496b3156-26605659%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aacb8582f6229a2871097366ca1e547c3e473c59' => 
    array (
      0 => 'D:\\xampp\\htdocs\\demo38\\includes\\runtime/../../layouts/vlayout2\\modules\\Vtiger\\EditView.tpl',
      1 => 1441175211,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1603555ee72496b3156-26605659',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55ee72496c2b5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ee72496c2b5')) {function content_55ee72496c2b5($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("EditViewBlocks.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("EditViewActions.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>