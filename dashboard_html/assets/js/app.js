/*AJAX global call function*/
// data consist of:
// 	data.url
// 	data.param
function post_json(data){
		var response = jQuery.ajax({
                    type: "POST",
                    url: data.url,
                    data: data.params,
                    dataType: data.type
	            });
		return response;
}


App = Ember.Application.create({
	LOG_TRANSITIONS: true
});

App.Router.map(function() {
  //this.route("index", { path: "/brand" });
  this.route("ba", { path: "/ba" });
  this.route("user", { path: "/user" });
  this.route("ga", { path: "/ga" });
  this.route("badge", { path: "/badge" });
  this.route("topvisitedpage", { path: "/topvisitedpage" });
  this.route("game", { path: "/game" });
  this.route("redeem", { path: "/redeem" });
  this.route("auction", { path: "/auction" });
  this.route("device", { path: "/device" });
});

//brand engagement/Index
App.IndexRoute = Ember.Route.extend({
	model: function(params) {
		var data = {};
	    data.url='home/loginActivity';
	    data.params={ajax:1};
	    data.type="json";
		post_json(data).done(function(response){
			try{
				if(response.login_total.status==1){
					var rdata = response.login_total.data;
					//Total
					var htm='';
					htm+='<div class="row">';
			            htm+='<h3 class=""><span>Number of User</span>';
			            htm+='<a class="icon_arrow expand" href="#">Click to Expand</a>';
			            htm+='<a class="icon_arrow_grey hide2" href="#">hide</a> </h3>';
			            htm+='<div class="acc_section">';
			                htm+='<div class="total_login chart_section">';
			                    
			                htm+='</div>';        
			            htm+='</div>';
			        htm+='</div>';
			        $("#brand_engagement").append(htm);
			        var table = "";
			        table+='<table border="1">';
				        table+='<thead>';
				        table+='<tr>';
				        table+='<td>Unique Visitor Login Per-Campaign</td>';
				        table+='<td>Total Login</td>';
				        table+='</tr>';
				        table+='</thead>';
				        table+='<tbody>';
				        table+='<tr>';
				        table+='<td>'+rdata.unik_user+'</td>';
				        table+='<td>'+rdata.total+'</td>';
				        table+='</tr>';
				        table+='</tbody>';
			        table+='</table>';
				    $("#brand_engagement").find('.total_login').html(table);
					

					//Daily
					var htm='';
					htm+='<div class="row">';
			            htm+='<h3 class=""><span>Number of User</span>';
			            htm+='<a class="icon_arrow expand" href="#">Click to Expand</a>';
			            htm+='<a class="icon_arrow_grey hide2" href="#">hide</a> </h3>';
			            htm+='<div class="acc_section">';
			                htm+='<div class="daily_login chart_section">';
			                    
			                htm+='</div>';        
			            htm+='</div>';
			        htm+='</div>';
			        $("#brand_engagement").append(htm);
			        
			    	var rdata = response.login_daily.data;
			        $("#brand_engagement").find('.daily_login').highcharts({
			            title: false,
			         
			            xAxis: {
			                categories: rdata.categories
			            },
			            yAxis: {
			                title: {
			                    text: rdata.title
			                },
			                plotLines: [{
			                    value: 0,
			                    width: 1,
			                    color: '#808080'
			                }]
			            },
			            legend: {
			                layout: 'vertical',
			                align: 'right',
			                verticalAlign: 'middle',
			                borderWidth: 0
			            },
			            series: [{
			                name: rdata.title,
			                data: rdata.data
			            }]
			        });

				}
			}catch(e){}
		});
		

		//Game Activity
		var data = {};
	    data.url='home/gameActivity';
	    data.params={ajax:1};
	    data.type="json";
		post_json(data).done(function(response){
			try{
				if(response.game_total.status==1){
					//Total
					var rdata = response.game_total.data;
					var htm='';
					htm+='<div class="row">';
			            htm+='<h3 class=""><span>'+response.game_total.title+'</span>';
			            htm+='<a class="icon_arrow expand" href="#">Click to Expand</a>';
			            htm+='<a class="icon_arrow_grey hide2" href="#">hide</a> </h3>';
			            htm+='<div class="acc_section">';
			                htm+='<div class="game_total chart_section">';
			                    
			                htm+='</div>';        
			            htm+='</div>';
			        htm+='</div>';
			        $("#brand_engagement").append(htm);
			        var table = "";
			        table+='<table border="1">';
				        table+='<thead>';
				        table+='<tr>';
				        $.each(rdata,function(k,v){
				        	table+='<td colspan="2">Game '+v.gamesid+'</td>';
				        });
				        table+='</tr>';
				        table+='<tr>';
				        $.each(rdata,function(k,v){
				        	table+='<td>Unique</td>';
				        	table+='<td>Total</td>';
				        });
				        table+='</tr>';
				        table+='</thead>';
				        table+='<tbody>';
				        table+='<tr>';
				        $.each(rdata,function(k,v){
					        table+='<td>'+v.unik_user+'</td>';
					        table+='<td>'+v.total+'</td>';
				    	});
				        table+='</tr>';
				        table+='</tbody>';
			        table+='</table>';
				    $("#brand_engagement").find('.game_total').html(table);
				}
			}catch(e){}
		});
	}
});

