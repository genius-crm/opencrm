/**
 * VGS Dynamic Blocks
 *
 * @package        VGS Dynamic Blocks
 * @author         Conrado Maggi
 * @license        Commercial
 * @copyright      2014 VGS Global - www.vgsglobal.com
 * @version        Release: 1.0
 */

jQuery.Class('VGSDynamicBlocks_Js', {}, {
    /**
     * Function to register change event for source module field
     */
    registerOnChangeEventOfSourceModule: function () {
        jQuery('#sourceModule').on('change', function (e) {
            jQuery('#picklistFields').find('option').remove().end().append('<option value="--">--</option>').val('--');
            jQuery("#picklistFields").trigger('liszt:updated');
            jQuery('#picklistFieldValues').find('option').remove().end().append('<option value="--">--</option>').val('--');
            jQuery("#picklistFieldValues").trigger('liszt:updated');
            jQuery("#fieldsSelect").select2('data', null);

            var element = jQuery(e.currentTarget);
            var params = {};
            var sourceModule = element.val();

            params = {
                'module': 'VGSDynamicBlocks',
                'action': "VGSDynamicBlocksAjax",
                'mode': "changeModule",
                'sourceModule': sourceModule
            }

            AppConnector.request(params).then(
                    function (data) {
                        if (data) {
                            jQuery.each(data.result.options, function (i, item) {

                                var o = new Option(item, i);
                                jQuery(o).html(item);
                                jQuery("#picklistFields").append(o);
                                jQuery("#picklistFields").trigger('liszt:updated');

                            });
                        }
                    },
                    function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus);
                    });
        });
    },
    /**
     * Function to register change event for source module field
     */
    registerOnChangeEventOfPicklistField: function () {
        jQuery('#picklistFields').on('change', function (e) {
            jQuery('#picklistFieldValues').find('option').remove().end().append('<option value="--">--</option>').val('--');
            jQuery("#picklistFieldValues").trigger('liszt:updated');
            jQuery("#fieldsSelect").select2('data', null);
            var element = jQuery(e.currentTarget);
            var params = {};
            var picklistField = element.val();

            params = {
                'module': 'VGSDynamicBlocks',
                'action': "VGSDynamicBlocksAjax",
                'mode': "changePicklistField",
                'sourceModule': jQuery('#sourceModule').val(),
                'picklistField': picklistField
            }

            AppConnector.request(params).then(
                    function (data) {
                        if (data) {
                            jQuery.each(data.result.options, function (i, item) {

                                var o = new Option(item, i);
                                jQuery(o).html(item);
                                jQuery("#picklistFieldValues").append(o);
                                jQuery("#picklistFieldValues").trigger('liszt:updated');

                            });
                        }
                    },
                    function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus);
                    });
        });
    },
    /**
     * Function to register change event for source module field
     */
    registerOnChangeEventOfPicklistValue: function () {
        jQuery('#picklistFieldValues').on('change', function (e) {
            jQuery("#fieldsSelect").select2('data', null);
            var element = jQuery(e.currentTarget);
            var params = {};

            params = {
                'module': 'VGSDynamicBlocks',
                'action': "VGSDynamicBlocksAjax",
                'mode': "changePicklistValue",
                'sourceModule': jQuery('#sourceModule').val(),
                'picklistField': jQuery("#picklistFields").val(),
                'picklistFieldValue': element.val()
            }

            AppConnector.request(params).then(
                    function (data) {
                        if (data) {
                            jQuery('#fieldSelectTD').html(data.result.caption);
                            app.showSelect2ElementView(jQuery('body').find('select.select2'));
                        }
                    },
                    function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus);
                    });
        });
    },
    /**
     * Function to register event for saving module custom numbering
     */
    saveVGSDynamicBlocks: function () {
        jQuery('.saveButton').on('click', function (e) {
            params = {
                'module': app.getModuleName(),
                'action': "VGSDynamicBlocksAjax",
                'mode': "saveColumnsForModule",
                'sourceModule': jQuery('#sourceModule').val(),
                'picklistField': jQuery("#picklistFields").val(),
                'picklistFieldValue': jQuery("#picklistFieldValues").val(),
                'fields': jQuery('#fieldsSelect').val()
            }
            AppConnector.request(params).then(
                    function (data) {
                        var params;
                        if (data.success == true) {
                            params = {
                                animation: "show",
                                type: 'info',
                                text: data.result.caption
                            };
                            Vtiger_Helper_Js.showPnotify(params);
                            document.location = "index.php?parent=Settings&module=VGSDynamicBlocks&view=SettingList";
                        } else {
                            var errorMessage = app.vtranslate(data.error.message);
                            params = {
                                animation: "show",
                                text: errorMessage,
                                type: 'error'
                            };
                            Vtiger_Helper_Js.showPnotify(params);
                        }
                    },
                    function (jqXHR, textStatus, errorThrown) {
                    });
        });
    },
    deleteVGSDynamicBlocks: function () {
        jQuery('.deleteRecordButton').on('click', function (e) {

            params = {
                'module': app.getModuleName(),
                'action': "VGSDynamicBlocksAjax",
                'mode': "deleteColumnsForModule",
                'record_id': jQuery(this).attr('id'),
            }
            
            var line_id = jQuery(this).attr('id');
            AppConnector.request(params).then(
                    function (data) {
                        var params;
                        if (data.success == true) {
                            jQuery('#'+line_id).closest('tr').hide();
                            params = {
                                animation: "show",
                                type: 'info',
                                text: data.result.caption
                            };
                            Vtiger_Helper_Js.showPnotify(params);
                            
                        } else {
                            var errorMessage = app.vtranslate(data.error.message);
                            params = {
                                animation: "show",
                                text: errorMessage,
                                type: 'error'
                            };
                            Vtiger_Helper_Js.showPnotify(params);
                        }
                    },
                    function (jqXHR, textStatus, errorThrown) {
                    });
        });
    },
    /**
     * Function to register events
     */
    registerEvents: function () {
        var thisInstance = this;
        this.registerOnChangeEventOfSourceModule();
        this.registerOnChangeEventOfPicklistField();
        this.registerOnChangeEventOfPicklistValue();
        this.deleteVGSDynamicBlocks();
        this.saveVGSDynamicBlocks();
    }
})
jQuery(document).ready(function () {
    var VGSDynamicBlocksInstance = new VGSDynamicBlocks_Js();
    VGSDynamicBlocksInstance.registerEvents();
});
