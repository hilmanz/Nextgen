$(document).ready(function($) {
$(".poolingStarSocial").mouseover(function(){

	var rate = $(this).attr("node");
			for(var x=1;x<=rate;x++){
				
				$(".node"+x).children("img").attr("src","assets/images/icon/stars.png");
			
			}
	$(".poolingStarSocial").mouseleave(function(){
	var rate = $(this).attr("node");
			for(var x=1;x<=rate;x++){
				
				$(".node"+x).children("img").attr("src","assets/images/icon/star.png");
			
			}
	
	});
	
});


$(".poolingStarSocial").click(function(){
		var rate = $(this).attr("node");
		var cid = $(".rating").attr("content");
		$.post('index.php?page=content&act=saveRateSocial',{cid:cid,rate:rate}, function(data){
		if(data){
			for(var x=1;x<=rate;x++){
				
				$(".node"+x).children("img").attr("src","assets/images/icon/stars.png");
			
			}
		}
		});
		
		$(".poolingStarSocial").mouseleave(function(){
		var rate = $(this).attr("node");
			for(var x=1;x<=rate;x++){
				
				$(".node"+x).children("img").attr("src","assets/images/icon/stars.png");
			
			}
	
		});
	
	});


			$('.postCommentSocial').click(function(){
			
				var cid = $(this).attr('cid');
				var comment = $('.myComment_'+cid).val();
				$.post('index.php?page=content&act=sendCommentSocial',{cid:cid,comment:comment},function(data){
				if(data){
					var html ='';
						html+='			 <div class="row">';
						html+='					<div class="thumb">';
						if(userimg)  html+='						<a href="#"><img width="55" src="'+basedomain+'public_assets/user/photo/'+userimg+'" /></a>';
						else html+='						<img width="55" src="'+socimg+'" /></a>';
						html+='					</div>';
						html+='					<div class="post">';
						html+='						<h3 class="username">'+uname+'</h3>';
						html+='						<p>'+comment+'</p>';
						html+='						<span class="post-date">'+Date.now()+'</span>';
						html+='					</div>';
						html+='							<span class="arrows"></span>';
						html+='						</div>';
					 $('.appendComment_'+cid).prepend(html);
					 $('.myComment_'+cid).val("");
				}else return false;
				});
			
			});
			
			$(".rateVal").each(function(n,i){
				var rate = $(this).attr('rate');
					for(var x=1;x<=rate;x++){
						
						$(".nodes"+x).children("img").attr("src","assets/images/icon/stars.png");
					
					}
			});
			
			$(".favoriteSocial").click(function(){
				var contentid = $(this).attr("contentid");
				var countFav = $(".fav-count").attr("countFav");
				$.post("index.php?page="+userpage+"&act=addMyFavoriteSocial&id="+contentid,function(data){
					if(data) {
						countFav++;
						$(".fav-count").html(countFav);
						$(".fav-count").attr("countFav",countFav);
					}else return false;
				});
			
			});
			
			
	
});