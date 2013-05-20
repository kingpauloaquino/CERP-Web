<?php
  /* Module: Forecasts Show */
  $capability_key = 'show_forecast';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$product = $Query->product_by_id($_GET['pid']);
		
		$data = $DB->Find('forecasts', array('columns' => 'created_at', 'conditions' => 'product_id='.$_GET['pid'].' ORDER BY created_at DESC LIMIT 1'));
		if(!empty($data)) {
			if(date('Y', strtotime($data['created_at'])) == date('Y')) { //already has entry for current year
				
			} else {
				
			}
		}
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
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
							<td>Production Qty:</td><td><input type="text" value="<?php echo $product['production_qty'] ?>" class="text-field text-right" disabled/></td>
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
      	
				<div id="grid-forecast" class="grid jq-grid" style="min-height:146px;">
					<table cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td width="90" class="border-right text-center">Week</td>
								<td class="border-right text-center">Remarks</td>
								<td width="160" class="border-right text-center">Ship Date</td>
								<td width="110" class="text-center">Quantity</td>
							</tr>
						</thead>
						<tbody id="forecasts"></tbody>
					</table>
				</div>
      </form>
		</div>
	</div>
	
	<script>
   	$(function() {
	  	var data = { 
	    	"url":"/populate/forecast-items.php?pid=<?php echo $_GET['pid']; ?>",
	      "limit":"60",
				"data_key":"forecast_items",
				"row_template":"row_template_forecast_items_read_only"
			}
		
			$('#grid-forecast').grid(data);
	  }) 
  </script>
<?php }
require('footer.php'); ?>