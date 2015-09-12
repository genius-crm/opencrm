{*+***********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}
{strip}

  <div class="editContainer" style="padding-left: 3%;padding-right: 3%">
	{assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MODULE}
        <h3>
            {if $RECORDID eq ''}
				{vtranslate('LBL_CREATING_NEW', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)}
            {else}
				 {vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} - {$RECORD_STRUCTURE_MODEL->getRecordName()}
            {/if}
        </h3>

        <hr>
        <div id="breadcrumb">
            <ul class="crumbs marginLeftZero">
                <li class="step"  style="z-index:9" id="step1">
                    <a href="#">
                        <span class="stepNum">1</span>
                        <span class="stepText">{vtranslate('LBL_1ST_STEP',$MODULE)}</span>
                    </a>
                </li>
                <li style="z-index:8" class="step" id="step2">
                    <a href="#">
                        <span class="stepNum">2</span>
                        <span class="stepText">{vtranslate('LBL_2ST_STEP',$MODULE)}</span>
                    </a>
                </li>
                <li class="step" style="z-index:7" id="step3">
                    <a href="#">
                        <span class="stepNum">3</span>
                        <span class="stepText">{vtranslate('LBL_3ST_STEP',$MODULE)}</span>
                    </a>
                </li>
				<li class="step last" style="z-index:6" id="step4">
                    <a href="#">
                        <span class="stepNum">4</span>
                        <span class="stepText">{vtranslate('LBL_4ST_STEP',$MODULE)}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
{/strip}
