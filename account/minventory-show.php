<?php
  /*
   * Module: Material Inventory - Show
  */
  $capability_key = 'show_material_inventory';
  require('header.php');
  
  if(isset($_REQUEST['id'])) {
  	$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS mid, materials.material_code, materials.description, brand_models.brand_model, lookups1.description AS unit,
																  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic,
																  	lookups3.description AS material_type, lookups4.description AS status', 
					  	    'conditions' 	=> 'materials.id = '.$_REQUEST['id'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id 
																		LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																		LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
																		LEFT OUTER JOIN lookups AS lookups1 ON lookups1.id = item_costs.unit
																		LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																		LEFT OUTER JOIN lookups AS lookups4 ON materials.status = lookups4.id'
	  ));
  }
	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  // echo '<a href="'.$Capabilities->All['add_material_inventory']['url'].'" class="nav">'.$Capabilities->All['add_material_inventory']['name'].'</a>';
				  // echo '<a href="'.$Capabilities->All['edit_material_inventory']['url'].'?iid='.$_REQUEST['iid'].'&mid='.$_REQUEST['id'].'" class="nav">'.$Capabilities->All['edit_material_inventory']['name'].'</a>'; 
			  	// echo '<a href="'.$Capabilities->All['material_inventory_history']['url'].'?iid='.$_REQUEST['iid'].'&mid='.$_REQUEST['id'].'" class="nav" target="_blank">'.$Capabilities->All['material_inventory_history']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($materials['material_code']!='') ? link_to('materials-show.php?mid='.$_REQUEST['id']) : '' ?>
              </td>
              <td width="150">Type:</td><td><input type="text" value="<?php echo $materials['material_type'] ?>" class="text-field text-date" disabled/></td>
           </tr>
           <tr>
              <td>Classification:</td><td><input type="text" value="<?php echo $materials['classification'] ?>" class="text-field" disabled/></td>
              
              <td>Model:</td><td><input type="text" value="<?php echo $model = ($materials['material_type'] == 'Direct Material') ? $materials['brand_model'] : 'N/A' ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Person in-charge:</td><td><input type="text" value="<?php echo $materials['pic'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($materials['pic']!='') ? link_to('users-show.php?uid='.$materials['user_id']) : '' ?>
              </td>
              <td>Status:</td><td><input type="text" value="<?php echo $materials['status'] ?>" class="text-field" disabled/></td>
           </tr>             
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" value="<?php echo $materials['description'] ?>" class="text-field" style="width:645px" disabled/>
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
        
      	<br/>
      	
      	<?php
	        				$warehouse = $DB->Get('warehouse_inventories', array(
							  			'columns' => 'warehouse_inventories.item_id, warehouse_inventories.invoice_no, warehouse_inventories.lot_no,
							  										warehouse_inventories.qty, warehouse_inventories.remarks', 
							  	    'conditions' => 'warehouse_inventories.item_type = "MAT" AND warehouse_inventories.item_id = '.$_REQUEST['id']
							  	    ));
									//echo '<tr><td class="border-right text-right" colspan="5">'.$warehouse[0]['terminal_name'].'</td></tr>';
									$total_qty = 0.0;
									$ctr = 1;
									$tbody = '';
									foreach ($warehouse as $invt) {
	        					$tbody .= '<tr>';
										$tbody .= '<td class="border-right text-right">'.$ctr.'</td>';
										$tbody .= '<td class="border-right text-center"><a href="#">'.$invt['invoice_no'].'</a></td>';
										$tbody .= '<td class="border-right text-center"><a href="#">'.$invt['lot_no'].'</a></td>';
										$tbody .= '<td class="border-right">'.$invt['remarks'].'</td>';
										$tbody .= '<td class="border-right text-center">'.$materials['unit'].'</td>';
										$tbody .= '<td class="border-right text-right">'.trim_decimal($invt['qty']).'</td>';
	        					$tbody .= '</tr>';
										$ctr+=1;
										$total_qty += (double)$invt['qty'];
									}

	        		?>

        <h3 class="form-title">Warehouse <?php echo ($total_qty==0) ? '<span class="magenta">(Out-of-stock)</span>' : ''; ?></h3>
	      <div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="5%" class="border-right text-center"><a></a></td>
	              <td width="10%" class="border-right text-center"><a>Invoice</a></td>
	              <td width="10%" class="border-right text-center"><a>Mat. Lot No</a></td>
	              <td class="border-right text-center"><a>Remarks</a></td>
	              <td width="10%" class="border-right text-center"><a>UOM</a></td>
	              <td width="10%" class="border-right text-center"><a>Qty</a></td>
	            </tr>
	          </thead>
	          <tbody>
	        		<?php echo $tbody ?>
	          	<tr>
	          		<td class="border-right text-right" colspan="5"><b>Total:</b></td>
	          		<td class="border-right text-right"><b><?php echo $total_qty ?></b></td>
	          	</tr>
	          </tbody>
	        </table>
	      </div>	
	      <br/>
	      <h3 class="form-title">Production Request</h3>
	      <div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="3%" class="border-right text-center"><a></a></td>
	              <td width="8%" class="border-right text-center"><a>P/O No.</a></td>
	              <td width="10%" class="border-right text-center"><a>Tracking No.</a></td>
	              <td width="10%" class="border-right text-center"><a>Prod. Lot No</a></td>
	              <td width="14%" class="border-right text-center"><a>Requested</a></td>
	              <td width="14%" class="border-right text-center"><a>Expected</a></td>
	              <td class="border-right text-center"><a>Status</a></td>
	              <td width="7%" class="border-right text-center"><a>UOM</a></td>
	              <td width="8%" class="border-right text-center"><a>Pending</a></td>
	              <td width="8%" class="border-right text-center"><a>Released</a></td>
	              <td width="8%" class="border-right text-center"><a>Requested</a></td>
	            </tr>
	          </thead>
	          <tbody>
	        		<?php
	        				$requests = $DB->Get('production_purchase_order_product_parts', array(
							  			'columns' => 'orders.po_number, production_purchase_order_products.lot_no AS prod_lot_no, production_purchase_orders.id AS ppoid,
																		production_purchase_order_product_parts.tracking_no, production_purchase_order_product_parts.material_id,
																		production_purchase_order_product_parts.plan_qty, production_purchase_order_product_parts.pending_qty,
																		production_purchase_order_product_parts.updated_at, lookups.description AS status, production_purchase_orders.order_id AS oid,
																		production_purchase_order_product_parts.expected_datetime, production_purchase_order_product_parts.is_requested', 
  										'joins' => 'INNER JOIN production_purchase_order_products ON production_purchase_order_products.id = production_purchase_order_product_parts.production_purchase_order_product_id
																	INNER JOIN production_purchase_orders ON production_purchase_orders.id =  production_purchase_order_products.production_purchase_order_id
																	INNER JOIN orders ON orders.id = production_purchase_orders.order_id
																	INNER JOIN lookups ON lookups.id = production_purchase_order_product_parts.status',
							  	    'conditions' => 'production_purchase_order_product_parts.material_id = '.$_REQUEST['id'], 
							  	    'sort_column' => 'production_purchase_order_product_parts.created_at',
							  	    'sort_order' => 'DESC '
							  	    ));
											// status id 149 = production request pending
									$total_req = 0.0;
									$ctr = 1;
	        				foreach ($requests as $req) {
	        					echo '<tr>';
										echo '<td class="border-right text-right">'.$ctr.'</td>';
										echo '<td class="border-right text-center"><a href="production-plan-show.php?ppoid='.$req['ppoid'].'&oid='.$req['oid'].'">'.$req['po_number'].'</a></td>';
										echo '<td class="border-right text-center"><a href="#">'.$req['tracking_no'].'</a></td>';
										echo '<td class="border-right text-center"><a href="#">'.$req['prod_lot_no'].'</a></td>';
										echo '<td class="border-right">'.date("M. d, Y g:i a", strtotime($req['updated_at'])).'</td>';
										echo '<td class="border-right">'.date("M. d, Y g:i a", strtotime($req['expected_datetime'])).'</td>';
										echo '<td class="border-right text-center">'.$req['status'].'</td>';
										echo '<td class="border-right text-center">'.$materials['unit'].'</td>';
										echo '<td class="border-right text-right">'.trim_decimal($req['pending_qty']).'</td>';
										echo '<td class="border-right text-right">'.(trim_decimal($req['plan_qty']) - trim_decimal($req['pending_qty'])).'</td>';
										echo '<td class="border-right text-right">'.trim_decimal($req['plan_qty']).'</td>';
										$ctr+=1;
										$total_req += (double)$req['plan_qty'];
	        					echo '</tr>';
									}
	        		?>
	          	<tr>
	          		<td class="border-right text-right" colspan="10"><b>Total:</b></td>
	          		<td class="border-right text-right"><b><?php echo $total_req ?></b></td>
	          	</tr>
	          </tbody>
	        </table>
	      </div>	
      </form>
    	<br/>
		</div>
	</div>

<?php require('footer.php'); ?>