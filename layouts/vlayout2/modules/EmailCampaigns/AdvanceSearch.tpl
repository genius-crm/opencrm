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
{strip}<div class="allConditionContainer conditionGroup contentsBackground well">
		<select id="customFilter" style="width:350px;">
									{foreach key=GROUP_LABEL item=GROUP_CUSTOM_VIEWS from=$CUSTOM_VIEWS}
									<optgroup label=' {if $GROUP_LABEL eq 'Mine'} &nbsp; {else if} {vtranslate($GROUP_LABEL)} {/if}' >
											{foreach item="CUSTOM_VIEW" from=$GROUP_CUSTOM_VIEWS}
												<option  data-editurl="{$CUSTOM_VIEW->getEditUrl()}" data-deleteurl="{$CUSTOM_VIEW->getDeleteUrl()}" data-approveurl="{$CUSTOM_VIEW->getApproveUrl()}" data-denyurl="{$CUSTOM_VIEW->getDenyUrl()}" data-editable="{$CUSTOM_VIEW->isEditable()}" data-deletable="{$CUSTOM_VIEW->isDeletable()}" data-pending="{$CUSTOM_VIEW->isPending()}" data-public="{$CUSTOM_VIEW->isPublic() && $CURRENT_USER_MODEL->isAdminUser()}" id="filterOptionId_{$CUSTOM_VIEW->get('cvid')}" value="{$CUSTOM_VIEW->get('cvid')}" data-id="{$CUSTOM_VIEW->get('cvid')}" {if $VIEWID neq '' && $VIEWID neq '0'  && $VIEWID == $CUSTOM_VIEW->getId()} selected="selected" {elseif ($VIEWID == '' or $VIEWID == '0')&& $CUSTOM_VIEW->isDefault() eq 'true'} selected="selected" {/if} class="filterOptionId_{$CUSTOM_VIEW->get('cvid')}">{if $CUSTOM_VIEW->get('viewname') eq 'All'}{vtranslate($CUSTOM_VIEW->get('viewname'), $SOURCE_MODULE)} {vtranslate($SOURCE_MODULE, $SOURCE_MODULE)}{else}{vtranslate($CUSTOM_VIEW->get('viewname'), $SOURCE_MODULE)}{/if}{if $GROUP_LABEL neq 'Mine'} [ {$CUSTOM_VIEW->getOwnerName()} ]  {/if}</option>
											{/foreach}
										</optgroup>
									{/foreach}
									{if $FOLDERS neq ''}
										<optgroup id="foldersBlock" label='{vtranslate('LBL_FOLDERS', $SOURCE_MODULE)}' >
											{foreach item=FOLDER from=$FOLDERS}
												<option data-foldername="{$FOLDER->getName()}" {if decode_html($FOLDER->getName()) eq $FOLDER_NAME} selected=""{/if} data-folderid="{$FOLDER->get('folderid')}" data-deletable="{!($FOLDER->hasDocuments())}" class="filterOptionId_folder{$FOLDER->get('folderid')} folderOption{if $FOLDER->getName() eq 'Default'} defaultFolder {/if}" id="filterOptionId_folder{$FOLDER->get('folderid')}" data-id="{$DEFAULT_CUSTOM_FILTER_ID}">{$FOLDER->getName()}</option>
											{/foreach}
										</optgroup>
									{/if}
							</select>
		</div>					
		<div class="span"> &nbsp; </div>
		<div class="filterElements">
				<form name="advanceFilterForm">
					{if $SOURCE_MODULE eq 'Home'}
						<div class="textAlignCenter marginBottom10px well contentsBackground">{vtranslate('LBL_PLEASE_SELECT_MODULE',$MODULE)}</div>
					{else}
						<input type="hidden" name="labelFields" data-value='{ZEND_JSON::encode($SOURCE_MODULE_MODEL->getNameFields())}' />
						{include file='AdvanceFilter.tpl'|@vtemplate_path}
					{/if}	
                    
				</form>
				<div class="row-fluid actions">
					<!-- TODO: should be done in better way to show right elements -->
					<div class="span5">
                        {if $SAVE_FILTER_PERMITTED}
						<div class="row-fluid">
							<span class="span4">&nbsp;</span>
							<span class="span7">
								<input class="zeroOpacity row-fluid" type="text" value="" name="viewname"/>
							</span>
						</div>
                        {else}
                            &nbsp;
                        {/if}
					</div>
					<div class="span7">
						<span class="btn-toolbar">
							<span class="btn-group">
							</span>
							<span class="btn-group  pull-right pushDown">
								<a class="cancelLink" type="reset" id="advanceSearchCancel" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
							</span>
							<span class="btn-group pull-right">
								<button class="btn" id="advanceSearchButton" {if $SOURCE_MODULE eq 'Home'} disabled="" {/if}  type="submit"><strong>{vtranslate('LBL_SEARCH', $MODULE)}</strong></button>
							</span>
                            {if $SAVE_FILTER_PERMITTED}
							<span class="btn-group pull-right ">
								<button class="btn hide" {if $SOURCE_MODULE eq 'Home'} disabled="" {/if} id="advanceSave"><strong>{vtranslate('LBL_SAVE_FILTER', $MODULE)}</strong></button>
							</span>
							<span class="btn-group pull-right">
								<button class="btn" {if $SOURCE_MODULE eq 'Home'} disabled="" {/if} id="advanceIntiateSave"><strong>{vtranslate('LBL_SAVE_AS_FILTER', $MODULE)}</strong></button>
							</span>
                            {/if}
						</span>
					</div>
				</div>
			</div>
		