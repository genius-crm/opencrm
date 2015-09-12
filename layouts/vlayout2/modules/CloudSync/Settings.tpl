{include file='CloudSyncHeader.tpl'|@vtemplate_path:$MODULE}
<form action = 'index.php?module={$MODULE}&action=SaveSettings' method = 'POST' name = 'savesettings'>
<div class="contentsDiv span10 marginLeftZero" style = 'width: 95%; min-height: 225px !important;display: inline-table;'>
	<div class="contents row-fluid">
		<div class="widget_header row-fluid">
			<div class="span8">
				<h3>{vtranslate($settingsTitle, $MODULE)} {$version}</h3>
			</div>
			<div class="span4">
				<div class="pull-right">
					{if $MODE eq 'detail'}
						<button class="btn editButton" onclick = 'window.location.href="index.php?module={$MODULE}&view=Settings&mode=edit"' type="button" title="Edit"> <strong> {vtranslate('LBL_EDIT', $MODULE)} </strong> </button>
					{else}
						<button class="btn editButton" type="submit" onclick = 'return validateSettingsForm(this.form)' title="Save"> <strong> {vtranslate('LBL_SAVE', $MODULE)} </strong> </button>
					{/if}
				</div>
			</div>
		</div>
		<hr>
	</div>
	<div class="contents row-fluid">
		<table class="table table-bordered table-condensed themeTableColor">
			<thead>
				<tr class="blockHeader">
					<th colspan="2" class="">
						<span class="alignMiddle blockHeaderTitle"> {vtranslate($dropboxHeaderTitle, $MODULE)} </span>
						{if $MODE eq 'detail'}
							{if $dropbox_connectionstatus eq 'LBL_NOT_CONNECTED'}
								<span class = 'pull-right' style = 'padding-right: 5px; padding-left: 5px;'>
									<button type = 'button' class = "btn editButton" onclick = "startAuthorisation('{$AUTHORIZE_URL}')"> 
										<strong> Click here to Authorize </strong>
									</button>		
								</span>
							{else}
								<span class = 'pull-right' style = 'padding-right: 5px; padding-left: 5px;'>
                                                                        <button type = 'button' class = "btn editButton" onclick = "revokeDropboxAccess()">
                                                                                <strong> Click here to revoke access </strong>
                                                                        </button>
                                                                </span>
							{/if}
					
							<span class = 'pull-right {$dropbox_connectionstatus_class}' style = 'padding: 5px; margin-bottom: 0px;'> {vtranslate($dropbox_connectionstatus, $MODULE)} </span>
						{/if}
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="25%" class="">
						<label class="muted pull-right marginRight10px"> {vtranslate('LBL_STATUS', $MODULE)} </label>
					</td>
					<td class="" style="border-left: none;">
						{if $MODE eq 'detail'}
							<span>{vtranslate($dropboxstatus, $MODULE)}</span>
						{else}
							<input type = 'checkbox' name = 'dropboxstatus' id = 'dropboxstatus' {if $dropboxstatus eq 'LBL_YES'} checked {/if}>
						{/if}
						<span class = 'helpIcon'> <i class="fa fa-question-circle fa-lg" data-toggle="tooltip" data-placement="right" title="Dropbox status. If you dont want to sync documents to dropbox, uncheck the checkbox"></i> </span>
					</td>
				</tr>
