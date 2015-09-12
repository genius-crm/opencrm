<?php /* Smarty version Smarty-3.1.7, created on 2015-08-01 13:10:14
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Settings/LSWYSIWYG/EditView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1681114955bcc536e9b1a6-92385436%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '167b64b7cffb9abc7386367c9e4a419f0ebe41d2' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Settings/LSWYSIWYG/EditView.tpl',
      1 => 1438433155,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1681114955bcc536e9b1a6-92385436',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'MODULE_MODEL' => 0,
    'modulename' => 0,
    'module' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55bcc536ed0e6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55bcc536ed0e6')) {function content_55bcc536ed0e6($_smarty_tpl) {?>
<div class="editViewContainer container-fluid"><div class="contents"><form id="EditLSWYSIWYG" class="form-horizontal"><div class="widget_header row-fluid"><div class="span8"><h3><?php echo vtranslate('LBL_WYSIWYG_SETTINGS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h3>&nbsp;<?php echo vtranslate('LBL_WYSIWYG_CONFIG_DESCRIPTION',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div></div><hr><?php  $_smarty_tpl->tpl_vars['module'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['module']->_loop = false;
 $_smarty_tpl->tpl_vars['modulename'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getModules(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['module']->key => $_smarty_tpl->tpl_vars['module']->value){
$_smarty_tpl->tpl_vars['module']->_loop = true;
 $_smarty_tpl->tpl_vars['modulename']->value = $_smarty_tpl->tpl_vars['module']->key;
?><div class="row-fluid"><div class="control-group"><label class="control-label"><?php echo vtranslate($_smarty_tpl->tpl_vars['modulename']->value);?>
</label><div class="controls row-fluid"><div id="tabid<?php echo $_smarty_tpl->tpl_vars['module']->value['tabid'];?>
" class="modulebuttons btn-group" data-toggle="buttons"><label class="btnon btn <?php if ($_smarty_tpl->tpl_vars['module']->value['enabled']){?>btn-primary active<?php }?>"><?php echo vtranslate('LBL_MODULE_ON',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label><label class="btnoff btn <?php if (!$_smarty_tpl->tpl_vars['module']->value['enabled']){?>btn-primary active<?php }?>"><?php echo vtranslate('LBL_MODULE_OFF',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></div></div><?php } ?></form></div></div>
<script>
$(".modulebuttons").click(function(e){
//activates or deactivates our link from a module
//we do an ajax call to our service and flip a module link
    tabid=$(this).attr('id');
    operation=$(this).find('.btnon').hasClass('active') ? 'disable':'enable';
    var params = {
            'module' : app.getModuleName(),
            'parent' : app.getParentModuleName(),
            'action' : 'LSWYSIWYGSaveAjax',
            'tabid' : tabid,
            'operation':operation
    }
    AppConnector.request(params).then(
            function(data) {                    
                    //something happened, should check the data and set the toggle accordingly
                    if(data.result.enabled){
                        $('#'+data.result.tabid + ' .btnon').addClass('active btn-primary');
                        $('#'+data.result.tabid + ' .btnoff').removeClass('active btn-primary');
                        Vtiger_Helper_Js.showMessage({text:app.vtranslate('JS_WYSIWYG_ACTIVATED'),type:'info'})
                    }else{
                        $('#'+data.result.tabid + ' .btnoff').addClass('active btn-primary');
                        $('#'+data.result.tabid + ' .btnon').removeClass('active btn-primary');
                        Vtiger_Helper_Js.showMessage({text:app.vtranslate('JS_WYSIWYG_DEACTIVATED'),type:'info'})
                    }
            },
            function(error,err){
                    //the call failed, don't update the toggle, maybe do a message box
                        Vtiger_Helper_Js.showMessage({text:app.vtranslate('CHANGE_FAILED'),type:'error'})
            }
    );
    e.preventDefault();
  });
</script>

<?php }} ?>