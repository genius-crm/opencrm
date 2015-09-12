jQuery(document).ready(function() {

	jQuery.ajax({
		dataType: 'text',
		url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=check&source'+window.location.search.substring(1)
	});

	jQuery('body').on('click','.btn-primary',function(e) {
	    var strings = jQuery.parseJSON(jQuery('#js_strings').html());
		if(strings.LBL_DELETE_CONFIRMATION==jQuery(this).parents('.bootbox').children('.modal-body').html()) {
			if (((getUrlVar("Module")=="Invoice")||(getUrlVar("Module")=="Quotes"))&&(getUrlVar("View")=="Detail")) {
				jQuery.ajax({
					dataType: 'text',
					url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=delete&entity='+getUrlVar("Record")+'&line=-1'
				});
			}
		}
	});

	jQuery('.editViewContainer').on('click','.discounts, .finalDiscounts',function(e) {
		var thisInstance = jQuery(this);
		var discountInputType = thisInstance.attr("data-discount-type");
		var discountInputName = thisInstance.attr("name");
		var discountInputNumber = discountInputName.replace("discount","");
		if (discountInputType!="percentage") {
			jQuery('#composite_discount' + discountInputNumber).addClass("hide");
		} else {
			jQuery('#composite_discount' + discountInputNumber).removeClass("hide");
		}
	});

	jQuery('.editViewContainer').on('click','.individualDiscount',function(e) {
		var discount = jQuery(this).parents('td').find('.discounts').eq(1);
		var discountInputName = discount.attr("name");
		var discountInputNumber = discountInputName.replace("discount","");
		var tdElement = discount.parent();
		var content = tdElement.html();
		if (content.indexOf("0")>0) {
			content = content.replace(/0/g,discountInputNumber);
			tdElement.html(content);
		}
	});

	jQuery('.editViewContainer').on('blur','.composite_discount',function(e) {
		var thisInstance = jQuery(this);
		var discountInputName = thisInstance.attr("name");
		var discountInputNumber = discountInputName.replace("composite_discount","");
		if (discountInputNumber=="_final") {
			var initPrice = jQuery('#netTotal').html();
		} else {
			var initPrice = jQuery('#listPrice' + discountInputNumber).val();
		}
		var value = thisInstance.val();
		var discountUnit = new Array();
		discountUnit = value.split("+")
		var arrayLength = discountUnit.length;
		var price = initPrice;		
		for (var i = 0; i < arrayLength; i++) {
			if (discountUnit[i]!="") {
				price = price - (price*parseFloat(discountUnit[i])/100);
			}
		}
		var discountTotal = (1-(price/initPrice))*100;
		discountTotal = discountTotal.toFixed(2);
		if (discountTotal!=0) {
			jQuery('#discount_percentage' + discountInputNumber).val(discountTotal);
		}
	});

	jQuery('#EditView').submit(function(e) {
		var recordId = jQuery('input[name=record]').val();
		var lineId = 0;
		jQuery.each(jQuery('.lineItemRow'),function(index) {
			var thisInstance = jQuery(this);
			var discountInput = thisInstance.find('.composite_discount');
			if (typeof discountInput.val() != 'undefined') {
				lineId = index+1;
				var value = "";
				if (jQuery('input[name=discount' + lineId + ']').eq(1).prop('checked')==true) {
					value = discountInput.val();
					value = encodeURIComponent(value);
				}
				jQuery.ajax({
					async: false,
					url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=save&entity='+recordId+'&line='+lineId+'&composite='+value
				});		
			}
		});
		var discountInputFinal = jQuery('#composite_discount_final');
		if (typeof discountInputFinal.val() != 'undefined') {
			var value = "";
			if (jQuery('input[name=discount_final]').eq(1).prop('checked')==true) {
				value = discountInputFinal.val();
				value = encodeURIComponent(value);
			}
			jQuery.ajax({
				async: false,
				url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=save&entity='+recordId+'&line=0&composite='+value
			});		
			jQuery.ajax({
				async: false,
				url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=delete&entity='+recordId+'&line='+lineId
			});		
		}
	});

	jQuery('.discounts').each(function(index) {
		var recordId = jQuery('input[name=record]').val();
		if (getUrlVar("isDuplicate")=="true") {
			recordId = getUrlVar("record");
		}
		var thisInstance = jQuery(this);
		var discountInputType = thisInstance.attr("data-discount-type");
		if (discountInputType=="percentage") {
			var discountInputName = thisInstance.attr("name");
			var discountInputNumber = discountInputName.replace("discount","");
			var element = jQuery('input[name=discount' + discountInputNumber + ']').eq(1);
			var tdElement = element.parent();
			var content = tdElement.html();

			content = content.replace("type=\"radio\"","type=\"radio\" checked");
			content = content + '&nbsp;&nbsp;<input id="composite_discount' + discountInputNumber + '" name="composite_discount' + discountInputNumber + '" value="" class="composite_discount smallInputBox pull-right" type="text">';
			tdElement.html(content);
			if (discountInputNumber!=0) {
				jQuery.ajax({
					dataType: 'text',
					url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=load&entity='+recordId+'&line='+discountInputNumber,
					complete: function(response) {
						if (response.responseText != 'undefined') {
							jQuery('#composite_discount' + discountInputNumber).val(response.responseText);
						}
					}
				});
			}
		}
	});
	
	jQuery('.finalDiscounts').each(function(index) {
		var recordId = jQuery('input[name=record]').val();
		if (getUrlVar("isDuplicate")=="true") {
			recordId = getUrlVar("record");
		}
		var thisInstance = jQuery(this);
		var discountInputType = thisInstance.attr("data-discount-type");
		if (discountInputType=="percentage") {
			var element = jQuery('input[name=discount_final]').eq(1);
			var tdElement = element.parent();
			var content = tdElement.html();

			content = content.replace("type=\"radio\"","type=\"radio\" checked");
			content = content + '&nbsp;&nbsp;<input id="composite_discount_final" name="composite_discount_final" value="" class="composite_discount smallInputBox pull-right" type="text">';
			tdElement.html(content);
			jQuery.ajax({
				dataType: 'text',
				url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=load&entity='+recordId+'&line=0',
				complete: function(response) {
					if (response.responseText != 'undefined') {
						jQuery('#composite_discount_final').val(response.responseText);
					}
				}
			});
		}
	});

	jQuery('.inventoryLineItemDetails').each(function(index) {
		var thisInstance = jQuery(this);
		var recordId = jQuery('#recordId').val();	
		var lineId = index+1;
		var info = thisInstance.attr("data-info");
		if (thisInstance.attr("id")=="finalDiscount") {
			lineId = 0;
		}
		jQuery.ajax({
			dataType: 'text',
			url: 'index.php?module=Discount&action=DiscountAjax&file=DiscountAjax&do=load&entity='+recordId+'&line='+lineId,
			complete: function(response) {
				if (response.responseText != 'undefined') {
					if (response.responseText != '') {
						thisInstance.attr("data-info",response.responseText+" : "+info);
						thisInstance.html(thisInstance.html()+" ("+response.responseText+")");
					}
				}
			}
		});
	});	

});

function getUrlVar(key){
    var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search); 
    return result && unescape(result[1]) || ""; 
}