<div>
	<div class="row-fluid">
		<div class="span5">{vtranslate('summary_count', $MODULENAME)}: {$RECOLDLIST['summary'][0]['count']}</div>
		<div class="span7">{vtranslate('summary_sum', $MODULENAME)}: {CurrencyField::convertToUserFormat( $RECOLDLIST['summary'][0]['sum'] )}</div>
	</div>
	<hr/>
	<div class="row-fluid">
		<div class="span6">{vtranslate('Costs_no', $MODULENAME)}</div>
		<div class="span6">{vtranslate('Total', $MODULENAME)}</div>
	</div>
	{foreach from=$RECOLDLIST['rows'] key=key item=item}
		<div class="row-fluid">
			<div class="span6">{$item['osscosts_no']}</div>
			<div class="span6">{CurrencyField::convertToUserFormat( $item['total'] )}</div>
		</div>
	{/foreach}
</div>