
/**
 * VGS Webmail Extension Module
 *
 *
 * @package        VGSWebmail Module
 * @author         Conrado Maggi - VGS Global - www.vgsglobal.com
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 */

$(document).on('change', '.server-template', {}, function (e) {


    if ($(this).val() == 'gmail') {
        $('input[name="host"]').val('ssl://imap.gmail.com');
        $('input[name="port"]').val('443');
        $('input[name="smtp_server"]').val('ssl://smtp.gmail.com');
        $('input[name="smtp_port"]').val('465');


    } else if ($(this).val() == 'other') {
        if (confirm('Remove Current Seetings')) {
            $('input[name="host"]').val('');
            $('input[name="port"]').val('');
            $('input[name="smtp_server"]').val('');
            $('input[name="smtp_port"]').val('');
        }

    }



});


function addRecordVT() {


    if (top.frames["rcframe"].contentDocument != undefined) {
        var doc = top.frames["rcframe"].contentDocument;
    } else if (top.frames["rcframe"].contentWindow != undefined) {
        var doc = top.frames["rcframe"].contentWindow;
    } else {
        var doc = top.frames["rcframe"].document;
    }

    var option = doc.getElementById("add_vt").value;

    if (doc.getElementsByClassName("rcmBody")[0] != undefined) {
        var body = doc.getElementsByClassName("rcmBody")[0].innerHTML.replace(/<(?:.|\n)*?>/gm, '');

    } else if (doc.getElementsByClassName("message-part")[0] != undefined) {
        var body = doc.getElementsByClassName("message-part")[0].innerHTML.replace(/<(?:.|\n)*?>/gm, '');

    }
    var from = doc.getElementById("from_email").value;
    var from_name = doc.getElementById("from_name").value;
//    var to = top.frames["rcframe"].contentDocument.getElementById("to_email").value;
    var subject = doc.getElementsByClassName("subject")[0].innerHTML.replace(/<(?:.|\n)*?>/gm, '');

    var quickCreateParams = {};


    var postQuickCreateSave = function (data) {
//        alert('Record Added');
    }


    switch (option) {
        case 'contact':
            var quickcreateUrl = 'index.php?lastname=' + from_name + '&email=' + from + '&module=Contacts&view=QuickCreateAjax';
            var moduleName = 'Contacts';
            break;
        case 'lead':
            var quickcreateUrl = 'index.php?lastname=' + from_name + '&email=' + from + '&module=Leads&view=QuickCreateAjax';
            var moduleName = 'Leads';
            break;
        case 'account':
            var quickcreateUrl = 'index.php?accountname=' + from_name + '&email1=' + from + '&module=Accounts&view=QuickCreateAjax';
            var moduleName = 'Accounts';
            break;
        case 'event':
            var quickcreateUrl = 'index.php?subject=' + subject + '&module=Events&view=QuickCreateAjax';
            var moduleName = 'Events';
            break;
        case 'todo':
            var quickcreateUrl = 'index.php?subject=' + subject + '&module=Calendar&view=QuickCreateAjax';
            var moduleName = 'Calendar';
            break;
        case 'ticket':
            var quickcreateUrl = 'index.php?ticket_title=' + subject + '&module=HelpDesk&view=QuickCreateAjax&description=' + body;
            var moduleName = 'HelpDesk';
            break;
        case '':
            break;

    }

    quickCreateParams['noCache'] = true;
    quickCreateParams['callbackFunction'] = postQuickCreateSave;
    var progress = jQuery.progressIndicator();
    var headerInstance = new Vtiger_Header_Js();
    headerInstance.getQuickCreateForm(quickcreateUrl, moduleName, quickCreateParams).then(function (data) {
        progress.progressIndicator({'mode': 'hide'});
        headerInstance.handleQuickCreateData(data, quickCreateParams);

    });

}


$(document).ready(function () {
    if ($('#composeheaders') != undefined) {

        var urlparts = $(location).attr('pathname').split('/');
        var url = $(location).attr('hostname');

        $.each(urlparts, function (index, value) {
            if (value != 'modules' && value != '') {
                url = url + '/' + value;

            } else if (value == 'modules') {
                return false;
            }
        });

        console.log(url);

        $.ajax({
            url: 'http://' + url + '/index.php?module=VGSWebmail&action=LoadEmailTemplates',
            type: 'GET',
            data: {
                'numberOfWords': 10
            },
            dataType: 'json',
            success: function (data) {
                $('#composeheaders').append('<div id="emailtemplates">Available Email Templates: ' + data.result + '</div>');
            }
        });


    }
});


$(document).on('change', '#template-select', {}, function (e) {

    var templateid = $(this).val();

    var urlparts = $(location).attr('pathname').split('/');
    var url = $(location).attr('hostname');

    $.each(urlparts, function (index, value) {
        if (value != 'modules' && value != '') {
            url = url + '/' + value;

        } else if (value == 'modules') {
            return false;
        }
    });

    console.log(url);

    $.ajax({
        url: 'http://' + url + '/index.php?module=VGSWebmail&action=LoadEmailTemplateContents&template_id=' + templateid,
        type: 'GET',
        data: {
            'numberOfWords': 10
        },
        dataType: 'json',
        success: function (data) {
            $('#compose-subject').val(data.result.subject)
            if (tinyMCE.activeEditor == null) {
                var text = $(data.result.content).text();
                $('#composebody').val(text);
            } else {
                tinyMCE.activeEditor.setContent(data.result.content);
            }



        }
    });
});