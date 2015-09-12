<?php /* Smarty version Smarty-3.1.7, created on 2015-09-01 07:16:12
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/CloudSync/DetailViewWidget.tpl" */ ?>
<?php /*%%SmartyHeaderCode:122495826555e550bce08de3-63846715%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8b149d90e5c5026ff1f7d3ed87dc6be825a02bbc' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/CloudSync/DetailViewWidget.tpl',
      1 => 1438433163,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122495826555e550bce08de3-63846715',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'show_widget' => 0,
    'source_module' => 0,
    'record' => 0,
    'assigned_user_id_html' => 0,
    'token_error_message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55e550bce92c6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e550bce92c6')) {function content_55e550bce92c6($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('CloudSyncHeader.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php if ($_smarty_tpl->tpl_vars['show_widget']->value==true){?>
<div class = 'row-fluid'>
        <div style = 'display: inline-block'>
		<!-- show  -->
		<?php if ($_smarty_tpl->tpl_vars['source_module']->value=='Documents'){?>
                <ul class = 'nav nav-list'>
                        <li> <button class = 'btn' onclick = 'syncToDropboxDetailView("<?php echo $_smarty_tpl->tpl_vars['record']->value;?>
")'> Upload Document to <i class="fa fa-dropbox fa-lg"></i> </button> </li>
                </ul>
                <br>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['source_module']->value!='Documents'){?>
                <ul class = 'nav nav-list'>
                        <li> 
				<div style = 'display:inline-block;'>
				<div style = 'float:left;'> <button class = 'btn' onclick = 'showDropboxFiles("/")'> Select files from <i class="fa fa-dropbox fa-lg"></i> </button> </div>
				<div style = 'float:right;'> <span class = 'helpIcon' style = 'padding-left:3px;'> <i class="fa fa-question-circle fa-lg" data-toggle="tooltip" data-placement="right" title="Clicking this button will show list of files in Dropbox. Selected file will be created in Vtiger as Document and created Document will be related to the current record"></i> </span> </div>
				</div>
			</li>
                </ul>
		<?php }?>
        </div>
</div>
<?php if ($_smarty_tpl->tpl_vars['source_module']->value!='Documents'){?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="DropboxFiles" aria-hidden="true">
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title" id="DropboxFiles"> <i class="fa fa-dropbox fa-2x" style = 'font-size: 1.5em; color: #007EE5;'> Dropbox Files </i> <span style = 'float:right; display:block;' id = 'loadinggif_modalheader'><i class="fa fa-circle-o-notch fa-spin fa-lg"></i> </span></h4>
                        </div>
                        <div class="modal-body" style = 'max-height: 350px; overflow: auto;'>
                                <div id = 'showDropboxView' class = 'filetree'> </div>
                        </div>
                        <div class="modal-footer">
                                <div id = 'filetree_notification'> </div>
                                <div> 
					<span class = 'pull-left'> 
						<label> <strong> Assigned To </strong> </label> 
					</span> 
					<span class = 'pull-left' style = 'padding-left: 3%;'> <?php echo $_smarty_tpl->tpl_vars['assigned_user_id_html']->value;?>
 </span> 
					<span> <button type="button" class="btn btn-default" data-dismiss="modal" onclick = 'location.reload()'> Close </button> </span>
				</div>
                        </div>
                </div>
        </div>
</div>
<?php }?>
<?php }else{ ?>
        <div class = 'alert alert-warning' style = 'padding: 5px; margin-bottom: 0px;'> <?php echo vtranslate($_smarty_tpl->tpl_vars['token_error_message']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <br> <a href = 'index.php?module=CloudSync&view=Settings'> Go to CloudSync Settings </a> </div>
<?php }?>
<?php }} ?>