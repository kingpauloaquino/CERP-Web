<?php
  /* Module: Name  */
  $capability_key = 'key';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	//if(!$allowed) {
	//	require('inaccessible.php');	
	//}else{
		
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title">Sample Analytics</span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <div class="form-container">
				<h3 class="form-title">Production Output</h3>
      	<div id="chart" style="min-width: 400px; height: 200px; margin: 0 auto"></div>
      </div>
      <br/>
      <div class="form-container">
				<h3 class="form-title">Defect Rates</h3>
      	<div id="chart3" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
      </div>
      <br/>
      <div class="form-container" style="width: 400px; float:left">
				<h3 class="form-title">Market Demand Shares</h3>
      	<div id="chart2" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
      </div>
      
      <div class="form-container" style="width: 720px; float:left; margin-left: 20px">
				<h3 class="form-title">Shipment Output</h3>
      	<div id="chart4" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
      </div>
		</div>
	</div>
	
	<script type="text/javascript">
	$(function () {
	  $(document).ready(function() {
	      Highcharts.setOptions({
	          global: {
	              useUTC: false
	          }
	      });
	  
	      var chart;
	      chart = new Highcharts.Chart({
	          chart: {
	              renderTo: 'chart',
	              type: 'spline',
	              marginRight: 10,
	              events: {
	                  load: function() {
	  
	                      // set up the updating of the chart each second
	                      var series = this.series[0];
	                      setInterval(function() {
	                          var x = (new Date()).getTime(), // current time
	                              y = (Math.random() * (100-1) + 1);
	                          series.addPoint([x, y], true, true);
	                      }, 1000);
	                  }
	              }
	          },
	          title: {
	              text: 'Live Production'
	          },
	          xAxis: {
	              type: 'datetime',
	              tickPixelInterval: 150,
						    labels: {
						        formatter: function() {
						            var hourMinute = Highcharts.dateFormat('%H:%M', this.value);
						            return hourMinute;
						        }
						    }
	          },
	          yAxis: {
	              title: {
	                  text: 'Quantity'
	              },
	              plotLines: [{
	                  value: 0,
	                  width: 1,
	                  color: '#808080'
	              }]
	          },
	          tooltip: {
	              formatter: function() {
	                      return '<b>'+ this.series.name +'</b><br/>'+
	                      Highcharts.dateFormat('%Y-%m-%d %H:%M', this.x) +'<br/>'+
	                      Highcharts.numberFormat(this.y, 2) + ' pcs';
	              }
	          },
	          legend: {
	              enabled: false
	          },
	          exporting: {
	              enabled: false
	          },
	          series: [{
	              name: 'Sample Quantity',
	              data: (function() {
	                  // generate an array of random data
	                    var data = [],
	                        time = (new Date()).getTime(),
	                        i;
	    
	                    for (i = -19; i <= 0; i++) {
	                        data.push({
	                            x: time + i * 1000,
	                            y: (Math.random() * (100-1) + 1)
	                        });
	                    }
	                    return data;
	                })()
	            }]
	        });
	    });
	    
	});
	</script>
	
	<script type="text/javascript">
$(function () {
    var chart;
    
    $(document).ready(function () {
    	
    	// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart2',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Ink market shares as of 2012'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Ink share',
                data: [
                    ['Epson',   45.0],
                    ['HP',       26.8],
                    {
                        name: 'Canon',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['Brother',    8.5],
                ]
            }]
        });
    });
    
});
		</script>
		
	<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart3',
                zoomType: 'xy'
            },
            title: {
                text: 'Sample Defect Graph'
            },
            subtitle: {
                text: 'CERP: Defect Module'
            },
            xAxis: [{
                categories: ['Foreign Material', 'Stain', 'IC Rust', 'Leakage', 'Weight Over', 'Reset NG',
                    'Gray Film', 'Air Hole Leak', 'Holder Damaged', 'Mix Color', 'Recognition NG', 'Drilling']
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    formatter: function() {
                        return this.value +'%';
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: '',
                    style: {
                        color: '#89A54E'
                    }
                },
                opposite: true
    
            }, { // Secondary yAxis
                gridLineWidth: 0,
                title: {
                    text: '',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    formatter: function() {
                        return this.value +' ';
                    },
                    style: {
                        color: '#4572A7'
                    }
                }
    
            }],
            tooltip: {
                formatter: function() {
                    var unit = {
                        'ECI-E32B': '',
                        'Something': ''
                    }[this.series.name];
    
                    return ''+
                        this.x +': '+ this.y +' '+ unit;
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 700,
                verticalAlign: 'top',
                y: 140,
                floating: true,
                backgroundColor: '#FFFFFF'
            },
            series: [{
                name: 'ECI-E32B',
                color: '#4572A7',
                type: 'column',
                yAxis: 1,
                data: [45, 40, 30, 17, 14, 5, 2, 0, 0, 0, 0, 0]
    
            }, {
                name: 'Something',
                color: '#FF5555',
                type: 'spline',
                data: [0, 30, 55, 75, 85, 95, 98, 100, 100, 100, 100, 100]
            }]
        });
    });
    
});
</script>

<script type="text/javascript">
$(function () {

	window.chart = new Highcharts.Chart({
	            
	    chart: {
	        renderTo: 'chart4',
	        polar: true,
	        type: 'line'
	    },
	    
	    title: {
	        text: 'Plan vs Actual',
	        x: -80
	    },
	    
	    pane: {
	    	size: '80%'
	    },
	    
	    xAxis: {
	        categories: ['Epson', 'Canon', 'HP', 'Brother'],
	        tickmarkPlacement: 'on',
	        lineWidth: 0
	    },
	        
	    yAxis: {
	        gridLineInterpolation: 'polygon',
	        lineWidth: 0,
	        min: 0
	    },
	    
	    tooltip: {
	    	shared: true,
	        valueSuffix: ' pcs'
	    },
	    
	    legend: {
	        align: 'right',
	        verticalAlign: 'top',
	        y: 100,
	        layout: 'vertical'
	    },
	    
	    series: [{
	        name: 'Planned Shipment',
	        data: [43000, 19000, 60000, 35000],
	        pointPlacement: 'on'
	    }, {
	        name: 'Actual Shipment',
	        data: [50000, 39000, 42000, 31000],
	        pointPlacement: 'on'
	    }]
	
	});
});
		</script>		
	<script src="../include/Highcharts/js/highcharts.js"></script>
	<script src="../include/Highcharts/js/highcharts-more.js"></script>
	<script src="../include/Highcharts/js/modules/exporting.js"></script>

<?php //}
//require('footer.php'); 
?>