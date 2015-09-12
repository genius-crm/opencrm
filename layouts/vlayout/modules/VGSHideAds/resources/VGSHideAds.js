/**
 * VGS Hide Ads Banner Module
 *
 *
 * @package        VGSHideAds Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */

$(document).on('click', '#vgs_save_hideads_settigns', {}, function(e) {

   
    jQuery(".notices").hide();
    var loadingMessage = jQuery('.listViewLoadingMsg').text();
    
    var settings_checkbox =  jQuery('#vgshideads_enable').prop('checked');

    var progressIndicatorElement = jQuery.progressIndicator({
        'message': loadingMessage,
        'position': 'html',
        'blockInfo': {
            'enabled': true
        }
    });

    var dataUrl = "index.php?module=VGSHideAds&action=saveVGSHideAdsSettings&checkbox=" + settings_checkbox;
    AppConnector.request(dataUrl).then(
            function(data) {

                if (data.success) {

                    var result = data.result;
                    if (result == 'ok') {

                       jQuery("#success_message").show();

                    } else {
                        jQuery("#error_notice").show();
                    }
                }
            },
            function(error, err) {

            }
    );

    progressIndicatorElement.progressIndicator({
        'mode': 'hide'
    });

});