/**
 * VGS Related Field Generator Module
 *
 *
 * @package        VGSRelatedFields Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */

jQuery.Class("VGSRelModUpdates_Js", {}, {
    SourceModuleUpdate: function () {
        jQuery('#module1').on('change', function (e) {

            var module1 = jQuery(this).val();
            jQuery('#picklist1').find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');
            jQuery('#picklist_values1').find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');
            jQuery('#module2').find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');
            jQuery('#picklist2').find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');
            jQuery('#picklist_values2').find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');

            jQuery(".notice").hide();
            var loadingMessage = jQuery('.listViewLoadingMsg').text();

            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });

            var dataUrl = "index.php?module=VGSRelModUpdates&action=getPicklistFields&source_module=" + module1;
            AppConnector.request(dataUrl).then(
                    function (data) {

                        if (data.success) {

                            var result = data.result;
                            if (result.result == 'ok') {

                                jQuery.each(result.options, function (i, item) {
                                    var o = new Option(item, i);
                                    jQuery(o).html(item);
                                    jQuery("#picklist1").append(o);
                                });

                                jQuery("#picklist1").trigger('liszt:updated');

                            } else {
                                alert(app.tranlsate('JS_ERROR_LOADING_FIELDS'));
                            }
                        }
                    },
                    function (error, err) {

                    }
            );

            var dataUrl = "index.php?module=VGSRelModUpdates&action=getRelatedModule&source_module=" + module1;
            AppConnector.request(dataUrl).then(
                    function (data) {

                        if (data.success) {

                            var result = data.result;
                            if (result.result == 'ok') {

                                jQuery.each(result.options, function (i, item) {

                                    var o = new Option(item, i);
                                    jQuery(o).html(item);
                                    jQuery("#module2").append(o);
                                });

                                jQuery("#module2").trigger('liszt:updated');

                            } else {
                                 alert(app.tranlsate('JS_ERROR_LOADING_RELATED_MODULES'));
                            }
                        }
                    },
                    function (error, err) {

                    }
            );

            progressIndicatorElement.progressIndicator({
                'mode': 'hide'
            });

        });
    },
    targetModuleUpdate: function () {

        jQuery('#module2').on('change', function (e) {

            var module2 = jQuery(this).val();
            jQuery('#picklist2').find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');
            jQuery('#picklist_values2').find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');

            jQuery("#error_notice").hide();
            var loadingMessage = jQuery('.listViewLoadingMsg').text();

            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });

            var dataUrl = "index.php?module=VGSRelModUpdates&action=getPicklistFields&source_module=" + module2;
            AppConnector.request(dataUrl).then(
                    function (data) {

                        if (data.success) {

                            var result = data.result;
                            if (result.result == 'ok') {

                                jQuery.each(result.options, function (i, item) {

                                    var o = new Option(item, i);
                                    jQuery(o).html(item);
                                    jQuery("#picklist2").append(o);
                                });
                                jQuery("#picklist2").trigger('liszt:updated');

                            } else {
                                alert(app.tranlsate('JS_ERROR_LOADING_FIELDS'));
                            }
                        }
                    },
                    function (error, err) {

                    }
            );

            progressIndicatorElement.progressIndicator({
                'mode': 'hide'
            });

        });

    },
    loadPicklistValues: function () {
        jQuery('.picklist').on('change', function (e) {


            var picklist_id = jQuery(this).attr('id');
            var picklist_number = picklist_id.substr(picklist_id.length - 1);

            jQuery('#picklist_values' + picklist_number).find('option').remove().end().append('<option value="--">--</option>').val('--').trigger('liszt:updated');

            jQuery("#error_notice").hide();
            var loadingMessage = jQuery('.listViewLoadingMsg').text();

            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });

            var dataUrl = "index.php?module=VGSRelModUpdates&action=getPicklistValues&source_module=" + jQuery('#module' + picklist_number).val() + "&picklist_name=" + jQuery(this).val();
            AppConnector.request(dataUrl).then(
                    function (data) {

                        if (data.success) {

                            var result = data.result;
                            if (result.result == 'ok') {

                                jQuery.each(result.options, function (i, item) {

                                    var o = new Option(item, i);
                                    /// jquerify the DOM object 'o' so we can use the html method
                                    jQuery(o).html(item);
                                    jQuery('#picklist_values' + picklist_number).append(o);
                                });

                                jQuery('#picklist_values' + picklist_number).trigger('liszt:updated');

                            } else {
                                alert(app.tranlsate('JS_ERROR_LOADING_FIELDS_VALUES'));
                            }
                        }
                    },
                    function (error, err) {

                    }
            );

            progressIndicatorElement.progressIndicator({
                'mode': 'hide'
            });

        });

    },
    saveEntry: function () {
        jQuery('#add_entry').on('click', function (e) {

            jQuery(".notices").hide();
            var loadingMessage = jQuery('.listViewLoadingMsg').text();

            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });

            var params = {
                module: 'VGSRelModUpdates',
                action: 'saveRelatedFields',
                mode: 'addEntry',
                module1: jQuery("#module1").val(),
                picklist1: jQuery("#picklist1").val(),
                picklist_values1: jQuery("#picklist_values1").val(),
                module2: jQuery("#module2").val(),
                picklist2: jQuery("#picklist2").val(),
                picklist_values2: jQuery("#picklist_values2").val()
            };

            AppConnector.request(params).then(
                    function (data) {

                        if (data.success) {
                            var response = data.result;

                            if (response.result == 'ok') {

                                   window.location = 'index.php?module=VGSRelModUpdates&view=IndexSettings&parent=Settings';

                            } else {
                                
                                   jQuery(".alert").addClass('alert-danger').html(response.message);
                                
                            }
                        }
                    },
                    function (error, err) {

                    }
            );

            progressIndicatorElement.progressIndicator({
                'mode': 'hide'
            });

        });
    },
    deleteEntry: function () {
        jQuery('.deleteRecordButton').on('click', function (e) {

            jQuery(".notices").hide();
            var loadingMessage = jQuery('.listViewLoadingMsg').text();

            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });

            var params = {
                module: 'VGSRelModUpdates',
                action: 'saveRelatedFields',
                mode: 'deleteRecord',
                record_id: jQuery(this).attr('id'),
            };
            
            var line = jQuery(this).closest('tr');

            AppConnector.request(params).then(
                    function (data) {

                        if (data.success) {
                            var response = data.result;

                            if (response.result == 'ok') {
                                line.hide('slow');
                            

                            } else {
                               
                                 alert(app.tranlsate('JS_ERROR_DELETING'));   
                               
                            }
                        }
                    },
                    function (error, err) {

                    }
            );

            progressIndicatorElement.progressIndicator({
                'mode': 'hide'
            });

        });
    },
    registerEvents: function () {
        this.SourceModuleUpdate();
        this.targetModuleUpdate();
        this.loadPicklistValues();
        this.saveEntry();
        this.deleteEntry();
    }

});

jQuery(document).ready(function () {

    var instance = new VGSRelModUpdates_Js();
    instance.registerEvents();
});