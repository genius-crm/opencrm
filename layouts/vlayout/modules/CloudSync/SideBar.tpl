<div class = 'row-fluid'>
	<div class = 'sideBarContents'>
		<div class = 'quickLinksDiv'>
			{foreach item=sideBarLink from=$sideBarLinks}
			<p onclick = "window.location.href='{$sideBarLink.link}'" class = '{$sideBarLink.class}'> 
				<a class = 'quicklinks' href = '{$sideBarLink.link}'>
					<strong> {vtranslate($sideBarLink.name, $moduleName)} </strong>
				</a>
			</p>
			{/foreach}
		</div>
	</div>
</div>

