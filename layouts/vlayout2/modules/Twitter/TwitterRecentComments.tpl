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

{* Change to this also refer: AddCommentForm.tpl *}
{assign var="COMMENT_TEXTAREA_DEFAULT_ROWS" value="2"}
{if $LOGGEDIN eq '1'}
	{literal}
		<script type="text/javascript">
			$(document).ready(function() {
				$( "#twitter" ).click(function(event) {
					event.preventDefault();
				});
			});
		</script>
	{/literal}
	<button id="twitter" onClick="window.location.href=&quot;{$REDIRECTURL}&quot;" class="btn btn-success"><strong>Login</strong></button>
{else}
	<form action="#">
		<div class="commentContainer">
			<div class="commentTitle row-fluid">
				<div class="addCommentBlock">
					<div>
						<strong><span id="success" style="margin-left: 14px;"></span><strong>
						<input type="hidden" value="{$TWITTERUSERID}" id="twitter_user_id" name="twitter_user_id" />
						<input type="hidden" value="{$SITE_URL}" id="site_URL" name="site_URL" />
						<input type="hidden" value="{$AJAXMESSAGEURL}" id="ajax_message_url" name="ajax_message_url" />
						<input type="hidden" value="{$AJAXGETMESSAGEURL}" id="ajax_sent_message_url" name="ajax_sent_message_url" />
						<textarea style="min-width:94%;width:175px;margin-left: 6px;" id="message" name="commentcontent" class="commentcontent"  placeholder="{vtranslate('LBL_ADD_YOUR_COMMENT_HERE', $MODULE_NAME)}" rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}"></textarea>
					</div>
					<div class="pull-right">
						<button id="send_message" style="margin-right: 10px;" class="btn btn-success" type="submit" data-mode="add"><strong>{vtranslate('LBL_POST', $MODULE_NAME)}</strong></button>
					</div>
				</div>
			</div>
			<div class="commentsBody" id="sendMessageResult"></div>
		</div>
	</form>
	{literal}
	<script type="text/javascript">
		$(document).ready(function() {
			var site_url = document.getElementById('site_URL').value;
			var img = "<img src='" + site_url + "layouts/vlayout/skins/softed/images/loading.gif' style='align-content:center;'>";
			$("#sendMessageResult").css( "text-align", "center" );
			document.getElementById('sendMessageResult').innerHTML = img;
			var get_message_list = document.getElementById('ajax_sent_message_url').value;
			$.ajax({
				  type: 'GET',
				  url: get_message_list,
				  success: function(data) {
					$("#sendMessageResult").css( "text-align", "" );
					document.getElementById('sendMessageResult').innerHTML = data;
				  }, error: function (data) {
					alert('Failed');
				  }
			});
			$( "#send_message" ).click(function(event) {
				event.preventDefault();
				var message = document.getElementById('message').value;
				var ajax_message_url = document.getElementById('ajax_message_url').value;
				var screen_name = document.getElementById('twitter_user_id').value;
				
				if(message != ''){
					$.ajax({
						  type: 'GET',
						  url: ajax_message_url + "?message=" + message + "&screen_name=" + screen_name,
						  success: function(data) {
							document.getElementById('success').innerHTML = "Message Sent";
							document.getElementById('message').value = "";
						  }, error: function (data) {
							document.getElementById('success').innerHTML = "Message Failed";
						  }
					});
				}else{
					alert("Please type some text to Send");
				}
			});
		});
	</script>
	{/literal}
{/if}
{/strip}