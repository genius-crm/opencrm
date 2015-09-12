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
	<form name="EditEmailCampaigns" action="index.php" method="post" id="EmailCampaigns_step2" class="form-horizontal">
			<input type="hidden" name="module" value="SubscribersList">
            <input type="hidden" name="view" value="Edit">
            <input type="hidden" name="action" value="Save" /> 
			<input type="hidden" name="conditions1" id="advanced_filter" value='' />
			<input type="hidden" id="record" name="record" value="{$RECORD_ID}" />
			
			<div class="padding1per" style="border:1px solid #ccc;">
				{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name="EditViewBlockLevelLoop"}
					{if $BLOCK_LABEL eq 'Subscribers List Details'}
					{if $BLOCK_FIELDS|@count lte 0}{continue}{/if}
					{if $FIELD_NAME neq 'module_name' && $FIELD_NAME neq 'conditions'}
						<label>
							<strong>{vtranslate($BLOCK_LABEL, $MODULE)}</strong>
						</label>
					{/if}	
					<br>
						{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
						{if $FIELD_NAME neq 'module_name' && $FIELD_NAME neq 'conditions' && $FIELD_NAME neq 'subscribers_list'}
							<div class="control-group">
								<div class="control-label">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								</div>
								<div class="controls">
								  {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
								</div>
							</div>
						{elseif $FIELD_NAME eq 'subscribers_list'}
							<div class="control-group">
								<div class="control-label">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								</div>
								<div class="controls">
								{$FIELD_MODEL->getUITypeModel()->getTemplateName()}
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
								</div>
							</div>
						
						{/if}
						{/foreach}
					{/if}
					
				{/foreach}		
							<div class="control-group">
								<div class="control-label">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{'Select module to load recipients'}
								</div>
								<div class="controls">
									<select class="chzn-select" style="width:140px;" name="searchmodulelist" id="searchModuleList" data-placeholder="{vtranslate('LBL_SELECT_MODULE')}">
										<option></option>
										{foreach key=MODULE_NAME item=fieldObject from=$SEARCHABLE_MODULES}
											<option value="{$MODULE_NAME}" {if $MODULE_NAME eq $SOURCE_MODULE}selected="selected"{/if}>{vtranslate($MODULE_NAME,$MODULE_NAME)}</option>
										{/foreach}
									</select>		
								</div>	
							</div>	
								
							<div class="control-group">
								<div class="control-label">
									{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{'Load conditions from'}
								</div>
								<div class="controls">
									<select class="chzn-select" id="customFilter" style="width:350px;">
										{foreach key=GROUP_LABEL item=GROUP_CUSTOM_VIEWS from=$CUSTOM_VIEWS}
											<optgroup label=' {if $GROUP_LABEL eq 'Mine'} &nbsp; {else if} {vtranslate($GROUP_LABEL)} {/if}' >
												{foreach item="CUSTOM_VIEW" from=$GROUP_CUSTOM_VIEWS}
														<option  data-editurl="{$CUSTOM_VIEW->getEditUrl()}" data-deleteurl="{$CUSTOM_VIEW->getDeleteUrl()}" data-approveurl="{$CUSTOM_VIEW->getApproveUrl()}" data-denyurl="{$CUSTOM_VIEW->getDenyUrl()}" data-editable="{$CUSTOM_VIEW->isEditable()}" data-deletable="{$CUSTOM_VIEW->isDeletable()}" data-pending="{$CUSTOM_VIEW->isPending()}" data-public="{$CUSTOM_VIEW->isPublic() && $CURRENT_USER_MODEL->isAdminUser()}" id="filterOptionId_{$CUSTOM_VIEW->get('cvid')}" value="{$CUSTOM_VIEW->get('cvid')}" data-id="{$CUSTOM_VIEW->get('cvid')}" {if $VIEWID neq '' && $VIEWID neq '0'  && $VIEWID == $CUSTOM_VIEW->getId()} selected="selected" {elseif ($VIEWID == '' or $VIEWID == '0')&& $CUSTOM_VIEW->isDefault() eq 'true'} selected="selected" {/if} class="filterOptionId_{$CUSTOM_VIEW->get('cvid')}">{if $CUSTOM_VIEW->get('viewname') eq 'All'}{vtranslate($CUSTOM_VIEW->get('viewname'), $SOURCE_MODULE)} {vtranslate($SOURCE_MODULE, $SOURCE_MODULE)}{else}{vtranslate($CUSTOM_VIEW->get('viewname'), $SOURCE_MODULE)}{/if}{if $GROUP_LABEL neq 'Mine'} [ {$CUSTOM_VIEW->getOwnerName()} ]  {/if}</option>
												{/foreach}
											</optgroup>
										{/foreach}
									</select>
								</div>
							</div>
			<div class="row-fluid padding1per contentsBackground" style="border:1px solid #ccc;box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5); width:1065px;">
				
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
						<span > Subscribers</span>
					</span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button class="btn btn-success filterClick" type="button">Apply</button> 
				</div>

				<div class="pull-right">
					
					<button class="btn btn-danger" type="button" onclick="javascript:window.history.back();" type="reset"><strong>{vtranslate('Cancel', $QUALIFIED_MODULE)}</strong></button>&nbsp;&nbsp;
					<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_NEXT', $QUALIFIED_MODULE)}</strong></button>
					
				</div>
				<div class="clearfix"></div>
		</form>
				
{/strip}
