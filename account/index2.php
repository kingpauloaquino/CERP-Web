<?php
  /*
   * Module: Dashboard 
  */
  $capability_key = 'dashboard';
  require('header.php');
?>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['x', 'Epson', 'Canon', 'HP'  ,'Brother'],
          ['',    0,        0,      0,         0],
          ['Mar', 80000,    50000,  30000,     15000],
          ['Apr', 70000,    60000,  40000,     25000],
          ['May', 90000,    60000,  40000,     30000],
          ['Jun', 50000,    50000,  75000,     10000]
        ]);
      
        // Create and draw the visualization.
        new google.visualization.LineChart(document.getElementById('visualization')).
            draw(data, {curveType: "function",
                        width: 750, height: 210,
                        vAxis: {maxValue: 10}}
                );
      }
      google.setOnLoadCallback(drawVisualization);
    </script>

	<div id="page">
		 <div id="page-title">
        <h2>
          <span class="title"><?php echo $Capabilities->GetName(); ?></span>
          <div class="clear"></div>
        </h2>
      </div>
				
		<div id="content">
			<div style="padding: 20px;">
	  		<div style="width: 820px; height: 260px; margin: 5px; padding:4px; float: left; border: solid 1px #eee">
	  			<p><b style="font-size: 18px">TIMELINE</b> ( Production Output )</p>
	  			<div style="padding-left: 20px">
	  				<!-- <img style="width:800px;" src="css/images/graph.png"/> -->
	  				<div id="visualization" style="width: 900px; height: 250px;"></div>
	  			</div>
	  		</div>
	  		<div style="width: 380px; height: 230px; margin: 5px; padding:4px; float: left; border: solid 1px #eee">
	  			<p><b style="font-size: 18px">OUTSTANDING</b></p>
	  			<p style="float: right; margin-top: -32px"><a href="#">see more ..</a></p>
	  			<div style="padding: 10px;">
	  				<div style="width: 90px; height: 90px; margin: 3px 12px 3px 12px; padding: 0px; float: left;">
	  					<div style="width: 90px; height: 70px; margin-bottom:4px; background-color: #93e025;font-size: 50px; text-align: center; color: #fff">
		  					3
		  				</div>
		  				<p style="text-align: center; font-size: 10px"><a href="#">Work Orders</a></p>
	  				</div>
	  				<div style="width: 90px; height: 90px; margin: 3px 12px 3px 12px; padding: 0px; float: left;">
	  					<div style="width: 90px; height: 70px; margin-bottom:4px; background-color: #2593e0;font-size: 50px; text-align: center; color: #fff">
		  					46
		  				</div>
		  				<p style="text-align: center; font-size: 10px"><a href="#">Stocks Replenish</a></p>
	  				</div>
	  				<div style="width: 90px; height: 90px; margin: 3px 12px 3px 12px; padding: 0px; float: left;">
	  					<div style="width: 90px; height: 70px; margin-bottom:4px; background-color: #e0257d;font-size: 50px; text-align: center; color: #fff">
		  					25
		  				</div>
		  				<p style="text-align: center; font-size: 10px"><a href="#">Mat'l. Request</a></p>
	  				</div>
	  				<div style="width: 90px; height: 90px; margin: 3px 12px 3px 12px; padding: 0px; float: left;">
	  					<div style="width: 90px; height: 70px; margin-bottom:4px; background-color: #e0bf25;font-size: 50px; text-align: center; color: #fff">
		  					2
		  				</div>
		  				<p style="text-align: center; font-size: 10px"><a href="#">Deliveries</a></p>
	  				</div>
	  			</div>
	  		</div>
	  		<div style="width: 380px; height: 230px; margin: 5px; padding:4px; float: left; border: solid 1px #eee">
	  			<p><b style="font-size: 18px">TOP 5 MATERIALS</b>  ( Orders per Purchase )</p>
	  			<p style="float: right; margin-top: -32px"><a href="#">see more ..</a></p>
	  			<div>
	  				<table>
	  					<tbody style="border-bottom: none">
	  					<tr>
	  						<td><b>1. ERC07EM-I-078</b></td>
	  						<td><b>40,000</b></td>
	  					</tr>
	  					<tr>
	  						<td>2. ERC07EY-I-078</td>
	  						<td>34,000</td>
	  					</tr>
	  					<tr>
	  						<td>3. BCI-21B/24C</td>
	  						<td>29,000</td>
	  					</tr>
	  					<tr>
	  						<td>4. RC7YE-0440AS</td>
	  						<td>11,000</td>
	  					</tr>
	  					<tr>
	  						<td>5. TJ-GST-BCI-7BK</td>
	  						<td>6,000</td>
	  					</tr>
	  					</tbody>
	  				</table>
	  			</div>
	  		</div>  			
  		</div> 
		</div>
	</div>

<?php require('footer.php'); ?>