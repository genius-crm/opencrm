<?php /* Smarty version Smarty-3.1.7, created on 2015-09-01 07:25:54
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Potentials/DetailViewHeaderTitle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:76350594355a68b7072d089-61976834%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e3d1a5d6672ce299a0a15fd190dcbc8c169bedfd' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Potentials/DetailViewHeaderTitle.tpl',
      1 => 1438173562,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '76350594355a68b7072d089-61976834',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55a68b7074eb8',
  'variables' => 
  array (
    'RECORD' => 0,
    'MODULE_MODEL' => 0,
    'NAME_FIELD' => 0,
    'FIELD_MODEL' => 0,
    'RELATED_TO' => 0,
    'MODULE_NAME' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55a68b7074eb8')) {function content_55a68b7074eb8($_smarty_tpl) {?>
<span class="span2"><img src="<?php echo vimage_path('summary_Oppurtunities.png');?>
" class="summaryImg" /></span><span class="span8 margin0px"><span class="row-fluid"><h4 class="recordLabel pushDown" title="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getName();?>
"><?php  $_smarty_tpl->tpl_vars['NAME_FIELD'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['NAME_FIELD']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getNameFields(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['NAME_FIELD']->key => $_smarty_tpl->tpl_vars['NAME_FIELD']->value){
$_smarty_tpl->tpl_vars['NAME_FIELD']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getField($_smarty_tpl->tpl_vars['NAME_FIELD']->value), null, 0);?><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getPermissions()){?><span class="<?php echo $_smarty_tpl->tpl_vars['NAME_FIELD']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['RECORD']->value->get($_smarty_tpl->tpl_vars['NAME_FIELD']->value);?>
</span>&nbsp;<?php }?><?php } ?></h4></span><?php $_smarty_tpl->tpl_vars['RELATED_TO'] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD']->value->get('related_to'), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['RELATED_TO']->value)){?><span class="row-fluid"><span class="muted"><?php echo vtranslate('Related to',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
 - </span><?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getDisplayValue('related_to');?>
</span><?php }?></span><?php }} ?>