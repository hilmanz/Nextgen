			
			$(document).on('click',".iconadd",function(){
				/* cid="0" fromwhere="0" myid="0" typeofpage="0" */
				var contentid = $(this).attr("contentid");
				var fromwhere = $(this).attr("fromwhere");
				var myid = $(this).attr("myid");
				var typeofpage = $(this).attr("typeofpage");
				var authorid = $(this).attr("authorid");
				var action = "set";
				if (fromwhere==2) {
					$.post(basedomain+"music/addMyPlaylist",{cid:contentid,fromwhere:fromwhere,myid:myid,typeofpage:typeofpage,authorid:authorid,action:action},function(data){
							if(data) $('.notifmessage').html('kamu berhasil menambahkan playlist kamu');
							else  $('.notifmessage').html('kamu gagal menambahkan playlist kamu');
					},"JSON");
				} else {
					$.post(basedomain+"my/cover",{cid:contentid,fromwhere:fromwhere,myid:myid,typeofpage:typeofpage,action:action},function(data){
							if(data) $('.notifmessage').html('Gambar berhasil terpasang sebagai cover');
							else  $('.notifmessage').html('Gambar gagal terpasang sebagai cover');
					},"JSON");				
				}
			
			});
			
			$(document).on('change',".changegroupoffriend",function(){
				var groupid = $(this).attr("groupid");
				var friendid = $(this).attr("friendid");	
				var thisobject = $(this);
				$.get(basedomain+"my/friends/add/"+friendid+"/"+groupid,function(data){
					if(data) {
						thisobject.addClass('unchangegroupoffriend');
						thisobject.removeClass('changegroupoffriend');
					}
				},"JSON");
			
			});
			
			$(document).on('change',".unchangegroupoffriend",function(){
				var groupid = $(this).attr("groupid");
				var friendid = $(this).attr("friendid");		
				var thisobject = $(this);				
				$.get(basedomain+"my/friends/degroup/"+friendid+"/"+groupid,function(data){
					if(data) {
						thisobject.removeClass('unchangegroupoffriend');
						thisobject.addClass('changegroupoffriend');
					}
				},"JSON");
			
			});
			
			$(document).on('click',".creategroup",function(){
				var groupname = $("#newgroupname").val();
				var friendid = $(this).attr("friendid");				
				$.post(basedomain+"my/circle/create/"+groupname,function(data){
					if(data.result){
						var groupid = data.content;
						var html = "";
						html+=" <div class='contentgroupname_"+groupid+"' >";
						html+="<input name='groupname' type='text' value='"+groupname+"' disabled='disabled' class='w60 fl groupname_"+groupid+"'/>";
						html+="					<div class='act-btn fl'>";
						html+="						<a href='javascript:void(0)' class='renamegroup' groupid='"+groupid+"' >Rename</a>";
						html+="						<a href='javascript:void(0)' class='deletegroup'  groupid='"+groupid+"'  >Delete</a>";
						html+="					</div>";
						html+=" </div>";
						$(".contentgroup").prepend(html);
					}
				},"JSON");
			
			});
			
			
			$(document).on('click',".deletegroup",function(){
				var groupid = $(this).attr("groupid");		
				var groupname = $(".groupname_"+groupid).val();				
				$.post(basedomain+"my/circle/loss/"+groupname,{circleid:groupid},function(data){
					if(data){
						$(".contentgroupname_"+groupid).hide();
					}
				},"JSON");
			
			});
			
			$(document).on('click',".renamegroup",function(){
				var groupid = $(this).attr("groupid");	
				$(".groupname_"+groupid).attr("disabled",false);
				$(this).addClass("doit");
				$(this).html("Save");
			});
			
			
			$(document).on('click',".renamegroup.doit",function(){
				
				var groupid = $(this).attr("groupid");		
				var groupname = $(".groupname_"+groupid).val();				
				$.post(basedomain+"my/circle/create/"+groupname,{groupid:groupid},function(data){
					
					$(".groupname_"+groupid).attr("disabled",true);
				},"JSON");
				
				$(this).removeClass("doit");
				$(this).html("Rename");
			});
			
			$(document).on('click','.content_action .unfriends',function(){
				var friendid = $(this).attr('friendid');
				var thisobject = $(this);
				var totalfriends = parseInt($(".total-my-friends").attr('total'),10);
				$.get(basedomain+"my/friends/undo/"+friendid,function(data){
					if(data){
						$('.friendslist_'+friendid).html('');
						thisobject.addClass("addfriends");
						thisobject.addClass("icon_follows");
						thisobject.addClass("fl");
						thisobject.removeClass("icon_trash");
						thisobject.removeClass("fr");
						thisobject.removeClass("unfriends");
				
						thisobject.html("Add Friends");
						totalfriends--;
						$(".total-my-friends").attr('total',totalfriends);
						$(".total-my-friends").html('"'+totalfriends+'"');
					}else return false;
						
				},"JSON");
			});
			
			$(document).on('click','.follow_link .unfriends',function(){
				var friendid = $(this).attr('friendid');
				var thisobject = $(this);
				var totalfriends = parseInt($(".total-my-friends").attr('total'),10);
				$.get(basedomain+"my/friends/undo/"+friendid,function(data){
					if(data){
						$('.friendslist_'+friendid).html('');
						thisobject.addClass("addfriends");
						thisobject.addClass("icon_follows");
						thisobject.removeClass("unfriends");
						thisobject.removeClass("unfriends-box");
						thisobject.html("ADD FRIENDS");
						totalfriends--;
						$(".total-my-friends").attr('total',totalfriends);
						$(".total-my-friends").html('"'+totalfriends+'"');
					}else return false;
						
				},"JSON");
			});
			
			$(document).on('click','.content_action  .addfriends',function(){
				var friendid = $(this).attr('friendid');
				var thisobject = $(this);
				var totalfriends = parseInt($(".total-my-friends").attr('total'),10);
				$.get(basedomain+"my/friends/add/"+friendid,function(data){
					if(data){
						/* 
							var friendphoto = $("#photoProfile a img").attr("src");
							var html ="";
							html +="<li class='friendslist_"+friendid+"'><a class='thumb45 ' href='"+basedomain+"friends/"+friendid+"'>";
							html +="<img width='30' src='"+friendphoto+"' /></a></li>";
							$('.friends-member').prepend(html);
						*/
			
						thisobject.addClass("unfriends");
						thisobject.addClass("icon_trash");
						thisobject.addClass("fr");
						thisobject.removeClass("icon_follows");
						thisobject.removeClass("fl");
						thisobject.removeClass("addfriends");
						thisobject.html("");
						totalfriends++;
						$(".total-my-friends").attr('total',totalfriends);
						$(".total-my-friends").html('"'+totalfriends+'"');
					}else return false;
						
				},"JSON");
			});
			
			$(document).on('click','.follow_link .addfriends',function(){
				var friendid = $(this).attr('friendid');
				var thisobject = $(this);
				var totalfriends = parseInt($(".total-my-friends").attr('total'),10);
				$.get(basedomain+"my/friends/add/"+friendid,function(data){
					if(data){
						/* 
							var friendphoto = $("#photoProfile a img").attr("src");
							var html ="";
							html +="<li class='friendslist_"+friendid+"'><a class='thumb45 ' href='"+basedomain+"friends/"+friendid+"'>";
							html +="<img width='30' src='"+friendphoto+"' /></a></li>";
							$('.friends-member').prepend(html);
						*/
						thisobject.addClass("unfriends-box");
						thisobject.addClass("unfriends");
						thisobject.removeClass("icon_follows");
						thisobject.removeClass("fl");
						thisobject.removeClass("addfriends");
						thisobject.html("REMOVE FRIENDS");
						totalfriends++;
						$(".total-my-friends").attr('total',totalfriends);
						$(".total-my-friends").html('"'+totalfriends+'"');
					}else return false;
						
				},"JSON");
			});
			
			$(document).on('click','.trashinbox',function(){
					
					var noteid = $(this).attr('noteid');
					var thisobject = $(this);
					thisobject.html("<span style='position: relative; left: 28px;'><div class='loaders'><img src='"+basedomain+"assets/images/loader.gif'></div></span>");
					$.post(basedomain+"my/ajax",{needs:'inbox-trash',noteid:noteid},function(data){
						if(data){
							$("#message"+noteid).hide();						
						}
						else thisobject.html("<span style='position: relative; left: 28px;'>failed</span>");
					},"JSON");
			});
			
			$(document).on('click','.readinbox',function(){
				var noteid = $(this).attr('noteid');
				var thisobject = $(this);				
					$.post(basedomain+"my/ajax",{needs:'inbox-read',noteid:noteid},function(data){
						
						$("#message"+noteid).removeClass("message_close");
						$("#message"+noteid).addClass("message_open");
						if(data){
							window.location = thisobject.attr('nexturl');
						}
					},"JSON");
					
			});
			
			$(document).on('keyup' , '.keywords-search-friends',function(){
				var keywords =$(".keywords-search-friends").val();
				//if(keywords=='') return false;
				// $(".keywords-search-friends").val(keywords);
				$(".friends-box").html('<div class="loaders"><img src="'+basedomain+'assets/images/loader.gif"></div>');
				$("#pagingID").html('');
				$.post(basedomain+'search/friends',{keywords:keywords},function(data){
					if(data){
						var html ="";
						html += friendsListViewHtml(data.result,data.myid);
					
						$(".friends-box").html(html);
						
						getpaging(0,data.total,"pagingID","paging_ajax_friends_search",16);
						
						Custom.init;	
										
					}
						$('.loaders').remove();		
				},"JSON");
					
			});