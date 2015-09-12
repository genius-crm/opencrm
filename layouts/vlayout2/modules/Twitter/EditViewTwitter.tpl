{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
{strip}
<div class='container-fluid editViewContainer'>
{if $TOTAL_RECORD > 0}
	<form class="form-horizontal recordEditView" id="EditView" name="EditView" method="post" action="index.php">
	{literal}
		<script>
			$(document).ready(function() {
				$("#create_lead").click(function() {
					$('#create').val('Leads');
				});
				$("#create_contact").click(function() {
					$('#create').val('Contacts');
				});
				$("#listViewNextPageButton").click(function() {
					var url = $('#paging_url').val() + "&cursor=" + $('#next_cursor').val();
					window.location.href = url;
				});
				$("#listViewPreviousPageButton").click(function() {
					var url = $('#paging_url').val() + "&cursor=" + $('#previous_cursor').val();
					window.location.href = url;
				});
			});
		</script>
	{/literal}
		<input type="hidden" name="total_count" value="{$TOTAL_RECORD}" />
		<input type="hidden" name="create" id="create" value="Leads" />
		<input type="hidden" name="next_cursor" id="next_cursor" value="{$NEXT_CURSOR}" />
		<input type="hidden" name="previous_cursor" id="previous_cursor" value="{$PREVIOUS_CURSOR}" />
		<input type="hidden" name="paging_url" id="paging_url" value="{$PAGING_URL}" />
		<input type="hidden" name="save_record" id="save_record" value="leads" />
		<input id="module" type="hidden" value="Twitter" name="module">
		<input type="hidden" value="Save" name="action">
		<div class="contentHeader row-fluid">
			<span class="btn-toolbar span4">
				<span class="btn-group">
					<button class="btn addButton" id="create_lead" type="submit"><strong>Create Leads</strong></button>
				</span>
				<span class="btn-group">
					<button class="btn addButton" id="create_contact" type="submit"><strong>Create Contacts</strong></button>
				</span>
			</span>
			<span class="btn-toolbar pull-right">
				<span class="btn-group">
					<button class="btn" id="listViewPreviousPageButton" {if $PREVIOUS_CURSOR eq 0}disabled="disabled"{/if} type="button"><span class="icon-chevron-left"></span></button>
					<button class="btn" id="listViewNextPageButton" {if $NEXT_CURSOR eq 0}disabled="disabled"{/if} type="button"><span class="icon-chevron-right"></span></button>
				</span>
			</span>
		</div>
		<div class="listViewEntriesDiv contents-bottomscroll">
			{assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
			<table class="table table-bordered listViewEntriesTable" style="margin-left:-3px;">
				<thead>
					<tr class="listViewHeaders">
						<th width="8%" class="{$WIDTHTYPE}">
							<input type="checkbox" id="listViewEntriesMainCheckBox" />
						</th>
						{foreach item=LISTVIEW_HEADER from=$HEADERS}
						<th nowrap class="{$WIDTHTYPE}">
							<a href="javascript:void(0);" class="listViewHeaderValues" >{$LISTVIEW_HEADER}</a>
						</th>
						{/foreach}
					</tr>
				</thead>
				{foreach key=ID item=VALUES from=$DATA}
					<tr class="listViewEntries">
						<td width="5%" class="{$WIDTHTYPE}"><input type="checkbox" value="1" name="checkbox{$ID}" class="listViewEntriesCheckBox"/></td>
						<td class="medium"><img src={$VALUES['profile_image_url']}/></td>
						<td class="medium">{$VALUES['name']}<input type="hidden" name="last_name{$ID}" value="{$VALUES['name']}" /></td>
						<td class="medium">{$VALUES['screen_name']}<input type="hidden" name="twitter_id{$ID}" value="{$VALUES['screen_name']}" /></td>
						<td class="medium">{$VALUES['location']}<input type="hidden" name="location{$ID}" value="{$VALUES['location']}" /></td>
						<td class="medium">{$VALUES['description']}<input type="hidden" name="description{$ID}" value="{$VALUES['description']}" /></td>
					</tr>
				{/foreach}
			</table>
		</div>
	</form>
{else}
	<div class="listViewEntriesDiv contents-bottomscroll">
		{assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
		<table class="table table-bordered listViewEntriesTable" style="margin-left:-3px;">
			<thead>
				<tr class="listViewHeaders">
					<th width="8%" class="{$WIDTHTYPE}">
						<input type="checkbox" id="listViewEntriesMainCheckBox" />
					</th>
					{foreach item=LISTVIEW_HEADER from=$HEADERS}
					<th nowrap class="{$WIDTHTYPE}">
						<a href="javascript:void(0);" class="listViewHeaderValues" >{$LISTVIEW_HEADER}</a>
					</th>
					{/foreach}
				</tr>
			</thead>
			<tr class="listViewEntries">
				<td colspan="6" style="text-align:center;"><strong>Oops! There is no follower.</strong></td>
			</tr>
		</table>
	</div>
{/if}
</div>
{/strip}