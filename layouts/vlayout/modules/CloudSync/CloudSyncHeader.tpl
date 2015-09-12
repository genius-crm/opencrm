<!-- adding javascript -->
{if $VIEW neq 'Settings'}
	{foreach key=index item=jsModel from=$SCRIPTS}
        	<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
	{/foreach}
{/if}
<!-- adding font awesome style -->
<link rel="stylesheet" type="text/css" href="modules/CloudSync/public/css/font-awesome.css" media="screen" />
<link rel="stylesheet" type="text/css" href="modules/CloudSync/lib/jQueryFileTree/jqueryFileTree.css" media="screen" />
