<?php
  /*
   * Module: Dashboard 
  */
  $capability_key = 'dashboard';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$requests = $DB->Find('notifications', array(
					  			'columns' 		=> 'COUNT(id) AS unread', 
					  	    'conditions' 	=> 'type=161 AND status = 163', //163 = UNREAD
	  	  ));
?>	

<div id="page">
	 <div id="page-title">
      <h2>
        <span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <div class="clear"></div>
      </h2>
    </div>
			
	<div id="content">
		
<!-- 		<div id="chart" style="min-width: 400px; height: 400px; margin: 0 auto"></div> -->

		<div style="min-width:400px; width:1200px; height:260px; margin:5px; padding:4px; float:left; border:solid 1px #eee">
			<div id="chart" style="min-width: 400px; height: 260px; margin: 0 auto"></div>
		</div>
		
		<div style="width:386px; height:260px; margin:5px; padding:4px; float:left; border:solid 1px #eee">
			NOTIFICATIONS
			<ul>
				<li>Stock Replenish</li>
				<li>Production Requests</li>
				<li>Machinery Issues</li>
			</ul>
		</div>
				
		<div style="width:386px; height:260px; margin:5px; padding:4px; float:left; border:solid 1px #eee">
			CALENDAR
			<ul>
				<li>Public Holiday</li>
				<li>General Meeting</li>
				<li>Production Shutdown</li>
			</ul>
		</div>
		
		<div style="width:386px; height:260px; margin:5px; padding:4px; float:left; border:solid 1px #eee">
			<h3>OTHERS</h3>
		</div>
		
	</div>
</div>
<script type="text/javascript">
	$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Monthly Production Output',
                x: -20 //center
            },
            subtitle: {
                text: 'Fiscal Year 2012',
                x: -20
            },
            xAxis: {
                categories: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            },
            yAxis: {
                title: {
                    text: 'Production Output'
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
                        this.x +': '+ this.y +' pcs.';
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                name: 'Epson',
                data: [2000, 3000, 5000, 10500, 15000, 8000, 12000, 7000, 4000, 2000, 0, 0]
            }, {
                name: 'Canon',
                data: [3000, 3000, 6000, 8000, 22000, 14000, 10000, 5000, 4000, 1000, 500, 500]
            }, {
                name: 'HP',
                data: [2000, 5000, 4000, 5500, 3600, 5400, 5500, 4800, 7500, 2500, 1500, 500]
            }, {
                name: 'Brother',
                data: [0, 1500, 2000, 4000, 4000, 4000, 6000, 2000, 5050, 3500, 0, 0]
            }]
        });
    });
    
});
</script>
<script src="../Highcharts/js/highcharts.js"></script>
<script src="../Highcharts/js/modules/exporting.js"></script>

<?php }
require('footer.php'); ?>