<!--				<tr>
					<td width="25%" class="">
						<label class="muted pull-right marginRight10px"> {vtranslate('LBL_UPLOAD_ON_WORKFLOW', $MODULE)} </label>
					</td>
					<td class="" style="border-left: none;">
						{if $MODE eq 'detail'}
							<span>{vtranslate($dropboxworkflow, $MODULE)}</span>
						{else}
							<input type = 'checkbox' name = 'dropboxworkflow' id = 'dropboxworkflow' {if $dropboxworkflow eq 'LBL_YES'} checked {/if}>
						{/if}
						<span class = 'helpIcon'> <i class="fa fa-question-circle fa-lg" data-toggle="tooltip" data-placement="right" title="Turn on this feature to upload Documents to Dropbox when creating New Document"></i> </span>
					</td>
				</tr> -->
				<tr>
					<td class="">
						<label class="muted pull-right marginRight10px"> {vtranslate('LBL_ACCESS_TOKEN', $MODULE)} </label>
					</td>
					<td class="" style="border-left: none;"> 
						{if $MODE eq 'detail'}
							<span>{$dropboxtoken}</span> 
						{else}
							<input class = "settings_text" type = 'text' name = 'dropboxtoken' id = 'dropboxtoken' value = '{$dropboxtoken}'>
						{/if}
						<span class = 'helpIcon'> <i class="fa fa-question-circle fa-lg" data-toggle="tooltip" data-placement="right" title="Dropbox access token"></i> </span>
					</td>
				</tr>
				<tr>
					<td width="25%" class="">
						<label class="muted pull-right marginRight10px"> {vtranslate('LBL_ATTACHMENT_TYPE', $MODULE)} </label>
					</td>
					<td class="" style="border-left: none;">
						{if $MODE eq 'detail'}
							<span> {if $dropboxattachmenttype eq 'I'} Internal {else if $dropboxattachmenttype eq 'E'} External {/if} </span>
						{else}
							<select name = 'dropboxattachmenttype' id = 'dropboxattachmenttype' class = 'chzn-select'>
								<option value = 'I' {if $dropboxattachmenttype eq 'I'} selected {/if}> Internal </option>
								<option value = 'E' {if $dropboxattachmenttype eq 'E'} selected {/if}> External </option>
							</select>
						{/if}
						<span class = 'helpIcon'> <i class="fa fa-question-circle fa-lg" data-toggle="tooltip" data-placement="right" title="Select Attachment Type. External - Create a sharable link from where the file can be downloaded for selected Dropbox file. Internal - Download selected Dropbox file and create Document using the attachment"></i> </span>
					</td>
				</tr>
				<tr>
					<td width="25%" class="">
						<label class="muted pull-right marginRight10px"> {vtranslate('LBL_DROPBOX_APP_FOLDER', $MODULE)} </label>
					</td>
					<td class="" style="border-left: none;">
						{if $MODE eq 'detail'}
							<span> {$dropboxappfolder} </span>
						{else}
							<input type = 'text' name = 'dropboxappfolder' id = 'dropboxappfolder' readonly value = '{$dropboxappfolder}' class = 'settings_text' > <i class = 'fa fa-link fa-2x' onclick = 'showDropboxFilesSettings()'></i> 
						{/if}
						<span class = 'helpIcon'> <i class="fa fa-question-circle fa-lg" data-toggle="tooltip" data-placement="right" title="Select Dropbox folder where we upload VtigerCRM Documents. If nothing selected, then files will be uploaded to Dropbox Root"></i> </span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</form>

<form action = 'index.php?module={$MODULE}&action=SaveWidgetSettings' method = 'POST' name = 'savesettings'>
 <div class="contentsDiv span10 marginLeftZero" style = 'width: 95%; min-height: 500px !important; display: inline-table;'>
        <div class="contents row-fluid">
                <div class="widget_header row-fluid">
                        <div class="span8">
                                <h3>{vtranslate($DDD_WIDGET_SETTINGS_TITLE, $MODULE)}</h3>
                        </div>
                        <div class="span4">
				{if $is_permitted_for_widget eq true}
                                <div class="pull-right">
                                        <button class="btn editButton" type="submit" title="Save"> <strong> {vtranslate('LBL_SAVE', $MODULE)} </strong> </button>
                                </div>
				{/if}
                        </div>
                </div>
                <hr>
        </div>
        <div class="contents row-fluid">
                <table class="table table-bordered table-condensed listViewEntriesTable">
                        <thead>
                                <tr class="listViewHeaders">
                                        <th colspan="2" class="medium" width = "50%" nowrap>
                                                <span class="alignMiddle blockHeaderTitle"> <strong> {vtranslate('LBL_LIST_MODULES', $MODULE)} </strong> </span>
                                        </th>
                                        <th colspan="2" class="medium" width = "50%" nowrap>
                                                <span class="alignMiddle blockHeaderTitle"> <strong> {vtranslate('LBL_STATUS', $MODULE)} </strong> </span>
                                        </th>
                                        <th colspan="2" class="medium" width = "50%" nowrap>
                                                <span class="alignMiddle blockHeaderTitle"> <strong> {vtranslate('LBL_DOCUMENT_FOLDERS', $MODULE)} </strong> </span>
                                        </th>
                                </tr>
                        </thead>
                        <tbody>
                                {foreach from=$data key=data_key item=single_data}
                                <tr>
                                        <td colspan="2" style="border-left: none;" class = 'listViewEntries medium' width = "33.33%">
                                                <label class="muted marginRight10px" style = 'top: 5px; position: relative;'> {$single_data.moduleName} </label>
                                        </td>
                                        <td colspan="2" style="border-left: none;" width = "33.33%" class = 'listViewEntries medium'>
						{if $is_permitted_for_widget eq true && $single_data.moduleName neq 'Documents'}
                                                	<input type = 'checkbox' name = '{$single_data.moduleName}_status' id = '{$single_data.moduleName}_status' {$single_data.checked} class = 'iconic-check'>
							 <label for="{$single_data.moduleName}_status" style = 'font-size: 1.5em; top: 5px; position: relative;'></label>
						{else if $single_data.moduleName eq 'Documents'}
							<i class="fa fa-asterisk fa-2x" style = 'color: #3C763D; font-size: 1.5em; top: 5px; position: relative;'></i>
						{else}
							{if $single_data.checked eq 'checked'} 
								<i class="fa fa-check-circle-o fa-2x" style = 'color: #3C763D; font-size: 1.5em; top: 5px; position: relative;'></i> 
							{else} 
								<i class="fa fa-times-circle fa-2x" style = 'color: #C9302C; font-size: 1.5em; top: 5px; position: relative;'></i> 
				 			{/if}
						{/if}
                                        </td>
					<td colspan="2" style="border-left: none;" width = "33.33%" class = 'listViewEntries medium'>
                                                {if $is_permitted_for_widget eq true}
                                                        <select name = 'dropboxdocumentfolders_{$single_data.moduleName}' id = 'dropboxdocumentfolders_{$single_data.moduleName}' class = 'chzn-select'>
                                                                {foreach from=$document_folders key=folder_id item=folder_name}
                                                                	{assign var="df_selected" value=""}
                                                                        {if $folder_id eq $single_data.folder_id}
                                                                                {assign var="df_selected" value="selected"}
                                                                        {/if}
                                                                        <option value = '{$folder_id}' {$df_selected}> {$folder_name} </option>
                                                                {/foreach}
                                                        </select>
						{else}
							<strong> {$HELPER->getDocumentFolderName($single_data.folder_id)} </strong>
						{/if}
                                        </td>	
                                </tr>
				{/foreach}
                        </tbody>
                </table>
        </div>
        <div class="contents row-fluid">
                <div class="widget_header row-fluid">
                        <div class="span8">
                                <h3> </h3>
                        </div>
                        <div class="span4">
				{if $is_permitted_for_widget eq true}
                                <div class="pull-right">
                                        <button class="btn editButton" type="submit" title="Save"> <strong> {vtranslate('LBL_SAVE', $MODULE)} </strong> </button>
                                </div>
				{/if}
                        </div>
                </div>
                <hr>
        </div>

