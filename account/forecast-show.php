<?php
  /* Module: Forecasts Show */
  $capability_key = 'show_forecast';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$product = $Query->product_by_id($_GET['pid']);
		
		$active_year = date('Y');
		
		$calendar = $Query->forecast_calendar_by_product_id_year($_GET['pid'], $active_year);
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
      	<?php
				  echo '<a href="'.$Capabilities->All['forecast_calendar']['url'].'" class="nav">'.$Capabilities->All['forecast_calendar']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['edit_forecast']['url'].'?pid='.$_GET['pid'].'" class="nav">'.$Capabilities->All['edit_forecast']['name'].'</a>';
      	?>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	<div>
					<table>
						<tr>
							<td width="150">Product Code:</td><td width="310"><input type="text" value="<?php echo $product['product_code'] ?>" class="text-field" disabled/></td>
							<td width="150">Brand:</td><td><input type="text" value="<?php echo $product['brand'] ?>" class="text-field" disabled/>
							</td>
						</tr>
						<tr>
							<td>Barcode:</td><td><input type="text" value="<?php echo $product['bar_code'] ?>" class="text-field" disabled/></td>
							<td>Color:</td><td><input type="text" value="<?php echo $product['color'] ?>" class="text-field" disabled/></td>
						</tr>    
						<tr>
							<td>Pack:</td><td><input type="text" value="<?php echo $product['classification'] ?>" class="text-field" disabled/></td>
							<td>Status:</td><td><input type="text" value="<?php echo $product['status'] ?>" class="text-field" disabled/></td>
						</tr>    
						<tr>
							<td>Production CP:</td><td><input type="text" value="<?php echo $product['prod_cp'] ?>" class="text-field text-right numbers" disabled/></td>
							<td></td>
						</tr>             
						<tr>
							<td>Description:</td>
							<td colspan="99">
								<input type="text" value="<?php echo $product['description'] ?>" class="text-field" style="width:645px" disabled/>
							</td>
						</tr>
						<tr><td height="5" colspan="99"></td></tr>
					</table>
				</div>
	      <br/>
				<h3 class="form-title">Year Forecast</h3>
				<div id="grid-calendar" class="grid jq-grid" style="min-height:60px;">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center"><a>Year</a></td>
	              <td width="60" class="border-right text-center"><a>Jan</a></td>
	              <td width="60" class="border-right text-center"><a>Feb</a></td>
	              <td width="60" class="border-right text-center"><a>Mar</a></td>
	              <td width="60" class="border-right text-center"><a>Apr</a></td>
	              <td width="60" class="border-right text-center"><a>May</a></td>
	              <td width="60" class="border-right text-center"><a>Jun</a></td>
	              <td width="60" class="border-right text-center"><a>Jul</a></td>
	              <td width="60" class="border-right text-center"><a>Aug</a></td>
	              <td width="60" class="border-right text-center"><a>Sep</a></td>
	              <td width="60" class="border-right text-center"><a>Oct</a></td>
	              <td width="60" class="border-right text-center"><a>Nov</a></td>
	              <td width="60" class="border-right text-center"><a>Dec</a></td>
	              <td width="60" class="border-right text-center"><a>Total</a></td>
	            </tr>
	          </thead>
	          <tbody>
	          	<?php
	          		$total = 0; 
	          		foreach($calendar as $cal) { 
	          			$total = $cal['jan'] + $cal['feb'] + $cal['mar'] + $cal['apr'] + $cal['may'] + $cal['jun'] +
	          								$cal['jul'] + $cal['aug'] + $cal['sep'] + $cal['oct'] + $cal['nov'] + $cal['dece'];
          		?>
	          	<tr>
	          		<td class="border-right text-center"><?php echo $cal['forecast_year'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['jan'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['feb'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['mar'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['apr'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['may'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['jun'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['jul'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['aug'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['sep'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['oct'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['nov'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $cal['dece'] ?></td>
	          		<td class="border-right text-right numbers"><?php echo $total ?></td>
	          	</tr>
	          	<?php } ?>
	          </tbody>
	        </table>
	      </div>
	      <br/>
				<h3 class="form-title">Month &raquo; <span class="red">January</span></h3>
      	<a id="btn-daily-forecast" href="#mdl-daily-forecast" rel="modal:open"></a>
	      <div id="grid-month" class="grid jq-grid" style="min-height:140px;">
	        <table id="tbl-week" cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="100" class="border-right text-center"><a>Week</a></td>
	              <td class="border-right text-center"><a>Remarks</a></td>
	              <td width="100" class="border-right text-center"><a>Prod Date</a></td>
	              <td width="100" class="border-right text-center"><a>Ship Date</a></td>
	              <td width="60" class="border-right text-center"><a>Qty</a></td>
	            </tr>
	          </thead>
	          <tbody>
	          	<?php
	          		for($i=1; $i<5; $i++) {
        			?>
        			<tr>
        				<td class="border-right text-center"><a href="#" class="week">Week<?php echo $i ?></a></td>
        				<td class="border-right">test</td>
        				<td class="border-right text-center"></td>
        				<td class="border-right text-center"></td>
        				<td class="border-right text-right">0</td>
        			</tr>
        			<?php
	          		}
	          	?>
	          </tbody>
	        </table>
	      </div>
      </form>
		</div>
	</div>
	
	<div id="mdl-daily-forecast" class="modal">
		<div class="modal-title"><h3>Daily Qty</h3></div>
		<div class="modal-content">
			<form id="frm-daily-forecast" method="POST">
				<span class="notice"></span>     
				<input type="hidden" name="action" value="edit_daily_forecast"/>
				<input type="hidden" name="week_id" value="1"/>
					
				<div class="field">
					<label>Day 1:</label>
					<input type="text" id="daily-day1" name="daily[day1]" class="text-field" default="0" />
				</div>
				 
				<div class="field">
					<label>Day 2:</label>
					<input type="text" id="daily-day2" name="daily[day2]" class="text-field" default="0" />
				</div>
				 
				<div class="field">
					<label>Day 3:</label>
					<input type="text" id="daily-day3" name="daily[day3]" class="text-field" default="0" />
				</div>
				 
				<div class="field">
					<label>Day 4:</label>
					<input type="text" id="daily-day4" name="daily[day4]" class="text-field" default="0" />
				</div>
				 
				<div class="field">
					<label>Day 5:</label>
					<input type="text" id="daily-day5" name="daily[day5]" class="text-field" default="0" />
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn" style="width:50px;">Cancel</a>
			<a id="submit-daily-forecast" rel="modal:close" href="#frm-daily-forecast" class="btn" style="width:50px;">Save</a>
		</div>
	</div>
	<script>
		$(function() {
	    $('#tbl-week').find('tbody tr .week').show_daily_modal();
	  })
	  
	  $.fn.show_daily_modal = function() {
  	$()
    	this.live('click', function(e) {
	    	$('#btn-daily-forecast').click();	
    })
  }
	</script>
<?php }
require('footer.php'); ?>