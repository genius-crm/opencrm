{*
/**
 * VGS Dynamic Blocks
 *
 * @package        VGS Dynamic Blocks
 * @author         Conrado Maggi
 * @license        Commercial
 * @copyright      2014 VGS Global - www.vgsglobal.com
 * @version        Release: 1.0
 */
*}

<div style="margin-top: 2%; margin-left: 2%;"><h4>{vtranslate('VGS_DYNAMIC_BLOCKS', $MODULE)}</h4></div>


<div style="width: 90%;margin: auto;margin-top: 2%;">
    <button class="btn pull-right" style="margin-bottom: 0.5em;" id="Contacts_detailView_basicAction_LBL_EDIT" onclick="window.location.href='index.php?parent=Settings&module=VGSDynamicBlocks&view=AddNew'">{vtranslate('ADD_NEW', $MODULE)}</button>

    <table class="table table-bordered listViewEntriesTable">
        <thead>
            <tr class="listViewHeaders">
                <th> {vtranslate('MODULE_NAME', $MODULE)} </th>
                <th> {vtranslate('FIELD_LABEL', $MODULE)} </th>
                <th> {vtranslate('FIELD_VALUE', $MODULE)} </th>
                <th> {vtranslate('HIDDEN_BLOCKS', $MODULE)} </th>
                <th> {vtranslate('ACTIONS', $MODULE)} </th>

            </tr>
        </thead>
        {foreach item=HIDDEN_BLOCKS from=$HIDDEN_BLOCKS_ARRAY}
            <tr class="listViewEntries">
                
                <td class="listViewEntryValue" nowrap> {$HIDDEN_BLOCKS.module_name}</td>
                <td class="listViewEntryValue" nowrap> {$HIDDEN_BLOCKS.field_label} </td>
                <td class="listViewEntryValue" nowrap> {$HIDDEN_BLOCKS.field_value}</td>
                <td class="listViewEntryValue" nowrap> {$HIDDEN_BLOCKS.hidden_blocks}</td>
                <td class="listViewEntryValue" nowrap> <a class="deleteRecordButton" id="{$HIDDEN_BLOCKS.id}"><i title="Delete" class="icon-trash alignMiddle"></i></a></td>

            </tr>
        {/foreach}
    </table>
</div>