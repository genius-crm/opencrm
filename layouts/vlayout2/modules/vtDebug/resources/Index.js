/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("vtDebug_Index_Js",{},{
	
	
	registerEvents : function() {
		return this;
	}
	});

jQuery(document).ready(function() {
	var instance = new vtDebug_Index_Js();
	instance.registerEvents()
})