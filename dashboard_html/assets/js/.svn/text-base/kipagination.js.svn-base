//JS Pagination - kiPagination
//created by @cendekiapp
var realpage = 0;
function kiPagination(data, n, divPage, type, fungsi, setPerPage, goToPage){
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
					str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo; PREV</a>';
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
					str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">NEXT &raquo;</a>';
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
					str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo; PREV</a>';
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
					str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">NEXT &raquo;</a>';
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
					str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo; PREV</a>';
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
					str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');kiPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">NEXT &raquo;</a>';
				}
				$("#"+divPage+"").html(str);
			
			//highlight
			$("#"+divPage+" a#p"+current+"").addClass('current');
		}
	}

	if(goToPage>0){
		var gotoHTML = '';
			gotoHTML+='<input type="number" name="goto" onkeyup="checkGoTo(this,'+data+',\''+fungsi+'\',\''+type+'\',\''+divPage+'\','+setPerPage+','+goToPage+');" value="'+n+'" /><a data-current="'+n+'" class="btn gotoBTN" href="#" >GO</a>';
		$("#"+divPage+"").append(gotoHTML);
		$("input[name='goto']").keydown(function(event) {
	        // Allow: backspace, delete, tab, escape, enter and .
	        if ( $.inArray(event.keyCode,[46,8,9,27,13,190]) !== -1 ||
	             // Allow: Ctrl+A
	            (event.keyCode == 65 && event.ctrlKey === true) || 
	             // Allow: home, end, left, right
	            (event.keyCode >= 35 && event.keyCode <= 39)) {
	                 // let it happen, don't do anything
	                 return;
	        }else {
	            // Ensure that it is a number and stop the keypress
	            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
	                event.preventDefault(); 
	            }   
	        }
	    });
		/*$(".gotoBTN").click(function(){
			kiPagination(data,$(this).data('current'),divPage,type,fungsi,setPerPage,1);
		});*/

	   
	}
}

function checkGoTo(i,max,fungsi,type,divPage,setPerPage,goToPage){

	var page = Math.ceil(max/setPerPage);
	var current = i.value;


	if(i.value > page){
		$(i).val(page);
		current = page;
	}
	var realPage =(setPerPage*current)-setPerPage;
	//$('a.gotoBTN').data('current',current);
	$('a.gotoBTN').attr('onClick','');
	$('a.gotoBTN').attr('onClick',fungsi+'(\''+type+'\', '+realPage+');kiPagination('+max+','+current+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+','+goToPage+')');
	
}
