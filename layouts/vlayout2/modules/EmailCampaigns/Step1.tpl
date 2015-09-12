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
<div class="workFlowContents" style="padding-left: 3%;padding-right: 3%">
		{include file="EditHeader.tpl"|@vtemplate_path:$MODULE}
		<form name="EditEmailCampaigns" action="index.php" method="post" id="EmailCampaigns_step1" class="form-horizontal">
            <input type="hidden" name="module" value="EmailCampaigns">
            <input type="hidden" name="view" value="Edit">
            <input type="hidden" name="mode" value="Step2" />
            <input type="hidden" class="step" value="1" />
			<input type="hidden" id="source_module" name="source_module" value="" />
			
			<input type="hidden" id="subscribers_list1" name="subscribers_list1" value="{$subscribers_list}" />
            <input type="hidden" id="record" name="record" value="{$RECORDID}" />
			<div class="padding1per" style="border:1px solid #ccc;">
               {foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name="EditViewBlockLevelLoop"}
				{if $BLOCK_LABEL eq 'Campaign Details' || $BLOCK_LABEL eq 'Email Traking'}
					{if $BLOCK_FIELDS|@count lte 0}{continue}{/if}
					 <label>
                    <strong>{vtranslate($BLOCK_LABEL, $MODULE)}</strong>
                </label>
					{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
						<div class="control-group">
							<div class="control-label">
								{if $FIELD_MODEL->isMandatory() eq true } <span class="redColor">*</span> {/if}{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
							</div>
							<div class="controls">
								{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
							</div>
						</div>	
					{/foreach}
				{/if}
				{/foreach}
			</div>
		<div class="pull-right" id="step1next">
				<button class="btn btn-success" type="submit" disabled="disabled"><strong>{vtranslate('LBL_NEXT', $QUALIFIED_MODULE)}</strong></button>
				<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
			</div>
			<div class="clearfix"></div>
		</form>
	</div><br>
{/strip}
