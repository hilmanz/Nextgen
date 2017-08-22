
function a360actvityhomeviews(data){
	var html ="";
	$.each(data,function(i,e){
		html +=" <div class='row'>";
		if (e.userdetail){
			if(userid==e.userdetail.id) html +=" <a class='thumb65' href='"+basedomain+"my/"+e.userdetail.id+"' >";
			else  html +=" <a class='thumb65' href='"+basedomain+"friends/"+e.userdetail.id+"' >";
			if(e.userdetail.img) html +=" 	<img src='"+basedomain+"public_assets/user/photo/"+e.userdetail.img+"' />";
			else  html +=" 	<img src='"+basedomain+"public_assets/user/photo/default.jpg' />";
			html +=" </a>";
		}else{
			if(userid==e.userdetail.id) html +=" <a class='thumb65' href='"+basedomain+"my/"+e.userdetail.id+"' >";
			else html +=" <a class='thumb65' href='"+basedomain+"friends/"+e.userdetail.id+"' >";
			html +=" 	<img src='"+basedomain+"public_assets/user/photo/default.jpg' />";
			html +=" </a>";
		}
	
		html +=" <div class='entry-feed'>";
		html +=" 	<h3 class='username'><a href='"+basedomain+"friends/"+e.userdetail.id+"'>"+ucwords(e.userdetail.name)+" "+ucwords(e.userdetail.last_name)+"</a></h3>";
		html +="   <p>";
		if (e.notification){
			html += e.notification.activities;
			if (e.contentdetail.contentType=='social') {
				html +="<a href='"+basedomain+"friends/"+e.contentdetail.id+"' >"+e.notification.subjective+"</a> ";
			
			}else {
				if(e.contentdetail.id) html +=" <a href='"+basedomain+e.contentdetail.type+"/"+e.contentdetail.id+"' >"+e.notification.subjective+"</a> ";
				else html +=" <a href='javascript:void(0)' >"+e.notification.subjective+"</a> ";
			}
		}
			
		html +="  </p>";
		if (e.typeofnotification!='social'){
			html +="  <div class='content_action'>";
			html +="      <ul>";
			html +="         <li><a class='icon_love' href='#'>"+e.contentdetail.favorite+"</a></li>";
			html +="        <li><a class='icon_comment' href='#'>"+e.contentdetail.commentcount+"</a></li>";
			html +="        <li><a class='icon_view' href='#'>"+e.contentdetail.views+"</a></li>";
			html +="     </ul>";
			html +="  </div>";
		}
		html +=" </div>";
		html +="  </div>";
	});
	return html;		
}

function newsview(article){
	var html ="";
	html +=" <div class='blog-title clearfix'>";
	html +=" <div class='fl'>";
	html +="		<h3><a href='#' class='newstitle '>"+article.title+" </a><span class='newscatposted'> ";
	if(article.posted_date!='0000-00-00 00:00:00') html += article.posted_date+" -  ";
	if(article.category)	html +="		Posted in "+article.category+" / News</span></h3>";
	else html +=" News</span></h3>";
	html +="	</div>";
	html +="   <div class='comment-count fr'>";
	html +="		<h3><a href='#' class='newscommentcount' >"+article.commentcount+"</a></h3>";
	html +="	</div>";
	html +="</div> ";
	html +="<div class='blog-content clearfix'>";
		<!-- SLIDER NEWS --->
	if(article.imagepath){
		html +="	<div class='slider slide-loader clearfix'>";
		html +="		<div id='slidernews' class='flexslider'>";
		html +="			<ul class='slides'>";
				<!-- can be loop -->
		html +="					<li><img src='"+basedomain+"public_assets/article/"+article.image+"'  alt=''></li>";
		html +="			</ul>";
		html +="		</div>";
		html +="	</div>";
	}
		<!--- E :SLIDER --->
	html +="	<h5 class='newsbrief'>"+article.brief+"</h5>  ";      
	html +="  <span class='newscontents'>"+article.content+"</span>";
	html +="</div> ";
	return html;
}
	
function audiotemplates(mp3){
	var htmlaudio = "<object width='160' height='16' data='"+basedomain+"assets/media/player_mp3.swf' type='application/x-shockwave-flash'>";
	htmlaudio += "<param value='"+basedomain+"assets/media/player_mp3.swf' name='movie'>";
	htmlaudio += "<param value='mp3="+basedomain+"public_assets/music/mp3/"+mp3+"&amp;skin="+basedomain+"assets/images/skinMP32.jpg&amp;showstop=1' name='FlashVars'>";
	htmlaudio += "<param value='opaque' name='wmode'>"
	htmlaudio += "</object>";
	return htmlaudio;
}
	
function html5audio(mp3){
	var html="  <div class='mp3Player'>";
		html+="               <audio src='"+basedomain+"public_assets/music/mp3/"+mp3+"' type='audio/mp3' controls='controls' width='150'></audio>	";
		html+="          </div>";
	return html;
}
	
function searchcontentviews(article){
	var html ="";
	$.each(article,function(i,e){
		if (e.pagenames=='music' || e.pagenames=='dj')	html += searchmediaviewshtml(i,e);
		else html +=  searchimageviewshtml(i,e);                            
	   
	});
	return html;
}
	
function searchmediaviewshtml(i,e){
	var html ="";
	html += "					<div class='box photo cols2'>";
	html += "					   <a href='#popup-music' class='showPopup thumb120 articledetail'  contentid='"+e.id+"' articleType='"+e.pagesname+"' >";
	html += "						<img src=' ";
	if (e.video_thumbnail) html += " https://img.youtube.com/vi/"+e.video_thumbnail+"/0.jpg ";
	else{
		if (e.thumbnail_image) html += " "+basedomain+"public_assets/"+e.imagepath+"/thumbnail_"+e.thumbnail_image+" ";
		else{
			if (e.image) html += ""+basedomain+"public_assets/"+e.imagepath+"/"+e.image+" ";
			else html += ""+basedomain+"assets/content/thumb/img_content8.jpg			";									
		}
	}
	html += "' alt='AltImage' alt='' class='full-image product-image' />		";	
	html += "						</a> ";
	html += "						<div class='isi_boxLittle'>";
	html += "							<h3>"+e.title+"</h3>";
	html += "							<span><a href='#popup-photography' class='showPopup articledetail'  contentid='"+e.id+"'>";
	if (e.author) html += e.author.name;
	else html +=  "anonymuse";
	html += " </a></span>";
	if (!e.video_thumbnail) 
	{
		html += "		<div class='mp3Player'>";
			if (e.hasfile && e.file){
			html += " <div class='mp3Player fr'>";
			html += "	<audio src='"+basedomain+"public_assets/music/mp3/"+e.file+"' type='audio/mp3' controls='controls' width='150'></audio>	";
			html += "</div> ";
			}
		html += " </div>";
	}
	html += "<div class='content_action'>";
	html += "	<ul>";
	html += "			<li><a class='icon_love count' counttype='favorite' count='"+e.favorite+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.favorite+"</a></li>";
	html += "			<li><a class='icon_comment count' counttype='comment' count='"+e.commentcount+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.commentcount+"</a></li>";
	html += "			<li><a class='icon_view count' counttype='views' count='"+e.views+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.views+"</a></li>";
	html += "		</ul>";
	html += "	</div>";
	html += "</div>";
	html += "</div>";
	return html;
}
	
