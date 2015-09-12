<?php /* Smarty version Smarty-3.1.7, created on 2015-07-15 12:48:06
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/EmailCampaigns/EditHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:88519142755a65686e63d21-29144312%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a3c2db6e0327e02b44f5a372e54da8c5d2cfe5c1' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/EmailCampaigns/EditHeader.tpl',
      1 => 1435691423,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '88519142755a65686e63d21-29144312',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'RECORDID' => 0,
    'SINGLE_MODULE_NAME' => 0,
    'RECORD_STRUCTURE_MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55a65686eb166',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55a65686eb166')) {function content_55a65686eb166($_smarty_tpl) {?>
<div class="editContainer" style="padding-left: 3%;padding-right: 3%"><?php $_smarty_tpl->tpl_vars['SINGLE_MODULE_NAME'] = new Smarty_variable(('SINGLE_').($_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><h3><?php if ($_smarty_tpl->tpl_vars['RECORDID']->value==''){?><?php echo vtranslate('LBL_CREATING_NEW',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate($_smarty_tpl->tpl_vars['SINGLE_MODULE_NAME']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }else{ ?><?php echo vtranslate('LBL_EDITING',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate($_smarty_tpl->tpl_vars['SINGLE_MODULE_NAME']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 - <?php echo $_smarty_tpl->tpl_vars['RECORD_STRUCTURE_MODEL']->value->getRecordName();?>
<?php }?></h3><hr><div id="breadcrumb"><ul class="crumbs marginLeftZero"><li class="step"  style="z-index:9" id="step1"><a href="#"><span class="stepNum">1</span><span class="stepText"><?php echo vtranslate('LBL_1ST_STEP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></a></li><li style="z-index:8" class="step" id="step2"><a href="#"><span class="stepNum">2</span><span class="stepText"><?php echo vtranslate('LBL_2ST_STEP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></a></li><li class="step" style="z-index:7" id="step3"><a href="#"><span class="stepNum">3</span><span class="stepText"><?php echo vtranslate('LBL_3ST_STEP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></a></li><li class="step last" style="z-index:6" id="step4"><a href="#"><span class="stepNum">4</span><span class="stepText"><?php echo vtranslate('LBL_4ST_STEP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></a></li></ul></div><div class="clearfix"></div></div>
<?php }} ?>