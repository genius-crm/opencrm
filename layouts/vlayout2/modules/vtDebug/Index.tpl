{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/
-->*}
{strip}
    <div class="container-fluid" id="layoutEditorContainer">
        <div class="widget_header row-fluid">
            <div class="span8">
                <h3>{vtranslate('LBL_VTDEBUG', $QUALIFIED_MODULE)}</h3>
            </div>
            <div class="span4">
                <!--div class="pull-right">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="form-inline" role="form">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="KZKWHEWD2VAM2">
					<input type="hidden" name="currency_code" value="USD">
					<input class="form-control" type="hidden" name="on0" value="Contribution :">Contribution :&nbsp;
					<select class="form-control chzn-select" name="os0">
						<option value="Generous">Generous $19.00 USD</option>
						<option value="Awesome">Awesome $29.00 USD</option>
						<option value="God">God $39.00 USD</option>
					</select>&nbsp;
					<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
					</form>
                </div-->
            </div>
        </div>
        <hr>
        <div class="contents tabbable">
            <ul class="nav nav-tabs layoutTabs massEditTabs">
                <li class="settingsTab active"><a data-toggle="tab" href="#settingsLayout"><strong>{vtranslate('LBL_VTDEBUG_SETTINGS', $QUALIFIED_MODULE)}</strong></a></li>
                <li class="userGuideTab"><a data-toggle="tab" href="#userGuide"><strong>{vtranslate('Usage Guide', $QUALIFIED_MODULE)}</strong></a></li>
                <li class="newsTab"><a data-toggle="tab" href="#newsLayout"><strong>{vtranslate('LBL_VTDEBUG_NEWS', $QUALIFIED_MODULE)}</strong></a></li>
                <li class="aboutTab"><a data-toggle="tab" href="#aboutLayout"><strong>{vtranslate('LBL_ABOUT_VTDEBUG', $QUALIFIED_MODULE)}</strong></a></li>
                <li class="registerTab"><a data-toggle="tab" href="#registerLayout"><strong>{vtranslate('LBL_VTDEBUG_SUPPORT', $QUALIFIED_MODULE)}</strong></a></li>
                <li class="productsTab"><a data-toggle="tab" href="#productsLayout"><strong>{vtranslate('LBL_VTIGRESS_PRODUCTS', $QUALIFIED_MODULE)}</strong></a></li>
            </ul>
            <div class="tab-content layoutContent padding20 themeTableColor overflowVisible">
                <div class="tab-pane " id="newsLayout">
					<iframe width="100%" height="400px" seamless="seamless" frameborder=0 src="https://docs.google.com/document/d/1Vulvk5CpkvdenPPhjC19RjLlzcHCUihE70Uyo8PSy1Q/pub?embedded=true"></iframe>
                </div>
                <div class="tab-pane " id="productsLayout">
					<iframe width="100%" height="400px" seamless="seamless" frameborder=0 src="http://intellectmatrix.biz/myenterprises/?page_id=38"></iframe>
                </div>
                <div class="tab-pane " id="registerLayout">
					<!--table class="table">
						<thead>
						<th colspan=2>
							Enter details to complete registration for vtDebug
						</th>
						</thead>
						<tbody>
						<tr>
							<td><span class="redColor">*</span>Email
							</td>
							<td><input id="useremail" type="text"/>
							</td>
						</tr>
						<tr>
							<td><span class="redColor">*</span>Repeat Email
							</td>
							<td><input id="useremail2" type="text"/>
							</td>
						</tr>
						<tr>
							<td>Name
							</td>
							<td><input id="username" type="text"/>
							</td>
						</tr>
						</tbody>
						<tr>
							<td>Organisation
							</td>
							<td><input id="userorg" type="text"/>
							</td>
						</tr>
						<tr>
							<td colspan=2>
							<button class="btn btn-success" onclick="registervtDebug();">Register</button>
							</td>
						</tr>
						</tbody>
					</table-->
					<iframe width="100%" height="400px" seamless="seamless" frameborder=0 src="http://customers.vtigress.com"></iframe>
				</div>
                <div class="tab-pane " id="aboutLayout">
					<pre>
						{include file="modules/vtDebug/README.txt"}
					</pre>
                </div>
                <div class="tab-pane active" id="settingsLayout">
					<div class="btn-toolbar">
						<button type="button" class="btn addButton" onclick="resetvtDebug();">
							<i class="icon-asterisk"></i>&nbsp;<strong>Reset All Settings</strong>
						</button>
						<span class="pull-right">
							<button type="button" class="btn btn-danger" onclick="removevtDebug();">
								<strong>Remove</strong>
							</button>
							&nbsp;
							<button type="button" class="btn btn-warning" onclick="disablevtDebug();">
								<strong>Disable</strong>
							</button>
						</span>
					</div>
					<table class="table">
						<thead>
						<th>Option
						</th>
						<th colspan=2>About
						</th>
						</thead>
						<tbody>
							<tr>
								<td>
									{if $VTDEBUGSETTINGS.logfile_log}
									<strong><i class="icon-ok"></i>&nbsp;Log4PHP</strong>
									{else}
									<i class="icon-remove"></i>&nbsp;Log4PHP
									{/if}
								</td>
								<td colspan=2>
									Native PHP logging system. Now part of the Apache project. Log are available for the Vtiger CRM as wel as specific sub areas
								</td>
							</tr>
							{if $VTDEBUGSETTINGS.logfile_log}
							<tr>
								<td>
									&nbsp;
								</td>
								<td colspan=2>
									<table class="table table-condensed" style="margin-bottom: 0px;">
										<thead>
											<th>
												Option
											</th>
											<th>
												Level
											</th>
											<th>
												Description
											</th>
										</thead>
										<tbody>
											<tr>
												<td>
													<p style="color:red;">Root Logger&nbsp;</p>
													{assign var=vtvar value=","|explode:$VTDEBUGSETTINGS.log4phpProperties['log4php.rootLogger']}
												</td>
												<td>
													{$vtvar[0]}
												</td>
												<td colspan=2>
													Main logging for the CRM. Outputs to <code>{$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A1.File']}</code>. Maximum file size {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A1.MaxFileSize']}, Max Backups {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A1.MaxBackupIndex']}
												</td>
											</tr>
											<tr>
												<td>
													Soap&nbsp;
													{assign var=vtvar value=","|explode:$VTDEBUGSETTINGS.log4phpProperties['log4php.logger.SOAP']}
												</td>
												<td>
													{$vtvar[0]}
												</td>
												<td colspan=2>
													Entries written here by any soap scripts that use the logger. See installed soap scripts. Outputs to <code>{$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A5.File']}</code>. Maximum file size {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A5.MaxFileSize']}, Max Backups {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A5.MaxBackupIndex']}
												</td>
											</tr>
											<tr>
												<td>
													SQLTime&nbsp;
													{assign var=vtvar value=","|explode:$VTDEBUGSETTINGS.log4phpProperties['log4php.logger.SQLTIME']}
												</td>
												<td>
													{$vtvar[0]}
												</td>
												<td colspan=2>
													Outputs to <code>{$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A7.File']}</code>. Maximum file size {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A7.MaxFileSize']}, Max Backups {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A7.MaxBackupIndex']}
												</td>
											</tr>
											<tr>
												<td>
													Security&nbsp;
													{assign var=vtvar value=","|explode:$VTDEBUGSETTINGS.log4phpProperties['log4php.logger.SECURITY']}
												</td>
												<td>
													{$vtvar[0]}
												</td>
												<td colspan=2>
													Outputs to <code>{$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A2.File']}</code>. Maximum file size {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A2.MaxFileSize']}, Max Backups {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A2.MaxBackupIndex']}
												</td>
											</tr>
											<tr>
												<td>
													Migration&nbsp;
													{assign var=vtvar value=","|explode:$VTDEBUGSETTINGS.log4phpProperties['log4php.logger.MIGRATION']}
												</td>
												<td>
													{$vtvar[0]}
												</td>
												<td colspan=2>
													Outputs to <code>{$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A4.File']}</code>. Maximum file size {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A4.MaxFileSize']}, Max Backups {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A4.MaxBackupIndex']}
												</td>
											</tr>
											<tr>
												<td>
													Installation&nbsp;
													{assign var=vtvar value=","|explode:$VTDEBUGSETTINGS.log4phpProperties['log4php.logger.INSTALL']}
												</td>
												<td>
													{$vtvar[0]}
												</td>
												<td colspan=2>
													Outputs to <code>{$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A3.File']}</code>. Maximum file size {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A3.MaxFileSize']}, Max Backups {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A3.MaxBackupIndex']}
												</td>
											</tr>
											<tr>
												<td>
													Platform&nbsp;
													{assign var=vtvar value=","|explode:$VTDEBUGSETTINGS.log4phpProperties['log4php.logger.PLATFORM']}
												</td>
												<td>
													{$vtvar[0]}
												</td>
												<td colspan=2>
													Outputs to <code>{$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A6.File']}</code>. Maximum file size {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A6.MaxFileSize']}, Max Backups {$VTDEBUGSETTINGS.log4phpProperties['log4php.appender.A6.MaxBackupIndex']}
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							{/if}
							<tr>
								<td>
									{if $VTDEBUGSETTINGS.config_toggle}
									<strong><i class="icon-ok"></i>&nbsp;config.inc.php</strong>
									{else}
									<i class="icon-remove"></i>&nbsp;config.inc.php
									{/if}
								</td>
								<td colspan=2>
									Production mode and Development modes are controlled by a PHP setting in this script
								</td>
							</tr>
							<tr>
								<td>
									{if $VTDEBUGSETTINGS.smarty_console}
									<strong><i class="icon-ok"></i>&nbsp;Smarty Console</strong>
									{else}
									<i class="icon-remove"></i>&nbsp;Smarty Console
									{/if}
								</td>
								<td colspan=2>
									Outputs Smarty Template variables and called templates list in a popup window. Native option by Smarty
								</td>
							</tr>
							<tr>
								<td>
									{if $VTDEBUGSETTINGS.smarty_log}
									<strong><i class="icon-ok"></i>&nbsp;Smarty Log</strong>
									{else}
									<i class="icon-remove"></i>&nbsp;Smarty Log
									{/if}
								</td>
								<td colspan=2>
									Vtiger setting to write Smarty calls to logfile. Outputs to <code>./logs/viewer-debug.log</code>
								</td>
							</tr>
							<tr>
								<td>
									{if $VTDEBUGSETTINGS.peardb_log}
									<strong><i class="icon-ok"></i>&nbsp;Database</strong>
									{else}
									<i class="icon-remove"></i>&nbsp;Database
									{/if}
								</td>
								<td colspan=2>
									Adodb supplied feature to log all mySQL calls. Outputs to <code>./logs/adblogfile.html</code>
								</td>
							</tr>
							{if $VTDEBUGSETTINGS.peardb_log}
							<tr>
								<td>
									&nbsp;
								</td>
								<td colspan=2>
									{if $VTDEBUGSETTINGS.browser_log}
										Browser Console Logging On
									{else}
										Browser Console Logging Off
									{/if}
								</td>
							</tr>
							{/if}
						</tbody>
					</table>
                </div>
                <div class="tab-pane " id="userGuide">
					<!--object data="//issuu.com/vtigress/docs/vtdziner_studio09-may-2013?e=9591124/5244322" width="100%" height="400px">
						<p>You don't have a PDF plugin for this browser. <a href="./modules/vtDebug/userGuide.pdf">click here to download</a></p>
					</object-->
					<!--div data-configid="9591124/5244322" style="width:525px;height:340px" class="issuuembed">
					</div>
					<script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script-->
					<iframe width="100%" height="400px" seamless="seamless" frameborder=0 src="https://docs.google.com/document/d/1odBmtkFU80OhVv1hypTDodUMfGjcCohl3g3dvC67Yb0/pub?embedded=true"></iframe>
				</div>
            </div>
		</div>
	</div>
	{literal}
	<script>
	function registervtDebug(){
		jQuery("#registerLayout").find("input").each(function(i,v){
			//console.log(jQuery(v).attr("id"), jQuery(v).val());
		});
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		var params = {};
		params['module'] = 'vtDebug';
		params['parent'] = 'Settings';
		params['action'] = 'Module';
		params['mode'] = 'registervtDebug';

		AppConnector.request(params).then(
			function(data) {
				progressIndicatorElement.progressIndicator({'mode' : 'hide'});
				params = {};
				if (data.success){
					params['text'] = data["result"]["message"];
				}
				else {
					params['text'] = data["error"]["message"];
					params['type'] = "error";
				}
				Settings_Vtiger_Index_Js.showMessage(params);
			},
			function(error) {
				progressIndicatorElement.progressIndicator({'mode' : 'hide'});
			}
		);
	}

	function removevtDebug(){
		var message = app.vtranslate('Are you absolutely sure you wish to remove vtDebug module from this Vtiger installation? Of course, you can always reinstall it !!');
		Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
			function(){
				var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
				});
				var params = {};
				params['module'] = 'vtDebug';
				params['parent'] = 'Settings';
				params['action'] = 'Module';
				params['mode'] = 'removevtDebug';

				AppConnector.request(params).then(
					function(data) {
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
						params = {};
						params['text'] = data["result"]["message"];
						Settings_Vtiger_Index_Js.showMessage(params);
						window.location.href="index.php";
					},
					function(error) {
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
					}
				);
			}
		);
	}

	function resetvtDebug(){
		var message = app.vtranslate('Are you absolutely sure you wish to reset all vtDebug options from this Vtiger installation?');
		Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
			function(){
				var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
				});
				var params = {};
				params['module'] = 'vtDebug';
				params['parent'] = 'Settings';
				params['action'] = 'Module';
				params['mode'] = 'resetAllOptions';

				AppConnector.request(params).then(
					function(data) {
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
						params = {};
						params['text'] = data["result"]["message"];
						Settings_Vtiger_Index_Js.showMessage(params);
						window.location.reload();
					},
					function(error) {
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
					}
				);
			}
		);
	}

	function disablevtDebug(){
		// All settings must be reset
		var message = app.vtranslate('Are you absolutely sure you wish to disable vtDebug from this Vtiger installation?<br>Please verify that you have turned OFF all vtDebug Options');
		Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
			function(){
				var progressIndicatorElement = jQuery.progressIndicator({
					'position' : 'html',
					'blockInfo' : {
						'enabled' : true
					}
				});
				var params = {};
				params['module'] = 'ModuleManager';
				params['parent'] = 'Settings';
				params['action'] = 'Basic';
				params['mode'] = 'updateModuleStatus';
				params['forModule'] = 'vtDebug';
				params['updateStatus'] = false;

				AppConnector.request(params).then(
					function(data) {
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
						params = {};
						params['text'] = 'vtDebug Module disabled. Re-enable thru Module Manager when needed';
						params['delay'] = 10000;
						params['type'] = 'warning';
						Settings_Vtiger_Index_Js.showMessage(params);
						window.location.href="index.php";
					},
					function(error) {
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
					}
				);
			}
		);
	}
	jQuery(document).ready(function(){
		resizeuserGuide();
	});

	window.onresize = function(event) {
		resizeuserGuide();
	}

	function resizeuserGuide(){
		jQuery("#userGuide").find("iframe").css('height', window.innerHeight-260);
		jQuery("#productsLayout").find("iframe").css('height', window.innerHeight-260);
	}
	</script>
	{/literal}
{/strip}