//Draw Widgets
function draw_widgets(ID){
	var container = $('#'+ID.divID+' table tbody');
	container.html('<div style="width:100%;display:block;height:100px;text-align:center;"><img src="images/sos-loader.gif" width="142" height="18" style="margin: 50px auto;" /></div>');
	smac_api(smac_api_url+'?method=widgets&action=load',function(response){
		container.html('');
		if(response.data!=null){
			$.each(response.data,function(k,v){
				
				var table_data = {table:ID.divID,
									label:v.wLabel,
									report_type:v.wReportType,
									topic:v.wTopic,
									topic_isu:v.wTopicIsu,
									topic_multiple:v.wTopicMultiple,
									topic_multiple_2:v.wTopicMultiple2,
									topic_candidateNparty: v.wTopicPartyCandidate,
									channel:v.wChannel,
									channel2:v.wChannel2,
									sentiments:v.wSentiment,
									rangeTime: v.wRangeTime,
									dimension:{colspan:v.wColspan,rowspan:v.wRowspan},
									widget_id:v.widget_id,
									containerBox:v.wContainer};
				draw_table(table_data,true);
			});	
		}else{
			$('#home table tbody').html('<div style="width:100%;display:block;height:100px;text-align:center;"><p>WIDGET BELUM TERSEDIA.</p></div>');
		}
	});
}

