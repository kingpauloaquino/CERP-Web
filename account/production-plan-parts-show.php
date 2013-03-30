<?php
  /*
   * Module: Production Plan Parts - Show 
  */
  $capability_key = 'show_production_parts';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	  if(isset($_GET['popid'])) {
	  	$ppop = $DB->Find('production_purchase_order_products', array(
					  			'columns' 		=> 'id AS ppopid, product_id, lot_no, init',
					  	    'conditions' 	=> 'id = '.$_GET['popid']
			));		
			
	  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				   echo '<a href="'.$Capabilities->All['show_production_plan']['url'].'?ppoid='.$_GET['ppoid'].'&oid='.$_GET['oid'].'" class="nav">'.$Capabilities->All['show_production_plan']['name'].'</a>';
				   //echo '<a href="'.$Capabilities->All['show_production_plan_parts_request']['url'].'?ppoid='.$_GET['ppoid'].'&oid='.$_GET['oid'].'&popid='.$_GET['popid'].'&prod_lot_no='.$_GET['prod_lot_no'].'&prod='.$_GET['prod'].'" class="nav">'.$Capabilities->All['show_production_plan_parts_request']['name'].'</a>';
				   echo '<a href="'.$Capabilities->All['send_production_part_request']['url'] . '?' . 					 
					 												http_build_query(array('ppoid' => $_GET['ppoid'], 'oid' => $_GET['oid'], 'popid' => $_GET['popid'], 'prod_lot_no' => $_GET['prod_lot_no'], 
					 												'pid' => $_GET['pid'],'prod' => $_GET['prod'], 'po_no' => $_GET['po_no'], 'po_date' => $_GET['po_date'], 'delivery_date' => $_GET['delivery_date'], 
					 												'target_date' => $_GET['target_date'], 'status' => $_GET['status'])) 
																	. '" class="nav">'.$Capabilities->All['send_production_part_request']['name'].'</a>';
				  // echo '<a href="'.$Capabilities->All['edit_product_tree']['url'].'?pid='.$_GET['pid'].'&code='.$_GET['code'].'" class="nav">'.$Capabilities->All['edit_product_tree']['name'].'</a>';
					// echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_GET['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">				
				<h3 class="form-title">Details</h3>
				<table>
           <tr>
              <td width="150">Production Plan ID:</td><td width="340"><input type="text" value="CPP-<?php echo $_GET['ppoid'] ?>" class="text-field" disabled/></td>
              <td width="150">P/O Number:</td><td width="340"><input type="text" value="<?php echo $_GET['po_no'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>P/O Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($_GET['po_date'])) ?>" class="text-field text-date" disabled/></td>
              <td>P/O Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($_GET['delivery_date'])) ?>" class="text-field text-date" disabled/></td>
           </tr>
           <tr>
              <td>Production Target Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($_GET['target_date'])) ?>" class="text-field text-date" disabled/></td>
              <td>Status:</td><td><input type="text" value="<?php echo $_GET['status'] ?>" class="text-field" disabled/></td>
           </tr> 
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        <br/>
        <h3 class="form-title"><a href="products-show.php?pid=<?php echo $_GET['pid'] ?>" target="_blank"><?php echo $_GET['prod']?></a> Parts</h3>
				<div class="grid jq-grid">
		      <table id="tbl-order-items" cellspacing="0" cellpadding="0">
		        <thead>
		          <tr>
		            <td width="5%" class="border-right"><a></a></td>
		            <td width="20%" class="border-right text-center"><a>Material Code</a></td>
		            <td width="35%" class="border-right text-center"><a>Description</a></td>
		            <td width="10%" class="border-right text-center"><a>Unit</a></td>
		            <td width="10%" class="border-right text-center"><a>Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Plan Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Location</a></td>
		          </tr>
		        </thead>
		        <tbody>
		        	<?php
		        			$pop_mats = $DB->Get('production_purchase_order_product_parts', array(
											  			'columns' 		=> 'production_purchase_order_product_parts.id, production_purchase_order_product_parts.material_id AS mat_id, materials.material_code AS material_code, production_purchase_order_product_parts.tracking_no AS tracking_no,
																								materials.description AS description, lookups.description AS unit, production_purchase_order_product_parts.qty AS qty, production_purchase_order_product_parts.plan_qty AS plan_qty',
											  	    'joins' 			=> 'INNER JOIN materials ON materials.id = production_purchase_order_product_parts.material_id
																								LEFT OUTER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT" 
																								LEFT OUTER JOIN lookups ON lookups.id = item_costs.unit',
											  	    'conditions' 	=> 'production_purchase_order_product_parts.production_purchase_order_product_id = '.$_GET['popid']
							  	  )
									);
									$ctr=1;
									foreach ($pop_mats as $material) {
										echo '<tr>';
										echo '<td class="border-right text-center">'.$ctr.'</td>';
										echo '<td class="border-right"><a href="materials-show.php?mid='.$material['mat_id'].'" target="_blank">'.$material['material_code'].'</a></td>';
										echo '<td class="border-right">'.$material['description'].'</td>';
										echo '<td class="border-right text-center">'.$material['unit'].'</td>';
										echo '<td class="border-right text-right">'.trim_decimal($material['qty']).'</td>';
										echo '<td class="border-right text-right numbers">'.trim_decimal($material['plan_qty']).'</td>';
										echo '<td class="border-right text-center"><a href="production-line-show.php?mid='.$material['mat_id'].'&prod_lot_no='.$_GET['prod_lot_no'].'&trk_no='.$material['tracking_no'].'">view</a></td>';
										echo '</tr>';
										$ctr+=1;
									}
								?>
	        	</tbody>
					</table>
				</div>	
			</form>
			<br/>
		</div>
	</div>

<?php }
require('footer.php'); ?>