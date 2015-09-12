{*
/*********************************************************************************
 * This file will list the records in widget as per module selection from CRM setting
 * All Rights Reserved.
 ********************************************************************************/
*}
<div  class="row-fluid">

	<div>
        <ul class="nav nav-list" >
		<a href="index.php?module=Accounting&view=Edit&&accounting=Carburante&paymenttype=Expense{$relation_module}">
			<b>Carburante</b>
			<i class="icon-plus" title="Add Expense"></i>
		</a><br>
		<a href="index.php?module=Accounting&view=Edit&&accounting=Assicurazione&paymenttype=Expense{$relation_module}">
			<b>Assicurazione</b>
			<i class="icon-plus" title="Add Expense"></i>
		</a><br>
		<a href="index.php?module=Accounting&view=Edit&&accounting=Bollo&paymenttype=Expense{$relation_module}">
			<b>Bollo</b>
			<i class="icon-plus" title="Add Expense"></i>
		</a><br>
		<a href="index.php?module=Accounting&view=Edit&&accounting=Tagliando/manutenzione&paymenttype=Expense{$relation_module}">
			<b>Tagliando/manutenzione</b>
			<i class="icon-plus" title="Add Expense"></i>
		</a><br>
			<li style="overflow-x:auto;overflow-y:auto;">
				<table class="table table-bordered equalSplit detailview-table" width="100% !important">
                    <tbody>
					<tr>
						<th style="color:#000000;">
                            <b>{vtranslate($HEADER['accounting'], 'Accounting')}</b>
                        </th>
						<th style="color:#000000;">
                            <b>{vtranslate($HEADER['total'], 'Accounting')}({$CURRENCY_SYMBOL})</b>
                        </th>
                    </tr>
			{if $EXPENSE_RECORDS}
				{foreach item=LISTVIEW_ENTRY from=$EXPENSE_RECORDS name=listview}
					<tr style="background-color:{$LISTVIEW_ENTRY['COLOR_CODE']}" >
						<td class="fieldValue medium">
							<span class="value" data-field-type="reference">
								<a style="padding-left: 0px; overflow: hidden; max-width: 62px; white-space: nowrap; text-overflow: ellipsis; display: table-cell;" data-original-title="Accounting" href="index.php?module=Accounting&view=Detail&record={$LISTVIEW_ENTRY['ACCOUNTINGID']}" style="padding-left: 0px;">{$LISTVIEW_ENTRY['accounting']}</a>
							</span>
						</td>
						<td class="fieldValue medium" style="overflow: hidden; max-width: 62px; white-space: nowrap; text-overflow: ellipsis;">
							{if $CURRENCY_PLACE eq 1 }{$CURRENCY_SYMBOL} {$LISTVIEW_ENTRY['total']}{else}{$LISTVIEW_ENTRY['total']} {$CURRENCY_SYMBOL}{/if}
						</td>
					</tr>
				{/foreach}
			{/if}
					</tbody>
				</table>
            </li>
			{if $EXPENSE_RECORDS}
				<li>
					<br/><span><b>{vtranslate($HEADER['total'], 'Accounting')} {vtranslate('Expense', 'Accounting')} : </b>{if $CURRENCY_PLACE eq 1 }{$CURRENCY_SYMBOL} {$TOTAL_EXPENSE}{else}{$TOTAL_EXPENSE} {$CURRENCY_SYMBOL}{/if} </span>	
				</li>
			{/if}
		</ul>
		<br/>
	</div>

</div>
{literal}
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
{/literal}
