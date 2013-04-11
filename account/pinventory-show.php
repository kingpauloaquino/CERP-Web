<?php
  /*
   * Module: Product Inventory - Show
  */
  $capability_key = 'show_product_inventory';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  if(isset($_GET['id'])) {
  	$products = $DB->Find('products', array(
					  			'columns' 		=> 'products.id AS pid, products.product_code, products.description, brand_models.brand_model, lookups1.description AS unit,
																  	products.color, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic, lookups4.description AS status,
																  	item_classifications.classification, products.bar_code', 
					  	    'conditions' 	=> 'products.id = '.$_GET['id'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON products.brand_model = brand_models.id 
																		LEFT OUTER JOIN users ON products.person_in_charge = users.id
																		LEFT OUTER JOIN item_costs ON products.id = item_costs.item_id
																		LEFT OUTER JOIN lookups AS lookups1 ON lookups1.id = item_costs.unit
																		LEFT OUTER JOIN lookups AS lookups4 ON products.status = lookups4.id
																		LEFT OUTER JOIN item_classifications ON item_classifications.id = products.product_classification'
	  ));
  }
	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  // echo '<a href="'.$Capabilities->All['add_material_inventory']['url'].'" class="nav">'.$Capabilities->All['add_material_inventory']['name'].'</a>';
				  // echo '<a href="'.$Capabilities->All['edit_material_inventory']['url'].'?iid='.$_GET['iid'].'&mid='.$_GET['mid'].'" class="nav">'.$Capabilities->All['edit_material_inventory']['name'].'</a>'; 
			  	// echo '<a href="'.$Capabilities->All['material_inventory_history']['url'].'?iid='.$_GET['iid'].'&mid='.$_GET['mid'].'" class="nav" target="_blank">'.$Capabilities->All['material_inventory_history']['name'].'</a>';

				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Product Code:</td><td width="310"><input type="text" value="<?php echo $products['product_code'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($products['product_code']!='') ? link_to('products-show.php?pid='.$_GET['id']) : '' ?>
              </td>
              <td width="150">Brand:</td><td><input type="text" value="<?php echo $products['brand_model'] ?>" class="text-field" disabled/>
              </td>
           </tr>
           <tr>
              <td>Pack:</td><td><input type="text" value="<?php echo $products['classification'] ?>" class="text-field" disabled/></td>
              <td>Color:</td><td><input type="text" value="<?php echo $products['color'] ?>" class="text-field" disabled/></td>
           </tr>    
           <tr>
              <td>Barcode:</td><td><input type="text" value="<?php echo $products['bar_code'] ?>" class="text-field" disabled/></td>
              <td>Status:</td><td><input type="text" value="<?php echo $products['status'] ?>" class="text-field" disabled/></td>
           </tr>             
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" value="<?php echo $products['description'] ?>" class="text-field" style="width:645px" disabled/>
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>

      	<br/>

        <h3 class="form-title">Warehouse</h3>
	      <div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="5%" class="border-right text-center"><a></a></td>
	              <td width="10%" class="border-right text-center"><a>Prod. Plan ID</a></td>
	              <td width="10%" class="border-right text-center"><a>Prod. Lot No</a></td>
	              <td width="10%" class="border-right text-center"><a>Tracking No</a></td>
	              <td class="border-right text-center"><a>Remarks</a></td>
	              <td width="10%" class="border-right text-center"><a>UOM</a></td>
	              <td width="10%" class="border-right text-center"><a>Qty</a></td>
	            </tr>
	          </thead>
	          <tbody>
	        		<?php
	        				$warehouse = $DB->Get('warehouse2_inventories', array(
							  			'columns' => 'warehouse2_inventories.item_id, warehouse2_inventories.production_purchase_order_id, warehouse2_inventories.tracking_no,
							  										warehouse2_inventories.prod_lot_no, warehouse2_inventories.qty, warehouse2_inventories.remarks', 
							  	    'conditions' => 'warehouse2_inventories.item_id = '.$_GET['id']
							  	    ));
									//echo '<tr><td class="border-right text-right" colspan="5">'.$warehouse[0]['terminal_name'].'</td></tr>';
									$total_qty = 0.0;
									$ctr = 1;
	        				foreach ($warehouse as $invt) {
	        					echo '<tr>';
										echo '<td class="border-right text-right">'.$ctr.'</td>';
										echo '<td class="border-right text-center"><a href="#">CPP-'.$invt['production_purchase_order_id'].'</a></td>';
										echo '<td class="border-right text-center"><a href="#">'.$invt['prod_lot_no'].'</a></td>';
										echo '<td class="border-right text-center"><a href="#">'.$invt['tracking_no'].'</a></td>';
										echo '<td class="border-right">'.$invt['remarks'].'</td>';
										echo '<td class="border-right text-center">'.$products['unit'].'</td>';
										echo '<td class="border-right text-right">'.trim_decimal($invt['qty']).'</td>';
										$ctr+=1;
										$total_qty += (double)$invt['qty'];
	        					echo '</tr>';
									}
	        		?>
	          	<tr>
	          		<td class="border-right text-right" colspan="6"><b>Total:</b></td>
	          		<td class="border-right text-right"><b><?php echo $total_qty ?></b></td>
	          	</tr>
	          </tbody>
	        </table>
	      </div>	
      </form>
    	<br/>
		</div>
	</div>

<?php }
require('footer.php'); ?>