<?php /* Smarty version Smarty-3.1.7, created on 2015-08-07 08:21:44
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/ChartsWidget/BasicHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:92027325455c46a98c326c2-05112217%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e09314efa591635d2df98e22cf2db8d9ed27c310' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/ChartsWidget/BasicHeader.tpl',
      1 => 1435691423,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92027325455c46a98c326c2-05112217',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'VIEW_TYPE' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55c46a98c99a2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55c46a98c99a2')) {function content_55c46a98c99a2($_smarty_tpl) {?>
<div class="navbar navbar-fixed-top  navbar-inverse noprint"><?php if ($_smarty_tpl->tpl_vars['VIEW_TYPE']->value!='ChartDetail'){?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('MenuBar.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('CommonActions.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['VIEW_TYPE']->value=='ChartDetail'){?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('MenuBar.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('CommonActions.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }?></div><?php }} ?>