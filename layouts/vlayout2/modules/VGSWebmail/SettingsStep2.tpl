{*
/**
 * VGS Webmail Extension Module
 *
 *
 * @package        VGSWebmail Module
 * @author         Conrado Maggi - VGS Global - www.vgsglobal.com
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 */
*}
<div style="width: 65%;margin: auto;margin-top: 2em;padding: 2em;">
    <h3 style="padding-bottom: 1em;text-align: center">{vtranslate('vgs_webmail_module', $MODULE)}</h3>
    <p>{vtranslate('vgs_webmail_settings', $MODULE)}</p>
    <form class="form-horizontal" id="VGSWebmail" name="VGSWebmail" method="post" action="index.php">
        <input type="hidden" name="module" value="{$MODULE}" />
        <input type="hidden" name="action" value="Save" />
                  
        <div>
        
            {if $LOGIN_FAILED eq true}
                
            <div class="alert alert-error" style="margin-bottom: 2% !important;margin-top: 2% !important;width: 80%;">
                {vtranslate('error_notice', $MODULE)}
            </div>
            {/if}    
            <div class="contentHeader row-fluid">
            
            <span class="pull-right">
                <a class="cancelLink pull-right padding1per" onClick="javascript:window.history.back()">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                <button class="btn btn-success pull-right" id="customViewSubmit" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
            </span>
            </div>
            

            <table class="table table-bordered table-condensed themeTableColor" style="margin-top: 1em;">
                <thead>
                    <tr class="blockHeader">
                        <th colspan="2" class="mediumWidthType"><span class="alignMiddle">{vtranslate('email_imap_settings', $MODULE)}</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('server-template', $MODULE)}</label></td>
                        <td style="border-left: none;">
                                <select name="server-template" class="server-template">
                                        <option value="other" selected="selected">Other</option>
                                        <option value="gmail">Gmail / Google Apps</option>
                                </select></td></tr>
                                
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('username', $MODULE)}</label></td><td style="border-left: none;"><input type="text" name="username" value="{$EMAIL_SETTINGS.username}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('password', $MODULE)}</label></td><td style="border-left: none;"><input type="password" name="password" value="{$EMAIL_SETTINGS.password}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('host', $MODULE)}</label></td><td style="border-left: none;"><input type="text" name="host" value="{$EMAIL_SETTINGS.host}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('port', $MODULE)}</label></td><td style="border-left: none;"><input type="text" name="port" value="{$EMAIL_SETTINGS.port}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('smtp_server', $MODULE)}</label></td><td style="border-left: none;"><input type="text" name="smtp_server" value="{$EMAIL_SETTINGS.smtp_server}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('smtp_port', $MODULE)}</label></td><td style="border-left: none;"><input type="text" name="smtp_port" value="{$EMAIL_SETTINGS.smtp_port}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('smtp_user', $MODULE)}</label></td><td style="border-left: none;"><input type="text" name="smtp_user" value="{$EMAIL_SETTINGS.smtp_user}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('smtp_pass', $MODULE)}</label></td><td style="border-left: none;"><input type="password" name="smtp_pass" value="{$EMAIL_SETTINGS.smtp_pass}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('displayname', $MODULE)}</label></td><td style="border-left: none;"><input type="text" name="displayname" value="{$EMAIL_SETTINGS.displayname}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('autosaveattachments', $MODULE)}</label></td><td style="border-left: none;"><input type="checkbox" name="autosaveattachments" {if $EMAIL_SETTINGS.autosaveattachments eq 1} checked {/if}"></input></td></tr>
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate('language', $MODULE)}</label></td><td style="border-left: none;">
                            <select name="webmail_language">
                                <option value="sq_AL">Albanian (Shqip)</option>
                                <option value="ar_SA">Arabic (العربية)</option>
                                <option value="hy_AM">Armenian (Հայերեն)</option>
                                <option value="ast">Asturiana (Asturianu)</option>
                                <option value="az_AZ">Azerbaijani (Azərbaycanca)</option>
                                <option value="eu_ES">Basque (Euskara)</option>
                                <option value="be_BE">Belarusian (беларуская мова)</option>
                                <option value="bn_BD">Bengali (বাংলা)</option>
                                <option value="bs_BA">Bosnian (Bosanski)</option>
                                <option value="br">Breton (Brezhoneg)</option>
                                <option value="bg_BG">Bulgarian (Български)</option>
                                <option value="ca_ES">Catalan (Català)</option>
                                <option value="zh_TW">Chinese (正體中文)</option>
                                <option value="zh_CN">Chinese (简体中文)</option>
                                <option value="hr_HR">Croatian (Hrvatski)</option>
                                <option value="cs_CZ">Czech (Česky)</option>
                                <option value="da_DK">Danish (Dansk)</option>
                                <option value="fa_AF">Dari (ﻯﺭﺩ)</option>
                                <option value="nl_NL">Dutch (Nederlands)</option>
                                <option value="en_GB">English (GB)</option>
                                <option value="en_US" selected="selected">English (US)</option>
                                <option value="eo">Esperanto</option>
                                <option value="et_EE">Estonian (Eesti)</option>
                                <option value="fi_FI">Finnish (Suomi)</option>
                                <option value="nl_BE">Flemish (Vlaams)</option>
                                <option value="fr_FR">French (Français)</option>
                                <option value="gl_ES">Galician (Galego)</option>
                                <option value="ka_GE">Georgian (ქართული)</option>
                                <option value="de_DE">German (Deutsch)</option>
                                <option value="de_CH">German (Schweiz)</option>
                                <option value="el_GR">Greek (Ελληνικά)</option>
                                <option value="he_IL">Hebrew (עברית)</option>
                                <option value="hi_IN">Hindi (हिनदी)</option>
                                <option value="hu_HU">Hungarian (Magyar)</option>
                                <option value="is_IS">Icelandic (Íslenska)</option>
                                <option value="id_ID">Indonesian (Bahasa Indonesia)</option>
                                <option value="ia">Interlingua</option>
                                <option value="ga_IE">Irish (Gaedhilge)</option>
                                <option value="it_IT">Italian (Italiano)</option>
                                <option value="ja_JP">Japanese (日本語)</option>
                                <option value="km_KH">Khmer (ភាសាខ្មែរ)</option>
                                <option value="ko_KR">Korean (한국어)</option>
                                <option value="ku">Kurdish (Kurmancî)</option>
                                <option value="lv_LV">Latvian (Latviešu)</option>
                                <option value="lt_LT">Lithuanian (Lietuviškai)</option>
                                <option value="mk_MK">Macedonian (Македонски)</option>
                                <option value="ms_MY">Malay (Bahasa Melayu)</option>
                                <option value="ml_IN">Malayalam (മലയാളം)</option>
                                <option value="mr_IN">Marathi (मराठी)</option>
                                <option value="ne_NP">Nepali (नेपाली)</option>
                                <option value="nb_NO">Norwegian (Bokmål)</option>
                                <option value="nn_NO">Norwegian (Nynorsk)</option>
                                <option value="ps">Pashto</option>
                                <option value="fa_IR">Persian (فارسی)</option>
                                <option value="pl_PL">Polish (Polski)</option>
                                <option value="pt_BR">Portuguese (Brasil)</option>
                                <option value="pt_PT">Portuguese (Português)</option>
                                <option value="ro_RO">Romanian (Româneşte)</option>
                                <option value="ru_RU">Russian (Русский)</option>
                                <option value="sr_CS">Serbian (Српски)</option>
                                <option value="si_LK">Sinhalese (සිංහල)</option>
                                <option value="sk_SK">Slovak (Slovenčina)</option>
                                <option value="sl_SI">Slovenian (Slovenščina)</option>
                                <option value="es_AR">Spanish (Argentina)</option>
                                <option value="es_ES">Spanish (Español)</option>
                                <option value="sv_SE">Swedish (Svenska)</option>
                                <option value="ta_IN">Tamil</option>
                                <option value="th_TH">Thai (ไทย)</option>
                                <option value="tr_TR">Turkish (Türkçe)</option>
                                <option value="uk_UA">Ukrainian (Українська)</option>
                                <option value="vi_VN">Vietnamese (Tiếng Việt)</option>
                                <option value="cy_GB">Welsh (Cymraeg)</option>
                                <option value="fy_NL">West Frisian (Frysk)</option>
                            </select></td></tr>

                </tbody>
            </table>
        </div>



  
    </form>

</div>
<script>
    {literal}
        $("#customViewSubmit").click(function(event) {
            error_free = true;
            var form_data = $("#VGSWebmail").serializeArray();

            for (var input in form_data) {
                
                if(form_data[input]['value']==''){
                    error_free = false;
                    var form_name = form_data[input]['name'];
                    var input_error = $("input[name='" + form_name + "']");
                    input_error.css({'border-color' : 'rgba(240, 20, 20, 1)'});
                }
            }
            if (!error_free) {
                event.preventDefault();
                 alert('All fields are mandatory');
            }
            else {
               
            }
        });
    {/literal}
</script>

