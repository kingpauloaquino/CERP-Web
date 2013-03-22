<?php
  /*
   * Module: Defects 
  */
  $capability_key = 'defect-reports.php';  
  require('header.php');
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>

				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			
			<div id="chart" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
			
		</div>
	</div>
	
	
	
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
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
<script src="../Highcharts/js/highcharts.js"></script>
<script src="../Highcharts/js/modules/exporting.js"></script>
<?php require('footer.php'); ?>