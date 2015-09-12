/**
 * VGS Webmail Extension Module
 *
 *
 * @package        VGSWebmail Module
 * @author         Conrado Maggi - VGS Global - www.vgsglobal.com
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 */


if (window.rcmail) {
    rcmail.addEventListener('init', function (evt) {

        $('#composeview-right').css('left', '0px');
        $('#composesplitterv').hide();

        rcmail.register_command('splshscreen', function () {
            rcmail.show_splash()
        }, true);


    });
}


rcube_webmail.prototype.show_splash = function ()
{
    var pathArray = window.location.pathname.split('/');
    var newPathname = "";

    var newURL = window.location.protocol + "//" + window.location.host + ""

    for (i = 0; i < pathArray.length; i++) {

        if (pathArray[i] == 'modules') {
            break;
        }else{
            newURL += pathArray[i] + "/";
        }


    }
    newURL += "index.php?module=VGSWebmail&view=SettingsStep1";
    window.top.location.href = newURL;
    console.log(newURL);
};


