<?php
  /*
   * Module: Dashboard 
  */
  $capability_key = 'dashboard';
  require('header.php');
	
	$requests = $DB->Find('notifications', array(
					  			'columns' 		=> 'COUNT(id) AS unread', 
					  	    'conditions' 	=> 'type=161 AND status = 163', //163 = UNREAD
	  	  ));
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
	  		
	  		<div style="width: 370px; height: 200px; margin: 5px; padding:20px; float: left; border: solid 1px #eee">
	  			<p><b style="font-size: 18px">OUTSTANDING</b></p>
	  			<div style="padding-left:10px;  background: #e2e2e2">
	  				<div style="padding: 10px; width: 60px; float: left; margin-right:40px;">
		  				<div style="padding: 20px; width: 60px; font-size: 50px; color: #fff; background: #93e025; text-align: center; ">
		  					3
		  				</div>	
		  				<div style="padding-left: 20px; width: 60px; font-size: 10px; text-align: center; ">
		  					<a href="#">Work Orders</a>  
		  				</div>		  				
	  				</div>

	  				<div style="padding: 10px; width: 60px; float: left; margin-right:40px;">
		  				<div style="padding: 20px; width: 60px; font-size: 50px; color: #fff; background: #2593e0; text-align: center; ">
		  					46
		  				</div>	
		  				<div style="padding-left: 20px; width: 60px; font-size: 10px; text-align: center; ">
		  					<a href="#">Stocks</a>  
		  				</div>		  				
	  				</div>
	  				
	  				<div style="padding: 10px; width: 60px; float: left; margin-right:40px;">
		  				<div style="padding: 20px; width: 60px; font-size: 50px; color: #fff; background: #e0257d; text-align: center; ">
		  					<?php echo $requests['unread'] ?>
		  				</div>	
		  				<div style="padding-left: 20px; width: 60px; font-size: 10px; text-align: center; ">
		  					<a href="#">Requests</a>  
		  				</div>		  				
	  				</div>
	  				
	  				<div style="padding: 10px; width: 60px; float: left; margin-right:40px;">
		  				<div style="padding: 20px; width: 60px; font-size: 50px; color: #fff; background: #e0bf25; text-align: center; ">
		  					2
		  				</div>	
		  				<div style="padding-left: 20px; width: 60px; font-size: 10px; text-align: center; ">
		  					<a href="#">Deliveries</a>  
		  				</div>		  				
	  				</div>
	  			</div>
	  		</div>
	  		
	  		<div style="width: 370px; height: 200px; margin: 5px; padding:20px; float: left; border: solid 1px #eee">
	  			<p><b style="font-size: 18px">TOP MATERIALS</b></p>
	  			<div style="padding-left:20px; padding-top: 10px;">
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