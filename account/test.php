<?php 
$capability_key = ''; 
require('header.php');	

$allowed = $Role->isCapableByName('show_material');

if(!$allowed) {
	require('inaccessible.php');	
}else{
?>
	<div id="page">
  	<div class="breadcrumb">
  		<a href="#">Parent</a> &raquo; Current
  	</div>
		<div id="page-title">
    	<h2>
      	<span class="title">Title</span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
					<h2>usual content</h2>
					<span class="notice">
	          <p class="error"><strong>Notice!</strong> Material codes should be unique.</p>
	        </span>
	        
	        <button class="live-tile-button">test</button>
	        <button class="live-tile-button">test</button>
	        <button class="live-tile-button">test</button>
	        <button class="live-tile-button">test</button>
	        
	        <br/>
	        
	        
	        <a href="#" title="This is some information for our tooltip." class="tooltip"><span title="More">CSS3 Tooltip</span></a>
	        <br/>
					<?php
								function getDay($m, $index) {
									return date($m, strtotime(' Thursday +'.$index.' week', strtotime(date('Y').'-01-01')));	
								}
								
								$ctr = 1; 
								$mo_ctr = 1;
								$show = TRUE;
								for($i=0; $i<=52; $i++){
									if(getDay('Y', $i) == date('Y')) { //check if still current year
										$mo = getDay('M', $i);
										
										if(getDay('m', $i) != $mo_ctr) {
											$mo_ctr += 1; $show = TRUE;
										}
										if($show) {
											echo $mo.'<br/>'; $show = FALSE; // month name
										}
										
										echo 'Wk-'.$ctr.' :: '.getDay('m/d', $i).' <br/>'; // Week number
										$ctr+=1;
									}
								}
								
								
								function getWeeks($month, $series = false) {
									$ctr = 1;
									$timestamp = mktime(0, 0, 0, $month, 1, 2013);
								    while(date('n', $timestamp) == $month)
								    {
								    	if($series) {
								    		echo date('F', $timestamp).' - Week '.  date('W', $timestamp)."<br>";
								    	}
											else {
												echo date('F', $timestamp).' - Week '.  $ctr."<br>";
											}
								      $ctr+=1;  
							        $timestamp = strtotime("+1 week", $timestamp);
								    } 
									
								}
								
								//getWeeks(3);
								
								// for($i = 1; $i <= 12; $i++)
								// {
								    // $timestamp = mktime(0, 0, 0, $i, 1, 2013);
								    // while(date('n', $timestamp) == $i)
								    // {
								        // echo date('F', $timestamp).' - Week '.  date('W', $timestamp)."<br>";
								        // $timestamp = strtotime("+1 week", $timestamp);
								    // }   
								// } 
								
							?>
							
			<table>
				<tr>
					<td><div class="metro-tile">Store</div></td>
					<td><div class="metro-tile">Store</div></td>
				</tr>
			</table>	
		</div>
	</div>
<style>
.live-tile-button
{
    width:200px;
    height:100px;
    background:#ffee55;
    border:1px solid #fff;
}

.metro-tile {
	 background-color: #D8512B;
	 border-color: #DC6241;
	 width: 232px;
	 height: 104px;
	 padding: 5px;
	 color: #FFF;
	 font-family: sans-serif;
	 font-size: 12px;
	 border-width: 3px;
	 border-style: solid;
	 cursor: default;
	 -webkit-transition: 0.1s all;
	 -moz-transition: 0.1s all;
	 -ms-transition: 0.1s all;
	 transition: 0.1s all;
}

</style>	
<?php }
require('footer.php'); ?>