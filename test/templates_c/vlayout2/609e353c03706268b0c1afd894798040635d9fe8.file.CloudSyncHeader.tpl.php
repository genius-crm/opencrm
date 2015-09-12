<?php /* Smarty version Smarty-3.1.7, created on 2015-09-01 07:16:12
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/CloudSync/CloudSyncHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:212251562255e550bce96a34-18886049%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '609e353c03706268b0c1afd894798040635d9fe8' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/CloudSync/CloudSyncHeader.tpl',
      1 => 1438433163,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '212251562255e550bce96a34-18886049',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'VIEW' => 0,
    'SCRIPTS' => 0,
    'jsModel' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55e550bceaeff',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e550bceaeff')) {function content_55e550bceaeff($_smarty_tpl) {?><!-- adding javascript -->
<?php if ($_smarty_tpl->tpl_vars['VIEW']->value!='Settings'){?>
	<?php  $_smarty_tpl->tpl_vars['jsModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['jsModel']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SCRIPTS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['jsModel']->key => $_smarty_tpl->tpl_vars['jsModel']->value){
$_smarty_tpl->tpl_vars['jsModel']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['jsModel']->key;
?>
        	<script type="<?php echo $_smarty_tpl->tpl_vars['jsModel']->value->getType();?>
" src="<?php echo $_smarty_tpl->tpl_vars['jsModel']->value->getSrc();?>
"></script>
	<?php } ?>
<?php }?>
<!-- adding font awesome style -->
<link rel="stylesheet" type="text/css" href="modules/CloudSync/public/css/font-awesome.css" media="screen" />
<link rel="stylesheet" type="text/css" href="modules/CloudSync/lib/jQueryFileTree/jqueryFileTree.css" media="screen" />
<?php }} ?>