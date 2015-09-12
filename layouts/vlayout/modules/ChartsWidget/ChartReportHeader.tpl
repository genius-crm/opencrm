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
<style>
    body { font-size:18px !important; }
</style>
	<form name='chartDetailForm' id='chartDetailForm'>
		<input type="hidden" name="module" value="{$MODULE}" />
		<input type="hidden" name="recordId" id="recordId" value="{$RECORD}" />
		<input type="hidden" name="reportname" value="{$REPORT_MODEL->get('reportname')}" />
		<input type="hidden" name="reports_description" value="{$REPORT_MODEL->get('reports_description')}" />
		<input type="hidden" name="advanced_filter" id="advanced_filter" value={ZEND_JSON::encode($ADVANCED_FILTERS)} />
		<input type="hidden" name='groupbyfield' value={$CHART_MODEL->getGroupByField()} />
		<input type="hidden" name='datafields' value={Zend_JSON::encode($CHART_MODEL->getDataFields())} />
		<input type="hidden" name='charttype' value="{$CHART_MODEL->getChartType()}" />
	</form>
	<div id="reportContentsDiv">
{/strip}