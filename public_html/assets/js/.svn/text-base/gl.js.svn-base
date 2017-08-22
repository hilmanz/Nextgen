/**
 * Google Custom Search Engine Wrapper
 */

var google_search = function(t,lang,callback) {
	var l;
	var language = $(lang).val();
	if(language == '1'){l="lang_id";}else{l="lang_en";}
    var u = "google_search.php?q="+encodeURIComponent($(t).val())+"&lr="+l;
	
    var img = "";
    $.ajax({
        type: "GET",
		url: u,
		dataType: "json",
		success: function(response) {
			try{
				var data = {q:response.GSP.PARAM['3_attr'].js_escaped_value,
							total:parseInt(response.GSP.RES.M),
							items:[],
							next_url:response.GSP.RES.NB.NU,
							prev_url:""};
				
				$.each(response.GSP.RES.R,function(k,v){
					if(k.indexOf('_attr')==-1){
						try{
							img = v.PageMap.DataObject['0'].Attribute_attr.value;
						}catch(e){
							img = "";	
						}
						data.items.push({
							image:img,
							url:v.U,
							text:v.S,
							title:v.T
						});
					}
				});
			}catch(e){callback(null);}
	 		callback(data);
		}
	});
};
function google_search_next(next_url){
	var u = "google_search.php?u="+encodeURIComponent(next_url);
	console.log(u);
    var img = "";
    $.ajax({
        type: "GET",
		url: u,
		dataType: "json",
		success: function(response) {
	 		var data = {q:response.GSP.PARAM['3_attr'].js_escaped_value,
	 					total:parseInt(response.GSP.RES.M),
	 					items:[],
	 					next_url:response.GSP.RES.NB.NU,
	 					prev_url:""};
	 		$.each(response.GSP.RES.R,function(k,v){
	 			if(k.indexOf('_attr')==-1){
	 				try{
	 					img = v.PageMap.DataObject['0'].Attribute_attr.value;
	 				}catch(e){
	 					img = "";	
	 				}
	 				data.items.push({
	 					image:img,
	 					url:v.U,
	 					text:v.S,
	 					title:v.T
	 				});
	 			}
	 		});
	 		try{
	 			data.prev_url = response.GSP.RES.NB.PU;
	 		}catch(e){}
	 		callback(data);
		}
	});
}
