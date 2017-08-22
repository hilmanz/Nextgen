$(document).ready(function() {
	$('.showPopup').fancybox();

});

$(document).ready(function() { 	
// accordion code
	$(".accordion").accordion({
    	collapsible: true,
        autoHeight: false
	});
// date picker
	$(function() {
		$( "#from" ).datepicker({
		currentDate:"setDate",
		dateFormat:"yy-mm-dd",
		//defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		$( "#to" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#to" ).datepicker({
		dateFormat:"yy-mm-dd",
		//defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		$( "#from" ).datepicker( "option", "maxDate", selectedDate );
		}
		});
	});

});
//tabs
$(function() {
	$("#tabs").organicTabs({
		"speed": 200
	});


// Javascript originally by Patrick Griffiths and Dan Webb.
// http://htmldog.com/articles/suckerfish/dropdowns/
sfHover = function() {
   var sfEls = document.getElementById("main-menu").getElementsByTagName("li");
   for (var i=0; i<sfEls.length; i++) {
      sfEls[i].onmouseover=function() {
         this.className+=" hover";
      }
      sfEls[i].onmouseout=function() {
         this.className=this.className.replace(new RegExp(" hover\\b"), "");
      }
   }
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
});	