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
	<div class="container-fluid" id="paymentManagerContents">
		<div class="widget_header row-fluid">
			<div class="span6"><h3>Payment Module Management</h3></div>
		</div>
		<hr>
		
		<div class="contents">
			{assign var=COUNTER value=0}
			<table class="table table-bordered equalSplit">
				<tr>
				{foreach item=MODULE_NAME key=TAB_ID from=$ALL_MODULES}
					{if $COUNTER eq 2}
						</tr><tr>
						{assign var=COUNTER value=0}
					{/if}

					<td class="opacity">
						<div class="row-fluid moduleManagerBlock">
							<span class="span1">
								<input type="checkbox" value="" name="moduleStatus" data-module="{$TAB_ID}" data-module-translation="{vtranslate($MODULE_NAME, $MODULE_NAME)}" {if in_array($TAB_ID, $ACTIVE_MODULES)}checked{/if}/>
							</span>
							<span class="span2 moduleImage {if in_array($TAB_ID, $ACTIVE_MODULES)}dull {/if}">
								{if vimage_path($MODULE_NAME|cat:'.png') != false}
									<img class="alignMiddle" src="{vimage_path($MODULE_NAME|cat:'.png')}" alt="{vtranslate($MODULE_NAME, $MODULE_NAME)}" title="{vtranslate($MODULE_NAME, $MODULE_NAME)}"/>
								{else}
									<img class="alignMiddle" src="{vimage_path('DefaultModule.png')}" alt="{vtranslate($MODULE_NAME, $MODULE_NAME)}" title="{vtranslate($MODULE_NAME, $MODULE_NAME)}"/>
								{/if}	
							</span>
							<span class="span5 moduleName {if in_array($TAB_ID, $ACTIVE_MODULES)}dull {/if}"><h4>{vtranslate($MODULE_NAME, $MODULE_NAME)}</h4></span>
						</div>
						{assign var=COUNTER value=$COUNTER+1}
					</td>
				{/foreach}
				</tr>
			</table>
		</div>
	</div>
{/strip}
