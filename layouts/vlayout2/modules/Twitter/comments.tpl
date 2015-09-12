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
	{assign var=COMMENT_COUNT value=0}
	{foreach key=TIME item=VALUES from=$COMMENTS}
		{if $COMMENT_COUNT < 10}
		<div class='commentDetails' style="padding: 0 5px;">
			<div class='commentDiv'>
				<div class='singleComment'>
					<div class='commentInfoHeader row-fluid' data-commentid='' data-parentcommentid=''>
						<div class='commentTitle'>
							<div class='row-fluid'>
								<div class='span1'>
									<img class='alignMiddle pull-left' src='layouts/vlayout/skins/images/DefaultUserIcon.png'>
								</div>
								<div class='{if $CURRENT_VESRION eq '6.1.0'}span5 {else}span11 {/if} commentorInfo'>
									<div class='inner'>
										<span class='commentorName'><strong>{$VALUES['name']}</strong></span>
										<span class='pull-right'>
											<p class='muted'><small title='{$VALUES['time_title']}'>{$VALUES['time']}</small></p>
										</span>
										<div class='clearfix'></div>
									</div>
									<div class='commentInfoContent'>{$VALUES['text']}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{/if}
	{assign var=COMMENT_COUNT value=$COMMENT_COUNT+1}
	{/foreach}
{/strip}