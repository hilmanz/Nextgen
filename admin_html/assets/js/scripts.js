$(document).ready(function() { 
	// photo effect
	/*$('.greyscale').load(function() {    
    $('.greyscale').pixastic("desaturate");
	}).each(function(index) {
		var clone = $(this).clone().removeClass('greyscale').addClass('greyscale-color').css('opacity',1);
		$(this).parent().append(clone);
		$('.greyscale').css( "width","100%" );
	});*/
	// Gallery Hover Effect
	/*jQuery(".thumb300 img").hover(function(){		
		jQuery(this).animate({ opacity: 0 }, 300);
	}, function(){
		jQuery(this).animate({ opacity: 1 }, 300);
	});*/
	
	$('a.hoverslide').hover_transitions({
		background_color_two: "#CC0000",
		show_method: "sliding_doors_horizontal",
		hide_method: "sliding_doors_horizontal",
		cols: 2,
		rows: 1,
		pattern_speed: 0
		
	});
	$('a.hoverslide2').hover_transitions({
		background_color_two: "#666",
		show_method: "sliding_doors_horizontal",
		hide_method: "sliding_doors_horizontal",
		cols: 2,
		rows: 1,
		pattern_speed: 0
		
	});
						   
	// Charatter Count
	 $('.textComment').simplyCountable();
	
	// Drop Down Menu
	$('ul#main-menu,ul#topNav').superfish({ 
        delay:       600,
        animation:   {opacity:'show',height:'show'},
        speed:       'fast',
        autoArrows:  true,
        dropShadows: false
    });

	// Accordion
	$( ".accordion" ).accordion( { autoHeight: false } );
	$(function() {
		$( ".accordions" ).accordion({
		  heightStyle: "content",
		  collapsible: true, active: false
		});
    });

	// Toggle
	$( ".toggle > .inner" ).hide();
	$(".toggle .title").toggle(function(){
		$(this).addClass("active").closest('.toggle').find('.inner').slideDown(200, 'easeOutCirc').css( "z-index","1000" );
	}, function () {
		$(this).removeClass("active").closest('.toggle').find('.inner').slideUp(200, 'easeOutCirc').css( "z-index","1000" );
	});
	jQuery("#right_menu .inner").mouseleave(function(){		
		$(".toggle,.toggle .title").removeClass("active").closest('.toggle').find('.inner').slideUp(200, 'easeOutCirc').css( "z-index","1000" );
	});
	

	// Tabs
	$(function() {
		$( "#tabs" ).tabs();
	});
	
	//Horizontal Sliding
	$('.boxgrid.slideright').hover(function(){
											
		$(".cover", this).stop().animate({left:'210px'},{queue:false,duration:500});
		$(".caption", this).stop().animate({left:'5px'},{queue:false,duration:500});

		
	}, function() {
		$(".cover", this).stop().animate({left:'0'},{queue:false,duration:500});
		$(".caption", this).stop().animate({left:'-202px'},{queue:false,duration:500});
	});
	
	
	// Popup
	$("a[rel=photogroup]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over',
		'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
		}
	});
	$(".showPopup").fancybox({
		'titlePosition'		: 'inside',
		'transitionIn'		: 'none',
		'transitionOut'		: 'none'
	});
	// PrettyPhoto
	$(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto();
	});
	
	// Slides Loader
	$(".slider").removeClass("slide-loader");
	$(".slider-single").removeClass("slide-loader-single");
	
	// Mobile Menu

	// Create the dropdown base
	$("<select />").appendTo("#main-menu-wrapper");
      
	// Create default option "Go to..."
	$("<option />", {
		"selected": "selected",
		"value"   : "",
		"text"    : "Go to..."
	}).appendTo("#main-menu-wrapper select");
      
	// Populate dropdown with menu items
	$("#main-menu a").each(function() {
		var el = $(this);
		$("<option />", {
			"value"   : el.attr("href"),
			"text"    : el.text()
		}).appendTo("#main-menu-wrapper select");
	});
	
	// To make dropdown actually work
	$("#main-menu-wrapper select").change(function() {
		window.location = $(this).find("option:selected").val();
	});

	// Quantity Buttons
	$(function() {

		$("form .qty-text").before('<input type="button" class="plusminus minus" id="minus1" value="-">');
		$("form .qty-text").after('<input type="button" class="plusminus plus" id="plus1" value="+">');

		$(".plusminus").click(function() {
			var $button = $(this);
			var oldValue = $button.parent().find(".qty-text").val();
			
			if ($button.val() == "+") {
				var newVal = parseFloat(oldValue) + 1;
			} 
			
			else {		
				if (oldValue > 1) {
					var newVal = parseFloat(oldValue) - 1;
				}
				
				else {
					var newVal = 1;
				}
			}
	
			$button.parent().find(".qty-text").val(newVal);
	
	    });

	});
	
});
//player

// Slider
$(window).load(function(){
  $('#carousel').flexslider({
	controlNav: false,
	directionNav: false,
	animationLoop: false,
	slideshow: true,
	itemWidth: 240,
	itemMargin: 0,
	asNavFor: '#slider'
  });
  $('#slider').flexslider({
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	directionNav: false,
	slideshow: true,
	sync: "#carousel",
	start: function(slider){
	  $('body').removeClass('loading');
	}
  });
  $('#slidernews').flexslider({
	animation: "slide",
	controlNav: true,
	animationLoop: false,
	directionNav: false,
	slideshow: true,
	start: function(slider){
	  $('body').removeClass('loading');
	}
  });
  $('.widgetBanner').flexslider({
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	directionNav: true,
	slideshow: true,
	start: function(slider){
	  $('body').removeClass('loading');
	}
  });
});

$(function(){
	var $container = $('#photo_gallery,#searchPage');
	
	$container.imagesLoaded( function(){
	  $container.masonry({
		itemSelector : '.box'
	  });
	});
});
$(function() {

$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

});