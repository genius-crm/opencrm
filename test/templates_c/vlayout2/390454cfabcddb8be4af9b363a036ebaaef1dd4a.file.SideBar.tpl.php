<?php /* Smarty version Smarty-3.1.7, created on 2015-09-08 05:29:26
         compiled from "D:\xampp\htdocs\demo38\includes\runtime/../../layouts/vlayout2\modules\Vtiger\SideBar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2049655ee7236386772-70876925%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '390454cfabcddb8be4af9b363a036ebaaef1dd4a' => 
    array (
      0 => 'D:\\xampp\\htdocs\\demo38\\includes\\runtime/../../layouts/vlayout2\\modules\\Vtiger\\SideBar.tpl',
      1 => 1441175179,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2049655ee7236386772-70876925',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55ee72363a1d0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ee72363a1d0')) {function content_55ee72363a1d0($_smarty_tpl) {?>
<div class="sideBarContents"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarLinks.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<div class="clearfix"></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarWidgets.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><?php }} ?>