function searchimageviewshtml(i,e){
	var html ="";

	html += "		<div class='box photo cols2'>";
	html += "               <a href='#popup-photography' class='showPopup thumb300 articledetail'  contentid='"+e.id+"' articleType='"+e.pagesname+"' >";
	html += "				<img src=' ";
						if (e.video_thumbnail) html += " https://img.youtube.com/vi/"+e.video_thumbnail+"/0.jpg"; 
						else{
							if (e.thumbnail_image)	html += " "+basedomain+"public_assets/"+e.imagepath+"/thumbnail_"+e.thumbnail_image+"";
							else{
							if (e.image) html += ""+basedomain+"public_assets/"+e.imagepath+"/"+e.image+"";
							else html += " "+basedomain+"assets/content/thumb/img_content8.jpg		";								
							}
						}									
	html += "				' alt='AltImage' alt='' class='full-image product-image' />	";		
	html += "				</a>";
	if (i%2==0){
			html += "				<div class='entry-box'>  ";
			html += "                    <div class='judul_galery'>";
			html += "						<h3>";
			if (e.author) html += e.author.name+" "+ e.author.last_name ;
			else html += " anonymuse" ;
			html += "						</h3>";
			html += "						<span>"+e.title+"</span>";
			html += "					</div>";
			html += "                    <div class='content_action right'>";
			html += "                        <ul>";
			html += "                            <li><a class='icon_love count' counttype='favorite' count='"+e.favorite+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.favorite+"</a></li>";
			html += "							<li><a class='icon_comment count' counttype='comment' count='"+e.commentcount+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.commentcount+"</a></li>";
			html += "							<li><a class='icon_view count' counttype='views' count='"+e.views+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.views+"</a></li>";							
			html += "                       </ul>";
			html += "                   </div>";
			html += "                 </div>";
	}else{
		var datethis = getSplitDate(e.posted_date);
		html += "				<div class='article_isi'>";
		html += "                      <a href='#popup-photography' class='showPopup thumb300 articledetail'  contentid='"+e.id+"' articleType='"+e.pagesname+"' ><h3>"+e.title+"</h3></a>";
		html += "                     <span>"+datethis[2]+" "+datethis[3]+" "+datethis[0]+"</span>";
		html += "                     <p>"+e.brief+"</p>";
		html += "                    <div class='content_action'>";
		html += "                         <ul>";
		html += "						<li><a class='icon_love count' counttype='favorite' count='"+e.favorite+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.favorite+"</a></li>";
		html += "						<li><a class='icon_comment count' counttype='comment' count='"+e.commentcount+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.commentcount+"</a></li>";
		html += "						<li><a class='icon_view count' counttype='views' count='"+e.views+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.views+"</a></li>";
						
		html += "                        </ul>";
		html += "                    </div>";
		html += "               </div>";
	}
	html += "            </div>";
	return html;
}
	
function mediahtmlviews(data){
	var html ="<div class='content_media_listing' >";
	$.each(data,function(i,e){
		html += "<li class='col2 afterPaging'>";
		html += "	<div class='box_little'>";
		html += "		<a href='#popup-photography' class='showPopup thumb120 articledetail'  contentid='"+e.id+"'>";
		if(e.video_thumbnail) html += "<img class='greyscale' src='https://img.youtube.com/vi/"+e.video_thumbnail+"/0.jpg' />";
			else{
				if(e.image) 	html += "<img class='greyscale' src='"+basedomain+"public_assets/"+e.imagepath+"/"+e.image+"' />";
				else 			html += "<img class='greyscale' src='"+basedomain+"assets/content/thumb/default_music.jpg'/>";
			}
		
		html += "		</a>";
		html += "		<div class='isi_boxLittle'>";
		html += "			<h3><a contentid="+e.id+" class='showPopup articledetail' href='#popup-photography'>"+e.title+"</a></h3>";
		html += "			<span>";
		if(e.author) {
			if(e.profilepath) html += "		<a href='"+basedomain+""+e.profilepath+"/"+e.author.authorid+"' >"+e.author.name+"</a>";
			else html+= e.author.name;
		} else {
			html += "		<a href='#popup-photography' class='showPopup articledetail'  contentid='"+e.id+"'>anonymous</a>";
		}
		
		if (e.hasfile && e.file) {
			if (e.can_save) {
				html+= "<a href='"+basedomain+"music/downloadit/"+e.file+"' target='_blank' class='icon_download' title='download'>&nbsp;</a>";				
			} else {
				html += "";
			}
		}
		html += "			</span>";
		html += "			<div class='mp3Player'>  ";
		if(e.file&&e.hasfile) html += html5audio(e.file);
		html += "			</div>";
		html += "			<div class='content_action'>";
		html += "			<ul>";
		html += "				<li><abbr title='add favourite'><a class='icon_love count' counttype='favorite' count="+e.favorite+" contentid="+e.id+" href='javascript:void(0)'>"+e.favorite+"</a></abbr></li>";
		html += "				<li><abbr title='total comment'><a class='icon_comment count' counttype='comment' count="+e.commentcount+"  contentid="+e.id+" href='javascript:void(0)'>"+e.commentcount+"</a></abbr></li>";
		html += "				<li><abbr title='total view'><a class='icon_view count' counttype='views' count="+e.views+"  contentid="+e.id+" href='javascript:void(0)'>"+e.views+"</a></abbr></li>";
		html += "			</ul>";
		html += "			</div>";
		html += "		</div>";
		html += "	</div>";
		html += "</li>";
	});
	html+="</div>";
	return html;
}
	
function articleimagehtml(data){
	var html ="";
	$.each(data,function(i,e){
		html += "<div class='box photo cols2' >";
		html += "<a href='#popup-photography' class='showPopup thumb300 articledetail'  contentid='"+e.id+"'>";						
		if(e.video_thumbnail) html += "<img class='greyscale sequenceload cbanner"+i+"' src='' url='https://img.youtube.com/vi/"+e.video_thumbnail+"/0.jpg' />";
		else{
			if(e.image) 	html += "<img class='greyscale sequenceload cbanner"+i+"'  src='' url='"+basedomain+"public_assets/"+e.imagepath+"/"+e.image+"' />";
			else 			html += "<img class='greyscale sequenceload cbanner"+i+"'  src='' url='"+basedomain+"assets/content/thumb/default_image.jpg'/>";
		}
			html += "</a>";
			html += "<div class='entry-box'>  ";
			html += "	<div class='judul_galery'>";
		if(e.author) 	html += "		<h3>"+e.author.name+"</h3>";
		else			html += "		<h3>anonymous</h3>";
		html += "		<span>"+e.title+"</span>";
		html += "	</div>";
		html += "	<div class='content_action right'>";
		html += "		<ul>";
		html += "			<li><a class='icon_love' href='#'>"+e.favorite+"</a></li>";
		html += "			<li><a class='icon_comment' href='#'>"+e.commentcount+"</a></li>";
		html += "			<li><a class='icon_view' href='#'>"+e.views+"</a></li>";
		html += "		</ul>";
		html += "	</div>";
		html += "</div>";
		html += "</div>";
	});
	return html;
}
	
function playlisthtml(data){
	var html ="";
	$.each(data,function(i,e){
		file = e.file;
		html += "<tr >";
		html += "<td><a rel='"+basedomain+"public_assets/music/mp3/"+file+"' class='iconMp3 icon_play playlist_"+e.no+"' onclick='playthis($(this))' no='"+e.no+"' artisname="+e.author.name+" files='"+e.title+"'>&nbsp;</a></td>";
		html += "<td>"+e.title+"</td>";
		if(e.author) {
			html += "<td>"+e.author.name+"</td>";
		} else {
			html += "<td>anonymuse</td>";
		}
		if(e.author) {
			html += "<td>"+e.author.name+"</td>";
		} else {
			html += "<td>anonymuse</td>";
		}
		html += "<td>"+e.filesize+"</td>";
		html +="<td><a href='javascript:void(0)' class='icon_trash fr ";
		if (pages=='my') {
			html +="removeMyPlaylist";
		} else {
			html +="removePagesSong";
		}
		html +="' id_listsong='"+e.id+"' title='delete'></a></td>";
		html += "</tr>";
	});
	return html;
}
	
