jQuery(document).ready(function() {

	jQuery('.listViewEntries').on('click','.clvPopup-btn',function(e) {
		e.stopPropagation();
	});

	jQuery('body').on('click','.nav-stacked li',function(e) {
		setTimeout( function(){ checkElement(); }, 200);
	});

	clvPopup();
});

function checkElement(){
	if (jQuery('.blockOverlay').html()!=null) {
		setTimeout( function(){ checkElement(); }, 200);
	} else {
		clvPopup();
	}	
}

function clvPopup(){
	var related = getUrlVar("relatedModule");
	if ((getUrlVar("Module")=="Campaigns")&&((related=="Contacts")||(related=="Leads")||(related=="Accounts"))) {
		jQuery('.listViewHeaders').children().eq(1).before('<th width="6%">&nbsp;</th>');
		jQuery('.listViewEntries').each(function(index) {
			var url = jQuery(this).children().eq(1).children("a").attr("href");
			jQuery(this).children().eq(1).before('<td width="6%"><button class="btn clvPopup-btn" type="button">Detail</button></td>');
			jQuery(this).children().eq(1).popupWindow({ 
				height:600, 
				width:1200, 
				top:150, 
				left:150,			
				windowURL:url+'&popup_parent='+getUrlVar("record"), 
				windowName:related 
			}); 
		});	
	}

	if ((getUrlVar("Module")=="Contacts")&&(getUrlVar("popup_parent")!="")) {
		if ((getUrlVar("relatedModule")=="Calendar")||(getUrlVar("relatedModule")=="Potentials")) {
			var addButton = jQuery('.relatedHeader').find('button').eq(0);
			if (getUrlVar("relatedModule")=="Calendar") {
				var key = 'parent_id';
				addButton.attr('data-url',addButton.attr('data-url')+'&'+key+'='+getUrlVar("popup_parent"))
				addButton = jQuery('.relatedHeader').find('button').eq(1);
				addButton.attr('data-url',addButton.attr('data-url')+'&'+key+'='+getUrlVar("popup_parent"))
			}
			if (getUrlVar("relatedModule")=="Potentials") {
				var key = 'campaignid';			
			}
			addButton.attr('data-url',addButton.attr('data-url')+'&'+key+'='+getUrlVar("popup_parent"))
		} else {
			jQuery('.createActivity').attr('data-url',jQuery('.createActivity').attr('data-url')+'&parent_id='+getUrlVar("popup_parent"))
			jQuery('.nav-stacked li').each(function(index) {
				if (jQuery(this).attr('data-url').indexOf('relatedModule=Calendar')>0) {
					jQuery(this).attr('data-url',jQuery(this).attr('data-url')+'&popup_parent='+getUrlVar("popup_parent"))
				}
				if (jQuery(this).attr('data-url').indexOf('relatedModule=Potentials')>0) {
					jQuery(this).attr('data-url',jQuery(this).attr('data-url')+'&popup_parent='+getUrlVar("popup_parent"))
				}
			});
		}
	}
}

function getUrlVar(key){
    var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search); 
    return result && unescape(result[1]) || ""; 
}
