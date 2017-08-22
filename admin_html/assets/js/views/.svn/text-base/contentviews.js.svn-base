   

function listofuserviews(data){
	var html ="";

	$.each(data,function(k,v){
	 
		html+='<tr>';
			html+='<th>'+v.name+' '+v.last_name+'</th>';
			html+='<th>'+v.email+'</th>';
			html+='<th>'+v.pagename+'</th>';
			 html+='<th><a class="Btn icon-update" href="'+basedomain+'administrator/edit/'+v.id+'" ></a></th>';
			html+='<th><a class="Btn icon-delete" href="'+basedomain+'administrator/unusers/'+v.id+'" ></a></th>';
		html+='</tr>';
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