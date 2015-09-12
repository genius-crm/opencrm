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
<link href='layouts/vlayout/modules/Twitter/twitter.css' rel='stylesheet' type='text/css'>
<div class='Grid-cell u-size3of4' style="padding:10px;">
	<div class='Grid Grid--withGutter'>
		<div data-cursor='0' role='list' class='GridTimeline-items'>
			<div role='presentation' data-component-term='user' class='Grid Grid--withGutter'>
			<input type="hidden" value="{$SITEURL}" id="site_url" name="site_url" />
				{foreach key=ID item=VALUES from=$DATA}
					<div role='presentation' data-test-selector='ProfileTimelineUser' style='padding:5px;width:30%;float:left;' class='Grid-cell u-size1of3 u-mb10'>
					<div data-item-type='user' id='stream-item-user-{$VALUES->id}' data-item-id='{$VALUES->id}' role='listitem' class='js-stream-item'>
						<div data-impression-id='' data-feedback-token='' data-user-id='{$VALUES->id}' data-screen-name='{$VALUES->screen_name}' class='ProfileCard js-actionable-user'>
							<a style='background-color: #{$VALUES->profile_background_color};background-image: url();' aria-hidden='true' tabindex='-1' href='https://twitter.com/{$VALUES->screen_name}' class='ProfileCard-bg js-nav'></a>
							<div class='ProfileCard-content'>
								<a aria-hidden='true' tabindex='-1' href='https://twitter.com/{$VALUES->screen_name}' class='ProfileCard-avatarLink js-nav js-tooltip' data-original-title='{$VALUES->screen_name}'>
								  <img alt='' src='{$VALUES->profile_image_url}' class='ProfileCard-avatarImage js-action-profile-avatar'>
								</a>
								<div class='ProfileCard-actions'>
									<div class='ProfileCard-userActions with-rightCaret js-userActions'>
										<div class='UserActions  u-textLeft'>
											<div data-protected='false' data-name='{$VALUES->name}' data-screen-name='{$VALUES->screen_name}' data-user-id='{$VALUES->id}' class='user-actions btn-group following not-muting including '>
												{if $VALUES->following eq 1}
													{literal}
														<script>
															$(document).ready(function() {
													{/literal}
														var id = {$ID};
													{literal}
																$("#follow"+id).hover(function() {
																	var button_text = "Unfollow";
																	document.getElementById('follow'+id).innerHTML = "<strong>" + button_text + "</strong>";
																});
																$("#follow"+id).mouseout(function() {
																	var button_text = "Following";
																	document.getElementById('follow'+id).innerHTML = "<strong>" + button_text + "</strong>";
																});
															});
														</script>
													{/literal}
												{/if}
												<button class="btn btn-success" id="follow{$ID}" type="submit"><strong>{if $VALUES->following eq 1}Following{else}Follow{/if}</strong></button>
												<input type="hidden" value="{$VALUES->id}" id="userid{$ID}" name="site_url{$ID}" />
												<input type="hidden" value="{$VALUES->following}" id="following{$ID}" name="following{$ID}" />
												{literal}
													<script>
														$(document).ready(function() {
												{/literal}
															var id = {$ID};
												{literal}
															$("#follow"+id).click(function() {
																var user_id = document.getElementById('userid'+id).value;
																var site_url = document.getElementById('site_url').value;
																var following = document.getElementById('following'+id).value;
																
																if(following == 1){
																	var follow = 0;
																	var button_text = "Follow";
																	var follow_value = 0;
																}else{
																	var follow = 1;
																	var button_text = "Following";
																	var follow_value = 1;
																}
																
																var url = site_url + "modules/Twitter/views/follow.php?userid=" + user_id + "&follow=" + follow;
																  $.ajax({
																	  url: url,
																	  type: "get",
																	  datatype: 'json',
																	  success: function(data){
																			document.getElementById('following'+id).value = follow_value;
																			document.getElementById('follow'+id).innerHTML = "<strong>" + button_text + "</strong>";
																	  },
																	  error:function(){
																		  alert('There was an error updating the settings');
																	  }  
																}); 
															});
														});
													</script>
												{/literal}
											</div>
										</div>
									 </div>
								</div>
								<div class='ProfileCard-userFields'>
									<div class='ProfileNameTruncated'>
										<div class='u-textTruncate u-inlineBlock ProfileNameTruncated-withBadges ProfileNameTruncated-withBadges--1'>
											<a href='https://twitter.com/{$VALUES->screen_name}' class='ProfileNameTruncated-link u-textInheritColor js-nav js-action-profile-name'>{$VALUES->name}</a>
										</div>
									</div>
									<div dir='ltr' class='ProfileCard-screenname u-dir'>
										<a class='ProfileCard-screennameLink u-linkComplex js-nav' href='https://twitter.com/{$VALUES->screen_name}'>
											@<span class='u-linkComplex-target'>{$VALUES->name}</span>
										</a>
									</div>
									<p dir='ltr' class='ProfileCard-bio u-dir'>{$VALUES->description}</p>
								</div>
								<form class="form-horizontal recordEditView" id="EditView" name="EditView" method="post" action="index.php">
									{literal}
										<script>
											$(document).ready(function() {
												$("#create_lead").click(function() {
													$('#create').val('Leads');
												});
												$("#create_contact").click(function() {
													$('#create').val('Contacts');
												});
											});
										</script>
									{/literal}
									<input type="hidden" name="total_count" value="1" />
									<input type="hidden" name="create" id="create" value="Leads" />
									<input id="module" type="hidden" value="Twitter" name="module">
									<input type="hidden" value="Save" name="action">
									<input type="hidden" name="checkbox0" value="1" />
									<input type="hidden" name="last_name0" value="{$VALUES->name}" />
									<input type="hidden" name="twitter_id0" value="{$VALUES->screen_name}" />
									<input type="hidden" name="location0" value="{$VALUES->location}" />
									<input type="hidden" name="description0" value="{$VALUES->description}" />
									
									<div class="contentHeader">
										<span class="btn-toolbar">
											<span class="btn-group">
												<button type="submit" id="create_lead" class="btn addButton"><strong>Create Leads</strong></button>
											</span>
											<span class="btn-group">
												<button type="submit" id="create_contact" class="btn addButton"><strong>Create Contacts</strong></button>
											</span>
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
</div>
{/strip}