function draw_table(table_data,load){
	var arrCol = [1,2,3,4,5];
	var rows = parseInt(table_data.dimension.rowspan);
	var columns = parseInt(table_data.dimension.colspan);
	var table = $('#'+table_data.table+' > .widgets > tbody');
	var tr = table.find('tr').length;
	var selected_col,selected_row;
	//////////////console.log(table_data.widget_id);
	if(tr==0){ //If there is no data on table
		var col_left  = 5-columns;
		selected_col=1;
		selected_row=1;
		for(var i=1;i<=rows;i++){
			table.append('<tr data-row="'+i+'" data-col="'+col_left+'"></tr>');
		}
		if(table_data.containerBox){
			global_widget_div = table_data.containerBox;
		}else{
			global_widget_div = table_data.table+'rt'+table_data.report_type+'x'+table_data.dimension.colspan+'y'+table_data.dimension.rowspan+'c'+selected_col+'r'+selected_row;
		}
		var td ='<td valign="top" data-col="'+selected_col+'" colspan="'+columns+'" rowspan="'+rows+'" width="'+(columns*20)+'%">';
			td+='<div class="box">';	
			td+='<div class="darkbox">';
			td+='<div class="entryBox" data-x="'+columns+'" data-y="'+rows+'" style="height:'+((rows==1)?100:(100+((rows-1)*150)))+'px;">';
				td+='<div class="titleBox">';
				td+='<h2>'+table_data.label+'</h2>';
				td+='</div>';
				td+='<div id="'+global_widget_div+'" class="widget_content" style="height:'+((rows==1)?100:(100+((rows-1)*150)))+'px;margin: -25px auto 0px;">';
				td+='</div>';
				td+='<div class="footBox">';
				td+='<a href="#widget/fullscreen/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_view fr">&nbsp;</a>';
				td+='<a href="#widget/edit/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_setting fr">&nbsp;</a>';
				td+='<a href="#widget/delete/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_setting icon_trash fr">&nbsp;</a>';
				td+='</div>';
			td+='</div>';
			td+='</div>';
			td+='</div></td>';
		table.find('tr').eq(0).append(td);
	}else{
		var checkTDisExist = {isExist:false,onRow:0};
		//////console.log(tr+'--'+rows);
		var newTR = tr;
		if(tr<rows){ //create new row
			var col_left  = 5-columns;
			for(var i=tr;i<rows;i++){
				table.append('<tr data-row="'+(i+1)+'" data-col="5"></tr>');
				// //////////////console.log(i);
				newTR = newTR+1;
			}
		}	
		
		for(var i=0;i<newTR;i++){ //check if column is available
			var checkTD = (table.find('tr').eq(i).data('col')) - columns;
			//////console.log(checkTD);
			if(checkTD>=0){
				checkTDisExist={isExist:true,onRow:i+1};
				break;
			}
			
		}
		
		if(checkTDisExist.isExist){ //if column exist
			//////console.log('if TD exist/false on existing row');
			var checkNextTD = true;
			if(rows>1){ //check if in every row  has 'TD' available/exist
				var nextTDRow = parseInt(checkTDisExist.onRow);
				for(var i=0;i<rows;i++){
					var checkTD = (table.find('tr').eq(nextTDRow-1).data('col')) - columns;
					nextTDRow++;
					if(checkTD<0){
						checkNextTD = false;
						break;
					}
				}
			}
			
			if(checkNextTD==false){
				var trLength = (table.find('tr').length)+1;
				checkTDisExist={isExist:true,onRow:trLength};
				var col_left  = 5-columns;
				for(var i=0;i<(rows-1);i++){
					table.append('<tr data-row="'+trLength+'" data-col="5"></tr>');
					trLength++;
				}
				
				checkNextTD=true;
			}
			
			
			if(checkNextTD){			
				selected_col = arrCol[table.find('tr').eq(checkTDisExist.onRow-1).data('col')];
				selected_row = checkTDisExist.onRow;
				if(table_data.containerBox){
					global_widget_div = table_data.containerBox;
				}else{
					global_widget_div = table_data.table+'rt'+table_data.report_type+'x'+table_data.dimension.colspan+'y'+table_data.dimension.rowspan+'c'+selected_col+'r'+selected_row;
				}
				var td = '<td valign="top" data-col="'+selected_col+'" colspan="'+columns+'" rowspan="'+rows+'" width="'+(columns*20)+'%">';
					td+='<div class="box">';
					td+='<div class="darkbox">';
					td+='<div class="entryBox" data-x="'+columns+'" data-y="'+rows+'" style="height:'+((rows==1)?100:(100+((rows-1)*150)))+'px;">';
						td+='<div class="titleBox">';
						td+='<h2>'+table_data.label+'</h2>';
						td+='</div>';
						td+='<div id="'+global_widget_div+'" class="widget_content" style="height:'+((rows==1)?100:(100+((rows-1)*150)))+'px;margin: -25px auto 0px;">';
						td+='</div>';
						td+='<div class="footBox">';
						td+='<a href="#widget/fullscreen/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_view fr">&nbsp;</a>';
						td+='<a href="#widget/edit/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_setting fr">&nbsp;</a>';
						td+='<a href="#widget/delete/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_setting icon_trash fr">&nbsp;</a>';
						td+='</div>';
					td+='</div>';
					td+='</div>';
					td+='</div></td>';
				//update data-log on row
				if(rows>1){
					var eqs = checkTDisExist.onRow;
					for(var i=0;i<rows;i++){
						var updateDataRow = table.find('tr').eq(eqs-1).data('col')-columns;
						table.find('tr').eq(eqs-1).data({'col':updateDataRow});
						eqs++;
					}
				}else{
					var updateDataRow = table.find('tr').eq((checkTDisExist.onRow-1)).data('col')-columns;
					
					table.find('tr').eq((checkTDisExist.onRow-1)).data({'col':updateDataRow})
				}
				table.find('tr').eq((checkTDisExist.onRow-1)).append(td);
			}
		}else{ //if TD not exist/false on existing row
			//////console.log('if TD not exist/false on existing row');
			var trLength = (table.find('tr').length)+1;
			var col_left  = 5-columns;
			selected_col = 1;
			selected_row=trLength;
			for(var i=0;i<rows;i++){
				table.append('<tr data-row="'+trLength+'" data-col="'+col_left+'"></tr>');
				trLength++;
			}
			if(table_data.containerBox){
				global_widget_div = table_data.containerBox;
			}else{
				global_widget_div = table_data.table+'rt'+table_data.report_type+'x'+table_data.dimension.colspan+'y'+table_data.dimension.rowspan+'c'+selected_col+'r'+selected_row;
			}
			var td ='<td valign="top" data-col="'+selected_col+'" colspan="'+columns+'" rowspan="'+rows+'" width="'+(columns*20)+'%">';
				td+='<div class="box">';
				td+='<div class="darkbox">';
				td+='<div class="entryBox" data-x="'+columns+'" data-y="'+rows+'" style="height:'+((rows==1)?100:(100+((rows-1)*150)))+'px;">';
					td+='<div class="titleBox">';
					td+='<h2>'+table_data.label+'</h2>';
					td+='</div>';
					td+='<div id="'+global_widget_div+'" class="widget_content" style="height:'+((rows==1)?100:(100+((rows-1)*150)))+'px;margin: -25px auto 0px;">';
					td+='</div>';
					td+='<div class="footBox">';
					td+='<a href="#widget/fullscreen/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_view fr">&nbsp;</a>';
					td+='<a href="#widget/edit/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_setting fr">&nbsp;</a>';
					td+='<a href="#widget/delete/'+global_widget_div+'/'+table_data.widget_id+'" class="icon_setting icon_trash fr">&nbsp;</a>';
					td+='</div>';
				td+='</div>';
				td+='</div>';
				td+='</div></td>';
			table.find('tr').eq((selected_row-1)).append(td);
		}
		
	}
	
	//load data on widget
	if(load==true){
		load_widget_data(global_widget_div,table_data);
	}
	
	var checkTable = $('#home table tbody').find('tr').length;
	if(checkTable==0){
		$('#home table tbody').html('<div class="nodata" style="width:100%;display:block;height:100px;text-align:center;"><p>WIDGET BELUM TERSEDIA.</p></div>');
	}else{
		$('.nodata').remove();
	}
	
	//temporary mute
	// dimensionAdjustment(true);
	
	//unbindHover and bind
	unbindHover();
	bindHover();
}

