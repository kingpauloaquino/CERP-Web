<?php
  /*
   * Module: Product - Edit 
  */
  $capability_key = 'edit_product';
  require('header.php');
  
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'edit_product') {
			$args = array('variables' => $_POST['product'], 'conditions' => 'id='.$_POST['pid']); 
			$num_of_records = $Posts->EditProduct($args);
			
			$num_of_records2 = $Posts->EditItemCost(array('variables' => $_POST['item_cost'], 'conditions' => 'id='.$_POST['item_cost_id']));
			redirect_to($Capabilities->All['show_product']['url'].'?pid='.$_POST['pid']);		
		} 
		
	  if(isset($_GET['pid'])) {
	  	$products = $DB->Find('products', array(
					  			'columns' 		=> 'products.product_code, products.description, brand_models.id AS brand, pack_qty, 
					  												products.bar_code, products.color, products.prod_cp, products.series', 
					  	    'conditions' 	=> 'products.id = '.$_GET['pid'], 
					  	    'joins' 			=> 'INNER JOIN brand_models ON products.brand_model = brand_models.id
					  	    									LEFT OUTER JOIN item_classifications ON item_classifications.id = products.product_classification'
	  	  )
			);	
			$item_costs = $DB->Find('item_costs', array('columns' => 'id, supplier, unit, currency, cost', 
	  							'conditions' => 'item_id = '.$_GET['pid'].' AND item_type="PRD"'));  
			$item_images = $DB->Get('item_images', array('columns' => 'item_images.*',
			 																			'conditions' => 'item_id='.$_GET['pid']));	
	  }
	  $brands = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'order' => 'brand_model', 'conditions' => 'parent IS NULL'));
	  //$packs = $DB->Get('item_classifications', array('columns' => 'id, classification', 'order' => 'classification', 'conditions' => 'item_type = "PRD"'));
		$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'order' => 'name'));
		$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'order' => 'code'));
	  $currencies = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'order' => 'code'));
	  $statuses = $DB->Get('lookup_status', array('columns' => 'id, description', 'conditions'  => 'parent = "ITEM"'));
		$has_inventory = $DB->Find('item_inventories', array('columns' => 'id, item_id', 'conditions' => 'item_type="PRD" AND item_id = '.$_GET['pid']));	
		$series = $DB->Get('product_series', array('columns' => 'id, series', 'order' => 'series'));
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_GET['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>';
					
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" id="product-form" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<input type="hidden" name="action" value="edit_product">
				<input type="hidden" name="pid" value="<?php echo $_GET['pid'] ?>">
				<input type="hidden" id="item_cost_id" name="item_cost_id" value="<?php echo $item_costs['id'] ?>">
        
        <h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Product Code:</td><td width="310"><input type="text" id="product[product_code]" name="product[product_code]" value="<?php echo $products['product_code'] ?>" class="text-field magenta"/>
              	<span id="product_codestatus" class="warning"></span>
              </td>
              <td width="150">Brand:</td><td><?php select_query_tag($brands, 'id', 'brand_model', $products['brand'], 'product[brand_model]', 'product[brand_model]', '', 'width:192px;'); ?>
              </td>
           </tr>
           <tr>
              <td>Barcode:</td><td><input type="text" id="product[bar_code]" name="product[bar_code]" value="<?php echo $products['bar_code'] ?>" class="text-field" />
              	<span id="bar_codestatus" class="warning"></span>
              </td>
              <td>Series:</td><td><?php select_query_tag($series, 'id', 'series', $products['series'], 'product[series]', 'product[series]', '', 'width:192px;'); ?></td>
           </tr>    
           <tr>
              <td>Pack Qty:</td><td><input type="text" id="product[pack_qty]" name="product[pack_qty]" value="<?php echo $products['pack_qty'] ?>" class="text-field text-right" /></td>
              <td>Color:</td><td><input type="text" id="product[color]" name="product[color]" value="<?php echo $products['color'] ?>" class="text-field" /></td>
           </tr>        
           <tr>
              <td>Production CP:</td><td><input type="text" id="product[prod_cp]" name="product[prod_cp]" value="<?php echo $products['prod_cp'] ?>" class="text-field text-right"/></td>
              <td>Priority:</td><td><?php select_tag(array(0 => 'Low', 1 => 'High'), $products['priority'], 'product[priority]', 'product[priority]', '', 'width:192px;') ?></td>
           </tr>     
           <tr>
              <td>Status:</td><td><?php select_query_tag($statuses, 'id', 'description', $products['status'], 'product[status]', 'product[status]', '', 'width:192px;'); ?></td>
              <td></td><td></td>
           </tr>            
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="product[description]" name="product[description]" value="<?php echo $products['description'] ?>" class="text-field" style="width:645px" />
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
                <?php select_query_tag($suppliers, 'id', 'name', $item_costs['supplier'], 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;', TRUE); ?>
              </td>
           </tr>
           <tr>
              <td width="150">Currency:</td><td><?php select_query_tag($currencies, 'id', 'description', $item_costs['currency'], 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
              <td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" value="<?php echo $item_costs['cost'] ?>" class="text-field text-right" /></td>
           </tr>
           <tr>
              <td width="150">Unit:</td><td width="310"><?php select_query_tag($units, 'id', 'description', $item_costs['unit'], 'item_cost[unit]', 'item_cost[unit]', '', 'width:192px;'); ?></td>
              <td></td>
           </tr>    
           <tr><td height="5" colspan="99"></td></tr>
        </table>       
        
            
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('products-show.php?pid='.$_GET['pid']); ?>"/>
         </div>
			</form>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('[name*="product[product_code]"]').keyup(function() {
				$(this).val($(this).val().toUpperCase());
				$('[name*="product[bar_code]"]').val($(this).val());
				is_existing('products', 'id', '', 'product_code=\"' +$(this).val().toUpperCase()+ '\"', 'product_code');
				return false;    
			});
			$('[name*="product[bar_code]"]').keyup(function() {
				$(this).val($(this).val().toUpperCase());
				is_existing('products', 'id', '', 'bar_code=\"' +$(this).val().toUpperCase()+ '\"', 'bar_code');
				return false;    
			});
		});
	</script>
<?php }
require('footer.php'); ?>