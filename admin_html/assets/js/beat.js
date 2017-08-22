	// DETECT BROWSER
	var BrowserDetect = {
    init: function () {
        this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
        this.version = this.searchVersion(navigator.userAgent)
            || this.searchVersion(navigator.appVersion)
            || "an unknown version";
        this.OS = this.searchString(this.dataOS) || "an unknown OS";
    },
    searchString: function (data) {
        for (var i=0;i<data.length;i++)    {
            var dataString = data[i].string;
            var dataProp = data[i].prop;
            this.versionSearchString = data[i].versionSearch ||
			data[i].identity;
						if (dataString) {
							if (dataString.indexOf(data[i].subString) != -1)
								return data[i].identity;
						}
						else if (dataProp)
							return data[i].identity;
					}
				},
				searchVersion: function (dataString) {
					var index = dataString.indexOf(this.versionSearchString);
					if (index == -1) return;
					return
			parseFloat(dataString.substring(index+this.versionSearchString.length+1));
				},
				dataBrowser: [
					{
						string: navigator.userAgent,
						subString: "Chrome",
						identity: "Chrome"
					},
					{
						string: navigator.userAgent,
						subString: "OmniWeb",
						versionSearch: "OmniWeb/",
						identity: "OmniWeb"
					},
					{
						string: navigator.vendor,
						subString: "Apple",
						identity: "Safari",
						versionSearch: "Version"
					},
					{
						prop: window.opera,
						identity: "Opera"
					},
					{
						string: navigator.vendor,
						subString: "iCab",
						identity: "iCab"
					},
					{
						string: navigator.vendor,
						subString: "KDE",
						identity: "Konqueror"
					},
					{
						string: navigator.userAgent,
						subString: "Firefox",
						identity: "Firefox"
					},
					{
						string: navigator.vendor,
						subString: "Camino",
						identity: "Camino"
					},
					{        // for newer Netscapes (6+)
						string: navigator.userAgent,
						subString: "Netscape",
						identity: "Netscape"
					},
					{
						string: navigator.userAgent,
						subString: "MSIE",
						identity: "Explorer",
						versionSearch: "MSIE"
					},
					{
						string: navigator.userAgent,
						subString: "Gecko",
						identity: "Mozilla",
						versionSearch: "rv"
					},
					{         // for older Netscapes (4-)
						string: navigator.userAgent,
						subString: "Mozilla",
						identity: "Netscape",
						versionSearch: "Mozilla"
					}
				],
				dataOS : [
					{
						string: navigator.platform,
						subString: "Win",
						identity: "Windows"
					},
					{
						string: navigator.platform,
						subString: "Mac",
						identity: "Mac"
					},
					{
						string: navigator.platform,
						subString: "Linux",
						identity: "Linux"
					}
				]
			 
			};
			BrowserDetect.init();
			
$(function () {
	$(".col3:nth-child(3)").addClass('last');
	$(".col3:nth-child(6)").addClass('last');
	$(".col3s:nth-child(3)").addClass('col3s-last');
	$(".rows4:last-child").addClass('rows-last');
	$(".row3:last-child").addClass('rows-last');
});

$(function(){
	$('#file').customFileInput();	
});

// Slider
$(window).load(function(){
$("body").addClass(BrowserDetect.browser); 
  $('#slider').flexslider({
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
jQuery(document).ready(function($) {
	/*------------POP UP------------*/	
	jQuery("a.showPopup").click(function(){
		var targetID = jQuery(this).attr('href');
		$('html, body').animate({scrollTop: '0px'}, 800);
		jQuery(targetID).fadeIn();
		jQuery(targetID).addClass('visible');
		jQuery("#bgPopup").fadeIn();
	});
});
var Beat = function () {
	
	var chartColors, nav, navTop;
	
	chartColors = ["#00acec", "#F90", "#05a73e", "#f22c27"];
	
	
	return { start: start, chartColors: chartColors };
	
	function start () {

		nav = $('#nav');
		//navTop = nav.offset().top;
	
		bindNavEvents ();
		
		bindWidgetEvents ();
		
		bindAccordionEvents ();
		
		enableAutoPlugins ();
	}
	
	function enableAutoPlugins () {
		if ($.fn.tooltip) { 
			$('.ui-tooltip').tooltip (); 			
		}	
		
		if ($.fn.popover) { 
			$('.ui-popover').popover (); 			
		}		
		
		if ($.fn.lightbox) { 
			$('.ui-lightbox').lightbox();			
		}
		
		if ($.fn.dataTable) {
			$('.data-table').dataTable( {
				sDom: "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
				sPaginationType: "bootstrap",
				oLanguage: {
					"sLengthMenu": "_MENU_ records per page"
				}
			});
		}
	}
	
	function bindNavEvents () {
		
		var msie8 = $.browser.version === '8.0' && $.browser.msie;
		
		if (!msie8) {
			$(window).bind ('scroll', navScroll);
		}
				
		$('#info-trigger').live ('click', function (e) {
			
			e.preventDefault ();
			
			$('#info-menu').toggleClass ('toggle-menu-show');
			
			$(document).bind ('click.info', function (e) {
				
				if ($(e.target).is ('#info-menu')) { return false; }
				
				if ($(e.target).parents ('#info-menu').length == 1) { return false; }
				
				$('#info-menu').removeClass ('toggle-menu-show');
				
				$(document).unbind ('click.info');
				
			});
			
		});
	}
	
	function navScroll () {
		var p = $(window).scrollTop ();
		
		((p)>navTop) ? $('body').addClass ('nav-fixed') : $('body').removeClass ('nav-fixed');
		
	}
	
	function bindWidgetEvents () {
		$('.widget-tabs .nav-tabs a').live ('click', widgetTabClickHandler);
	}
	
	function bindAccordionEvents () {
		$('.widget-accordion .accordion').on('show', function (e) {
	         $(e.target).prev('.accordion-heading').parent ().addClass('open');
	    });
	
	    $('.widget-accordion .accordion').on('hide', function (e) {
	        $(this).find('.accordion-toggle').not($(e.target)).parents ('.accordion-group').removeClass('open');
	    });
	    
	    $('.widget-accordion .accordion').each (function () {	    	
	    	$(this).find ('.accordion-body.in').parent ().addClass ('open');
	    });
	}
	
	function widgetTabClickHandler (e) {
		e.preventDefault();
		$(this).tab('show');
	}
	
}();


$(function () {

	Beat.start ();

});

$(document).ready(function() { 
	$('.accept,.reject').click( function() {
		$(this).toggleClass('btn-grey');
	});
});