{*
/**
 * VGS Hide Ads Banner Module
 *
 *
 * @package        VGSHideAds Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */
*}

<script type="text/javascript" src="layouts/vlayout/modules/VGSHideAds/resources/VGSHideAds.js"></script>

<div style="width: 65%;margin: auto;margin-top: 2em;padding: 2em;">
    <h3 style="padding-bottom: 1em;text-align: center">{vtranslate('vgs_hide_ads', $MODULE)}</h3>
   
    <div>

        <p>{vtranslate('vgs_hide_ads_explain', $MODULE)}</p>
        <table class="table table-bordered table-condensed themeTableColor" style="margin-top: 1em;">
            <thead>
                <tr class="blockHeader">
                    <th colspan="4" class="mediumWidthType"><span class="alignMiddle">{vtranslate('vgs_hide_ads', $MODULE)}</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="50%" colspan="2"><label class="muted pull-right marginRight10px">{vtranslate('hide_banners', $MODULE)}</label></td>
                    <td colspan="2" style="border-left: none;">
                        <input type="checkbox" id="vgshideads_enable" name="vgshideads_enable" {if $ENABLED eq '1'} checked {/if} />
                    </td>
                </tr>

            </tbody>
        </table>
        <br><br>
        <div class="alert alert-error notices" id="error_notice" style="display:none;float: left;margin-left:1em !important; margin-bottom: 0px !important;margin-top: 0px !important;width: 80%;">
            {vtranslate('fail', $MODULE)}
        </div>
        <div class="alert alert-success notices" id="success_message" style="display:none;float: left;margin-left:1em !important; margin-bottom: 0px !important;margin-top: 0px !important;width: 80%;">
            {vtranslate('works', $MODULE)}
        </div>
        
        <button class="btn pull-right" style="margin-bottom: 0.5em;" id="vgs_save_hideads_settigns">{vtranslate('save', $MODULE)}</button>

    </div>
    
</div>
<div style="width: 65%;margin: auto;margin-top: 2em;padding: 2em;"><p>{vtranslate('vgs_hide_ads_powerby', $MODULE)}</p></div>