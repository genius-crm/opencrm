jQuery(document).ready(function() {
 Autofill();
 
});

function Autofill(){
//alert ("test1");
 if ((getUrlVar("Module")=="Accounts")&&(getUrlVar("view")=="Detail")) {
  var related = getUrlVar("relatedModule");
  //if (getUrlVar("relatedModule")=="Contacts") {
  //var addButton = jQuery('.relatedHeader').find('button').eq(0);
 // console.log(addButton);
 
   jQuery.ajax({
    dataType: 'text',
    async: true,
    url: 'index.php?module=Autofill&action=AutofillAjax&file=AutofillAjax&do=load&entity='+getUrlVar("record"),
    complete: function(response) {

    // alert (response.responseText);
     if (response.responseText != 'undefined') {
      var result = response.responseText;
      var data = result.split("||");
      var addButton = jQuery('.relatedHeader').find('button').eq(0);
var parametri= addButton.attr('data-url');
if (parametri !=undefined) alert ("Adding Record");
      addButton.attr('data-url',parametri+'&phone='+data[0]+'&email='+data[1]);
    
    }
   }});
  }
 //}
}

function getUrlVar(key){
    var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search); 
    return result && unescape(result[1]) || ""; 
}
