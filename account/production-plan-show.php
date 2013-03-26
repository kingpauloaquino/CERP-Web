<?php
  /*
   * Module: Production Plan Details - Show 
  */
  $capability_key = 'show_production_plan';
  require('header.php');
  
  if(isset($_REQUEST['ppoid']) && isset($_REQUEST['oid'])) {
  	$entry = $DB->Get('production_purchase_order_products', array(
				  			'columns' 		=> 'COUNT(id) AS cnt',
				  	    'conditions' 	=> 'production_purchase_order_id = '.$_REQUEST['ppoid']
		));
		
		$prod_detail = $DB->Find('production_purchase_orders', array(
				  			'columns' 		=> 'production_purchase_orders.id AS ppoid, production_purchase_orders.order_id AS oid, orders.po_number AS po_no, lookups.description AS status,
																	orders.po_date AS po_date, orders.delivery_date AS delivery_date, production_purchase_orders.target_date AS target_date',
				  	    'joins' 			=> 'INNER JOIN orders ON orders.id = production_purchase_orders.order_id
				  	    									INNER JOIN lookups ON lookups.id = production_purchase_orders.status',
								'sort_column'	=> '',
				  	    'conditions' 	=> 'production_purchase_orders.id = '.$_REQUEST['ppoid']
		));		
		
		if((int)$entry[0]['cnt']==0) {
			$args = array('ppoid' => $_REQUEST['ppoid'], 'oid' => $_REQUEST['oid'], 'target_date' => $prod_detail['target_date']); 
			$num_of_records = $Posts->InitPurchaseOrderProducts($args);	
			
			//TODO: update production_purchase_orders init->1
			
			// $ppops = $DB->Get('production_purchase_order_products', array(
				  			// 'columns' 		=> 'id AS ppopid, product_id, lot_no',
				  	    // 'conditions' 	=> 'production_purchase_order_id = '.$_REQUEST['ppoid']
			// ));
			// foreach ($ppops as $ppop) {
				// $args = array('ppopid' => $ppop['ppopid'], 'product_id' => $ppop['product_id']); 
				// $num_of_records = $Posts->InitPurchaseOrderProductMaterials($args);	
// 				
				// $parts = $DB->Get('production_purchase_order_product_parts', array(
					  			// 'columns' 		=> 'material_id',
					  	    // 'conditions' 	=> 'production_purchase_order_product_id = '.$ppop['ppopid']
				// ));
				// foreach ($parts as $part) {
					// $args = array('item_id' => $part['material_id'], 'prod_lot_no' => $ppop['lot_no'], 'ppopid' => $ppop['ppopid']); 
					// $num_of_records = $Posts->InitProductionInventory($args);	
				// }
			// }
		}
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  // echo '<a href="'.$Capabilities->All['add_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['add_product_tree']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_production_plan']['url'].'?ppoid='.$_REQUEST['ppoid'].'&oid='.$_REQUEST['oid'].'" class="nav">'.$Capabilities->All['edit_production_plan']['name'].'</a>';
					// echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 
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
              <td width="150">P/O Number:</td><td width="340"><input type="text" value="<?php echo $prod_detail['po_no'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>P/O Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($prod_detail['po_date'])) ?>" class="text-field text-date" disabled/></td>
              <td>P/O Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($prod_detail['delivery_date'])) ?>" class="text-field text-date" disabled/></td>
           </tr>
           <tr>
              <td>Production Target Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($prod_detail['target_date'])) ?>" class="text-field text-date" disabled/></td>
              <td>Status:</td><td><input type="text" value="<?php echo $prod_detail['status'] ?>" class="text-field" disabled/></td>
           </tr> 
           <tr><td height="5" colspan="99"></td></tr>
        </table>
				<br/>
     		<h3 class="form-title">Products</h3>
     		<div class="grid jq-grid">
		      <table id="tbl-order-items" cellspacing="0" cellpadding="0">
		        <thead>
		          <tr>
		            <td width="5%" class="border-right text-center;"><a></a></td>
		            <td width="10%" class="border-right text-center"><a>Tracking No.</a></td>
		            <td width="10%" class="border-right text-center"><a>Lot No.</a></td>
		            <td width="20%" class="border-right text-center"><a>Product</a></td>
		            <td width="5%" class="border-right text-center"><a>Color</a></td>
		            <td width="10%" class="border-right text-center"><a>Order Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Prod Qty</a></td>
		            <td width="15%" class="border-right text-center"><a>Target Date</a></td>
		            <td width="10%" class="border-right text-center"><a>Type</a></td>
		            <td width="5%" class="border-right text-center"><a>Terminals</a></td>
		          </tr>
		        </thead>
		        <tbody>
		        	<?php
				        	$po_products = $DB->Get('production_purchase_order_products', array(
										  			'columns' 		=> 'production_purchase_order_products.id AS pop_id, production_purchase_order_products.product_id AS pid, production_purchase_order_products.request_id, 
										  												item_classifications.classification AS pack, production_purchase_order_products.lot_no AS lot_no, products.product_code AS product, 
										  												products.color AS color, production_purchase_order_products.order_qty AS order_qty, lookups2.description AS type, CONCAT(production_purchase_order_products.id,"-",production_purchase_order_products.product_id) AS tracking_no, 
										  												production_purchase_order_products.produce_qty AS prod_qty, production_purchase_order_products.prod_ship_date AS ship_date',
										  	    'joins' 			=> 'INNER JOIN products ON products.id = production_purchase_order_products.product_id
										  	    									LEFT OUTER JOIN item_classifications ON item_classifications.id = products.product_classification
										  	    									INNER JOIN lookups AS lookups2 ON lookups2.id = production_purchase_order_products.type',
										  	    'conditions' 	=> 'production_purchase_order_products.production_purchase_order_id = '.$_REQUEST['ppoid'],
														'sort_column'	=> 'production_purchase_order_products.product_id',
						  	  ));
									$ctr=1;
									foreach ($po_products as $prod) {
										$addtl = ($prod['type'] == 'Request') ? ' highlight_yellow' : '';
										echo '<tr>';
										echo '<td class="border-right text-center'.$addtl.'">'.$ctr.'</td>';
										echo '<td class="border-right text-center'.$addtl.'">'.$prod['pop_id'].'-'.$prod['pid'].'</td>';									
										echo '<td class="border-right text-center'.$addtl.'">'.$prod['lot_no'].'</td>';
										echo $link = ($prod['lot_no']!=NULL) 
													? '<td class="border-right'.$addtl.'"><a href="production-plan-parts-show.php?'.
															http_build_query(array('ppoid' => $_REQUEST['ppoid'], 'oid' => $_REQUEST['oid'], 'popid' => $prod['pop_id'], 
																'prod_lot_no' => $prod['lot_no'], 'pid' => $prod['pid'],'prod' => $prod['product'], 'po_no' => $prod_detail['po_no'], 
																'po_date' => $prod_detail['po_date'], 'delivery_date' => $prod_detail['delivery_date'], 
																'target_date' => $prod_detail['target_date'], 'status' => $prod_detail['status'])).'">'.$prod['product'].'</a></td>'
													: '<td class="border-right'.$addtl.'">'.$prod['product'].'</td>'; 
										//echo '<td class="border-right text-center" style="'.set_color($prod['color']).'">'.$prod['color'].'</td>';									
										echo '<td class="border-right'.$addtl.'">'.get_ink_color($prod['color']).'</td>';									
										echo '<td class="border-right text-right numbers'.$addtl.'">'.$prod['order_qty'].'</td>';
										echo '<td class="border-right text-right numbers'.$addtl.'">'.$prod['prod_qty'].'</td>';
										echo '<td class="border-right text-center'.$addtl.'">'.date("F d, Y", strtotime($prod['ship_date'])).'</td>';
										
										echo $link = ($prod['type']=='Request') 
													? '<td class="border-right text-center'.$addtl.'"><a href="material-requests-show.php?'.
															http_build_query(array('mrid' => $prod['request_id'])).'">'.$prod['type'].'</a></td>'
													: '<td class="border-right text-center'.$addtl.'">'.$prod['type'].'</td>'; 
										
										echo $link = ($prod['lot_no']!=NULL) 
													? '<td class="border-right text-center'.$addtl.'"><a href="production-plan-terminals-show.php?'.
															http_build_query(array('popid' => $prod['pop_id'], 'po' => $prod_detail['po_no'],
																'lot' => $prod['lot_no'], 'prod' => $prod['product'], 'trk_no' => $prod['tracking_no'])).'">view</a></td>'
													: '<td class="border-right text-center'.$addtl.'">view</td>'; 
										echo '</tr>';
										$ctr+=1;
									}
								?>
	        	</tbody>
					</table>
				</div>	<br/>
				<p>* Rows highlighted in <span class="highlight_yellow">yellow</span> are Additional Production Material Requests</p>
			</form>
			<br/>
		</div>
	</div>

<?php require('footer.php'); ?>