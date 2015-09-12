<!-- adding font awesome style -->
<link rel="stylesheet" type="text/css" href="modules/{$MODULE}/public/css/font-awesome.css" media="screen" />
<!-- show loading image before javascript starts loading -->

<!-- adding javascript -->
{if $VIEW eq 'Widget'}
	{foreach key=index item=jsModel from=$SCRIPTS}
 		<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
	{/foreach}
{/if}
<!-- hide loading image after javascript starts loading -->
