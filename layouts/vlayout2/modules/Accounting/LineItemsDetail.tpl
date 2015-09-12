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
{assign var=FINAL_DETAILS value=$RELATED_PRODUCTS.1.final_details}
<table class="table table-bordered mergeTables">
	<tbody>
    <tr>
	<td>
	    <span class="redColor">*</span><b>{vtranslate('LBL_ITEM_NAME',$MODULE_NAME)}</b>
	</td>
        <td>
	    <b>{vtranslate('LBL_QTY',$MODULE_NAME)}</b>
	</td>
        <td>
	    <b>{vtranslate('LBL_LIST_PRICE',$MODULE_NAME)}</b>
	</td>
        <td>
	    <b>{vtranslate('LBL_TOTAL',$MODULE_NAME)}</b>
	</td>
        <td>
	    <b class="pull-right">{vtranslate('LBL_NET_PRICE',$MODULE_NAME)}</b>
	</td>
    </tr>
    {foreach key=INDEX item=LINE_ITEM_DETAIL from=$RELATED_PRODUCTS}
	<tr>
	    <td>
		<div class="row-fluid">
		    {$LINE_ITEM_DETAIL["productName$INDEX"]}
		</div>
		{if $LINE_ITEM_DETAIL["productDeleted$INDEX"]}
			<div class="row-fluid redColor deletedItem">
				{if empty($LINE_ITEM_DETAIL["productName$INDEX"])}
					{vtranslate('LBL_THIS_LINE_ITEM_IS_DELETED_FROM_THE_SYSTEM_PLEASE_REMOVE_THIS_LINE_ITEM',$MODULE)}
				{else}
					{vtranslate('LBL_THIS',$MODULE)} {$LINE_ITEM_DETAIL["entityType$INDEX"]} {vtranslate('LBL_IS_DELETED_FROM_THE_SYSTEM_PLEASE_REMOVE_OR_REPLACE_THIS_ITEM',$MODULE)}
				{/if}
			</div>
		{/if}
		{if !empty($LINE_ITEM_DETAIL["subProductArray$INDEX"])}
		    <div>
			{foreach item=SUB_PRODUCT_NAME from=$LINE_ITEM_DETAIL["subProductArray$INDEX"]}
			    <div>
				{if !empty($SUB_PRODUCT_NAME)}
					- &nbsp; <em>{$SUB_PRODUCT_NAME}</em>
				{/if}
			    </div>
			{/foreach}
		    </div>
		{/if}
		{if !empty($LINE_ITEM_DETAIL["productName$INDEX"])}
			<div>
				{$LINE_ITEM_DETAIL["comment$INDEX"]|nl2br}
			</div>
		{/if}
	    </td>
	    <td>
		{$LINE_ITEM_DETAIL["qty$INDEX"]}
	    </td>
	    <td>
		<div>
		    {$LINE_ITEM_DETAIL["listPrice$INDEX"]}
		</div>
		</td>
		<td>
		    <div>
			{$LINE_ITEM_DETAIL["productTotal$INDEX"]}
		    </div>
		</td>
		<td>
		    <span class="pull-right">
			{$LINE_ITEM_DETAIL["netPrice$INDEX"]}
		    </span>
		</td>
	    </tr>
	    {/foreach}
	    </tbody>
	</table>

	<table class="table table-bordered">
	    <tr>
		<td width="83%">
		    <div class="pull-right">
			<b>{vtranslate('LBL_ITEMS_TOTAL',$MODULE_NAME)}</b>
		    </div>
		</td>
		<td>
		    <span class="pull-right">
			<b>{$FINAL_DETAILS["hdnSubTotal"]}</b>
		    </span>
		</td>
	    </tr>
		{if $MODULE_NAME eq 'Invoice' or $MODULE_NAME eq 'PurchaseOrder'}
        <tr>
            <td width="83%">
                {if $MODULE_NAME eq 'Invoice'}
                    <span class="pull-right">
                        <b>{vtranslate('LBL_RECEIVED',$MODULE_NAME)}</b>
                    </span>
                {else}
                    <span class="pull-right">
                        <b>{vtranslate('LBL_PAID',$MODULE_NAME)}</b>
                    </span>
                {/if}
            </td>

            <td>
                {if $MODULE_NAME eq 'Invoice'}
                    <span class="pull-right">
                        {if $RECORD->getDisplayValue('received')}
							{$RECORD->getDisplayValue('received')}
                        {else}
                            0.00
                        {/if}
                    </span>
                {else}
                    <span class="pull-right">
                        {if $RECORD->getDisplayValue('paid')}
							{$RECORD->getDisplayValue('paid')}
                        {else}
                            0.00
                        {/if}
                    </span>
                {/if}
            </td>
        </tr>
        <tr>
            <td width="83%">
                <span class="pull-right">
                    <b>{vtranslate('LBL_BALANCE',$MODULE_NAME)}</b>
                </span>
            </td>
            <td>
                <span class="pull-right">
                    {if $RECORD->getDisplayValue('balance')}
						{$RECORD->getDisplayValue('balance')}
                    {else}0.00
                    {/if}
                </span>
            </td>
        </tr>
        {/if}
	</table>
