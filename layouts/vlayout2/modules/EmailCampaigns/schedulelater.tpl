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
    <div id="transferOwnershipContainer" class='modelContainer'>
        <div class="modal-header contentsBackground">
            <button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">&times;</button>
            <h3 id="massEditHeader">{'Schedule Later'}</h3>
        </div>
        <form class="form-horizontal" id="changeOwner" name="changeOwner" method="post" action="index.php">
		<input type="hidden" class = 'recordval' id="record" name="record" value="{$RECORDID}" />
            <div class="modal-body tabbable">
                <div class="control-group">
                    <div class="control-label" style="width: 50;">{'Send On'}</div>
                    <div class="controls">
                        {assign var="dateFormat" value=$USER_MODEL->get('date_format')}
						<div class="input-append date">
							<input id="startdate" name="startdate" type="text" style="border:1px solid #bababa;width:100px" size="20" class="startdate dateField" data-date-format="{$dateFormat}" type="text"  
								   value="{if !empty($data.$startdate)}{$data.$startdate}{/if}" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>	
			
                    </div></br>
                </div>
                <div class="control-group">
					<div class="controls">
					   {assign var="TIME_FORMAT" value=$USER_MODEL->get('hour_format')}
					   <div class="input-append time">
							<input id="time" class="timepicker-default input-small ui-timepicker-input" type="text" data-format="{$TIME_FORMAT}" value="" name="time"
							data-validation-engine="validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]]"   {if !empty($SPECIAL_VALIDATOR)}data-validator='{Zend_Json::encode($SPECIAL_VALIDATOR)}'{/if} data-fieldinfo='{$FIELD_INFO}' />
							<span class="add-on cursorPointer">
								<i class="icon-time"></i>
							</span>
						</div>

    </div>
</div>
</div>
{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
</form>
</div>
{/strip}
