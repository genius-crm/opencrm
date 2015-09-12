{include file='CloudSyncHeader.tpl'|@vtemplate_path:$MODULE}
{if $show_widget eq true}
<div class = 'row-fluid'>
        <div style = 'display: inline-block'>
		<!-- show  -->
		{if $source_module eq 'Documents'}
                <ul class = 'nav nav-list'>
                        <li> <button class = 'btn' onclick = 'syncToDropboxDetailView("{$record}")'> Upload Document to <i class="fa fa-dropbox fa-lg"></i> </button> </li>
                </ul>
                <br>
		{/if}
		{if $source_module neq 'Documents'}
                <ul class = 'nav nav-list'>
                        <li> 
				<div style = 'display:inline-block;'>
				<div style = 'float:left;'> <button class = 'btn' onclick = 'showDropboxFiles("/")'> Select files from <i class="fa fa-dropbox fa-lg"></i> </button> </div>
				<div style = 'float:right;'> <span class = 'helpIcon' style = 'padding-left:3px;'> <i class="fa fa-question-circle fa-lg" data-toggle="tooltip" data-placement="right" title="Clicking this button will show list of files in Dropbox. Selected file will be created in Vtiger as Document and created Document will be related to the current record"></i> </span> </div>
				</div>
			</li>
                </ul>
		{/if}
        </div>
</div>
{if $source_module neq 'Documents'}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="DropboxFiles" aria-hidden="true">
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title" id="DropboxFiles"> <i class="fa fa-dropbox fa-2x" style = 'font-size: 1.5em; color: #007EE5;'> Dropbox Files </i> <span style = 'float:right; display:block;' id = 'loadinggif_modalheader'><i class="fa fa-circle-o-notch fa-spin fa-lg"></i> </span></h4>
                        </div>
                        <div class="modal-body" style = 'max-height: 350px; overflow: auto;'>
                                <div id = 'showDropboxView' class = 'filetree'> </div>
                        </div>
                        <div class="modal-footer">
                                <div id = 'filetree_notification'> </div>
                                <div> 
					<span class = 'pull-left'> 
						<label> <strong> Assigned To </strong> </label> 
					</span> 
					<span class = 'pull-left' style = 'padding-left: 3%;'> {$assigned_user_id_html} </span> 
					<span> <button type="button" class="btn btn-default" data-dismiss="modal" onclick = 'location.reload()'> Close </button> </span>
				</div>
                        </div>
                </div>
        </div>
</div>
{/if}
{else}
        <div class = 'alert alert-warning' style = 'padding: 5px; margin-bottom: 0px;'> {vtranslate($token_error_message, $MODULE)} <br> <a href = 'index.php?module=CloudSync&view=Settings'> Go to CloudSync Settings </a> </div>
{/if}
