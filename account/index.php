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
			<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
			<div class="clear"></div>
		</h2>
	</div>
			
	<div id="content">
		
<!-- 		<div id="chart" style="min-width: 400px; height: 400px; margin: 0 auto"></div> -->

		<div style="min-width:400px; width:1200px; height:260px; margin:5px; padding:4px; float:left; border:solid 1px #eee">
			<div id="chart" style="min-width: 400px; height: 260px; margin: 0 auto"></div>
		</div>
		
		<div style="width:600px; height:auto; margin:5px; padding:4px; float:left; border:solid 1px #eee">
			<h3>CALENDAR</h3>
			<div id='calendar' style="width: 540px; margin: 0 auto;"></div>
		</div>
		
		<div style="width:500px; height:360px; margin:5px; padding:4px; float:left; border:solid 1px #eee">
			<h3>NOTIFICATIONS</h3>
			<div id="tabs" style="min-height: 315px">
			  <ul>
			    <li><a href="#tabs-1">General</a></li>
			    <li><a href="#tabs-2">Warehouse</a></li>
			    <li><a href="#tabs-3">Production</a></li>
			  </ul>
			  <div id="tabs-1">
			    <ul>
			    	<li>item 1</li>
			    	<li>item 2</li>
			    	<li>item 3</li>
			    </ul>
			  </div>
			  <div id="tabs-2" style="overflow: auto; max-height: 250px">
			    <ul>
			    	<li>item 1</li>
			    	<li>item 2</li>
			    	<li>item 3</li>
			    	<li>item 4</li>
			    	<li>item 5</li>
			    	<li>item 6</li>
			    	<li>item 7</li>
			    	<li>item 8</li>
			    	<li>item 9</li>
			    	<li>item 10</li>
			    	<li>item 11</li>
			    	<li>item 12</li>
			    	<li>item 13</li>
			    	<li>item 14</li>
			    	<li>item 15</li>
			    	<li>item 16</li>
			    	<li>item 17</li>
			    	<li>item 18</li>
			    	<li>item 19</li>
			    	<li>item 20</li>
			    </ul>
			  </div>
			  <div id="tabs-3">
			    <ul>
			    	<li>item 1</li>
			    	<li>item 2</li>
			    </ul>
			  </div>
			</div>
		</div>
		
	</div>
</div>
<script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
  
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
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
<script src="../include/Highcharts/js/highcharts.js"></script>
<script src="../include/Highcharts/js/modules/exporting.js"></script>

<script>
	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			editable: true,
			events: [
				{
					title: 'Test Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					title: 'CERP Dashboard',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://119.92.50.166/cerp/'
				}
			]
		});
		
	});

</script>
<!-- <script src='../jquery/jquery-1.9.1.min.js'></script>
<script src='../jquery/jquery-ui-1.10.2.custom.min.js'></script> -->
<script src='../include/fullcalendar/fullcalendar.min.js'></script>
<link href='../include/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='../include/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />

<?php }
//require('footer.php'); 
?>