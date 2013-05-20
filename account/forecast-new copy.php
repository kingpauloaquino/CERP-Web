<?php
  /* Module: Forecasts New */
  $capability_key = 'add_forecast';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$product = $Query->product_by_id($_GET['pid']);
		
		$active_year = date('Y');
		
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
								<td width="90" class="border-right text-center">Month</td>
								<td width="90" class="border-right text-center">Week</td>
								<td class="border-right text-center">Remarks</td>
								<td width="160" class="border-right text-center">Ship Date</td>
								<td width="110" class="text-center">Quantity</td>
							</tr>
						</thead>
						<tbody id="forecasts">
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
											echo $mo; $show = FALSE; // month name
										}
										
										echo 'Wk-'.$ctr; // Week number
										$ctr+=1;
									}
								}
								
							?>
						</tbody>
					</table>
				</div>
      </form>
		</div>
	</div>
	
<?php }
require('footer.php'); ?>