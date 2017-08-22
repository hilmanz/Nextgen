var colors= ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
	function gethighChart(type,name,renderTo,data,categories,tickInterval,labels,height,width){
		 
			
			var chart;
			if (labels!=false) {
				var labels = new Object();
				labels =  {
							rotation: -45,
							align: 'right',
							style: {
								fontSize: '11px',
								fontFamily: 'Verdana, sans-serif'
							}
						}
			}
			
			if(data&&(type=='column'||type=='bar')){
			var datas = [];
			var nColor = 0;
			var nIdxColors = colors.length;
			
				for(var idxData in data){
					var dataWithColor = { y: data[idxData], color: colors[nColor]};
					datas.push(dataWithColor);
					if(nIdxColors > nColor) nColor++;
					else nColor=0;
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
					title:false
                },
				tooltip: {
					formatter: function() {
						if(type=='pie')	return '<b>'+ this.point.name +'</b>: '+ parseInt(this.percentage,10)+' %';
						if(renderTo == 'badgePercentageChart') return '<b>'+ this.x +'</b>: '+ this.y+' %';
						if(renderTo=='loginChartHours' || renderTo =='peakHourChart') return  '<b>'+ this.x +'</b>: '+formatTime(this.y);
						return '<b>'+ this.x +'</b>: '+ this.y;
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							formatter: function() {
								return '<b>'+ this.point.name +'</b>: '+  parseInt(this.percentage,10) +' %';
							},
							color: 'black',
							
							
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
	
 
	$(function () {
    
        var colors = Highcharts.getOptions().colors,
            categories = ['Female','Male'],
            name = 'Age Relevancy',
            data = [{
                    y: 35.11,
                    color: '#01acec',
                    drilldown: {
                        name: 'Male',
                        categories: ['Male'],
                     
                        data: [thedata.gender.Male],
						color: '#01acec',
                    }
                }, {
                    y: 25.94,
                    color: '#ee6da8',
                    drilldown: {
                        name: 'Female',
                        categories: ['Female'],
                    
                        data: [thedata.gender.Female],
                        color: '#ee6da8'
					}
                }];
    
    
        // Build the data arrays
        var browserData = [];
        var versionsData = [];
        for (var i = 0; i < data.length; i++) {
    
            
            // add version data
            for (var j = 0; j < data[i].drilldown.data.length; j++) {
                var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
                versionsData.push({
                    name: data[i].drilldown.categories[j],
                    y: data[i].drilldown.data[j],
                    color: Highcharts.Color(data[i].color).brighten(brightness).get()
                });
            }
        }
    
        // Create the chart
        $('#container2').highcharts({
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            
            plotOptions: {
                pie: {
                    shadow: false,
                    center: ['50%', '50%']
                },
				 series: {
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return Math.round(this.percentage*100)/100 + ' %';
                    },
                    distance: -10,
                    color:'black'
                }
            }
            },
			credits:false
			, 
			exporting: {
					 enabled: false
			}
			,
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> ({point.y:,.0f})<br/>',
            }
			,
            series: [{
                name: 'Age',
                data: browserData,
                size: '70%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 3 ? this.point.name : null;
                    },
                    color: 'white',
                    distance: 30
                }
            }, {
                name: 'Gender',
                data: versionsData,
                size: '80%',
                innerSize: '50%',
                dataLabels: {
                    formatter: function() {
                        // display only if larger than 1
                         return this.y > 1 ? + Math.round(this.point.percentage) +'%'  : null;
                    }
                }
            }]
        });
    });
	
	function formatTime(seconds){
	
	
		var h = parseInt((seconds / 3600),10);
		var m = parseInt(((seconds - h*3600) / 60),10);
		var s = parseInt((seconds - h*3600 - m*60),10);
		return ((h)?((h<10)?("0"+h):h):"00")+":"+((m)?((m<10)?("0"+m):m):"00")+":"+((s)?((s<10)?("0"+s):s):"00");
	
	
	}
