var colors= ['#FF8C00', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
	function gethighChart(type,name,renderTo,data,categories,tickInterval,labels,height,width,uncolors,usedtypedata){
		 
			
			var chart;
			if (labels!=false) {
				var labels = new Object();
				labels =  {	
							formatter: function() {
							return '<span class="labelChart" title="'+this.value+'">'+this.value+'</span>';
							},
							useHTML:true
						}
			}
			
			if(data&&(type=='column'||type=='bar')){
			var datas = [];
			var nColor = 0;
			var nIdxColors = colors.length;
				if(uncolors==false){
					for(var idxData in data){
						var dataWithColor = { y: data[idxData], color: colors[nColor]};
						datas.push(dataWithColor);
						if(nIdxColors > nColor) nColor++;
						else nColor=0;
					}
				
					
				}else{
					for(var idxData in data){
						var dataWithColor = { y: data[idxData], color: colors[0]};
						datas.push(dataWithColor);						
					}
				
				}
				
				var data = datas;
			}
			$(document).ready(function() { 
			
			chart= new Highcharts.Chart({
				chart: {
					renderTo: renderTo,
					type: type,
					height:height,
					width:width
				},
				
				xAxis: {
					categories: categories,
					 tickInterval:tickInterval,
					 labels:labels
				},
				yAxis: {
					title:false,
					min: 0,
					allowDecimals : false
                },
				tooltip: {
					formatter: function() {
						if(usedtypedata == 'numeric') return '<b>'+ this.point.name +'</b>: '+ parseInt(this.y,10)+' ';
						if(type=='pie')	return '<b>'+ this.point.name +'</b>: '+ parseInt(this.percentage,10)+' %';
						if(renderTo == 'avgVisit') return '<b>'+ this.x +'</b>: '+ Highcharts.dateFormat('%H:%m:%S', parseInt(this.y)*1000);
						// if(renderTo == 'timepervisit') return '<b>'+ this.x +'</b>: '+ Highcharts.dateFormat('%H:%m:%S', parseInt(this.y)*1000);
						if(renderTo == 'avgTimeonpage') return '<b>'+ this.x +'</b>: '+ Highcharts.dateFormat('%H:%m:%S', parseInt(this.y)*1000);
						if(renderTo == 'badgePercentageChart') return '<b>'+ this.x +'</b>: '+ this.y+' %';
						if(renderTo == 'bounceRate') return '<b>'+ this.x +'</b>: '+ this.y+' %';
						if(renderTo=='loginChartHours' ) return  '<b>'+ this.x +'</b>: '+formatTime(this.y);
						return '<b>'+ this.x +'</b>: '+ this.y;
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							formatter: function() {
								if(usedtypedata == 'numeric') return  parseInt(this.y,10);								
								else return  parseInt(this.percentage,10) +' %';
							},
							color: 'white',
							distance: -20
							
						}	,
						showInLegend: true
					},
				},
				exporting:false,
				credits:false,
				legend:false,
				title:false,
				series: [{					
					data:data                   		
				}]
			});
			//$("#loginHistoryField").find('.labelChart').powerTip({ placement: 'sw-alt' });
			$("#redeemField").find('.labelChart').powerTip({ placement: 'sw-alt' });
			$("#basedonlocationField").find('.labelChart').powerTip({ placement: 'sw-alt' });
			$("#basedonbrandField").find('.labelChart').powerTip({ placement: 'sw-alt' });
			$("#popularTaskField").find('.labelChart').powerTip({ placement: 'sw-alt' });
			$("#topvisited").find('.labelChart').powerTip({ placement: 'sw-alt' });
			
		});
			
			
		
	}
	
	function drillDownChart(type,name,renderTo,data,categories){
				
				var chart;
				$(document).ready(function() {
				
					function setChart(name, categories, data, color) {
						chart.xAxis[0].setCategories(categories);
						chart.series[0].remove();
						chart.addSeries({
							name: name,
							data: data,
							color: color 
						});
					}
				
					chart = new Highcharts.Chart({
						chart: {
							renderTo: renderTo,
							type: 'column'
						},
						title: false,
						xAxis: {
							categories: categories,
							labels : {
									rotation: -45,
									align: 'right',
									style: {
										fontSize: '11px',
										fontFamily: 'Verdana, sans-serif'
									}
								}
						},
						yAxis: {
							title:false
						},
						plotOptions: {
							column: {
								cursor: 'pointer',
								point: {
									events: {
										click: function() {
											var drilldown = this.drilldown;
											if (drilldown) { // drill down
												setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
											} else { // restore
												setChart(name, categories, data);
											}
										}
									}
								}
							}
						},
						tooltip: {
							formatter: function() {
								var point = this.point,
									s = this.x +':<b>'+ this.y + '</b><br/>';
								if (point.drilldown) {
									s += 'Click to view '+ point.category +' ';
								} else {
									s += 'Click to return ';
								}
								return s;
							}
						},
						legend:false,
						credits:false,
						series: [{
							name: name,
							data: data
						
						}],
						exporting: {
							enabled: false
						}
					});
				});
			
	}
	
	function formatTime(seconds){
	
	
		var h = parseInt((seconds / 3600),10);
		var m = parseInt(((seconds - h*3600) / 60),10);
		var s = parseInt((seconds - h*3600 - m*60),10);
		return ((h)?((h<10)?("0"+h):h):"00")+":"+((m)?((m<10)?("0"+m):m):"00")+":"+((s)?((s<10)?("0"+s):s):"00");
	
	
	}