function myfavoritehtml(data){
	var html ="";
	$.each(data,function(i,e){
		file = e.file;
		html += " <li class='col3 myFavlist_"+e.id+"'>";
		html += " 	<div class='article_box'>";
		html += " 		<div class='imgBox'>";
		html += "	<a href='#popup-photography' class='showPopup thumb300 articledetail' contentid='"+e.id+"' articleType='"+e.pagesname+"' >";
		html += "	<img class='greyscale' src=' ";
			if (e.video_thumbnail) html += " https://img.youtube.com/vi/"+e.video_thumbnail+"/0.jpg"; 
			else{
				if (e.thumbnail_image)	html += " "+basedomain+"public_assets/"+e.imagepath+"/thumbnail_"+e.thumbnail_image+"";
				else{
				if (e.image) html += ""+basedomain+"public_assets/"+e.imagepath+"/"+e.image+"";
				else html += " "+basedomain+"assets/content/thumb/image3.jpg		";								
				}
			}
		html += "				' /></a>";									
		html += " 		<a class='icon_cat icon_"+e.pagesname+"' href='#'>&nbsp;</a>";
		html += " 		</div>";								
		html += " 		<div class='article_isi'>";
		html += " 			<a href='#popup-photography' class='showPopup thumb300 articledetail'  contentid='"+e.id+"' articleType='"+e.pagesname+"'><h3>"+e.title+"</h3></a>";
		html += " 			<span>";
		if (e.author) { 
			html += ""+e.author.name+""; 
			if (e.author.last_name!=null) html += " "+e.author.last_name+"";
		} else { 
			html += "anonymuse"; 
		}
		html +="</span>";
		if(e.articleType==3) {
			html += "			<div class='mp3Player'>";
			if(e.file&&e.hasfile) html += html5audio(e.file);
			html += "			</div>";
			html += "			<div class='boxBottom2'>";
			html += "				<div class='content_action fl'>";
			html += "					<ul>";
			html += "						 <li><a class='icon_love' href='#'>"+e.favorite+"</a></li>";
			html += "					     <li><a class='icon_comment' href='#'>"+e.commentcount+"</a></li>";
			html += "					     <li><a class='icon_view' href='#'>"+e.views+"</a></li>";
			html += "				    </ul>";
			html += " 				</div>";
			html += "				<a href='javascript:void(0)' class='icon_trash fr' id='deleteimg' imageid="+e.id_image+"></a>";
			html += "			</div>";
		} else {
			html += "			";
		}
		html += "			<div class='boxBottom2'>";
		html += "				<div class='content_action fl'>";
		html += "					<ul>";
		html += "						 <li><a title='total favourite' class='icon_love' href='#'>"+e.favorite+"</a></li>";
		html += "					     <li><a title='total comment' class='icon_comment' href='#'>"+e.commentcount+"</a></li>";
		html += "					     <li><a title='total view' class='icon_view' href='#'>"+e.views+"</a></li>";
		html += "				    </ul>";
		html += " 				</div>";
		html += "				<a href='javascript:void(0)' class='icon_trash fr removeMyFav' id_myfav="+e.id+" title='delete'></a>";
		html += "			</div>";
		html += "		</div>";
		html += "	</div>";
		html += "</li>";
	});
	return html;
}
	
function mygalleryhtml(data){
	var html ="";
	$.each(data,function(i,e){
		file = e.file;
		html += " <li class='col3'>";
		html += " 	<div class='article_box'>";
		html += " 		<div class=='imgBox'>";
		html += "	<a class='thumb300'><img class='greyscale' src=' ";
			if (e.video_thumbnail) html += " https://img.youtube.com/vi/"+e.video_thumbnail+"/0.jpg"; 
			else{
				if (e.thumbnail_image)	html += " "+basedomain+"public_assets/"+e.imagepath+"/thumbnail_"+e.thumbnail_image+"";
				else{
				if (e.image) html += ""+basedomain+"public_assets/"+e.imagepath+"/"+e.image+"";
				else html += " "+basedomain+"assets/content/thumb/image3.jpg		";								
				}
			}									
		html += "				' /></a>	";
		html += " 		<a class='icon_cat icon_"+e.typeofarticle+"' href='#'>&nbsp;</a>";
		html += " 		</div>";								
		html += " 		<div class='article_isi'>";
		html += " 			<h3>"+e.title+"</h3>";
		if(e.articleType==3) {
		html += "			<div class='mp3Player'>";
		if(e.file&&e.hasfile) html += html5audio(e.file);
		html += "			</div>";
		html += "			<div class='boxBottom2'>";
		html += "				<div class='content_action fl'>";
		html += "					<ul>";
		html += "						 <li><a class='icon_love' href='#'>"+e.favorite+"</a></li>";
		html += "					     <li><a class='icon_comment' href='#'>"+e.commentcount+"</a></li>";
		html += "					     <li><a class='icon_view' href='#'>"+e.views+"</a></li>";
		html += "				    </ul>";
		html += " 				</div>";
		html += "				<a href='javascript:void(0)' class='icon_trash fr' id='deleteimg' imageid="+e.id_image+"></a>";
		html += "			</div>";
		} else {
		html += "			";
		}
		html += "			<div class='boxBottom2'>";
		html += "				<div class='content_action fl'>";
		html += "					<ul>";
		html += "						 <li><a class='icon_love' href='#'>"+e.favorite+"</a></li>";
		html += "					     <li><a class='icon_comment' href='#'>"+e.commentcount+"</a></li>";
		html += "					     <li><a class='icon_view' href='#'>"+e.views+"</a></li>";
		html += "				    </ul>";
		html += " 				</div>";
		html += "				<a href='javascript:void(0)' class='icon_trash fr' id='deleteimg' imageid="+e.id_image+"></a>";
		html += "			</div>";
		html += "		</div>";
		html += "	</div>";
		html += "</li>";
	});
	return html;
}
	
function pagesgalleryhtml(data){
	var html ="";
	$.each(data,function(i,e){
		file = e.file;
		html += " 	<li class='col3 pagesGallerylist_"+e.id+"'>";
		html += " 		<div class='article_box'>";
		html += " 			<div class='imgBox'>";
		html += " 			<a href='#popup-photography' class='showPopup thumb300 articledetail'  contentid='"+e.id+"' articleType='"+e.pagesname+"' >";
		html += " 				<img class='greyscale' src=' ";
		if (e.video_thumbnail) html += " https://img.youtube.com/vi/"+e.video_thumbnail+"/0.jpg"; 
		else if (e.file) {
			html+= " "+basedomain+"public_assets/pages/small_"+e.img_pages+" ";
		} else {
			if (e.thumbnail_image) {
				html+= " "+basedomain+"public_assets/"+e.imagepath+"/thumbnail_"+e.thumbnail_image+" ";
			} else {
				if (e.image) {
					html+= " "+basedomain+"public_assets/"+e.imagepath+"/"+e.image+" ";
				} else {
					html+= " "+basedomain+"assets/content/thumb/img_content8.jpg ";
				}
			}
		}
		html += "		' /></a>	";
		html+= "			<a class='icon_cat icon_";
		if(e.file || e.url) {
			html+=""+e.pagesname+"";
		} else {
			html+="photography";
		}
		html+="' href='javascript:void(0)'>&nbsp;</a>";
		html+= "			</div>";
		html+= "			<div class='article_isi'>";
		html+= "				<a href='#popup-photography' class='showPopup thumb300 articledetail' contentid='"+e.id+"' articleType='"+e.pagesname+"' ><h3>"+e.title+"</h3></a>";
		html+= "				<span>";
		if (e.name_pages) {
			html+= " "+e.name_pages+" ";
		} else {
			html+= " anonymuse </span>";
		}
		html+= " 			<div class='boxBottom2'>";
		html+= " 				<div class='content_action fl'>";
		html+= " 					<ul class='bandGallery'>";
		html+= " 						<li><a title='total favourite' class='icon_love count' counttype='favorite' count='"+e.favorite+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.favorite+"&nbsp;</a></li>";
		html+= " 						<li><a title='total comment' class='icon_comment count' counttype='comment' count='"+e.commentcount+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.commentcount+"&nbsp;</a></li>";
		html+= " 						<li><a title='total view' class='icon_view count' counttype='views' count='"+e.views+"' contentid='"+e.id+"' href='javascript:void(0)'>"+e.views+"&nbsp;</a></li>";
		html+= " 					</ul>";
		html+= " 				</div>";
		html+= " 				<a id_galleryPages='"+e.id+"' class='icon_trash fr removegallery' href='javascript:void(0)' title='delete'></a>";
		html+= " 			</div>";
		html+= " 			</div>";
		html+= " 		</div>";
		html+= " 		</li>";
	});
	return html;
}
	
