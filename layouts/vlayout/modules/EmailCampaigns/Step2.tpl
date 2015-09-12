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
	<form name="EmailCampaigns_step2" action="index.php" method="post" id="EmailCampaigns_step2" class="form-horizontal">
			<input type="hidden" name="module" value="EmailCampaigns">
            <input type="hidden" name="view" value="Edit">
            <input type="hidden" name="mode" value="Step3" />
            <input type="hidden" class="step" value="2" />
			<input type="hidden" id="from_email" name="from_email" value="{$from_email}" />
			<input type="hidden" id="from_name" name="from_name" value="{$from_name}" />
			<input type="hidden" id="campaign_name" name="campaign_name" value="{$campaign_name}" />
			<input type="hidden" id="assigned_user_id" name="assigned_user_id" value="{$assigned_user_id}" />
			<input type="hidden" id="track_opens" name="track_opens" value="{$track_opens}" />
			<input type="hidden" id="track_clicks" name="track_clicks" value="{$track_clicks}" />
			<input type="hidden" id="track_bounces" name="track_bounces" value="{$track_bounces}" />
			<input type="hidden" id="track_complaints" name="track_complaints" value="{$track_complaints}" />
			<input type="hidden" id="batch" name="batch" value="{$batch}" />
			<input type="hidden" id="emailcamp_interval" name="emailcamp_interval" value="{$emailcamp_interval}" />
			<input type="hidden" name="conditions" id="advanced_filter" value='' />
			<input type="hidden" name="list_name1" id="list_name1" value='' />
			<input type="hidden" id="record" name="record" value="{$RECORD_ID}" />
			<input type="hidden" id="reply_to" name="reply_to" value="{$reply_to}" />
			<input type="hidden" id="unsubscribe_option" name="unsubscribe_option" value="{$unsubscribe_option}" />
			<input type="hidden" id="related_recorts" name="related_recorts" value="{$related_recorts}" />
			
			
			<div class="padding1per" style="border:1px solid #ccc;">
				{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name="EditViewBlockLevelLoop"}
					{if $BLOCK_LABEL eq 'List Information'}
					{if $BLOCK_FIELDS|@count lte 0}{continue}{/if}
					{if $FIELD_NAME neq 'module_name' && $FIELD_NAME neq 'conditions'  && $FIELD_NAME neq 'related_recorts'}
						<label>
							<strong>{vtranslate($BLOCK_LABEL, $MODULE)}</strong>
						</label>
					{/if}	
					<br>
						{assign var=srno value=1}
						{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
						{if $FIELD_NAME neq 'module_name' && $FIELD_NAME neq 'conditions' && $FIELD_NAME neq 'subscribers_list' && $FIELD_NAME neq 'parent_emailcampaignsid' && $FIELD_NAME neq 'related_recorts'}
							<div class="control-group" {if $select_option neq 'Start from past campaign' && $FIELD_NAME neq 'select_option'} style="display:none;" {/if} id="{$FIELD_NAME}">
								<div class="control-label" style="width:148px;">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								</div>
								<div class="controls">
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
								</div>
							</div>
						{elseif $FIELD_NAME eq 'subscribers_list' }	
								<div class="control-group" {if $select_option eq 'Start from past campaign'} style="display:none;" {/if} id="subscriber_select">

								<div class="control-label" style="width:148px;">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								</div>
								<div class="controls">
								
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
								</div>
							</div>
						{elseif $FIELD_NAME eq 'parent_emailcampaignsid'}	
								<div class="control-group" {if $select_option neq 'Start from past campaign'} style="display:none;" {/if} id="past_emailcampaign">
								<div class="control-label" style="width:148px;">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								</div>
								<div class="controls">
													
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
								</div>
							</div>
						{/if}
						{assign var=srno value=$srno+1}
						{/foreach}
					{/if}
					
				{/foreach}		
							<div class="control-group">
								<div class="control-label" style="display:none;">
									{'Primary Module'}
								</div>
								<div class="controls" style="display:none;">
									<select class="chzn-select" style="width:140px;"  name="searchmodulelist" id="searchModuleList" data-placeholder="{vtranslate('LBL_SELECT_MODULE')}">
										<option></option>
										{foreach key=MODULE_NAME item=fieldObject from=$SEARCHABLE_MODULES}
											<option value="{$MODULE_NAME}" readonly="true" {if $MODULE_NAME eq $SOURCE_MODULE}selected="selected"{/if}>{vtranslate($MODULE_NAME,$MODULE_NAME)}</option>
										{/foreach}
									</select>		
								</div>	
							</div>	
								
							
			<div class="row-fluid padding1per contentsBackground" id="filter_condition" {if $select_option eq 'Start from past campaign'} style="display:none;" {else} style="border:1px solid #ccc;box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5); width:1000px;" {/if}>
				
				<div id="advanceFilterContainer" class="conditionsContainer padding1per">
					<h5 class="padding-bottom1per"><strong>{vtranslate('LBL_CHOOSE_FILTER_CONDITIONS',$MODULE)}</strong></h5>
					<span class="span10" >
						{include file='AdvanceFilter.tpl'|@vtemplate_path RECORD_STRUCTURE=$RECORD_STRUCTURE1}
					</span>
				</div>
			</div>
				<div class="subscriberBlock" style="margin-top:15px;">
					<span style="font-size:large" class="subscribersCount cursorPointer themeColor" data-id="1">
						<span id="filter_1" data-id="1">0</span>
						<span > {vtranslate(Subscribers, $MODULE)}</span>
					</span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button class="btn btn-success filterClick" type="button">{vtranslate(Apply, $MODULE)}</button> 
				</div>

				<div class="pull-right">
					
					<button class="btn btn-danger backStep" type="button"><strong>{vtranslate('LBL_BACK', 'EmailCampaigns')}</strong></button>&nbsp;&nbsp;
					<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_NEXT', 'EmailCampaigns')}</strong></button>
					<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', 'EmailCampaigns')}</a>
				</div>
				<div class="clearfix"></div>
		</form>
<table id="howto" {if $select_option eq 'Start from past campaign'} style="display:none;" {/if}>
<body>
<tr>
<td>	
<h3>HOW TO</h3>
</td>
</tr>
<tr>
<td>1. To select a list click on <img src="layouts/vlayout/modules/EmailCampaigns/images/search.JPG" height="20px" width="20px" /><br/>&nbsp;&nbsp;To create a new list click on <img src="layouts/vlayout/modules/EmailCampaigns/images/add.JPG" height="20px" width="20px" /></td>
</tr>
<tr>
<td>2. click <img src="layouts/vlayout/modules/EmailCampaigns/images/process.JPG" height="20px" width="20px" /> to refresh condition</td>
</tr>
<tr>
<td>3. click <button class="btn btn-success" type="button" style="width:50px; height:25px; font-size:10px;">{vtranslate(Apply, $MODULE)}</button> to confirm condition(you get number of subscriber)</td>
</tr>
<tr>
<td>4. click on <button class="btn btn-success" type="button" style="width:60px; height:25px; font-size:10px;"><strong>{vtranslate('LBL_NEXT', 'EmailCampaigns')}</strong></button></td>
</tr>
</body>
</table>
				
{/strip}