function load_widget_data(div,table_data){
	//////console.log(table_data);
	var content_div = $('#'+div);
	var icon="";
	var sentiment="";
	var arrGroupType = [0,0,0,1,2,3,5,0];
	if(table_data.report_type){
		if(table_data.report_type=='6'){
			var channel = parseInt(table_data.channel2);
		}else if(table_data.report_type=='7'){
			var channel = parseInt(table_data.channel2);
			if(table_data.sentiments=='0'){
				sentiment=' : <span style="color: #8DC44A;">positif</span>';
			}else{
				sentiment=' : <span style="color: #EE1C24;">negatif</span>';
			}
		}else{
			var channel = parseInt(table_data.channel);
		}
	}
	var siteType = "";
	////////console.log(div);
	content_div.html('<p style="text-align:center;margin-top:50px;"><img src="images/sos-loader.gif" width="142" height="18" style="margin: 0 auto;" /></p>');
	if(channel==1){
		icon='<img src="images/icon_twitter.png" width="30" style="margin: -7px 0 0;"></img> ';
	}else if(channel==2){
		icon='<img src="images/icon_facebook.png" width="30" style="margin: -7px 0 0;"></img> ';
	}else if(channel>2){
		if(arrGroupType[channel]==1){
			siteType=' <span style="text-transform: lowercase;">(<span style="color:#999;">blog</span>)</span>';
		}else if(arrGroupType[channel]==2){
			siteType=' <span style="text-transform: lowercase;">(<span style="color:#999;">forum</span>)</span>';
		}else if(arrGroupType[channel]==3){
			siteType=' <span style="text-transform: lowercase;">(<span style="color:#999;">berita</span>)</span>';
		}else if(arrGroupType[channel]==5){
			siteType=' <span style="text-transform: lowercase;">(<span style="color:#999;">ecommerce</span>)</span>';
		}else if(arrGroupType[channel]==0){
			siteType=' <span style="text-transform: lowercase;">(<span style="color:#999;">corporate/personal</span>)</span>';
		}
		
		icon='<img src="images/icon_rss.png" width="30" style="margin: -7px 0 0;"></img> ';
	}
	content_div.closest('.entryBox').find('.titleBox h2:eq(0)').html(icon+table_data.label+siteType+sentiment);
	content_div.closest('.entryBox').find('.titleBox h2:eq(1)').remove();
	//////console.log(table_data.report_type);
	////////////console.log('ffffff',table_data);
	switch(table_data.rangeTime){
		case 'd':
			table_data.rangeTime = intval(strtotime(date('Y-m-d') +' -1 day'));
		break;
		case 'w':
			table_data.rangeTime = intval(strtotime(date('Y-m-d') +' -7 day'));
		break;
		default:
			table_data.rangeTime = intval(table_data.rangeTime);
		break;
	}
	switch(table_data.report_type){
		case '1':
			widget_volume_summary(div,table_data);
			break;
		case '2':
			widget_daily_topic(div,table_data);
			break;
		case '3':
			$('#'+div).css({'margin':'0 auto'});
			widget_top_keywords(div,table_data);
			break;
		case '4':
			$('#'+div).css({'margin':'0 auto'});
			widget_conversations(div,table_data);
			break;
		case '5':
			widget_sentiment(div,table_data);
			break;
		case '6':
			widget_custom_bar_chart(div,table_data);
			break;
		case '7':
			widget_influencer(div,0,table_data);
			break;
		case '8':
			widget_potential_impact_index(div,table_data);
			break;
		case '9':
			widget_interaction_rate(div,table_data);
			break;
		case '10':
			$('#'+div).css({'margin':'0 auto'});
			load_map_live_track(div,table_data);
			break;
		case '11':
			$('#'+div).css({'margin':'0 auto'});
			load_map_tampilan(div,table_data);
			break;
		case '12':
			widget_compare(div,table_data);
			break;
		case '13':
			widget_perfomance_party_on_issues(div,table_data);
			break;
		case '14':
			widget_comparing_party(div,table_data);
			break;
		case '15':
			widget_comparing_candidate(div,table_data);
			break;
		case '16':
			widget_load_partyNcandidate(div,table_data);
			break;
		case '16_pie_control':
			widget_party_and_theCandidate(div,table_data);
			break;
		case '17':
			widget_perfomance_candidate_on_issues(div,table_data);
			break;
		default:
			//////////////console.log('foo');
	}
}

//hover on dynamic dom
function bindHover(){
	$('.entryBox').hover(function(){
		$(this).find('.footBox').animate({bottom:'0px'},{queue:false,duration:500});
	}, function(){
		$(this).find('.footBox').animate({bottom:'-40px'},{queue:false,duration:500});
	});
}
function unbindHover(){
	$('.entryBox').off('mouseenter mouseleave');
}