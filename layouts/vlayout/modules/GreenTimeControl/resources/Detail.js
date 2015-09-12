/*+***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 *************************************************************************************************************************************/

jQuery(function() {

	if(jQuery('#GreenTimeControl_editView_fieldName_date_start')){
		jQuery('#GreenTimeControl_editView_fieldName_date_start').live('change',function(){
			$('input[name="sum_time"]').attr('readonly','readonly').css('width', '80px');
			$('input[name="sum_time"]').val('');

			sumeTime = differenceDays();
			if(sumeTime == 'Error'){
				alert("Start Date should be greater End Date");
				return false;
			}

			hours = (Math.round( (sumeTime/3600000) * 100 ) / 100).toFixed(2);
			jQuery('input[name="sum_time"]').val(hours);
		});
	}
	if(jQuery('#GreenTimeControl_editView_fieldName_due_date')){
		jQuery('#GreenTimeControl_editView_fieldName_due_date').live('change',function(){
			$('input[name="sum_time"]').attr('readonly','readonly').css('width', '80px');
			$('input[name="sum_time"]').val('');

			sumeTime = differenceDays();
			if(sumeTime == 'Error'){
				alert("Start Date should be greater End Date");
				return false;
			}

			hours = (Math.round( (sumeTime/3600000) * 100 ) / 100).toFixed(2);
			jQuery('input[name="sum_time"]').val(hours);
		});
	}

	if(jQuery('#GreenTimeControl_editView_fieldName_time_start')){
		jQuery('#GreenTimeControl_editView_fieldName_time_start').live('change',function(){
			$('input[name="sum_time"]').attr('readonly','readonly').css('width', '80px');
			$('input[name="sum_time"]').val('');
			
			sumeTime = differenceDays();
			if(sumeTime == 'Error'){
				alert("Start Date should be greater End Date");
				return false;
			}
			hours = (Math.round( (sumeTime/3600000) * 100 ) / 100).toFixed(2);
			jQuery('input[name="sum_time"]').val(hours);
		});
	}
	if(jQuery('#GreenTimeControl_editView_fieldName_time_end')){
		jQuery('#GreenTimeControl_editView_fieldName_time_end').live('change',function(){
			$('input[name="sum_time"]').attr('readonly','readonly').css('width', '80px');
			$('input[name="sum_time"]').val('');
			
			sumeTime = differenceDays();
			if(sumeTime == 'Error'){
				alert("Start Date should be greater End Date");
				return false;
			}
			hours = (Math.round( (sumeTime/3600000) * 100 ) / 100).toFixed(2);
			jQuery('input[name="sum_time"]').val(hours);
		});
	}

	jQuery('.btn-success').live('click',function(){
		if(jQuery('#GreenTimeControl_editView_fieldName_date_start').length >0 && jQuery('#GreenTimeControl_editView_fieldName_due_date').length >0 && jQuery('#GreenTimeControl_editView_fieldName_time_start').length >0 && jQuery('#GreenTimeControl_editView_fieldName_time_end').length >0) {
			$('input[name="sum_time"]').attr('readonly','readonly').css('width', '80px');
			$('input[name="sum_time"]').val('');
			var sumeTime = differenceDays();
			if(sumeTime == 'Error'){
				alert("Start Date should be greater End Date");
				return false;
			}else{
				hours = (Math.round( (sumeTime/3600000) * 100 ) / 100).toFixed(2);
				jQuery('input[name="sum_time"]').val(hours);
				return true;
			}
		}
	});

	function differenceDays() {
		var firstDate = jQuery('input[name="date_start"]');
		var firstDateFormat = firstDate.data('date-format');
		var firstDateValue = firstDate.val();
		var secondDate = jQuery('input[name="due_date"]');
		var secondDateFormat = secondDate.data('date-format');
		var secondDateValue = secondDate.val();
		var firstTime = jQuery('input[name="time_start"]');
		var secondTime = jQuery('input[name="time_end"]');
		var firstTimeValue = firstTime.val();
		var secondTimeValue = secondTime.val();
		var firstDateTimeValue = firstDateValue + ' ' + firstTimeValue;
		var secondDateTimeValue = secondDateValue + ' ' + secondTimeValue;
		var firstDateInstance = getDateInstance(firstDateTimeValue,firstDateFormat);
		var secondDateInstance = getDateInstance(secondDateTimeValue,secondDateFormat);

		var timeBetweenDates =  secondDateInstance - firstDateInstance;
		if(timeBetweenDates >= 0){
			return timeBetweenDates;
		}
        return 'Error';
	}

	function getDateInstance(dateTime,dateFormat) {
		var dateTimeComponents = dateTime.split(" ");
		var dateComponent = dateTimeComponents[0];
		var timeComponent = dateTimeComponents[1];
        var seconds = '00';

		var splittedDate = dateComponent.split("-");
		var splittedDateFormat = dateFormat.split("-");
		var year = splittedDate[splittedDateFormat.indexOf("yyyy")];
		var month = splittedDate[splittedDateFormat.indexOf("mm")];
		var date = splittedDate[splittedDateFormat.indexOf("dd")];
        var dateInstance = Date.parse(year+"-"+month+"-"+date);
		if((year.length > 4) || (month.length > 2) || (date.length > 2) || (dateInstance == null)){
			alert("Invlida Date");
		}

		if(typeof timeComponent == "undefined"){
			timeComponent = '00:00:00';
		}

        var timeSections = timeComponent.split(':');
        if(typeof timeSections[2] != 'undefined'){
            seconds = timeSections[2];
        }

		if(typeof dateTimeComponents[2] != 'undefined') {
			timeComponent += ' ' + dateTimeComponents[2];
            if(dateTimeComponents[2].toLowerCase() == 'pm' && timeSections[0] != '12') {
                timeSections[0] = parseInt(timeSections[0], 10) + 12;
            }

            if(dateTimeComponents[2].toLowerCase() == 'am' && timeSections[0] == '12') {
                timeSections[0] = '00';
            }
		}
        month = month-1;
		var dateInstance = new Date(year,month,date,timeSections[0],timeSections[1],seconds);
        return dateInstance;
	}
});