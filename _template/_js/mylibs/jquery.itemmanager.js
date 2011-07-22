/* Author: Pablo Alejo
 * This plugin will run the item manager which will make
 * manageing the item list easy to manage.
 * This will manage item sorting, item status, item deletion.
*/

(function($) {
	$.itemManager = {};
	
	$.itemManager.globalSettings = function(options){
		settings = {
			manageSort : false,
			manageChildren : false,
			manageStatus: false,
			manageDelete: false
		}
		
		if(options && typeof options == "object") $.extend(settings, options);
		
		return this;
	}; // $.itemManager.globalSettings
	/**
	* Set up the itemManager. 
	*/
	$.itemManager.init = function(){
		settings = $.extend(settings, $.itemManager.globalSettings.settings);

    // Initialize
    $(document).ready(_init);

    // For chaining
    return this;
    
    function _init(){
    	//hover states on the static widgets
	    $('.item-icon').hover(
	        function() { $(this).addClass('ui-state-hover'); }, 
	        function() { $(this).removeClass('ui-state-hover'); }
	    );
	    
	    $('.item-icon').click(function(){
	    	if($(this).children('span').hasClass("ui-icon-minus") && settings.manageChildren){$.itemManager.hideSubItems($(this));return false;}
	    	if($(this).children('span').hasClass("ui-icon-plus") && settings.manageChildren){$.itemManager.showSubItems($(this));return false;}
	    	if($(this).children('span').hasClass("ui-icon-bullet") && settings.manageStatus){$.itemManager.makeInactive($(this));return false;}
	    	if($(this).children('span').hasClass("ui-icon-radio-on") && settings.manageStatus){$.itemManager.makeActive($(this));return false;}
	    	if($(this).children('span').hasClass("ui-icon-circle-close") && settings.manageDelete){$.itemManager.deleteItem($(this));return false;}
	    });
	    
	    if(settings.manageSort){$( ".items" ).sortable({handle:".item-handle "});}
    }
	}; // end $.itemManager.init = function()
	/**
	* Show the sub items. Sub-pages or whatever. This is a 
	* tree like expansion.  
	*/
	$.itemManager.showSubItems = function(obj){
		var item = $(obj).parent().parent();
		$(item).children(".sub-items").slideDown(200, 'linear');
		$(obj).children('span').removeClass("ui-icon-plus");
		$(obj).children('span').addClass("ui-icon-minus");	
	}; // end $.itemManager.showSubItems = function(obj)
	
	/**
	* Hide the sub items.
	*/
	$.itemManager.hideSubItems = function(obj){
		var item = $(obj).parent().parent();
		$(item).children(".sub-items").slideUp(200, 'linear');
		$(obj).children('span').removeClass("ui-icon-minus");
		$(obj).children('span').addClass("ui-icon-plus");
	}; // end $.itemManager.hideSubItems = function(obj)
	
	/**
	* Make the item active or inactive. This will use
	* AJAX to connect to the record and set it to Active.
	*/
	$.itemManager.makeActive = function(obj){
		var item = $(obj).parent().parent();
		$(obj).children('span').removeClass("ui-icon-radio-on");
		$(obj).children('span').addClass("ui-icon-bullet");
	}; // end $.itemManager.makeActive = function(obj)
	/**
	* Make the item active or inactive. This will use
	* AJAX to connect to the record and set it to Inactive.
	*/
	$.itemManager.makeInactive = function(obj){
		var item = $(obj).parent().parent();
		$(obj).children('span').removeClass("ui-icon-bullet");
		$(obj).children('span').addClass("ui-icon-radio-on");
	}; // end $.itemManager.makeInactive = function(obj)
	/**
	* Delete the item. This will use AJAX to connect to the 
	* database and delete the record. There will need to be
	* some checking to make sure that this item does not
	* have any children. The plan is described below. 
	*/
	$.itemManager.deleteItem = function(obj){
		var item = $(obj).parent().parent().parent();
		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				"Delete item": function() {
					$( this ).dialog( "close" );
					//console.log("Delete item");
					// When a user confirms the deletion, check to see if the the
					// item has any children. If it does then do not delete the item,
					// notify the user and give them the opportunity to give the children
					// a new parent or to delete the children with the parent.
					$(item).slideUp(200);
				},
				Cancel: function() {
					$( this ).dialog( "close" );
					//console.log("don't delete items");
				}
			}
		});
	}; // end $.itemManager.deleteItem = function(obj)
	

})(jQuery);