function eventcalendarhtml(data){
	var html ="";
	$.each(data,function(i,e){
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
	return html;
}

function commentviewshtml(data){
	var commenthtml ="";
	$.each(data,function(ix,ex){
		$.each(ex,function(i,e){
			commenthtml +=" <div class='row'>";
			commenthtml +=" <div class='author'>";
			commenthtml +=" <div class='smallthumb'>";
			if(e.img) commenthtml +=" <a href='"+basedomain+"friends/"+e.userid+"'><img width='30px' src='"+basedomain+"public_assets/user/photo/"+e.img+"'></a>";
			else commenthtml +=" <a href='"+basedomain+"friends/"+e.userid+"'><img src='"+basedomain+"assets/content/thumb/user_30x30.jpg'></a>";
			commenthtml +=" </div>";
			commenthtml +=" <div class='meta-comment'>";
			commenthtml +=" <a href='"+basedomain+"friends/"+e.userid+"' class='comment-author'>"+e.name+"</a> &bull; ";
			commenthtml +=" <span class='comment-date'>"+e.date+"</span>";
			commenthtml +=" </div>";
			commenthtml +=" </div>";
			commenthtml +=" <div class='entry-comment'>";
			commenthtml +=" <p>"+e.comment+"</p>";
			commenthtml +=" </div>";
			commenthtml +=" </div>";
		});
	});
	return commenthtml;
}

function latestnewsviewshtml(data){
	var html ="";
	$.each(data,function(i,e){
		html += "<li class='clearfix'>";
		html += "<div class='lpl-content'>";
		html += "<h6><a href='"+basedomain+"news/detail/"+e.id+"'>"+e.title+"</a> ";
		if(e.posted_date!='0000-00-00 00:00:00'){
			var deto = getSplitDate(e.posted_date);
			// 2013,03,14,March
			html += "<span>"+ deto[2] + deto[3] + deto[0]  +"</span></h6>";
		}
		/* else html += "<span> unknown date</span></h6>"; */
		html += "</div>";
		html += "</li>";
	});
	return html;
}

function postcommenthtml(){
	var contentid = $(".commentdata").attr("contentid");
	var comment = $('.textComment').val();
	var userid = $('.comment_userid').val();
	var name = $('.comment_name').val();
	var img = $('.comment_img').val();
	var dates = getCalendarDate();
	var times = getClockTime();
	var commenthtml ="";
	commenthtml +=" <div class='row'>";
	commenthtml +=" <div class='author'>";
	commenthtml +=" <div class='smallthumb'>";
	if(img) commenthtml +=" <a href='"+basedomain+"my'><img width='30px' src='"+basedomain+"public_assets/user/photo/"+img+"'></a>";
	else commenthtml +=" <a href='"+basedomain+"my'><img src='"+basedomain+"assets/content/thumb/user_30x30.jpg'></a>";
	commenthtml +=" </div>";
	commenthtml +=" <div class='meta-comment'>";
	commenthtml +=" <a href='"+basedomain+"my' class='comment-author'>"+name+"</a> &bull; ";
	commenthtml +=" <span class='comment-date'>"+dates+' '+times+"</span>";
	commenthtml +=" </div>";
	commenthtml +=" </div>";
	commenthtml +=" <div class='entry-comment'>";
	commenthtml +=" <p>"+comment+"</p>";
	commenthtml +=" </div>";
	commenthtml +=" </div>";
	return commenthtml;
}
		
function popupmediahtml(article,articleType){
		var lastname = article.author.last_name;
		if (lastname) { lastname } else { lastname = ''; }
		
		var html="";
	
		html+="<div class='entry-music-detail'>";
				
		if(article.video_thumbnail){
			html+=" <div class='imgDetail' style='width:100%' >";	
			html+="	<div  class='articleimagedetail' ><iframe width='610' height='400' src='https://www.youtube.com/embed/"+article.video_thumbnail+"' frameborder='0' allowfullscreen></iframe></div>";												
		}else{
			if (!article.file) {
				html+=" <div class='imgDetail' style='width:100%'  >";	
			} else {
				html+=" <div class='imgDetail'>";	
			}
			html+="	<div  class='articleimagedetail' width='100%' ><img src='";
							if (article.image) html += ""+basedomain+"public_assets/"+article.imagepath+"/"+article.image+"";
							else{
								if (article.thumbnail_image)	html += " "+basedomain+"public_assets/"+article.imagepath+"/thumbnail_"+article.thumbnail_image+"";
								else html += " "+basedomain+"assets/content/thumb/default_music.jpg		";								
							}
			
			html +="' /></div>";
		}
		
		if (article.hasfile && article.file) {
			html+="	<abbr title='add playlist'><a href='javascript:void(0)' contentid='"+article.id+"' fromwhere='"+article.fromwho+"' typeofpage='0' authorid='"+article.authorid+"' class='icons ";
			if(article.video_thumbnail) html+= " icon_plus ";
			else {
				html+= " icon_plus ";
			}
		} else {
			html+="	<abbr title='set cover'><a href='javascript:void(0)' contentid='"+article.id+"' fromwhere='"+article.fromwho+"' typeofpage='0' authorid='"+article.authorid+"' class='icons ";
			if(article.video_thumbnail) html+= " ";
			else {
				html+= " icon_cover ";
			}					
		}
		html+=" iconadd '>&nbsp;</a></abbr>";
		html+="   </div>";
		html+="   <div class='entry-detail'>";
		html+="        <h3 class='articletitledetail'>"+article.title.toUpperCase()+"</h3>";
		html+="        <span class='authornamedetail'>"+ucwords(article.author.name) +" " + ucwords(lastname)+"</span>";
		html+="       <p class='articlecontentdetail' >"+article.content+"</p>";
		html+="     </div>";
		html+="</div>";
		html+="<div style=' margin: 0 0 0px;overflow: hidden;padding: -1px 10px;clear: both;background: repeat-x scroll center bottom transparent;'>";
		if(article.authorid==article.session_pageid) {
			if(article.fromwho==2) {
				if (article.session_ownerid) html +="<a href='#popup-photography' contentid='"+article.id+"' articleType="+article.type+" class='showPopup editarticledetail' style='background: no-repeat scroll 55px 3px #E40404;color: #FFFFFF;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>Edit Content</a>";
			}
		}
		html+="	&nbsp;<span class='notifmessage'></span>";
		html+="</div>";
		html+="	<div class='entry-mp3'>";
		html+="      <div class='mp3Player fl articlefiledetail'>";
		if(article.hasfile&&article.file) html+=html5audio(article.file) ;
		html+="    </div>";
		html+="     <div class='content_action fr'>";
		html+="        <ul>";
				
				if(article.favorite) html+="<li><abbr title='total favourite'><a class='icon_love count favecountdetail' counttype='favorite' count='"+article.favorite+"' contentid='"+article.id+"' href='javascript:void(0)'>"+article.favorite+"</a></abbr></li>";
				else html+="				<li><abbr title='total favourite'><a class='icon_love count favecountdetail' counttype='favorite' count='0' contentid='"+article.id+"' href='javascript:void(0)'>0</a></abbr></li>";
				
				if(article.commentcount)html+="				<li><abbr title='total comment'><a class='icon_comment count commentcountdetail' counttype='comment' count='"+article.commentcount+"' contentid='"+article.id+"' href='javascript:void(0)'>"+article.commentcount+"</a></abbr></li>";
				else html+="				<li><abbr title='total comment'><a class='icon_comment count commentcountdetail' counttype='comment' count='0' contentid='0' href='javascript:void(0)'>0</a></abbr></li>";
				
				if(article.views) html+="				<li><abbr title='total views'><a class='icon_view count viewcountdetail' counttype='views' count='"+article.views+"' contentid='"+article.id+"' href='javascript:void(0)'>"+article.views+"</a></abbr></li>";
				else html+="				<li><abbr title='total views'><a class='icon_view count viewcountdetail' counttype='views' count='0' contentid='0' href='javascript:void(0)'>0</a></abbr></li>";
				
		html+="       </ul>";
		html+="     </div>";
		html+="</div>";
		
		return html;
}

function popupimagehtml(article,articleType){
		var lastname = article.author.last_name;
		if (lastname) { lastname } else { lastname = ''; }
		var html="";
			html+="<div class='popupHeader'>";
			html+="<div class='imgDetail'>	";
			if(article.video_thumbnail){
			html+="	<div  class='articleimagedetail'  ><iframe width='610' height='400' src='https://www.youtube.com/embed/"+article.video_thumbnail+"' frameborder='0' allowfullscreen></iframe></div>";												
			}else{
			html+="	<div  class='articleimagedetail'  ><img style='width:100%' src='";
							if (article.image) html += ""+basedomain+"public_assets/"+article.imagepath+"/"+article.image+"";
							else{
								if (article.thumbnail_image)	html += " "+basedomain+"public_assets/"+article.imagepath+"/thumbnail_"+article.thumbnail_image+"";
								else html += " "+basedomain+"assets/content/thumb/default_image.jpg		";								
							}
			
			html +="' /></div>";
			}
			html+="	<abbr title='set cover'><a href='javascript:void(0)' contentid='"+article.id+"' fromwhere='"+article.fromwho+"' typeofpage='0' authorid='"+article.authorid+"' class='icons ";
			if(article.video_thumbnail) html+= " icon_plus ";
			else{
				if(articleType=='music'||articleType=='dj') html+= " icon_plus ";
				else html+= "icon_cover ";
			}
			html+=" iconadd'>&nbsp;</a></abbr>";
			html+="</div>";
			html+="<div style=' margin: 0 0 0px;overflow: hidden;padding: -1px 10px;clear: both;background: repeat-x scroll center bottom transparent;'>";
			if(article.authorid==article.session_userid) {
				if(article.fromwho!=0) {
				html +="<a href='#popup-photography' contentid='"+article.id+"' articleType="+article.type+" class='showPopup editarticledetail' style='background: no-repeat scroll 55px 3px #E40404;color: #FFFFFF;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>Edit Content</a>";
				}
			}
			html+="	&nbsp;<span class='notifmessage'></span>";
			html+="</div>";
			html+="<div class='popupContent'>";
			html+="	<div class='entry-title'>";
			if (articleType=='event')html+="		<div class='judul_event'>";
			else html+="		<div class='judul_galery'>";
			html+="			<h3 class='articletitledetail'>"+article.title.toUpperCase()+"</h3>";
			
			if (articleType!='event'){
				if(article.author) html+="<span class='authornamedetail'>"+ucwords(article.author.name) +" " + ucwords(lastname)+"</span>";
				else html +="<span class='authornamedetail'>anonymuse</span>";
			}
			
			if (articleType=='event') html+="<h4><span class='attender'>"+article.attending+"</span> Attending</h4>";
			if (articleType=='event') html+="<h4>"+article.brief+"</h4>";
			if (articleType=='event') {
				if(article.posted_date!='0000-00-00 00:00:00'){
					var eventdate = getSplitDate(article.posted_date);
					html+="<span>"+ eventdate[2] +" "+ eventdate[3] +" "+  eventdate[0]  +"</span>";
				}
			}
			html+="		</div>";
			html+="		<div class='content_action right'>";
			html+="			<ul>";
				
				if(article.favorite) html+="<li><abbr title='total favourite'><a class='icon_love count favecountdetail' counttype='favorite' count='"+article.favorite+"' contentid='"+article.id+"' href='javascript:void(0)'>"+article.favorite+"</a></abbr></li>";
				else html+="				<li><abbr title='total favourite'><a class='icon_love count favecountdetail' counttype='favorite' count='0' contentid='"+article.id+"' href='javascript:void(0)'>0</a></abbr></li>";
				
				if(article.commentcount)html+="				<li><abbr title='total comment'><a class='icon_comment count commentcountdetail' counttype='comment' count='"+article.commentcount+"' contentid='"+article.id+"' href='javascript:void(0)'>"+article.commentcount+"</a></abbr></li>";
				else html+="				<li><abbr title='total comment'><a class='icon_comment count commentcountdetail' counttype='comment' count='0' contentid='"+article.id+"' href='javascript:void(0)'>0</a></abbr></li>";
				
				if(article.views) html+="				<li><abbr title='total views'><a class='icon_view count viewcountdetail' counttype='views' count='"+article.views+"' contentid='"+article.id+"' href='javascript:void(0)'>"+article.views+"</a></abbr></li>";
				else html+="				<li><abbr title='total views'><a class='icon_view count viewcountdetail' counttype='views' count='0' contentid='"+article.id+"' href='javascript:void(0)'>0</a></abbr></li>";
				
			html+="			</ul>";
			html+="		</div>";
			html+="	</div>";
			html+="	<div class='entry-popup'>";
			html+="		<p class='articlecontentdetail' >"+article.content+"</p>";
			html+="	</div>";						
			html+="</div>";

		return html;

}

function popupforumhtml(article,articleType){
	var lastname = article.author.last_name;
	if (lastname) { lastname } else { lastname = ''; }
	var html="";
		html+="<div class='box_event'>";
		html+="		<a href='#'>";
		html+="		<div class='event_date'>";
		html+="			<span style='line-height:100px;'><img src='"+basedomain+"assets/images/icon/icon_"+article.type+".jpg'></span>";
		html+="		</div>";
		html+="		</a>";
		html+="		<div class='event_detail'>";
		html+="		<h3 style='text-transform:uppercase;'>("+article.type+")</h3>";
		html+="		<h3>"+article.title+"</h3>";
		if(article.posted_date!='0000-00-00 00:00:00'){
			var forumdate = getSplitDate(article.posted_date);
			html+="<span>"+ forumdate[2] +" "+ forumdate[3] +" "+  forumdate[0]  +"</span>";
		}
		html+="		</div>";
		html+="		<div class='event_action'>";
		html+="			<div class='content_action'>";
		html+="				<ul>";
		html+="				<li><a title='total view' class='icon_view' href='#'>"+article.views+"</a></li>";
		html+="				<li><a title='total comment' class='icon_comment' href='#'>"+article.commentcount+"</a></li>";
		html+="				</ul>";
		html+="			</div>";
		html+="		</div>";
		html+="</div>";
		html+="<div class='box_event'>";
		html+=""+article.content+"</br></br>";
		html+="<img src='"+basedomain+"public_assets/forum/small_"+article.image+"'>";
		html+="</div>";
	return html;
}

function editpopupcontenthtml(article,articleType){
	
	var lastname = article.author.last_name;
	if (lastname) { lastname } else { lastname = ''; }
	var html="";
	html+="<div>";
	html+="	<div class='formTitle'>";
	html+="		<h2 style='color: #E40404;font-family: BebasNeueRegular,serif;font-size: 24px;font-weight: normal;margin: 0;'>EDIT POST CONTENT </h2>";
	html+="		<p>Posted by "+article.author.name+"</p>";
	html+="	</div>";
	html+="<form id='form-editPopupContent' method='post' action='"+basedomain+""+pages+"/editContent' enctype='multipart/form-data'>";
	html+="		<table class='popupContent' cellpadding='0' cellspacing='0' border='0' width=100%>";
	html+="			<tbody>";
	html+="			 <tr height='20'><td><font style='color: #E40404;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>TITLE</font></td></tr>";
	html+="			 <tr height='35'><td><input type='text' name='title' value='"+article.title+"'/></td></tr>";
	html+="			 <tr height='20'><td>&nbsp;</td></tr>";
	html+="			 <tr height='20'><td><font style='color: #E40404;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>DESCRIPTION</font></td></tr>";
	html+="			 <tr height='35'><td><textarea type='text' name='description'>"+article.content+"</textarea></td></tr>";
	html+="			 <tr height='20'><td>&nbsp;</td></tr>";
	html+="			 <tr height='20'><td><font style='color: #E40404;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>TAGS</font></td></tr>";
	html+="			 <tr height='35'><td><input type='text' name='tags' value='"+article.un_tags+"'/></td></tr>";
	html+="			 <tr height='20'><td>&nbsp;</td></tr>";
	if (article.articleType==3 || article.articleType==15) {
		//html+="			 <tr height='20'><td><font style='color: #E40404;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>FILE MP3</font></td></tr>";				
		//html+="			 <tr height='35'><td><i>Ukuran file mp3 yang bisa diupload maksimum 5 MB</i><br><input type='file' name='music' size='30'/></td></tr>";
		html+="			 <tr height='20'><td>&nbsp;</td></tr>";
		html+="			 <tr height='20'><td><font style='color: #E40404;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>MP3 bisa di Download</font></td></tr>";				
		if (article.can_save==1) {
			html+="			 <tr height='35'><td><input type='checkbox' name='can_save' value='1' checked></td></tr>";
		} else {
			html+="			 <tr height='35'><td><input type='checkbox' name='can_save' value='1'></td></tr>";
		}
	} else {
		//html+="			 <tr height='20'><td><font style='color: #E40404;font-family:BebasNeueRegular;font-size: 20px;line-height:text-align: center;text-transform: uppercase;'>FILE IMAGE</font></td></tr>";				
		//html+="			 <tr><td><image src='"+basedomain+"public_assets/"+article.imagepath+"/"+article.image+"' width='100%'/></tr>";
		//html+="			 <tr height='35'><td><i>Ukuran file image yang bisa diupload maksimum 2 MB</i><br><input type='file' name='music' size='30'/></td></tr>";
	}
	
	html+="			 <tr height='20'><td>&nbsp;</td></tr>";
	html+="			 <tr height='20'><td><input type='submit' value='Submit' class='submit-editContent'></td></tr>";
	html+="			 <tr height='20'><td><input type='hidden' name='article_id' value="+article.id+" ></td></tr>";
	html+="			 <tr height='20'><td><input type='hidden' name='authorid' value="+article.authorid+" ></td></tr>";				
	html+="			 </tbody>";				
	html+="</table>";
	html+="</form>";			
	html+="</div>";
	
	return html;
}

function friendsListViewHtml(data,myid){
	var html ="";
	
	$.each(data,function(i,e){
		html += "<li class='col2 friendslist_"+e.id+"'>";
		html += "			 <div class='whiteBox'>";
		html += "				<a class='thumb100' href='"+basedomain+"friends/"+e.id+"'>";
		if(e.img) 	html += "<img src='"+basedomain+"public_assets/user/photo/"+e.img+"'></a>";
		else 		html += "<img src='"+basedomain+"public_assets/user/photo/default.jpg'></a>";
		html += "				<div class='entry-friends'>";
		html += "					<div class='name_friends'>";
		html += "						<h3>"+e.name+" "+e.last_name+"</h3>";
		html += "					 </div>	";
		html += "					<div class='content_action'>";
	
	
		if(e.isFriends){
				html += "						<a href='"+basedomain+"friends/"+e.id+"' class='icon_view fl'>Lihat Profile</a>";
				html += "						<a href='javascript:void(0)' friendid='"+e.id+"' class='icon_trash fr unfriends'>&nbsp;</a>";
		}else {
			html += "						<a href='"+basedomain+"friends/"+e.id+"' class='icon_view fl'>Lihat Profile</a>";
			if(e.id!=myid) html += "<a class='icon_follows fl addfriends' friendid='"+e.id+"' href='javascript:void(0)' >Add Me</a>";
		}
		
		html += "					</div>";
		html += "					</div>";
		html += "				</div>";
		html += "			</div>";
		html += "			 </li>";
	});
	return html;
}

function friendsWithGroupListViewHtml(data){
	var html ="";
	
	$.each(data,function(kCircle,vCircle){
			$.each(vCircle,function(kGroup,vGroup){
				$.each(vGroup,function(kUser,vUser){
						if (vUser.frienddetail.id){
							if (userid!=vUser.frienddetail.id||friendid){
								html+="<li class='col2 friendslist_"+vUser.frienddetail.id+"'>";
								 html+="<div class='whiteBox'>";
								html+="	<a class='thumb100' href='"+basedomain+"friends/"+vUser.frienddetail.id+"'><img src='"+basedomain+"public_assets/user/photo/";
									if (vUser.frienddetail.img) html+= vUser.frienddetail.img;
									else html+= "default.jpg";
									html+= "'></a>";
								html+="	<div class='entry-friends'>";
								html+="		<div class='name_friends'>";
								if(vUser.frienddetail.last_name) html+="			<h3>"+vUser.frienddetail.name.toUpperCase()+" "+vUser.frienddetail.last_name.toUpperCase()+"</h3>";
								else html+="			<h3>"+vUser.frienddetail.name.toUpperCase()+"</h3>";
								html+="		 </div>	";
								html+="		<div class='content_action'>";
								html+="			<a href='"+basedomain+"friends/"+vUser.frienddetail.id+"' class='icon_view fl'>Lihat Profile</a>";
								if (!friendid) 	html+="	<a href='javascript:void(0)' friendid='"+vUser.frienddetail.id+"' class='icon_trash fr unfriends'>&nbsp;</a>";
								if (!friendid){
												html+="		<div class='toggle fr'>";
												html+="			<div class='title'>";
												html+="			<a href='#' class='toggle icon_group fr'></a>";
												html+="			</div>";
												html+="			<div class='inner'>";
											
												$.each(group,function(k,v){														
														if (k!=0){
															
															if ($.inArray(k,vUser.frienddetail.groups)!=-1)
															{
																html+="<label><input type='checkbox' class='unchangegroupoffriend' groupid='"+k+"' friendid='"+vUser.frienddetail.id+"' checked /><span>"+v+"</span></label>";
															}else{
																html+="<label><input type='checkbox' class='changegroupoffriend' groupid='"+k+"' friendid='"+vUser.frienddetail.id+"' /><span>"+v+"</span></label>";
															}
														}
															
												});				
												html+="				</div>";
												html+="			</div>";
										}
								html+="			</div>";
								html+="		</div>";
								html+="	</div>";
								html+="	</li>";
							}
						}
				});					
		});
	});
	return html;
}

function postmoderationviews(data){
	var html ="";
	
	$.each(data,function(i,e){
		html+=" <div class='rows'>";
		html+=" 	<a href='"+basedomain+"moderation/detail/"+e.id+"' class='thumbsmall fl'>";
		html+=" 	<img src='"+basedomain+"public_assets/user/photo/";
		if(e.author.img) html+="small_"+e.author.img+"";
		else html+="default.jpg";
		html+="' /></a>";
		if (e.imagepath)html+="<div class='image-post'><span class='img-moderation'><a href='"+basedomain+"moderation/detail/"+e.id+"'><img src='"+e.image_full_path+"' /></a></span></div>";
		html+="		<div class='entries fl'>";
		html+="			<h3 class='username fl'>"+e.author.name+"</h3>";
	
		
		
		// format date
		var posted_date = e.posted_date;
		var datesarr = posted_date.split(' ');
		var dates = datesarr[0].split('-');
		var times = datesarr[1].split(':');
		var newdate = ""+dates[2]+"/"+dates[1]+"/"+dates[0]+" "+times[0]+":"+times[1]+"";
		html+="			<span class='date fr'>"+newdate+"</span>";
		html+="			<p>"+e.title+"</p>	";
		html+="		</div>";
		html+="			<a class='icons icon_expand' href='"+basedomain+"moderation/detail/"+e.id+"'>&nbsp;</a>";
		if (publishedtype==3)html+="			<a class='icons icon_check' href='"+basedomain+"moderation/publishit/"+e.id+"'>&nbsp;</a>";
		else html+="			<a class='icons icon_delete' href='"+basedomain+"moderation/unpublish/"+e.id+"'>&nbsp;</a>";
		html+="	</div>";
	});
	return html;
}

function listofuserviews(data){
	var html ="";

	$.each(data,function(k,v){
		var brand1="N/A";
		var brand2="N/A";
		html+='<tr>';
			html+='<th>'+v.name+' '+v.last_name+'</th>';
			html+='<th>'+v.email+'</th>';
			//console.log(v.pagename);
			if(v.pagename == null) html+='<th>N/A</th>';
			else html+='<th>'+v.pagename+'</th>';
			if(v.cityname == null) html+='<th>N/A</th>';
			else html+='<th>'+v.cityname+'</th>';
			
			if(v.brandid=='5'){
				brand1="MARLBORO";
			}else if(v.brandid=='4'){brand1="AMILD";}

			if(v.brandsubid=='5'){
				brand2="MARLBORO";
			}else if(v.brandsubid=='4'){brand2="AMILD";}
			html+='<th>'+brand1+'</th><th>'+brand2+'</th>';			
			html+='<th><a class="Btn icon-update" href="'+basedomain+'register/edit/'+v.id+'" ></a></th>';
			html+='<th><a class="Btn icon-delete" href="'+basedomain+'register/unusers/'+v.id+'" ></a></th>';
		html+='</tr>';
	});
	return html;
}	

function commentmoderationviews(data){
	var html ="";
	
	$.each(data,function(i,e){
		html+=" <div class='rows'>";
		html+=" 	<a href='"+basedomain+"moderation/detail/"+e.contentid+"' class='thumbsmall fl'>";
		html+=" 	<img src='"+basedomain+"public_assets/user/photo/";
		if(e.img) html+="small_"+e.img+"";
		else html+="default.jpg";
		html+="' /></a>";
		html+="		<div class='entries fl'>";
		html+="			<h3 class='username fl'>"+e.name+"</h3>";
		
		// format date
		var posted_date = e.date;
		var datesarr = posted_date.split(' ');
		var dates = datesarr[0].split('-');
		var times = datesarr[1].split(':');
		var newdate = ""+dates[2]+"/"+dates[1]+"/"+dates[0]+" "+times[0]+":"+times[1]+"";
		html+="			<span class='date fr'>"+newdate+"</span>";
		html+="			<p>"+e.comment+"</p>	";
		html+="		</div>";
		html+="			<a class='icons icon_expand' href='"+basedomain+"moderation/detail/"+e.contentid+"'>&nbsp;</a>";
		if (publishedtype==3) html+="			<a class='icons icon_check' href='"+basedomain+"moderation/commentpublished/"+e.id+"'>&nbsp;</a>";
		else html+="			<a class='icons icon_delete' href='"+basedomain+"moderation/uncomment/"+e.id+"'>&nbsp;</a>";
		html+="	</div>";
	});
	return html;
}

function venuemoderationviews(data){
	var html ="";
	
	$.each(data,function(i,e){
		var posted_date = e.datetimes;
		var datesarr = posted_date.split(' ');
		var dates = datesarr[0].split('-');
		var times = datesarr[1].split(':');
		var newdate = ""+dates[2]+"/"+dates[1]+"/"+dates[0]+" "+times[0]+":"+times[1]+"";
		html+=" <div class='rows'>";
		html+="		<div class='entries-full fl'>";
		html+="			<h3 class='venueName fl'>"+e.venuename+"</h3>";
			html+="			<span class='date fr'>"+newdate+"</span>";
		html+="			<p class='venueAddress'>"+e.address+", "+e.city+", "+e.provinceName+"</p>";
		html+="		</div>";
		html+="			<a class='icons icon_expand' href='"+basedomain+""+pages+"/detailVenue/"+e.id+"'>&nbsp;</a>";
		if (publishedtype==3) html+="			<a class='icons icon_check showPopup' href='"+basedomain+""+pages+"/publishitvenue/"+e.id+"' onclick='return confirm(Are you sure you want to delete this?)'>&nbsp;</a>";
		else html+="			<a class='icons icon_delete showPopup' href='"+basedomain+""+pages+"/unpublishvenue/"+e.id+"' onclick='return confirm(Are you sure you want to delete this?)'>&nbsp;</a>";
		html+="	</div>";
	});
	return html;
}

function challengeviews(data){
	var html ="";
	
	$.each(data,function(i,e){
		html+=" <div class='rows'>";
		html+=" 	<a href='#' class='ico icon_challenge fl'>&nbsp;</a>";
		html+="		<div class='entries fl'>";
		html+="			<h3 class='username fl'>"+e.title+"</h3>";
		html+="			 <span class='date fr'>"+e.posted_date+"</span>";
		html+="			<p>"+e.brief+"</p>";
		html+="		</div>";
		html+="			 <a class='icons icon_zoom' href="+basedomain+"challenge/detail/"+e.id+"'>&nbsp;</a>";
		if (e.authorid == userid) {
			html+="			<a class='icons icon_delete' href='"+basedomain+"challenge/unpublish/"+e.id+"' onclick='return confirm(\"Are you sure you want to delete this?\")'>&nbsp;</a>";
		}
		html+="	</div>";
	});
	return html;
}

function brandviews(data){
	var html ="";
	
	$.each(data,function(i,e){
		html+='<div class="rows">';
            html+='<div class="entries-full">';
                html+='<h3 class="username fl">'+e.title+'</h3>';
                html+='<span class="date fr">'+e.posted_date+'</span>';
                html+='<p>'+e.brief+'</p>';
            html+='</div>';
             html+='<a href="'+basedomain+'brand/edit/'+e.id+'" class="icons icon_expand">&nbsp;</a>';
            //html+='<a class="icons icon_tool" href="'+basedomain+'brand/detail/'+e.id+'">&nbsp;</a>';
           html+='<a class="icons icon_delete" href="'+basedomain+'brand/unpublish/'+e.id+'" onclick="return confirm(\'This Brands will be gone forever. Confirm deletion?\')">&nbsp;</a>';
        html+='</div>';
	});
	return html;
}

function challengeHastagviews(data){
	var html ="";
	
	$.each(data,function(i,e){
		if(i==0){
			html+=" 	<div class=head-row>";
			html+=" 		<a href='#' class='fl tagslist'>"+tags+"</a>";
			html+=" 		 <span class='fr coversation-total'> "+total_hashtag+" Conversations</span>";
			html+=" 	</div>";
		}
		html+=" 	<div class='rows'>";
		html+=" 		<a href='#' class='thumbsmall fl'><img src="+basedomain+"public_assets/user/photo/";
		if (e.author.img) {
			html+="small_"+e.author.img+"";
		} else {
			html+="default.jpg";
		}
		html+="></a>";
		html+=" 		<div class='entries fl'>";
		html+=" 		 	<h3 class='username fl'>"+e.author.name+"</h3>";
		html+=" 		 	<span class='date fr'>"+e.posted_date+"</span>";
		html+=" 		 	 <p>"+e.content+"</p>";
		html+=" 		 	 <a href='"+basedomain+"challenge/detailHashtag/"+e.id+"/"+cidStr+"/"+e.tags+"' class='seedetail'>See Detail &raquo;</a>";
		html+=" 		</div>";
		html+=" 	</div>";
	});
	return html;
}

function inboxviews(data){
	var html ="";
	
	$.each(data,function(i,e){
		html+=" <div class='rows'>";
		html+=" 	<a href='"+basedomain+"inbox/detail/"+e.id+"' class='thumbsmall fl'>";
		html+=" 	<img src='"+basedomain+"public_assets/user/photo/";
		if(e.userdetail.img) html+="small_"+e.userdetail.img+"";
		else html+="default.jpg";
		html+="' /></a>";
		html+="		<div class='entries fl'>";
		html+="			<h3 class='username fl'>"+e.userdetail.name+" "+e.userdetail.last_name+"</h3>";
		html+="			<span class='date fr'>"+e.datetime+"</span>";
		html+="			<p>to : "+e.recepientdetail.name+" </p>	";
		html+="			<p>message : "+e.message+"</p>	";
		html+="		</div>";
		html+="			<a class='icons icon_expand' href='"+basedomain+"inbox/detail/"+e.id+"'>&nbsp;</a>";
		html+="			<a class='icons icon_delete' href='"+basedomain+"inbox/uninboxmessage/"+e.id+"'>&nbsp;</a>";
		html+="	</div>";
	});
	return html;
}



function entourageviews(data){
	var html ="";
	
	$.each(data,function(i,e){
			html+="  <div class='rows4'>";
			html+="                     <div class='thumb-user fl'>";
			html+="                         <a href='"+basedomain+"entourage/profile/"+e.id+"'><img src='"+e.image_full_path+"' /></a>";
			html+="                     </div><!-- /.thumb-user -->";
			html+="                     <div class='entry-user fl'>";
			html+="                         <h4 class='username'><a href='"+basedomain+"entourage/profile/"+e.id+"'>"+e.name+"</a></h4>";
			html+="                        <span class='lates-date'>Latest engagement: ";
		if(e.latestengagament.date)  html+=e.latestengagament.date;
		else html+="  N/A";
			html+=" </span>";
			html+="<span class='type-user'>";
			html+="Type:";
		if(e.latestengagament)  html+= e.latestengagament.engagementtype;
		else html+="N/A";
			html+="</span> ";
			html+=" </div> ";
        if(e.n_status==0) html+=" <span class='icon-pending fr'>&nbsp;</span>";
		if(e.n_status==1) html+=" <span class='icon-approved fr'>&nbsp;</span>";
		if(e.n_status==2) html+=" <span class='icon-rejected fr'>&nbsp;</span>";
			html+=" </div> ";
	});
	return html;
}


function galleryviews(data){
	var html ="";
	$.each(data,function(i,e){
		html +=" <div class='col3'>";
		html +="       	<div class='box-gallery'>";
		html +="             	<div class='imgbox'>";
		html +="               	<a href='"+basedomain+"gallery/detail/"+e.id+"' class='thumb-gallery'><img src='"+e.image_full_path+"' width='100%' /></a>";
		html +="                     <div class='gallery-author'>";
		html +="                         <a href='#' class='thumbsmall fl'><img src='"+e.author.image_full_path+"' /></a><h1 class='fl username'>"+e.author.name+"  </h1>";
		html +="                     </div> ";
		html +="                </div>";
		html +="                 <div class='gallery-info'>";
		var posted_date = e.posted_date;
		var datesarr = posted_date.split(' ');
		var dates = datesarr[0].split('-');
		var times = datesarr[1].split(':');
		var newdate = ""+dates[2]+" "+monthconverter(dates[1])+" "+dates[0] ;
		html +="                	<span class='desc1'>"+e.title+" - "+newdate+"</span>";
		html +="					<span class='desc2'>"+e.totalgallery+" Photos</span>";
		html +="                </div> ";
		html +="            </div> ";
		html +="    </div> ";
	});
return html;

}

function badgeviews(data){
	var html ="";
	$.each(data,function(i,e){
		html +=" <div class='col3'>";
		html +="       	<div class='box-gallery'>";
		html +="             	<div class='imgbox'>";
		html +="               	<a href='#' class='thumb-gallery'><img src='"+basedomain+"public_assets/badges/"+e.image+"' width='100%' /></a>";
		html +="                     <div class='gallery-author'>";
		html +="                         <h1 class='fl username'>"+e.name+"  </h1>";
		html +="                     </div> ";
		html +="                </div>";
		html +="                 <div class='gallery-info'>";
		var posted_date = e.datetime;
		var datesarr = posted_date.split(' ');
		var dates = datesarr[0].split('-');
		var times = datesarr[1].split(':');
		var newdate = ""+dates[2]+" "+monthconverter(dates[1])+" "+dates[0] ;
		html +="                	<span class='desc1'>"+e.detail+"</span>";
		html +="					<span class='desc2'>"+newdate+"</span>";
			html +="	<a href='"+basedomain+"badges/sendTo/"+e.id+"'>Send Badge</a>";                      
            html +="      <a onclick='return confirm(\"Are you sure you want to delete this?\")' href='"+basedomain+"badges/deleteBadge/"+e.id+"' class='icons icon_delete fr'>&nbsp;</a>";
              html +="              <a href='"+basedomain+"badges/edit/"+e.id+"' class='icons icon_expand fr'>&nbsp;</a>";

		html +="                </div> ";
		html +="            </div> ";
		html +="    </div> ";
	});
return html;

}

function merchandiseviews(data){
	var html ="";
	$.each(data,function(i,e){
		html +=" <div class='col3'>";
		html +="       	<div class='box-gallery'>";
		html +="             	<div class='imgbox'>";
		html +="               	<a href='#' class='thumb-gallery'><img src='"+basedomain+"public_assets/merchandises/"+e.image+"' width='100%' /></a>";
		html +="                     <div class='gallery-author'>";
		html +="                         <h1 class='fl username'>"+e.name+"  </h1>";
		html +="                     </div> ";
		html +="                </div>";
		html +="                 <div class='gallery-info'>";
		var posted_date = e.postdate;
		//var datesarr = posted_date.split(' ');
		var dates = posted_date.split('-');
		//var times = datesarr[1].split(':');
		var newdate = ""+dates[2]+" "+monthconverter(dates[1])+" "+dates[0] ;
		html +="                	<span class='desc1'>"+e.detail+"</span>";
		html +="					<span class='desc2'>"+newdate+"</span>";                     
            html +="      <a onclick='return confirm(\"Are you sure you want to delete this?\")' href='"+basedomain+"badges/deleteMerchandise/"+e.id+"' class='icons icon_delete fr'>&nbsp;</a>";
              html +="              <a href='"+basedomain+"merchandise/edit/"+e.id+"' class='icons icon_expand fr'>&nbsp;</a>";

		html +="                </div> ";
		html +="            </div> ";
		html +="    </div> ";
	});
return html;

}

function monthconverter(i){
	var i  = parseInt(i,10);
	var month=new Array();
	month[1]="January";
	month[2]="February";
	month[3]="March";
	month[4]="April";
	month[5]="May";
	month[6]="June";
	month[7]="July";
	month[8]="August";
	month[9]="September";
	month[10]="October";
	month[11]="November";
	month[12]="December";
return month[i];

}