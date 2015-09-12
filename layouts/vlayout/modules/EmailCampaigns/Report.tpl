{strip}
	<input type = "hidden" value = "{$RECORDID}" name = "recordId" id = "recordId" />
	<table class="table table-bordered equalSplit detailview-table">
		<thead>
			<tr>
				<th colspan="4" class="blockHeader">{vtranslate('Email Campaign Report', $MODULE)}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td id="EmailCampaigns_detailView_fieldLabel_mailsent" class="fieldLabel medium">
					<label class="muted pull-right marginRight10px">{vtranslate('Mail Sent', $MODULE)}</label>
				</td>
				<td id="EmailCampaigns_detailView_fieldValue_mailsent" class="fieldValue medium">
					<span data-field-type="salutation" class="value">{$SENTMAILS}</span>
				</td>
			</tr>
			<tr>
				<td id="EmailCampaigns_detailView_fieldLabel_unique_open" class="fieldLabel medium">
					<label class="muted pull-right marginRight10px">{vtranslate('Unique Open', $MODULE)}</label>
				</td>
				<td id="EmailCampaigns_detailView_fieldValue_unique_open" class="fieldValue medium">
					<span data-field-type="string" class="value">{$UNIQUEOPEN}</span>
				</td>
			</tr>
			<tr>
				<td id="EmailCampaigns_detailView_fieldLabel_unique_open_per" class="fieldLabel medium">
					<label class="muted pull-right marginRight10px">{vtranslate('Unique Open(in %)', $MODULE)}</label>
				</td>
				<td id="EmailCampaigns_detailView_fieldValue_unique_open_per" class="fieldValue medium">
					<span data-field-type="string" class="value">{$UNIQUEOPENPERCENT}</span>
				</td>
			</tr>
			<tr>
				<td id="EmailCampaigns_detailView_fieldLabel_subscribers" class="fieldLabel medium">
					<label class="muted pull-right marginRight10px">{vtranslate('Subscribers', $MODULE)}</label>
				</td>
				<td id="EmailCampaigns_detailView_fieldValue_subscribers" class="fieldValue medium">
					<span data-field-type="string" class="value">{$SUBSCRIBER}</span>
				</td>
			</tr>
			<tr>
				<td id="EmailCampaigns_detailView_fieldLabel_unsubscribers" class="fieldLabel medium">
					<label class="muted pull-right marginRight10px">{vtranslate('UnSubscribers', $MODULE)}</label>
				</td>
				<td id="EmailCampaigns_detailView_fieldValue_unsubscribers" class="fieldValue medium">
					<span data-field-type="string" class="value">{$UNSUBSCRIBER}</span>
				</td>
			</tr>
		</tbody>
	</table><br>
	<table class="table table-bordered listViewEntriesTable">
		<thead>
			<tr class="listViewHeaders">
				<th nowrap="" class="medium">
					<a data-columnname="link_number" class="listViewHeaderValues" href="javascript:void(0);">{vtranslate('No', $MODULE)}</a>
				</th>
				<th nowrap="" class="medium">
					<a data-columnname="link_name" class="listViewHeaderValues" href="javascript:void(0);">{vtranslate('Link Name', $MODULE)}</a>
				</th>
				<th nowrap="" class="medium" colspan="2">
					<a data-columnname="unique_count" class="listViewHeaderValues" href="javascript:void(0);">{vtranslate('Count (Unique Click)', $MODULE)}</a>
				</th>
			</tr>
		</thead>
		<tbody>
		{assign var="TOTAL_COUNT" value="0"}
			{foreach key=COUNT_NO item=FIELD_MODEL from=$LINK_LIST}
				{assign var="TOTAL_COUNT" value=$TOTAL_COUNT + $FIELD_MODEL[1]}
				<tr class="listViewEntries" id="EmailCampaigns_listView_row_{$COUNT_NO}">
					<td nowrap="" class="listViewEntryValue medium" data-field-name="link_number">
						<span>{$COUNT_NO}</span>
					</td>
					<td nowrap="" class="listViewEntryValue medium" data-field-name="link_name">
						<span>{$FIELD_MODEL[0]}</span>
					</td>
					<td nowrap="" class="listViewEntryValue medium" data-field-name="unique_count">
						<span>{$FIELD_MODEL[1]} ({$FIELD_MODEL[2]}unique)</span>
					</td>
				</tr>
			{/foreach}
			<tr class="listViewEntries" id="EmailCampaigns_listView_row_totalCount">
				<td nowrap="" class="listViewEntryValue medium"></td>
				<td nowrap="" class="listViewEntryValue medium">
					<span>{vtranslate('Total Count', $MODULE)}</span>
				</td>
				<td nowrap="" class="listViewEntryValue medium" data-field-name="link_number">
					<span class="value" data-field-type="string">{$TOTAL_COUNT}</span>
				</td>
			</tr>
		</tbody>
	</table>
	<br/>
	<div id = "load_result"></div>
{/strip}
