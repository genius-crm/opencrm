{strip}	
	<div class="listViewTopMenuDiv noprint">
		<div class="listViewActionsDiv row-fluid">
			<span class="btn-toolbar">
				<span class="pull-right listViewActions">
					<span class="pageNumber alignTop" style="margin-right:10px; position: relative; top: 5px;">{$DISPLAYTOTALPAGE}</span>
					<span class="btn-group alignTop">
							<button type="button" {$DISABLEPRE} id="SubscriberlistViewPreviousPageButton" class="btn">
								<span class="icon-chevron-left"></span>
							</button>
							<button type="button" {$DISABLENXT} id="SubscriberlistViewNextPageButton" class="btn">
								<span class="icon-chevron-right"></span>
							</button>
					</span>
				</span>
				<div class="clearfix"></div>
				<input type="hidden" value="{$PAGE}" id="pageCount">
				<input type="hidden" value="{$LISTTYPE}" id="listtype">
			</span>
		</div>
	</div>
	<table class="table table-bordered listViewEntriesTable">
		<thead>
			<tr class="listViewHeaders">
				<th nowrap="" class="medium">
					<a data-columnname="link_name" class="listViewHeaderValues" href="javascript:void(0);">{vtranslate({$RELATED_MODULE}, $RELATED_MODULE)}</a>
				</th>
				<th nowrap="" class="medium">
					<a data-columnname="unique_count" class="listViewHeaderValues" href="javascript:void(0);">{vtranslate('Email', $MODULE)}</a>
				</th>
			</tr>
		</thead>
		<tbody>
			{foreach key=COUNT_NO item=FIELD_MODEL from=$RECORD_DATA}
				<tr class="listViewEntries" id="EmailCampaigns_reportList_row_{$COUNT_NO}" data-recordurl="{$link}={$FIELD_MODEL[2]}" data-id="{$FIELD_MODEL[2]}">
					<td nowrap="" class="listViewEntryValue medium" data-field-name="name">
						<span>{$FIELD_MODEL[0]}</span>
					</td>
					<td nowrap="" class="listViewEntryValue medium" data-field-name="email">
						<span>{$FIELD_MODEL[1]}</span>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{/strip}
