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
<div class="container-fluid">
	<div class="listViewEntriesDiv contents-bottomscroll" id="content">
		<table class="table table-bordered listViewEntriesTable">
			<tr class="listViewEntries">
				<td class="medium">
					<div style="float:left;">
						<img src="layouts/vlayout/skins/images/twitter.png" style="vertical-align:middle;"/>
						<span style="padding-left:10px;"><b>Twitter</b></span>
					</div>
					<div style="float:right;">
						{if $TWITTER_NO_CONFIG neq '1'}
							<button onclick="window.location.href=&quot;{$TWITTERREDIRECTURL}&quot;" class="btn btn-success"><strong>{if $TWITTER_LOGIN eq '1'}Login{else}Import{/if}</strong></button>
						{/if}
							<button onclick="window.location.href=&quot;{$TWITTERSETTINGURL}&quot;" class="btn btn-success"><strong>Settings</strong></button>
					</div>
				</td>
			</tr>
		</table>
	</div>
	{if $TWITTER_LOGIN neq '1'}
		<div class="listViewEntriesDiv contents-bottomscroll" style="padding:20px;" id="content">
		<br/><h3>Search People</h3><br/>
		<form action="{$SEARCHURL}" method="POST">
			<input type="text" name="search_people" id="search_people" />
			<div style="padding-right:70%;float:right;">
				<button onclick="window.location.href=&quot;{$SEARCHURL}&quot;" class="btn btn-success"><strong>Search</strong></button>
			</div>
		</form>
		</div>
	{/if}
	{/strip}
</div>