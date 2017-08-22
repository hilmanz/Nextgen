		
		 
		
		/* ------------------  pagination ------------------- */
		
		function getpaging(start,total_rows,contentPaging,pagingfunction,itemperpage){
				if(start == 0)start=1;
				if(total_rows == 0) total_rows=0;
				kiPagination(total_rows, start, contentPaging, pagingfunction,pagingfunction, itemperpage);
		}
	
		function paging_ajax(fungsi,start){
					$('.article_image_content').html('');
					$('.article_image_content').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
					$.post(basedomain+pages+"/ajax",{start:start,needs:"content"},function(data){
						if(data){
							if(data.result){
								var html = "";
								html+=articleimagehtml(data.result);
								
								$('.loaders').remove();
								$('.article_image_content').html(html);
								
								$(".showPopup").fancybox({
									'titlePosition'		: 'inside',
									'transitionIn'		: 'none',
									'transitionOut'		: 'none'
								});
								
								$(".cbanner0").attr('src',$(".cbanner0").attr("url"));
								var maxlength = ($(".sequenceload").length)-1;
								$(".sequenceload").each(function(i,e){
									$(this).load(function(){
											nextload('.cbanner',i);
										
											if(i==maxlength) $('#photo_gallery').prepend( ".box" ).masonry( 'reload' );
									});
									
								});
																
								
								
							}
						}
					},"JSON");
		}
		
		function paging_ajax_for_search(fungsi,start){
					var keywords = $('.querySearchonSearchpage').val();
					$('.article_search_content').html('');
					$('.article_search_content').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
					$.post(basedomain+pages+"/ajax",{start:start,needs:"content",q:keywords},function(data){
						if(data){
							if(data.result){
								var html = "";
								html += searchcontentviews(data.result);
								
								
							}
						}
						$('.loaders').remove();
								$('.article_search_content').html(html);
						$(".showPopup").fancybox({
									'titlePosition'		: 'inside',
									'transitionIn'		: 'none',
									'transitionOut'		: 'none'
								});
								$('#searchPage').masonry( 'reload' );		
					},"JSON");
		}
		
	 
		function get_list_of_user(data,start){
			user.start=start;
			$('table tbody').html('');
			$('table tbody').append('<tr><td colspan="8"><div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div></td></tr>');
			$.post(basedomain+pages+"/ajax",user,function(response){
				if(response){
					if(response.data){
						var html = "";
						html += listofuserviews(response.data);
						$('.loaders').remove();
						$('table tbody').html(html);
						if(start==0){
			                start=1;
			                kiPagination(response.total, start, 'paging_of_user_list', user, 'get_list_of_user', 20);
			            }
					}
				}
			},"JSON");
		}
		 
		$(document).on('click','.count',function(){	
			var thisobject = $(this);
			var types = $(this).attr("counttype");
			var countthis = parseInt($(this).attr("count"),10);
			var contentid = $(this).attr("contentid");
		
			if(types=="favorite")	{
				$.post(basedomain+'article/favorite',{cid:contentid},function(data){
					if(data) {
						countthis++;
						thisobject.html(countthis);									
						return true;
					}else return false;
					
					
				},"JSON");
			}
		});
		 
function getSplitDate(currentdate){
	var months = new Array(13);
	   months['01']  = "January";
	   months['02']  = "February";
	   months['03']  = "March";
	   months['04']  = "April";
	   months['05']  = "May";
	   months['06']  = "June";
	   months['07']  = "July";
	   months['08']  = "August";
	   months['09']  = "September";
	   months['10']  = "October";
	   months['11'] = "November";
	   months['12'] = "December";
	var arrfulldate = currentdate.split(' ');
	var arrdate = arrfulldate[0];
	var dateonly = arrdate.split('-');	
	dateonly.push(months[dateonly[1]]);

	return dateonly;
}

function getCalendarDate()
		{
		   var months = new Array(13);
		   months[0]  = "January";
		   months[1]  = "February";
		   months[2]  = "March";
		   months[3]  = "April";
		   months[4]  = "May";
		   months[5]  = "June";
		   months[6]  = "July";
		   months[7]  = "August";
		   months[8]  = "September";
		   months[9]  = "October";
		   months[10] = "November";
		   months[11] = "December";
		   var now         = new Date();
		   var monthnumber = now.getMonth();
		   var monthname   = months[monthnumber];
		   var monthday    = now.getDate();
		   var year        = now.getYear();
			monthnumber = monthnumber + 1;
		   if (monthnumber   < 10)  monthnumber   = "0" + monthnumber; 
			if (monthday   < 10)  monthday   = "0" + monthday;  		   
		   if(year < 2000)  year = year + 1900; 
		   var dateString = monthname +
							' ' +
							monthday +
							', ' +
							year;
			var dateString = monthday+'-'+monthnumber+'-'+year;
		   return dateString;
		}

		function getClockTime()
		{
		   var now    = new Date();
		   var hour   = now.getHours();
		   var minute = now.getMinutes();
		   var second = now.getSeconds();
		   var ap = "AM";
		   if (hour   > 11)  ap = "PM";             
		   if (hour   > 12)  hour = hour - 12;      
		   if (hour   == 0)  hour = 12;             
		   if (hour   < 10)  hour   = "0" + hour;   
		   if (minute < 10)  minute = "0" + minute; 
		   if (second < 10)  second = "0" + second; 
		   var timeString = hour +
							':' +
							minute +
							':' +
							second +
							" " +
							ap;
			var timeString = hour +
							':' +
							minute +
							' ' +
							ap ;
		   return timeString;
		}
		
function truncate(str,limit,crumb){
	var arrStr = str.split(" ");
	var newArr = new Array();
	$.each(arrStr,function(i,e){
		if(i<=limit) newArr.push(e);
		else return true;
	});
	if(newArr.length>0) {
		newArr.push(crumb);
		newStr = newArr.join(' ');
	}
	
	return newStr;
	
}
//load image
function nextload(selector,idx){
		idx++;
		$(selector+idx).attr('src',$(selector+idx).attr("url"));
	}
	
			$(document).on('click','.loginpopup',function(){
				var url = $(this).attr('url');
				
				window.open(url,'','width=850,height=450');
			
			});
		function ucwords (str) {
			return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
			return $1.toUpperCase();
		  });
		}
