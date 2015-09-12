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
<form name="EditEmailCampaigns" action="index.php" method="post" id="EmailCampaigns_step3" class="form-horizontal">
		<input type="hidden" name="module" value="EmailCampaigns" />
		 <input type="hidden" name="action" value="Save" /> 
		<input type="hidden" class="step" value="3" />
		<input type="hidden" id="record" name="record" value="{$RECORD_ID}" />
		<input type="hidden" name="mode" value="{$MODE}" />
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
		<input type="hidden" id="conditions1" name="conditions1" value='' />
		
		<input type="hidden" id="module_name" name="module_name" value="{$searchmodulelist}" />
		<input type="hidden" id="subscribers_list" name="subscribers_list" value="{$subscribers_list}" />
		
		<input type="hidden" id="select_option" name="select_option" value="{$select_option}" />
		<input type="hidden" id="whopenemail" name="whopenemail" value="{$whopenemail}" />
		<input type="hidden" id="emailclicklink" name="emailclicklink" value="{$emailclicklink}" />
		<input type="hidden" id="parent_emailcampaignsid" name="parent_emailcampaignsid" value="{$parent_emailcampaignsid}" />

		<input type="hidden" id="reply_to" name="reply_to" value="{$reply_to}" />
		<input type="hidden" id="unsubscribe_option" name="unsubscribe_option" value="{$unsubscribe_option}" />
		<input type="hidden" id="related_recorts" name="related_recorts" value='{$related_recorts}' />
		<div class="padding1per" style="border:1px solid #ccc;">
				{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name="EditViewBlockLevelLoop"}
				{if $BLOCK_LABEL eq 'Email Template'}
					{if $BLOCK_FIELDS|@count lte 0}{continue}{/if}
					 <label>
						<strong>{vtranslate($BLOCK_LABEL, $MODULE)}</strong>
					</label>
					<br>
					{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
						{if $FIELD_MODEL->get('uitype') neq "19"}
							<div class="control-group">
								<div class="control-label">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								</div>
								<div class="controls">
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
								</div>
							</div>	
						{elseif $FIELD_MODEL->get('uitype') eq "19"}
								<div class="control-group">
									<div class="control-label">
										{vtranslate('Insert Merge Tag', $MODULE)}
									</div>
									<div class="controls">
									<input type=hidden name="moduleFields" data-value='{ZEND_JSON::encode($ALL_FIELDS)}' />
										<span class="span4 conditionRow">
											<select class="chzn-select" name="modulename" >
												<option value="none">{vtranslate('LBL_SELECT_MODULE',$MODULE)}</option>
												{foreach key=MODULENAME item=FILEDS from=$ALL_FIELDS}
													{if $MODULENAME eq '0'}
														<option value="generalFields">{vtranslate('LBL_GENERAL_FIELDS', $MODULE)}</option>
													{else}
														<option value="{$MODULENAME}">{vtranslate($MODULENAME, $MODULENAME)}</option>
													{/if}
												{/foreach}
											</select>
										</span>&nbsp;&nbsp;
										<span class="span6">
											<select class="chzn-select span5" id="templateFields" name="templateFields">
												<option value="">{vtranslate('LBL_NONE',$MODULE)}</option>
											</select>
										</span>
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label">
										<span class="btn-toolbar pull-right">
											<button type="button" class="btn" id="selectEmailTemplate" data-url="{$EMAIL_TEMPLATE_URL}" title="{vtranslate('LBL_SELECT_EMAIL_TEMPLATE',$MODULE)}"><strong>{vtranslate('LBL_SELECT_EMAIL_TEMPLATE',$MODULE)}</strong></button>
										</span>
									</div>
									
										<div class="controls">
											{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
										</div>
									</div>
						{/if}
					{/foreach}
				{/if}
				{/foreach}
				</div>
				<div class="pull-right">
					<button class="btn btn-danger backStep" type="button"><strong>{vtranslate('LBL_BACK', 'EmailCampaigns')}</strong></button>&nbsp;&nbsp;
					<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_NEXT', 'EmailCampaigns')}</strong></button>
					<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
				</div>
				<div class="clearfix"></div>
		</form>
				
{/strip}
						
