{include file='DDDHeader.tpl'|@vtemplate_path:$MODULE}
<form action = 'index.php?module={$MODULE}&action=SaveSettings' method = 'POST' name = 'savesettings'>
<div class="contentsDiv span10 marginLeftZero" style = 'width: 95%; '>
	<div class="contents row-fluid">
		<div class="widget_header row-fluid">
			<div class="span8">
				<h3>{vtranslate($DDD_SETTINGS_TITLE, $MODULE)}</h3>
			</div>
			<div class="span4">
				<div class="pull-right">
					<button class="btn editButton" type="submit" title="Save"> <strong> {vtranslate('LBL_SAVE', $MODULE)} </strong> </button>
				</div>
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
				</tr>
			</thead>
			<tbody>
				{foreach from=$data key=data_key item=single_data}
				<tr>
					<td width="1%" nowrap="" class="medium"></td>
					<td colspan="2" style="border-left: none;" class = 'listViewEntries medium' width = "49.5%">
						<label class="muted marginRight10px"> {$single_data.moduleName} </label>
					</td>
					<td colspan="2" style="border-left: none;" width = "49.5%" class = 'listViewEntries medium'> 
						<input type = 'checkbox' name = '{$single_data.moduleName}_status' id = '{$single_data.moduleName}_status' {$single_data.checked}>
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
				<div class="pull-right">
					<button class="btn editButton" type="submit" title="Save"> <strong> {vtranslate('LBL_SAVE', $MODULE)} </strong> </button>
				</div>
			</div>
		</div>
		<hr>
	</div>

</div>
</form>
<br> <br>
