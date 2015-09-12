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
<link href='layouts/vlayout/modules/EmailCampaigns/emailcampaign.css' rel='stylesheet' type='text/css'>
<form name="EditEmailCampaigns" action="index.php" method="post" id="EmailCampaigns_step4" class="form-horizontal">
		<input type="hidden" name="module" value="EmailCampaigns" />
		 <input type="hidden" name="action" value="Save" /> 
		<input type="hidden" id="record" class="recordval" name="record" value="{$RECORD_ID}" />
		<input type="hidden" id="saverecord" name="saverecord" value="{$RECORD_ID}" />
		<input type="hidden" name="mode" value="{$MODE}" />
		<input type="hidden" class="step" value="4" />
	<!-- NEW CHANGES HERE -->
							<div class="schedule">
							<div class="row-fluid">
								<div class="span10">
									<div class="text-info lineHeight themeColor">{vtranslate('Campaign Name', EmailCampaigns)}</div>
									<div class="lineHeight textOverflowEllipsis">{$CAMPAIGN_NAME}</div>
								</div>
								<div class="span2 pull-right paddingTopBottom5per">
									<button data-value="1" type="button" class="btn addButton jumpToStep currentActive width100Per"> {vtranslate(Edit, EmailCampaigns)}  </button>
								</div>
								
							</div> 
						</div>
						<div class="schedule">
							<div class="row-fluid">
								<div class="span10">
									<div class="text-info lineHeight themeColor">{vtranslate('List Name', EmailCampaigns)}</div>
									<div class="lineHeight textOverflowEllipsis">{$SUBSCRIBERLIST_NAME}</div>
								</div>
								<div class="span2 pull-right paddingTopBottom5per">
									<button data-value="2" type="button" class="btn addButton jumpToStep currentActive width100Per">   {vtranslate(Edit, EmailCampaigns)}  </button>
								</div>
							</div>
						</div>
						<div class="schedule">
								<div class="row-fluid">
									<div class="span10">
										<div class="text-info lineHeight themeColor">{vtranslate(Subject, EmailCampaigns)}</div>
										<div title="Birthday Sample" class="lineHeight textOverflowEllipsis"> {$EMAIL_TEMP_SUBJECT} </div>
									</div>

									<div class="span2 pull-right paddingTopBottom5per">
										<button data-value="3" type="button" class="btn addButton jumpToStep currentActive width100Per"> {vtranslate(Edit, EmailCampaigns)}  </button>
									</div>
								</div>
							</div>		
						<div class="schedule">
							<div class="row-fluid">
								<div class="span10">
									<div class="text-info lineHeight themeColor">{vtranslate('From Email', EmailCampaigns)}</div>
									<div class="lineHeight">{$CAMPAIGN_FROM_EMAIL}</div>
								</div>
								<div class="span2 pull-right paddingTopBottom5per">
									<button data-value="1" type="button" class="btn addButton jumpToStep currentActive width100Per"> {vtranslate(Edit, EmailCampaigns)}  </button>
								</div>
							</div>
						</div>
						
						<!-- FINISH CHANGES -->
				<div class="pull-right">
					<button class="btn btn-danger backStep" type="button"><strong>{vtranslate('LBL_BACK', EmailCampaigns)}</strong></button>&nbsp;&nbsp;
					<button class="btn btn-success TestEmail" type="button"><strong>Test Email</strong></button>&nbsp;&nbsp;
					<button class="btn btn-danger ScheduleLater" type="button"><strong>{vtranslate('Schedule Later', EmailCampaigns)}</strong></button> &nbsp;&nbsp;
					<button class="btn btn-success SendNow" type="button"><strong>{vtranslate('Send Now', EmailCampaigns)}</strong></button>
			
					</div>
				<div class="clearfix"></div>
		</form>
{/strip}
						
