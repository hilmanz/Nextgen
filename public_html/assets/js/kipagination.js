//JS Pagination - kiPagination
//created by @cendekiapp
var realpage = 0;
function kiPagination(data, n, divPage, type, fungsi, setPerPage){
	$('#'+divPage).html('');
	var perPage = 10;
	if(setPerPage > perPage || setPerPage < perPage){perPage = setPerPage;}
	var totalPage = Math.ceil(parseInt(data)/perPage);
	if (totalPage > 1){
		//Current Page
		var backward = 0;
		var current = totalPage-(totalPage-n);
		$("#"+divPage+" a.cPaging").removeClass('current');
	
		if(current>=3){
			var lastPage = totalPage-current;
			
			if(lastPage<3){
				
				var str="";
				var i;
				if(totalPage <= 4){
					var nx = totalPage-1;
				}else{
					var nx = 4;
				}
				var p = totalPage-nx;
				
				if(totalPage < 5){
					var maxPageShow = totalPage;
				}else{
					var maxPageShow = 5;
				}
				
				//prev btn
				if(current != 1){
					var realPage = ((current-1)*perPage)-perPage;
					str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo;</a>';
				}
				//paging number
				for(i=0;i<maxPageShow;i++){
					if(p == 1){var realPage = 0;}else{var realPage = ((p-1)*perPage);}
					str+='<a id="p'+p+'" class="cPaging" href="#p'+p+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+p+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">'+p+'</a>';
					p++;
				}
				//next btn
				if(current != totalPage){
					if(current == 1){var realPage = perPage;}else{var realPage = (current*perPage);}
					str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&raquo;</a>';
				}
				$("#"+divPage+"").html(str);
				
				//highlight
				$("#"+divPage+" a#p"+current+"").addClass('current');
			}else{
				
				pageLog = 1;
				var str="";
				var i;
				var p = current-2;
				
				if(totalPage < 5){
					var maxPageShow = totalPage;
				}else{
					var maxPageShow = 5;
				}
				
				//prev btn
				if(current != 1){
					var realPage = ((current-1)*perPage)-perPage;
					str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo;</a>';
				}
				//paging number
				for(i=0;i<maxPageShow;i++){
					if(p == 1){var realPage = 0;}else{var realPage = ((p-1)*perPage);}
					str+='<a id="p'+p+'" class="cPaging" href="#p'+p+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+p+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">'+p+'</a>';
					p++;
				}
				//next btn
				if(current != totalPage){
					if(current == 1){var realPage = perPage;}else{var realPage = (current*perPage);}
					str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&raquo;</a>';
				}
				$("#"+divPage+"").html(str);
				
				//highlight
				$("#"+divPage+" a#p"+current+"").addClass('current');
			}
		}else{		
				var str="";
				var i;
				var p = 1;
				
				if(totalPage < 5){
					var maxPageShow = totalPage;
				}else{
					var maxPageShow = 5;
				}
				
				//prev btn
				if(current != 1){
					var realPage = ((current-1)*perPage)-perPage;
					str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo;</a>';
				}
				//paging number
				for(i=0;i<maxPageShow;i++){
					if(p == 1){var realPage = 0;}else{var realPage = ((p-1)*perPage);}
					str+='<a id="p'+p+'" class="cPaging" href="#p'+p+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+p+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">'+p+'</a>';
					p++;
				}
				//next btn
				if(current != totalPage){
					if(current == 1){var realPage = perPage;}else{var realPage = (current*perPage);}
					str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&raquo;</a>';
				}
				$("#"+divPage+"").html(str);
			
			//highlight
			$("#"+divPage+" a#p"+current+"").addClass('current');
		}
	}
}

var scrollRefresh = {
			pastTop: false,
			pastBottom: false,
			previous: 0,
			bottom: function(callback) {
			  var pBottom = $(window).height() + $(window).scrollTop() >= $(document).height();
			  if(!this.pastBottom && pBottom) {
				callback($(window).height() + $(window).scrollTop());
				this.pastBottom = true;
			  } else {
				 
				if(!pBottom) this.pastBottom = false;
			  }
			
			  this.previous = $(window).scrollTop();
			},
			top: function(callback) {
			  var pTop = $(window).scrollTop() < this.scrollPrevious && $(window).scrollTop <= 0;
			  if(!this.pastTop && pTop) {
				
				callback($(window).scrollTop());
				this.pastTop = true;
			  } else {
				
				if(!pTop) this.pastTop = false;
			  }
			  this.previous = $(window).scrollTop();
			}
		}
