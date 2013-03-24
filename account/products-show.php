<?php
  /*
   * Module: Products - Show 
  */
  $capability_key = 'show_product';
  require('header.php');
  
  if(isset($_REQUEST['pid'])) {
  	$products = $DB->Find('products', array(
				  			'columns' 		=> 'products.product_code, products.description, brand_models.brand_model AS brand, lookups3.description AS status, item_classifications.classification,
				  												products.bar_code, products.color,  
				  												suppliers.id AS sup_id, suppliers.name AS supplier, lookups1.description AS unit, lookups2.code AS currency, item_costs.cost', 
				  	    'conditions' 	=> 'item_costs.item_type="PRD" AND products.id = '.$_REQUEST['pid'], 
				  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON products.brand_model = brand_models.id
																	LEFT OUTER JOIN item_costs ON products.id = item_costs.item_id
																	LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
																	LEFT OUTER JOIN lookups AS lookups1 ON item_costs.unit = lookups1.id
																	LEFT OUTER JOIN lookups AS lookups2 ON item_costs.currency = lookups2.id
																	LEFT OUTER JOIN lookups AS lookups3 ON products.status = lookups3.id
																	LEFT OUTER JOIN item_classifications ON item_classifications.id = products.product_classification'
  	  )
		);
		$item_images = $DB->Get('item_images', array('columns' => 'item_images.*',
		 																			'conditions' => 'item_id='.$_REQUEST['pid']));	
		$has_inventory = $DB->Find('item_inventories', array('columns' => 'id, item_id', 
  																							'conditions' => 'item_type="PRD" AND item_id = '.$_REQUEST['pid']));	
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['products']['url'].'" class="nav">'.$Capabilities->All['products']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_product']['url'].'" class="nav">'.$Capabilities->All['add_product']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_product']['url'].'?pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['edit_product']['name'].'</a>'; 
					
					echo '<a href="'.$Capabilities->All['show_parts_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$products['product_code'].'" class="nav">'.$Capabilities->All['show_product_tree']['name'].'</a>'; 
					
					echo '<a href="'.$Capabilities->All['show_product_inventory']['url'].'?id='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product_inventory']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Product Code:</td><td width="310"><input type="text" value="<?php echo $products['product_code'] ?>" class="text-field" disabled/></td>
              <td width="150">Brand:</td><td><input type="text" value="<?php echo $products['brand'] ?>" class="text-field" disabled/>
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
				<h3 class="form-title">Purchase Information</h3>
        <table>
           <tr>
              <td width="150">Supplier:</td>
              <td colspan="99">
              	<input type="text" value="<?php echo $products['supplier'] ?>" class="text-field" style="width:645px" disabled/>
              	<?php echo $linkto = ($products['supplier']!='') ? link_to('suppliers-show.php?sid='.$products['sup_id']) : '' ?>
              </td>
           </tr>
           <tr>
              <td width="150">Currency:</td><td><input type="text" value="<?php echo $products['currency'] ?>" class="text-field" disabled/></td>
              <td width="150">Cost:</td><td><input type="text" value="<?php echo $products['cost'] ?>" class="text-field  text-right" disabled/></td>
           </tr>
           <tr>
              <td width="150">Unit:</td><td width="310"><input type="text" value="<?php echo $products['unit'] ?>" class="text-field" disabled/></td>
              <td></td>
           </tr>    
           <tr><td height="5" colspan="99"></td></tr>
        </table>
      </form>
		</div>
	</div>

<?php require('footer.php'); ?>