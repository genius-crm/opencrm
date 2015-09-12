<?php
/**
 * VGS Related Module Updates
 *
 *
 * @package        VGSRelModUpdates Module
 * @author         Conrado Maggi
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 * @version        Release: 1.0
 */

$languageStrings = array(
    'LBL_MODULE_NAME' =>'VGS Related Module Updates',
    'notice'=>'<p> <b>Important:</b> This module allows to update a picklist field '
    . ' of a related module. Remember that the login user MUST have read/write access to the related record being updated <br><br> User sharing access rules &  profiles are still valid. If the user cannot edit a field, this will not be updated. '
    . ' <br><br>If the target picklist is Role Base please make sure the user can choose that value in picklist editor',
    'edit' => 'Edit',
    'SOURCE_MODULE_NAME'=>'Source Module',
    'SOURCE_FIELD_LABEL'=>'Source Field',
    'SOURCE_FIELD_VALUE'=>'Value',
    'TARGET_MODULE_NAME' => 'Target Module',
    'TARGET_FIELD_LABEL'=>'Target Field',
    'TARGET_FIELD_VALUE'=>'Value to Set',
    'ACTIONS'=>'Actions',
    'ADD_NEW'=>'Add New',
    'ADD_NEW_UPDATE'=>'Add a update rutine',
    'ADD_NEW_UPDATE_EXPLAIN'=>'Start by selecting your source module, the dropdown field and its value. Then, select the target module and which field and value you need to set '
    . 'once the source field its set the defined value.',
    'SAVE'=>'Save',
    'LBL_DB_INSERT_FAIL' => 'The database insert fail. Please enable Vtiger debug and check the error log',
    'ALREADY_EXISTS'=>'Entry already exists',
    
);
$jsLanguageStrings = array(

        'JS_ERROR_LOADING_FIELDS'=>'Not posible to load the picklist fields',
        'JS_ERROR_LOADING_RELATED_MODULES'=>'Error loading the related modules',
        'JS_ERROR_LOADING_FIELDS_VALUES'=>'Error loading the picklist values',
        'JS_ERROR_DELETING'=>'The entry could not be deleted',
    
    
);
