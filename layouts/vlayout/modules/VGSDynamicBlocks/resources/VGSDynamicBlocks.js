/**
 * VGS Dynamic Blocks
 *
 * @package        VGS Dynamic Blocks
 * @author         Conrado Maggi
 * @license        Commercial
 * @copyright      2014 VGS Global - www.vgsglobal.com
 * @version        Release: 1.0
 */

jQuery.Class("VGSDynamicBlocks_Js", {}, {
    bindEvents: function () {
        var record = jQuery('input:hidden[name=record]').val();
        params = {
            'module': 'VGSDynamicBlocks',
            'action': 'VGSDynamicBlocksColumnsAjax',
            'mode': 'getInitialFields',
            'sourcemodule': app.getModuleName(),
            'recordid': record
        }
        AppConnector.request(params).then(
                function (data) {
                    if (data.result.caption == 'OK') {
                        blocks = data.result.blocks;
                        for (var i = 0; i < blocks.length; i++) {


                            if (jQuery('select[name=' + blocks[i]['fieldname'] + ']').val() === blocks[i]['fieldvalue']) {
                                var blockHeader = jQuery(document).find('.blockHeader:contains("' + blocks[i]['blocklabel'] + '")');
                                var table = blockHeader.closest('table');
                                table.hide();

                            }
                            jQuery('select[name=' + blocks[i]['fieldname'] + ']').on('change', function (e) {
                                var instance = new VGSDynamicBlocks_Js();
                                instance.updateBlocks(e);
                            });
                        }
                    }
                },
                function (jqXHR, textStatus, errorThrown) {
                }
        );
    },
    updateBlocks: function (e) {

        params = {
            'module': 'VGSDynamicBlocks',
            'action': 'VGSDynamicBlocksColumnsAjax',
            'mode': 'getblocks',
            'sourcemodule': app.getModuleName(),
            'fieldname': e.target.name,
            'fieldvalue': e.target.value
        }
        AppConnector.request(params).then(
                function (data) {
                    if (data.result.caption == 'OK') {
                        var splitted = data.result.hiddenblocks;
                        for (var i = 0; i < splitted.length; i++) {
                            var blockHeader = jQuery(document).find('.blockHeader:contains("' + splitted[i] + '")');
                            var table = blockHeader.closest('table');
                            table.hide();

                        }

                        var splitted = data.result.visibleblocks;
                        for (var i = 0; i < splitted.length; i++) {
                            var blockHeader = jQuery(document).find('.blockHeader:contains("' + splitted[i] + '")');
                            var table = blockHeader.closest('table');
                            table.show();
                        }
                    }
                },
                function (jqXHR, textStatus, errorThrown) {
                }
        );

    },
    updateBlocksDetail: function (e) {

        params = {
            'module': 'VGSDynamicBlocks',
            'action': 'VGSDynamicBlocksColumnsAjax',
            'mode': 'getDetailBlocks',
            'sourcemodule': app.getModuleName(),
        }
        AppConnector.request(params).then(
                function (data) {
                    if (data.result.caption == 'OK') {
                        blocks_info = data.result.hiddenblocks;
                        for (var i = 0; i < blocks_info.length; i++) {
                             var field_id = app.getModuleName() + '_detailView_fieldValue_' + blocks_info[i]['fieldname'];
                            if (jQuery.trim(jQuery('#' + field_id +' .value').text()) === blocks_info[i]['fieldvalue']) {
                                var blockHeader = jQuery(document).find('.blockHeader:contains("' + blocks_info[i]['blocklabel'] + '")');
                                var table = blockHeader.closest('table');
                                table.hide();

                            }


                        }
                    }
                },
                function (jqXHR, textStatus, errorThrown) {
                }
        );

    },
});


jQuery(document).ready(function () {
    var instance = new VGSDynamicBlocks_Js();
    if (app.getViewName() === 'Edit') {
        instance.bindEvents();
    } else if (app.getViewName() === 'Detail') {
        instance.updateBlocksDetail();
    }
    
      app.listenPostAjaxReady(function() {
         instance.updateBlocksDetail();
     });



});