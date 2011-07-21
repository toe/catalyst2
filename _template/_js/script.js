/* Author: 

*/


$(document).ready(function(){
	//hover states on the static widgets
    $('.item-icon').hover(
        function() { $(this).addClass('ui-state-hover'); }, 
        function() { $(this).removeClass('ui-state-hover'); }
    );
    
    $('.item-icon').click(function(){
    	if($(this).children('span').hasClass("ui-icon-minus")){hideSubItems($(this));return false;}
    	if($(this).children('span').hasClass("ui-icon-plus")){showSubItems($(this));return false;}
    	if($(this).children('span').hasClass("ui-icon-bullet")){makeInactive($(this));return false;}
    	if($(this).children('span').hasClass("ui-icon-radio-on")){makeActive($(this));return false;}
    	if($(this).children('span').hasClass("ui-icon-circle-close")){deleteItem($(this));return false;}
    });    
});


function showSubItems(obj){

	var item = $(obj).parent().parent();
	$(item).children(".sub-items").slideDown(500, 'linear');
	$(obj).children('span').removeClass("ui-icon-plus");
	$(obj).children('span').addClass("ui-icon-minus");
	
}

function hideSubItems(obj){
	var item = $(obj).parent().parent();
	$(item).children(".sub-items").slideUp(500, 'linear');
	$(obj).children('span').removeClass("ui-icon-minus");
	$(obj).children('span').addClass("ui-icon-plus");
	
}

function makeActive(obj){
	var item = $(obj).parent().parent();
	$(obj).children('span').removeClass("ui-icon-radio-on");
	$(obj).children('span').addClass("ui-icon-bullet");
}

function makeInactive(obj){
	var item = $(obj).parent().parent();
	$(obj).children('span').removeClass("ui-icon-bullet");
	$(obj).children('span').addClass("ui-icon-radio-on");
}


function deleteItem(obj){
	var item = $(obj).parent().parent();
	$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				"Delete all items": function() {
					$( this ).dialog( "close" );
					console.log("delete items");
				},
				Cancel: function() {
					$( this ).dialog( "close" );
					console.log("don't delete items");
				}
			}
		});
}