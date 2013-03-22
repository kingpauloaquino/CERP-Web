<?php
  /*
   * Module: ProductTree - Show 
  */
  $capability_key = 'production_products';
  require('header.php');
  
  if(isset($_REQUEST['pbid']) && isset($_REQUEST['pid'])) {
		$product_tree = $DB->Get('products_parts_tree', array(
				  			'columns' 		=> 'products_parts_tree.id parts_id, materials.id AS mat_id, materials.material_code, 
				  												lookups.code AS unit, products_parts_tree.material_qty',
				  	    'joins' 			=> 'LEFT OUTER JOIN materials ON products_parts_tree.material_id = materials.id
				  	    									LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
				  	    									LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
				  	    									LEFT OUTER JOIN lookups ON item_costs.unit = lookups.id',
								'sort_column'	=> 'products_parts_tree.id',
				  	    'conditions' 	=> 'item_costs.item_type = "MAT" AND products_parts_tree.product_id = '.$_REQUEST['pid']
  	  ));
		$batch = $DB->Find('production_batches', array(
				  			'columns' 		=> 'production_batches.id AS pbid, production_batches.po_no AS po_no, production_batches.po_qty AS po_qty, production_batches.qty AS qty, 
				  												products.product_code AS product_code, production_batches.lot_no AS lot_no, production_batches.infused_stamp AS infused_stamp, 
				  												production_batches.shipment_date AS shipment_date, production_batches.product_id AS product_id', 
				  	    'joins' 			=> 'INNER JOIN products on products.id = production_batches.product_id',				  	    
				  	    'conditions' 	=> 'production_batches.id = '.$_REQUEST['pbid']
  	  ));
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  // echo '<a href="'.$Capabilities->All['add_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['add_product_tree']['name'].'</a>'; 
				  // echo '<a href="'.$Capabilities->All['edit_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['edit_product_tree']['name'].'</a>';
					// echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 

				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" style="width:500px;">
        <h3 class="form-title">Basic Information</h3>
        
        <div>
        	<a href="productions.php">List</a>
        </div>
        
        <div class="field">
          <label class="label">Lot No.:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $batch['lot_no'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">P/O No.:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $batch['po_no'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">P/O Qty:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $batch['po_qty'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Qty:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $batch['qty'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Product:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $batch['product_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Infused / Stamp Date:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $batch['infused_stamp'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Shipment Date:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $batch['shipment_date'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
     </form>
     <br/>     
     <div class="form-container">
     		<h3 class="form-title">Materials</h3>
     		<div class="grid jq-grid">
	      <table id="tbl-order-items" cellspacing="0" cellpadding="0">
	        <thead>
	          <tr>
	            <td width="50px" class="border-right text-center;" style="font-style: italic"><a></a></td>
	            <td class="border-right text-center"><a>Material</a></td>
	            <td width="80px" class="border-right text-center"><a>Unit</a></td>
	            <td width="80px" class="border-right text-center"><a>Qty</a></td>
	            <td width="80px" class="border-right text-center"><a>Total Qty</a></td>
	            <td width="100px" class="border-right text-center"><a>Terminal Details</a></td>
	          </tr>
	        </thead>
	        <tbody>
	        	<?php
								$ctr=1;
								foreach ($product_tree as $material) {
									echo '<tr>';
									echo '<td class="border-right">'.$ctr.'</td>';
									echo '<td class="border-right"><a href="materials-show.php?mid='.$material['mat_id'].'">'.$material['material_code'].'</a></td>';
									echo '<td class="border-right text-center">'.$material['unit'].'</td>';
									echo '<td class="border-right text-right">'.$material['material_qty'].'</td>';
									echo '<td class="border-right text-right">0</td>';
									echo '<td class="border-right text-center"><a href="minventory-show.php">view details</a></td>';
									echo '</tr>';
									$ctr+=1;
								}
							?>
        	</tbody>
				</table>
			</div>
     		
     </div>			
			<br/>
		</div>
	</div>

<?php require('footer.php'); ?>