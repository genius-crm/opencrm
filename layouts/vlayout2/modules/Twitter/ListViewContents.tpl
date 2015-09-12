<div class="container-fluid">
   <div class="listViewEntriesDiv contents-bottomscroll" id="content">
      <div class="ProfileCanopy ProfileCanopy--withNav">
         <div class="ProfileCanopy-inner">
            <div class="ProfileCanopy-header u-bgUserColor" style="margin-top: 0px; {if $RESULT['profile_link_color']}background-color:#{$RESULT['profile_link_color']}{/if}">
               <div class="AppContainer">
                  <div class="ProfileCanopy-avatar">
                     <div class="ProfileAvatar">
                        <a target="_blank" data-url="{$RESULT['profile_image_url']}" href="{$PROFILE_IMAGE['200']}" class="ProfileAvatar-container u-block js-tooltip profile-picture media-thumbnail" data-original-title="{$RESULT['name']}">
                        <img alt="{$RESULT['name']}" src="{$PROFILE_IMAGE['400']}" class="ProfileAvatar-image">
                        </a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="ProfileCanopy-navBar">
               <div class="AppContainer">
                  <div class="Grid Grid--withGutter">
                     <div class="Grid-cell u-size1of3 u-lg-size1of4"></div>
                     <div class="Grid-cell u-size2of3 u-lg-size3of4">
                        <div class="ProfileCanopy-nav">
                           <div data-user-id="{$RESULT['id']}" role="navigation" class="ProfileNav">
                              <ul class="ProfileNav-list">
                                 <li class="ProfileNav-item ProfileNav-item--tweets is-active">
                                    <a data-nav="tweets" class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip js-nav" data-original-title="{$RESULT['statuses_count']} Tweets">
                                    <span class="ProfileNav-label">Tweets</span>
                                    <span data-is-compact="false" class="ProfileNav-value">{$RESULT['statuses_count']}</span>
                                    </a>
                                 </li>
                                 <li class="ProfileNav-item ProfileNav-item--following">
                                    <a data-nav="following" class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip js-nav u-textUserColor" data-original-title="{$RESULT['friends_count']} Following">
                                    <span class="ProfileNav-label">Following</span>
                                    <span data-is-compact="false" class="ProfileNav-value">{$RESULT['friends_count']}</span>
                                    </a>
                                 </li>
                                 <li class="ProfileNav-item ProfileNav-item--followers">
                                    <a data-nav="followers" class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip js-nav u-textUserColor" data-original-title="{$RESULT['followers_count']} Followers">
                                    <span class="ProfileNav-label">Followers</span>
                                    <span data-is-compact="false" class="ProfileNav-value">{$RESULT['followers_count']}</span>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="AppContainer">
         <div role="main" class="AppContent-main u-cf">
            <div class="Grid Grid--withGutter">
               <div class="Grid-cell u-size1of3 u-lg-size1of4">
                  <div class="Grid Grid--withGutter">
                     <div class="Grid-cell">
                        <div class="ProfileSidebar ProfileSidebar--withLeftAlignment">
                           <div class="ProfileHeaderCard">
                              <h1 class="ProfileHeaderCard-name">
                                 <a class="ProfileHeaderCard-nameLink u-textInheritColor js-nav " href="https://twitter.com/{$RESULT['screen_name']}">{$RESULT['name']}</a>
                              </h1>
                              <h2 dir="ltr" class="ProfileHeaderCard-screenname u-inlineBlock u-dir">
                                 <a href="https://twitter.com/{$RESULT['screen_name']}" class="ProfileHeaderCard-screennameLink u-linkComplex js-nav">@<span class="u-linkComplex-target">{$RESULT['screen_name']}</span></a>
                              </h2>
                              <p dir="ltr" class="ProfileHeaderCard-bio u-dir"></p>
                              <div class="ProfileHeaderCard-location u-hidden">
                                 <span class="Icon Icon--geo Icon--medium"></span>
                                 <span dir="ltr" class="ProfileHeaderCard-locationText u-dir"></span>
                              </div>
                              <div class="ProfileHeaderCard-url u-hidden">
                                 <span class="Icon Icon--url Icon--medium"></span>
                                 <span dir="ltr" class="ProfileHeaderCard-urlText u-dir">
                                 </span>
                              </div>
                              <div class="ProfileMessagingActions">
                                 <div class="ProfileMessagingActions-actionsContainer">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="Grid-cell u-size2of3 u-lg-size3of4">
                  <div class="Grid Grid--withGutter">
					<div class="contents-bottomscroll" style="padding:20px;" id="content">
						<br/><h3>Search People</h3><br/>
						<form action="{$SEARCHURL}" method="POST">
							<input type="text" name="search_people" id="search_people" />
							<div style="float:right;">
								<button onclick="window.location.href=&quot;{$SEARCHURL}&quot;" class="btn btn-success"><strong>Search</strong></button>
							</div>
						</form>
					</div>
                     <div data-test-selector="ProfileTimeline" class="Grid-cell">
                        <div class="ProfileHeading">
                           <div class="ProfileHeading-spacer"></div>
                           <div class="ProfileHeading-content">
                              <h2 class="ProfileHeading-title u-hiddenVisually" id="content-main-heading">Tweets</h2>
                              <ul class="ProfileHeading-toggle">
                                 <li class="ProfileHeading-toggleItem is-active ">Tweets</li>
                              </ul>
                           </div>
                        </div>
                        <div class="GridTimeline">
                           <div data-since-id="507843309536956417" data-max-id="507843239102013440" role="list" class="GridTimeline-items">
                              <div role="presentation" data-component-term="tweet" class="Grid">
                                 <div role="presentation" data-test-selector="ProfileTimelineTweet" class="Grid-cell ">
                                    <div data-item-type="tweet" id="stream-item-tweet-507843309536956417" data-item-id="507843309536956417" role="listitem" class="StreamItem js-stream-item">
                                       <div data-you-block="false" data-you-follow="false" data-user-id="{$RESULT['id']}" data-name="{$RESULT['name']}" data-screen-name="{$RESULT['screen_name']}" data-item-id="507843309536956417" data-tweet-id="507843309536956417" class="ProfileTweet u-textBreak js-tweet js-stream-tweet js-actionable-tweet ProfileTweet--low is-author">
                                          <div class="ProfileTweet-header u-cf">
                                             <div class="ProfileTweet-authorDetails">
                                                <a data-user-id="{$RESULT['id']}" href="https://twitter.com/{$RESULT['screen_name']}" class="ProfileTweet-originalAuthorLink u-linkComplex js-nav js-user-profile-link">
													<img alt="" src="https://abs.twimg.com/sticky/default_profile_images/default_profile_0_normal.png" class="ProfileTweet-avatar js-action-profile-avatar">
													<span class="ProfileTweet-originalAuthor u-floatLeft u-textTruncate js-action-profile-name">
														<b class="ProfileTweet-fullname u-linkComplex-target">{$RESULT['name']}</b>
														<span dir="ltr" class="ProfileTweet-screenname u-inlineBlock u-dir">
															<span class="at">@</span>{$RESULT['screen_name']}
														</span>
													</span>
                                                </a>
                                                <span class="u-floatLeft">&nbsp;.&nbsp;</span>
                                                <span class="u-floatLeft">
													<a href="https://twitter.com/{$RESULT['screen_name']}/status/507843309536956417" class="ProfileTweet-timestamp js-permalink js-nav js-tooltip" data-original-title="3:50 AM - 5 Sep 2014">
														<span data-long-form="true" data-time="1409914254" class="js-short-timestamp ">Sep 5</span>
													</a>
                                                </span>
                                             </div>
                                          </div>
                                          <div class="ProfileTweet-contents">
                                             <p dir="ltr" class="ProfileTweet-text js-tweet-text u-dir">Fri, 05 Sep 14 10:56:05 +0000</p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="GridTimeline-end has-items">
                              <div class="GridTimeline-footer">
                                 <div class="GridTimeline-footerIcon">
                                    <span class="spinner"></span>
                                    <span class="Icon Icon--large Icon--logo"></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>