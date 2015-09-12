<?php /* Smarty version Smarty-3.1.7, created on 2015-07-30 10:14:37
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Vtiger/DetailViewFullContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:121956545855a68b817e9098-77586497%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f4dd2f1410425a452562cee18db7cf128ce357d3' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Vtiger/DetailViewFullContents.tpl',
      1 => 1438173563,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '121956545855a68b817e9098-77586497',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55a68b8181bf2',
  'variables' => 
  array (
    'MODULE_NAME' => 0,
    'RECORD_STRUCTURE' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55a68b8181bf2')) {function content_55a68b8181bf2($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('DetailViewBlockView.tpl',$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('RECORD_STRUCTURE'=>$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value,'MODULE_NAME'=>$_smarty_tpl->tpl_vars['MODULE_NAME']->value), 0);?>
<?php }} ?>