</div>
</form>
<br> <br>

<!-- showing modal to get access token starts here -->	
<form action = "https://api.dropbox.com/1/oauth2/token" method = 'POST' onsubmit = "return checkDropboxCode()">
<input type = 'hidden' name = 'grant_type' value = 'authorization_code'>
<input type = 'hidden' name = 'client_id' value = '{$client_id}'>
<input type = 'hidden' name = 'client_secret' value = '{$client_secret}'>
<div class = "modal fade" id = "getAccessToken" tabindex = "-1" role = "dialog" aria-labelledby = "DropboxAccessToken" aria-hidden = "true">
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title" id="DropboxAccessToken"> Dropbox Code 
					<span style = 'float:right; display:none;' id = 'loadinggif_modalheader'>
						<i class="fa fa-circle-o-notch fa-spin fa-lg"> </i> 
					</span>
				</h4>
                        </div>
                        <div class="modal-body">
				<input type = 'text' name = 'dropbox_authcode' id = 'dropbox_authcode' placeholder = "Paste your dropbox code here" style = 'width: 400px;'>
                        </div>
                        <div class="modal-footer">
				<div id = 'dropboxtoken_notification'> </div>
                                <div> 
					<button type="button" class="btn btn-default" onclick = "saveDropboxAccessToken()" > Save </button> 
					<button type="button" class="btn btn-default" data-dismiss="modal"> Close </button> 
				</div>
                        </div>
                </div>
        </div>
</div>	
</form>
<!-- modal code ends here -->

<form name = 'DropboxFilesModal'>
<div class="modal fade" id="DropboxFilesModal" tabindex="-1" role="dialog" aria-labelledby="DropboxFilesModal" aria-hidden="true">
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title" id="DropboxFiles"> <i class="fa fa-dropbox fa-2x" style = 'font-size: 1.5em; color: #007EE5;'> Dropbox Files </i> <span style = 'float:right; display:block;' id = 'loadinggif_modalheader_settings'><i class="fa fa-circle-o-notch fa-spin fa-lg"></i> </span></h4>
                        </div>
                        <div class="modal-body" style = 'max-height: 350px; overflow: auto;'>
                                <div id = 'showDropboxView' class = 'filetree'> </div>
                        </div>
                        <div class="modal-footer">
                                <div id = 'filetree_notification'> </div>
                                <div>
                                        <span> <button type="button" class="btn btn-default" data-dismiss="modal"> Close </button> </span>
                                </div>
                        </div>
                </div>
        </div>
</div>
</form>
