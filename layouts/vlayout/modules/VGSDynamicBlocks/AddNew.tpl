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
{strip}
<div style="margin-top: 2%; margin-left: 2%;"><h4>{vtranslate('VGS_DYNAMIC_BLOCKS', $MODULE)}</h4></div>


<div style="width: 90%;margin: auto;margin-top: 2%;">
       
        <div  id="CompanyDetailsContainer">
            <div class="row-fluid">
                <form id="EditView" method="post">
                    <input type="hidden" id="companyid" name="companyid" value="{$smarty.request.companyid}">
                    <div class="row-fluid">
                        <div class="span12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="fieldLabel" style="width:200px;">
                                            <label class="pull-right marginRight10px"><b>{vtranslate('LBL_SELECT_MODULE', $QUALIFIED_MODULE)}</b></label>
                                        </td>
                                        <td class="fieldValue" style="width:50%;">
                                            <select class="chzn-select" name="sourceModule" id="sourceModule">
                                                {foreach key=throw item=MODULE_DATA from=$SUPPORTED_MODULES}
                                                    <option value={$MODULE_DATA.tabid} {if $MODULE_DATA.name eq $DEFAULT_MODULE.name} selected {/if}>
                                                        {vtranslate($MODULE_DATA.name, $MODULE_DATA.name)}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldLabel">
                                            <label class="pull-right marginRight10px"><b>{vtranslate('LBL_SELECT_PICKLIST_FIELDS', $QUALIFIED_MODULE)}</b></label>
                                        </td>
                                        <td class="fieldValue">
                                            <select class="chzn-select" name="picklistFields" id="picklistFields">
                                              
                                            </select>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td class="fieldLabel">
                                            <label class="pull-right marginRight10px"><b>{vtranslate('LBL_SELECT_PICKLIST_VALUES', $QUALIFIED_MODULE)}</b></label>
                                        </td>
                                        <td class="fieldValue">
                                            <select class="chzn-select" name="picklistFieldValues" id="picklistFieldValues">
                                              
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2 class="fieldLabel">
                                            <label class="marginRight10px"><b>{vtranslate('LBL_USE_FIELDS', $QUALIFIED_MODULE)}</b></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2 class="fieldValue" id="fieldSelectTD" style="width:50%;">
                                            <select data-placeholder="{vtranslate('LBL_CHOOSE_FIELDS',$QUALIFIED_MODULE)}" id="fieldsSelect" class="select2 span12" multiple>
                                            	{foreach key=fieldid item=field_data from=$MODULE_FIELDS}
                                            		<option value="{$fieldid}" {if $field_data.selected eq '1'}selected{/if}>{vtranslate($field_data.fieldlabel,$QUALIFIED_MODULE)}</option>
                                            	{/foreach}
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="row-fluid">
                        <div class="span12">
                            <span class="pull-right">
                                <button class="btn btn-success saveButton" type="button"><strong>{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</strong></button>
                                <a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
                            </span>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    {/strip}
    <script type="text/javascript">
        jQuery('#js_strings').html('{Zend_Json::encode($JS_LANG)}');
    </script>