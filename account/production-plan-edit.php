<?php
  /*
   * Module: Production Plan Details - Edit 
  */
  $capability_key = 'edit_production_plan';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'edit_production_plan') {
			foreach ($_POST['production_plan'] as $arr) {
				if($arr['type']=='Request') { 
					$request_target_date = array('prod_ship_date' => date('Y-m-d', strtotime($arr['prod_ship_date'])));
					$Posts->EditProductionPlan(array('variables' => $request_target_date, 'conditions' => 'id='.$arr['id']));	
				}
				if($arr['lot_no']!='') {
					$arr['init'] = 1;
					$args = array('ppopid' => $arr['id'], 'product_id' => $arr['product_id'], 'plan_qty' => $arr['produce_qty'], 'tracking_no' => ($arr['id'].'-'.$arr['product_id'])); 
					$num_of_records = $Posts->InitPurchaseOrderProductMaterials($args);	
					
					$parts = $DB->Get('production_purchase_order_product_parts', array(
						  			'columns' 		=> 'material_id',
						  	    'conditions' 	=> 'production_purchase_order_product_id = '.$arr['id']
					));
					// foreach ($parts as $part) {
						// $args = array('item_id' => $part['material_id'], 'prod_lot_no' => $arr['lot_no'], 'ppopid' => $arr['id'], 'tracking_no' => $arr['id'].$arr['product_id']); 
						// $num_of_records = $Posts->InitProductionInventory($args);	
					// }		
				$arr['type'] = 122; // Type ID for Plan
				$arr['prod_ship_date'] = date('Y-m-d', strtotime($arr['prod_ship_date']));			
				$Posts->EditProductionPlan(array('variables' => $arr, 'conditions' => 'id='.$arr['id']));							
				}
			}
			redirect_to($Capabilities->All['show_production_plan']['url'].'?ppoid='.$_POST['ppoid'].'&oid='.$_POST['oid']);	
		} 
	  
	  if(isset($_GET['ppoid']) && isset($_GET['oid'])) {		
			$prod_detail = $DB->Find('production_purchase_orders', array(
					  			'columns' 		=> 'production_purchase_orders.id AS ppoid, production_purchase_orders.order_id AS oid, orders.po_number AS po_no, lookups.description AS pack, lookup_status.description AS status,
																		orders.po_date AS po_date, orders.delivery_date AS delivery_date, production_purchase_orders.target_date AS target_date',
					  	    'joins' 			=> 'INNER JOIN orders ON orders.id = production_purchase_orders.order_id
					  	    									INNER JOIN lookups ON lookups.id = production_purchase_orders.status
					  	    									INNER JOIN lookup_status ON lookup_status.id = production_purchase_orders.status',
									'sort_column'	=> '',
					  	    'conditions' 	=> 'production_purchase_orders.id = '.$_GET['ppoid']
			));	
	  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
        	echo '<a href="'.$Capabilities->All['show_production_plan']['url'].'?ppoid='.$_GET['ppoid'].'&oid='.$_GET['oid'].'" class="nav">'.$Capabilities->All['show_production_plan']['name'].'</a>';
        
				  // echo '<a href="'.$Capabilities->All['add_product_tree']['url'].'?pid='.$_GET['pid'].'&code='.$_GET['code'].'" class="nav">'.$Capabilities->All['add_product_tree']['name'].'</a>'; 
				  // echo '<a href="'.$Capabilities->All['edit_product_tree']['url'].'?pid='.$_GET['pid'].'&code='.$_GET['code'].'" class="nav">'.$Capabilities->All['edit_product_tree']['name'].'</a>';
					// echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_GET['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 

				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="edit_production_plan">	
				<input type="hidden" name="ppoid" value="<?php echo $_GET['ppoid'] ?>">	
				<input type="hidden" name="oid" value="<?php echo $_GET['oid'] ?>">	

				<h3 class="form-title">Details</h3>
				<table>
           <tr>
              <td width="150">Production Plan ID:</td><td width="340"><input type="text" value="CPP-<?php echo $_GET['ppoid'] ?>" class="text-field" disabled/></td>
              <td width="150">P/O Number:</td><td width="340"><input type="text" value="<?php echo $prod_detail['po_no'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = (isset($prod_detail['po_no'])) ? link_to('orders-show.php?oid='.$prod_detail['oid']) : '' ?>
              </td>
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
		            <td width="20%" class="border-right text-center"><a>Target Date</a></td>
		            <td width="10%" class="border-right text-center"><a>Type</a></td>
		          </tr>
		        </thead>
		        <tbody>
		        	<?php
		        			$po_products = $DB->Get('production_purchase_order_products', array(
										  			'columns' 		=> 'production_purchase_order_products.id AS pop_id, production_purchase_order_products.product_id AS pid, item_classifications.classification AS pack,
										  												production_purchase_order_products.lot_no AS lot_no, products.product_code AS product, products.color AS color, production_purchase_order_products.order_qty AS order_qty,
										  												production_purchase_order_products.produce_qty AS produce_qty, production_purchase_order_products.prod_ship_date AS prod_ship_date, lookups2.description AS type',
										  	    'joins' 			=> 'INNER JOIN products ON products.id = production_purchase_order_products.product_id
										  	    									LEFT OUTER JOIN item_classifications ON item_classifications.id = products.product_classification
										  	    									INNER JOIN lookups AS lookups2 ON lookups2.id = production_purchase_order_products.type',
										  	    'conditions' 	=> 'production_purchase_order_products.production_purchase_order_id = '.$_GET['ppoid'],
														'sort_column'	=> 'production_purchase_order_products.product_id',
						  	  ));
		        			$pack_types = $DB->Get('lookups', array('columns' => 'id, description', 
																'conditions' => 'parent = "'.get_lookup_code('packing_type').'"', 'sort_column' => 'description'));
									$ctr=1;
									foreach ($po_products as $prod) {
										echo '<tr>';
										echo '<td class="border-right text-center">'.$ctr.'</td>';
										echo '<td class="border-right text-center"><input type="hidden" id="production_plan['.$ctr.'][id]" name="production_plan['.$ctr.'][id]" value="'.$prod['pop_id'].'"/>';			
										echo '<input type="hidden" id="production_plan['.$ctr.'][product_id]" name="production_plan['.$ctr.'][product_id]" value="'.$prod['pid'].'"/>';
										echo '<input type="hidden" id="production_plan['.$ctr.'][type]" name="production_plan['.$ctr.'][type]" value="'.$prod['type'].'"/>'.$prod['pop_id'].$prod['pid'].'</td>';
										echo $lot = ($prod['lot_no']=='') 
													? '<td class="border-right text-center"><input type="text" id="production_plan['.$ctr.'][lot_no]" name="production_plan['.$ctr.'][lot_no]" value="'.$prod['lot_no'].'" class="auto_width" autocomplete="off" /></td>'
													: '<td class="border-right text-center">'.$prod['lot_no'].'</td>';										
										echo '<td class="border-right">'.$prod['product'].'</td>';							
										echo '<td class="border-right">'.get_ink_color($prod['color']).'</td>';									
										echo '<td class="border-right text-right">'.$prod['order_qty'].'</td>';
										echo $prod_qty = ($prod['type']=='Plan') 		
													? '<td class="border-right text-right"><input type="text" id="production_plan['.$ctr.'][produce_qty]" name="production_plan['.$ctr.'][produce_qty]" value="'.$prod['produce_qty'].'" class="auto_width_right" /></td>'
													: '<td class="border-right text-right">'.$prod['produce_qty'].'</td>';	
										echo '<td class="border-right text-right"><input type="text" id="production_plan['.$ctr.'][prod_ship_date]" name="production_plan['.$ctr.'][prod_ship_date]" value="'.date("F d, Y", strtotime($prod['prod_ship_date'])).'" class="auto_width_center date-pick" /></td>';							
										echo '<td class="border-right text-center">'.$prod['type'].'</td>';
										echo '</tr>';
										$ctr+=1;										
									}
								?>
	        	</tbody>
					</table>
				</div>	
	      
         <div class="field-command">
       	   <div class="text-post-status">
       	     
           </div>
       	   <input type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('production-plan-show.php?ppoid='.$_GET['ppoid'].'&oid='.$_GET['oid']); ?>"/>
         </div>	
         				
			</form>	
		</div>
	</div>

<?php }
require('footer.php'); ?>