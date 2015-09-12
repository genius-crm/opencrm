<?php /* Smarty version Smarty-3.1.7, created on 2015-08-14 13:14:55
         compiled from "/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Accounting/GetAccountingActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:41937646255cde9cf535ee8-98659411%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '053fce0fcd279a5bbc25a727d67b21d05e1599f7' => 
    array (
      0 => '/home/opencrmita/domains/opencrmitalia.com/public_html/demo38/includes/runtime/../../layouts/vlayout2/modules/Accounting/GetAccountingActions.tpl',
      1 => 1438433156,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '41937646255cde9cf535ee8-98659411',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'relation_module' => 0,
    'HEADER' => 0,
    'CURRENCY_SYMBOL' => 0,
    'INCOME_RECORDS' => 0,
    'LISTVIEW_ENTRY1' => 0,
    'CURRENCY_PLACE' => 0,
    'TOTAL_INCOME' => 0,
    'EXPENSE_RECORDS' => 0,
    'LISTVIEW_ENTRY' => 0,
    'TOTAL_EXPENSE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_55cde9cf5e55f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cde9cf5e55f')) {function content_55cde9cf5e55f($_smarty_tpl) {?>
<div  class="row-fluid">
	<div>
        <ul class="nav nav-list" >
			<li style="overflow-x:auto;overflow-y:auto;">
				<table class="table table-bordered equalSplit detailview-table">
					<thead>
						<tr>
							<th colspan="4" class="blockHeader">
								<b><?php echo vtranslate('Incoming','Accounting');?>
</b>
								<a href="index.php?module=Accounting&view=Edit&paymenttype=Income<?php echo $_smarty_tpl->tpl_vars['relation_module']->value;?>
">
									<i class="icon-plus" title="<?php echo vtranslate('Add Incoming','Accounting');?>
"></i>
								</a>
							</th>
						</tr>
					</thead>
                    <tbody>
					<tr>
						<th style="color:#000000;">
                            <b><?php echo vtranslate($_smarty_tpl->tpl_vars['HEADER']->value['accounting'],'Accounting');?>
</b>
                        </th>
						<th style="color:#000000;">
                            <b><?php echo vtranslate($_smarty_tpl->tpl_vars['HEADER']->value['total'],'Accounting');?>
(<?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
)</b>
                        </th>
                    </tr>
			<?php if ($_smarty_tpl->tpl_vars['INCOME_RECORDS']->value){?>
				<?php  $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['INCOME_RECORDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->key => $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->_loop = true;
?>
					<tr style="background-color:<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->value['COLOR_CODE'];?>
" >
						<td class="fieldValue medium">
							<span class="value" data-field-type="reference">
								<a style="padding-left: 0px; overflow: hidden; max-width: 62px; white-space: nowrap; text-overflow: ellipsis; display: table-cell;" data-original-title="Accounting" href="index.php?module=Accounting&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->value['ACCOUNTINGID'];?>
" style="padding-left: 0px;"><?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->value['accounting'];?>
</a>
							</span>
						</td>
						<td class="fieldValue medium" style="overflow: hidden; max-width: 62px; white-space: nowrap; text-overflow: ellipsis;">
							<?php if ($_smarty_tpl->tpl_vars['CURRENCY_PLACE']->value==1){?><?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->value['total'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY1']->value['total'];?>
 <?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
<?php }?>
						</td>
					</tr>
				<?php } ?>
			<?php }?>
					</tbody>
				</table>
            </li>
		<?php if ($_smarty_tpl->tpl_vars['INCOME_RECORDS']->value){?>
			<li>
				<br/><span><b><?php echo vtranslate($_smarty_tpl->tpl_vars['HEADER']->value['total'],'Accounting');?>
 <?php echo vtranslate('Incoming','Accounting');?>
 : </b><?php if ($_smarty_tpl->tpl_vars['CURRENCY_PLACE']->value==1){?><?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['TOTAL_INCOME']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['TOTAL_INCOME']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
<?php }?></span>
			</li>
		<?php }?>
		</ul>
		<br/>
	</div>
	<div>
        <ul class="nav nav-list" >
			<li style="overflow-x:auto;overflow-y:auto;">
				<table class="table table-bordered equalSplit detailview-table" width="100% !important">
					<thead>
						<tr>
							<th colspan="4" class="blockHeader">
								<b><?php echo vtranslate('Expense','Accounting');?>
</b>
								<a href="index.php?module=Accounting&view=Edit&paymenttype=Expense<?php echo $_smarty_tpl->tpl_vars['relation_module']->value;?>
">
									<i class="icon-plus" title="Add Expense"></i>
								</a>
							</th>
						</tr>
					</thead>
                    <tbody>
					<tr>
						<th style="color:#000000;">
                            <b><?php echo vtranslate($_smarty_tpl->tpl_vars['HEADER']->value['accounting'],'Accounting');?>
</b>
                        </th>
						<th style="color:#000000;">
                            <b><?php echo vtranslate($_smarty_tpl->tpl_vars['HEADER']->value['total'],'Accounting');?>
(<?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
)</b>
                        </th>
                    </tr>
			<?php if ($_smarty_tpl->tpl_vars['EXPENSE_RECORDS']->value){?>
				<?php  $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['EXPENSE_RECORDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->key => $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = true;
?>
					<tr style="background-color:<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value['COLOR_CODE'];?>
" >
						<td class="fieldValue medium">
							<span class="value" data-field-type="reference">
								<a style="padding-left: 0px; overflow: hidden; max-width: 62px; white-space: nowrap; text-overflow: ellipsis; display: table-cell;" data-original-title="Accounting" href="index.php?module=Accounting&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value['ACCOUNTINGID'];?>
" style="padding-left: 0px;"><?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value['accounting'];?>
</a>
							</span>
						</td>
						<td class="fieldValue medium" style="overflow: hidden; max-width: 62px; white-space: nowrap; text-overflow: ellipsis;">
							<?php if ($_smarty_tpl->tpl_vars['CURRENCY_PLACE']->value==1){?><?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value['total'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value['total'];?>
 <?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
<?php }?>
						</td>
					</tr>
				<?php } ?>
			<?php }?>
					</tbody>
				</table>
            </li>
			<?php if ($_smarty_tpl->tpl_vars['EXPENSE_RECORDS']->value){?>
				<li>
					<br/><span><b><?php echo vtranslate($_smarty_tpl->tpl_vars['HEADER']->value['total'],'Accounting');?>
 <?php echo vtranslate('Expense','Accounting');?>
 : </b><?php if ($_smarty_tpl->tpl_vars['CURRENCY_PLACE']->value==1){?><?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['TOTAL_EXPENSE']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['TOTAL_EXPENSE']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['CURRENCY_SYMBOL']->value;?>
<?php }?> </span>	
				</li>
			<?php }?>
		</ul>
		<br/>
	</div>

</div>

<script>
jQuery(document).ready(function() {
	var references = jQuery.merge(jQuery('[data-field-type="reference"] > a'), jQuery('[data-field-type="multireference"] > a'));
	var lastPopovers = [];

	// Fetching reference fields often is not a good idea on a given page.
	// The caching is done based on the URL so we can reuse.
	var CACHE_ENABLED = true; // TODO - add cache timeout support.

	function prepareAndShowTooltipView() {
		hideAllTooltipViews();

		var el = jQuery(this);
		var url = el.attr('href')? el.attr('href') : '';
		if (url == '') {
			return;
		}

		// Rewrite URL to retrieve Tooltip view.
		url = url.replace('view=', 'xview=') + '&view=TooltipAjax';

		var cachedView = CACHE_ENABLED ? jQuery('[data-url-cached="'+url+'"]') : null;
		if (cachedView && cachedView.length) {
			showTooltip(el, cachedView.html());
		} else {
			AppConnector.request(url).then(function(data){
				cachedView = jQuery('<div>').css({display:'none'}).attr('data-url-cached', url);
				cachedView.html(data);
				jQuery('body').append(cachedView);
				showTooltip(el, data);
			});
		}
	}

	function showTooltip(el, data) {
		el.popover({
			//title: '', - Is derived from the Anchor Element (el).
			trigger: 'manual',
			content: data,
			animation: false,
			template: '<div class="popover popover-tooltip"><div class="arrow"></div><div class="popover-inner"><button name="vtTooltipClose" class="close" style="color:white;opacity:1;font-weight:lighter;position:relative;top:3px;right:3px;">x</button><h3 class="popover-title"></h3><div class="popover-content"><div></div></div></div></div>'
		});
		lastPopovers.push(el.popover('show'));
		registerToolTipDestroy();
	}

	function hideAllTooltipViews() {
		// Hide all previous popover
		var lastPopover = null;
		while (lastPopover = lastPopovers.pop()) {
			lastPopover.popover('hide');
		}
	}

	references.each(function(index, el){
		jQuery(el).hoverIntent({
			interval: 100,
			sensitivity: 7,
			timeout: 10,
			over: prepareAndShowTooltipView,
			out: hideAllTooltipViews
		});
	});

	function registerToolTipDestroy() {
		jQuery('button[name="vtTooltipClose"]').on('click', function(e){
			var lastPopover = lastPopovers.pop();
			lastPopover.popover('hide');
		});
	}
});
</script>

<?php }} ?>