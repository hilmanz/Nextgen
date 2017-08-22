		
		
		function showarticledetail(id,articleType){
			$('.commentsContainer').show();
			$('.popupDetail').hide();
			$(".popupLoader").append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$('.popupmsg').remove();
			
			if(!articleType) articleType = pages;
			$.post(basedomain+articleType+'/detail',{id:id},function(data){
			
				var html="";
				
				if(data.result){
					var article = data.article;
					if(article){
						$('.popupDetail').show();
						
						var imagepath = false;
						if(articleType=="event") {
							imagepath = "event";
						} else if (articleType=="forum") {
							imagepath = "forum";
						}
						
						if(articleType=='music' || articleType=='dj'){
							html+= popupmediahtml(article,articleType);
						} else if (articleType=='forum') {
							html+= popupforumhtml(article,articleType);
						} else{
							html+= popupimagehtml(article,articleType);
						}
						
						$(".popupDetail").html(html);
						
						$('.notes_add').hide();
						$('.notes_addfailed').hide();
						
						if(article.id) 	$('.commentdata').attr("contentid",article.id);
						else $('.commentdata').attr("contentid",0);
					
					}	
				
					
				
					$('.commentdata').html('');
					$("#commentpagingID").html("");
					if(data.comment){
						$('.commentdata').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
						var commenthtml = "";
						commenthtml += commentviewshtml(data.comment);
						getpaging(0,article.commentcount,"commentpagingID","paging_comment_ajax",5);
					
						$('.commentdata').html(commenthtml);
					}
					
				}else{
				
					$('.popupDetail').hide();
					$(".popupLoader").append('<div class="popupmsg">there is no content</div>');
				}
				
					/* attendting button */
					if(articleType=='event') {
						$('.attender').html(article.attending);	
						$(".doattending").attr("contentid",article.id);
					}
					
					
					$('.loaders').remove();					
					$('.mp3Player audio').mediaelementplayer({
							audioHeight: 30,
							startVolume: 0.8,
							loop: false,
							enableAutosize: true,
							features: ['playpause','current','progress']
							});
			},"JSON");
		}
		
		function showeditarticledetail(id,articleType){
			$('.editpopupDetail').hide();
			$(".popupLoader").append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$('.popupmsg').remove();
			
			if(!articleType) articleType = pages;
			$.post(basedomain+articleType+'/detail',{id:id},function(data){
			
				var html="";
				
				if(data.result){
					var article = data.article;
					
					if(article){
						$('.editpopupDetail').show();
						
						var imagepath = false;
						if(articleType=="event") imagepath = "event";						
						html+= editpopupcontenthtml(article,articleType);
						
						$(".editpopupDetail").html(html);					
					}	
				}else{
				
					$('.editpopupDetail').hide();
					$(".popupLoader").append('<div class="popupmsg">there is no content</div>');
				}
				
					/* attendting button */
					if(articleType=='event') {
						$('.attender').html(article.attending);	
						$(".doattending").attr("contentid",article.id);
					}
					
					
					$('.loaders').remove();					
					$('.mp3Player audio').mediaelementplayer({
							audioHeight: 30,
							startVolume: 0.8,
							loop: false,
							enableAutosize: true,
							features: ['playpause','current','progress']
							});
			},"JSON");
		}
		
		$(document).on("click",".articledetail", function(){
			$('.editpopupDetail').hide();
			var id = $(this).attr('contentid');
			var articleType =  $(this).attr('articleType');
			showarticledetail(id,articleType);
		});
		
		$(document).on("click",".editarticledetail", function(){
			$('.popupDetail').hide();
			$('.commentsContainer').hide();
			var id = $(this).attr('contentid');
			var articleType =  $(this).attr('articleType');
			showeditarticledetail(id,articleType);
		});
		
		$(document).on("click",".submit-editContent", function(){
			var cid = 1420;
			//$.post(basedomain+pages+"/editContent",{cid:cid},function(data){
				//if(data){
					var optionEditContent = {
						dataType:  'json',
						success:    function(data) {
							if(data) {
								$("#popup-messagebox .popupContent .entry-popup h3").html("Sukses Update Content");
								$(".messageboxpop").trigger('click');
							}else {
								$("#popup-messagebox .popupContent .entry-popup h3").html("Gagal Update Content");
								$(".messageboxpop").trigger('click');
							}
						}
					};
					$("#form-editPopupContent").ajaxForm(optionEditContent);
				//}
			//},"JSON");
		});
		
		/* ------------------  pagination ------------------- */
		
		function getpaging(start,total_rows,contentPaging,pagingfunction,itemperpage,gotoPage){
				if(start == 0)start=1;
				if(total_rows == 0) total_rows=0;
				kiPagination(total_rows, start, contentPaging, pagingfunction,pagingfunction, itemperpage, gotoPage);
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
		
		function paging_ajax_music(fungsi,start){
			$('.article_media_content').html("");
			
			if(start>1) $('.media_content').hide();
			else $('.media_content').show();
			
			$('.article_media_content').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"content"},function(data){
				if(data){
				
					if(data.result){
						var html = "";
						/* the views */
						html = mediahtmlviews(data.result);
						
						$('.loaders').remove();
						$('.article_media_content').html(html);
						$(".showPopup").fancybox({
							'titlePosition'		: 'inside',
							'transitionIn'		: 'none',
							'transitionOut'		: 'none'
						});
						
						$('.mp3Player audio').mediaelementplayer({
							audioHeight: 30,
							startVolume: 0.8,
							loop: false,
							enableAutosize: true,
							features: ['playpause','current','progress']						 
						});
						
						$('#photo_gallery').prepend( ".box" ).masonry( 'reload' );
					}
				}
			},"JSON");
		}
		
		function paging_ajax_playlist(fungsi,start){
			$('.rec_playlist').html('');
			$('.rec_playlist').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"content",uid:uid},function(data){
				if(data){
					if(data.result){
						var html = "";
						html +=playlisthtml(data.result);
						$('.loaders').remove();
						$('.rec_playlist').html(html);	
					
					}
				}
			},"JSON");
		}
		
		function paging_ajax_myfavourite(fungsi,start){
			$('#favorite-list ul').html('');
			$('#favorite-list ul').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"myFavourite",uid:uid},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += myfavoritehtml(data.result);
						$('.loaders').remove();
						$('#favorite-list ul').html(html);
						$(".showPopup").fancybox({
							'titlePosition'		: 'inside',
							'transitionIn'		: 'none',
							'transitionOut'		: 'none'
						});
					}
				}
			},"JSON");
		}
		
		function paging_ajax_postmoderation(fungsi,start){
			$('.theContent').html('');
			$('.theContent').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"post_moderation",publishedtype:publishedtype,startdate:startdate,enddate:enddate,search:search},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += postmoderationviews(data.result);
						$('.loaders').remove();
						$('.theContent').html(html);
					}
				}
			},"JSON");
		}
		function paging_ajax_gallerymoderation(fungsi,start){
			$('.theContent').html('');
			$('.theContent').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"gallery_moderation",publishedtype:publishedtype,startdate:startdate,enddate:enddate,search:search},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += postmoderationviews(data.result);
						$('.loaders').remove();
						$('.theContent').html(html);
					}
				}
			},"JSON");
		}
		function paging_ajax_challenge(fungsi,start){
			$('.theContent').html('');
			$('.theContent').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"challenge",startdate:startdate,enddate:enddate,search:search},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += challengeviews(data.result);
						$('.loaders').remove();
						$('.theContent').html(html);
					}
				}
			},"JSON");
		}
		function paging_ajax_brand(fungsi,start){
			$('.theContent').html('');
			$('.theContent').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"brand",startdate:startdate,enddate:enddate,search:search},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += brandviews(data.result);
						$('.loaders').remove();
						$('.theContent').html(html);
					}
				}
			},"JSON");
		}
		
		function paging_ajax_challengeHashtag(fungsi,start){
			$('.greyBox').html('');
			$('.greyBox').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"challenge_hashtag",tags:tags},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += challengeHastagviews(data.result);
						$('.loaders').remove();
						$('.greyBox').html(html);
					}
				}
			},"JSON");
		}
		
		function paging_ajax_gallery(fungsi,start){
			$('.theContent .section').html('');
			$('.theContent .section').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"gallery",category:categories,startdate:startdate,enddate:enddate,search:search},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += galleryviews(data.result);
						$('.loaders').remove();
						$('.theContent .section').html(html);
					}
				}
			},"JSON");
		}
		function paging_ajax_badge(fungsi,start){
			$('.theContent .section').html('');
			$('.theContent .section').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"badges"},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += badgeviews(data.result);
						$('.loaders').remove();
						$('.theContent .section').html(html);
					}
				}
			},"JSON");
		}
		function paging_ajax_merchandise(fungsi,start){
			$('.theContent .section').html('');
			$('.theContent .section').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"merchandise",n_status:nstatus},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += merchandiseviews(data.result);
						$('.loaders').remove();
						$('.theContent .section').html(html);
					}
				}
			},"JSON");
		}
		function paging_ajax_inbox(fungsi,start){
			$('.theContent').html('');
			$('.theContent').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"inbox"},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += inboxviews(data.result);
						$('.loaders').remove();
						$('.theContent').html(html);
					}
				}
			},"JSON");
		}
		
		function paging_ajax_entourage(fungsi,start){
			$('.entouragelisting').html('');
			$('.entouragelisting').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"entourage",uid:uid,filter:filter,cityid:cityid,brandid:brandid,totalengagement:totalengagement},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += entourageviews(data.result);
						$('.loaders').remove();
						$('.entouragelisting').html(html);
					}
				}
			},"JSON");
		}
		
		function paging_ajax_commentmoderation(fungsi,start){
			$('.theContent').html('');
			$('.theContent').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"comment_moderation",publishedtype:publishedtype,startdate:startdate,enddate:enddate,search:search},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += commentmoderationviews(data.result);
						$('.loaders').remove();
						$('.theContent').html(html);
					}
				}
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
		
		function paging_ajax_venuemoderation(fungsi,start){
			$('.theContent').html('');
			$('.theContent').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"venue_moderation",publishedtype:publishedtype,startdate:startdate,enddate:enddate,search:search,searchType:searchType},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += venuemoderationviews(data.result);
						$('.loaders').remove();
						$('.theContent').html(html);
					}
				}
			},"JSON");
		}
		
		function paging_ajax_mygallery(fungsi,start){
			$('.allgallery').html('');
			$('.allgallery').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"contentgallery"},function(data){
				if(data){
					if(data.result){
						var html = "";
						html += mygalleryhtml(data.result);
						$('.loaders').remove();
						$('.allgallery').html(html);	

						$('.mp3Player audio').mediaelementplayer({
							audioHeight: 30,
							startVolume: 0.8,
							loop: false,
							enableAutosize: true,
							features: ['playpause','current','progress']						 
						});						
					}
				}
			},"JSON");
		}
		
		function paging_ajax_pagesgallery(fungsi,start){
			$('#pagesgallery-list ul').html('');
			$('#pagesgallery-list ul').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
			$.post(basedomain+pages+"/ajax",{start:start,needs:"contentpagesgallery",pid:pid},function(data){
				if(data.result){
					var html = "";
					html += pagesgalleryhtml(data.result);
					$('.loaders').remove();
						$("#pagesgallery-list ul").html(html);
						$(".showPopup").fancybox({
							'titlePosition'		: 'inside',
							'transitionIn'		: 'none',
							'transitionOut'		: 'none'
						});

					$('.mp3Player audio').mediaelementplayer({
						audioHeight: 30,
						startVolume: 0.8,
						loop: false,
						enableAutosize: true,
						features: ['playpause','current','progress']
					});
				}
			},"JSON");
		}
	
		function paging_ajax_event_calendar(fungsi,start){
					$('.eventcontentdata').html('');
					$('.eventcontentdata').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
					$.post(basedomain+pages+"/ajax",{start:start,needs:"content"},function(data){
						if(data){
							if(data.result){
								var html = "";
								html += eventcalendarhtml(data.result);
								$('.loaders').remove();
								$('.eventcontentdata').html(html);
								$(".showPopup").fancybox({
									'titlePosition'		: 'inside',
									'transitionIn'		: 'none',
									'transitionOut'		: 'none'
								});
							}
						}
					},"JSON");
		}
		
		function paging_ajax_pagescalender(fungsi,start){
					$('.eventpagesdata').html('');
					$('.eventpagesdata').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
					$.post(basedomain+pages+"/ajax",{start:start,needs:"calender"},function(data){
						if(data){
							if(data.result){
								var html = "";
								$.each(data.result,function(i,e){
									var datethis = getSplitDate(e.posted_date);
										html += "<div class='box_event'>";
										html += "<a href='#popup-photography' class='showPopup articledetail'  contentid='"+e.id+"'  >";
										html += "<div class='event_date'>";
										html += "<span class='date'>"+datethis[2]+"</span>";
										html += "<span class='day'>"+datethis[3]+"</span>";
										html += "</div>";
										html += "</a>";
										html += "<div class='event_detail'>";
										html += "<h3>"+e.title+"</h3>";
										html += "<span>"+e.brief+"<br />"+e.cityid+"</span>";
										html += "<h4>xx Attending</h4>";
										html += "</div>";
										html += "<div class='event_action'>";
										html += "<div class='content_action'>";
										html += "<ul>";
										html += "<li><a class='icon_view' href='#'>"+e.views+"</a></li>";
										html += "<li><a class='icon_comment' href='#'>"+e.commentcount+"</a></li>";
										html += "</ul>";
										html += "</div>";
										html += "</div>";
										html += "</div>";
								});
								$('.loaders').remove();
								$('.eventpagesdata').html(html);
								$(".showPopup").fancybox({
									'titlePosition'		: 'inside',
									'transitionIn'		: 'none',
									'transitionOut'		: 'none'
								});
							}
						}
					},"JSON");
		}
		
		function paging_comment_ajax(fungsi,start){
					var contentid = $(".commentdata").attr("contentid");
					if(contentid!=0){
						$('.commentdata').html('');
						$('.commentdata').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
						$.post(basedomain+pages+"/ajax",{contentid:contentid,start:start,needs:"comment"},function(data){
							if(data){
							
									var commenthtml = "";
									commenthtml+= commentviewshtml(data);
								
							}
							$('.loaders').remove();
							$('.commentdata').html(commenthtml);
						},"JSON");
					}
		}
		function paging_ajax_latest_news(fungsi,start){
					$('.latestnewsdata').html('');
					$('.latestnewsdata').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
					$.post(basedomain+pages+"/ajax",{start:start,needs:"latest"},function(data){
						if(data){
							if(data.result){
								var html = "";
								html += latestnewsviewshtml(data.result);
								$('.loaders').remove();
								$('.latestnewsdata').html(html);
								
							}
						}
					},"JSON");
		}
		
		function paging_ajax_my_activity(fungsi,start){
					$('.myactivitydata').html('');
					$('.myactivitydata').append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
					$.post(basedomain+pages+"/ajax",{start:start,needs:"activity"},function(data){
						if(data){
							if(data.result){
								var html = "";
								html+= myactivityviews(data.result);
								$('.loaders').remove();
								$('.myactivitydata').html(html);
								$('.myactivitydata').html(html);
								
							}
						}
					},"JSON");
		}
		
		function paging_ajax_friends(fungsi,start){
				
				$(".friends-box").append('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
				$.post(basedomain+pages+'/ajax',{needs:"friends-list",start:start},function(data){
					if(data){
						var html ="";
						html += friendsWithGroupListViewHtml(data.result);
						
						$(".friends-box").html(html);
						// $(".toggle .inner").hide();
						// Toggle
						$( ".toggle > .inner" ).hide();
						$(".toggle .title").toggle(function(){
							$(this).addClass("active").closest('.toggle').find('.inner').slideDown(200, 'easeOutCirc').css( "z-index","1000" );
						}, function () {
							$(this).removeClass("active").closest('.toggle').find('.inner').slideUp(200, 'easeOutCirc').css( "z-index","1000" );
						});
						Custom.init;		
					}
				},"JSON");
				$('.loaders').remove();		
		}
		
		
		function paging_ajax_friends_search(fungsi,start){
				var keywords =$(".keywords-search-friends").val();
				
				$(".friends-box").html('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
				$("#pagingID").html('');
				$.post(basedomain+'search/friends',{keywords:keywords,start:start},function(data){
					if(data){
						var html ="";
						html += friendsListViewHtml(data.result,data.myid);
						
						$(".friends-box").html(html);
						
						Custom.init;			
					}
					$('.loaders').remove();	
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
		
		$(document).on('click','.doattending', function(){
			var contentid = $(this).attr("contentid");
			var thisobject = $(this);
			if(pages=='event'){
				$.post(basedomain+pages+"/attending",{contentid:contentid},function(data){
					if(data) {
						// alert("Selamat Kamu Berhasil Attending");
					
						$("#popup-messagebox .popupContent .entry-popup h3").html(locale.event.attending.success);
						$(".messageboxpop").trigger('click');
						thisobject.removeClass("doattending");
						thisobject.addClass("anttending-disable");
					}else {
						// alert("Kamu Gagal Bergabung, Silahkan coba Lagi");
					
						$("#popup-messagebox .popupContent .entry-popup h3").html(locale.event.attending.failed);
						$(".messageboxpop").trigger('click');
						thisobject.removeClass("doattending");
						thisobject.addClass("anttending-disable");
					}
				},"JSON");
			}else return false;
			
		});
		
		$(document).on('click','.postComment', function(){
				var contentid = $(".commentdata").attr("contentid");
				if(contentid!=0){				
					var cid = contentid;
					var comment = $('.textComment').val();
				$.post(basedomain+"article/comment",{cid:cid,comment:comment},function(data){
						if(data){
							var commenthtml ='';
								commenthtml += postcommenthtml();
							 $('.commentdata').prepend(commenthtml);
							 $('.textComment').val("");
						}else return false;
					
					},"JSON");
				}
			});
			
					
			$(document).on('click',".favorite",function(){
				var contentid = $(this).attr("contentid");
				var countFav = $(".fav-count").attr("countFav");
				var favorite =  $(this);
				$.post(basedomain+"index.php?page="+userpage+"&act=addMyFavorite&id="+contentid,function(data){
					if(data) {
						countFav++;
						$(".fav-count").html(countFav);
						$(".fav-count").attr("countFav",countFav);
						favorite.attr("contentid",0);
					}else return false;
				});
			
			});
		
		
		$(document).on('click','.nexta360activity',function(){
				var start = parseInt($(".pagea360activity").attr("startpage"),10)+2;
				if(start < a360length){
					$.post(basedomain+'home/ajax',{start:start,action:"a360activity"},function(data){
						if(data){
							var html ="";
							html += a360actvityhomeviews(data);
							
							$(".a360activityhome").html(html);
							$(".pagea360activity").attr("startpage",start);
						}
					},"JSON");
				}
				
		});
		
		$(document).on('click','.preva360activity',function(){
				var start = parseInt($(".pagea360activity").attr("startpage"),10)-2;
				if(start>=0){
					$.post(basedomain+'home/ajax',{start:start,action:"a360activity"},function(data){
						if(data){
							var html ="";
							html += a360actvityhomeviews(data);
							
							$(".a360activityhome").html(html);
							$(".pagea360activity").attr("startpage",start);
						}
					},"JSON");
				}
				
		});		


		$(document).on('click','.nextnews',function(){
				var start = parseInt($(".pagenews").attr("startpage"),10)+numofpage;
				if(start < newslength){
					$.post(basedomain+'news/ajax',{start:start,needs:"content"},function(data){
						if(data){
							var html ="";
							if(data.result){
								var article = data.result;
									html += newsview(article);
							}
							
							$(".content-news").html(html);
							$(".pagenews").attr("startpage",start);
						}
						 $('#slidernews').flexslider({
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
					},"JSON");
				}
				
		});
		
		$(document).on('click','.prevnews',function(){
				var start = parseInt($(".pagenews").attr("startpage"),10)-numofpage;
				if(start>=0){
					$.post(basedomain+'news/ajax',{start:start,needs:"content"},function(data){
						if(data){
							var html ="";
							if(data.result){
								var article = data.result;
									html += newsview(article);
							}
							
							$(".content-news").html(html);
							$(".pagenews").attr("startpage",start);
						}
						 $('#slidernews').flexslider({
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
