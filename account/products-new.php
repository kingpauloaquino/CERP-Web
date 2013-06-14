<?php
  /*
   * Module: Product - Add 
  */
  $capability_key = 'add_product';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'add_product') { 
			$id = $Posts->AddProduct($_POST['product']);
			$_POST['item_cost']['supplier'] = 1; // CRESC
			$_POST['item_cost']['item_id'] = $id;
			$Posts->AddItemCost($_POST['item_cost']);
			if(isset($id)){ redirect_to($Capabilities->All['show_product']['url'].'?pid='.$id); }
		} 
		
	  $brands = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'order' => 'brand_model', 'conditions' => 'parent IS NULL'));
	  $packs = $DB->Get('item_classifications', array('columns' => 'id, classification', 'order' => 'classification', 'conditions' => 'item_type = "PRD"'));
		$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'order' => 'description'));
		$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'order' => 'name'));
		$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'order' => 'code'));
	  $currencies = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'order' => 'code'));
	  $statuses = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"'));
		$series = $DB->Get('product_series', array('columns' => 'id, series', 'order' => 'series'));
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="add_product">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="PRD">
				
        <h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Product Code:</td><td width="310"><input type="text" id="product[product_code]" name="product[product_code]" class="text-field magenta" />
              	<span id="product_codestatus" class="warning"></span>
              </td>
              <td width="150">Brand:</td><td><?php select_query_tag($brands, 'id', 'brand_model', '', 'product[brand_model]', 'product[brand_model]', '', 'width:192px;'); ?>
              </td>
           </tr>
           <tr>
              <td>Barcode:</td><td><input type="text" id="product[bar_code]" name="product[bar_code]" class="text-field" />
              	<span id="bar_codestatus" class="warning"></span>
              </td>
              <td>Series:</td><td><?php select_query_tag($series, 'id', 'series', '', 'product[series]', 'product[series]', '', 'width:192px;'); ?></td>
           </tr>    
           <tr>
              <td>Pack:</td><td><?php select_query_tag($packs, 'id', 'classification', '', 'product[product_classification]', 'product[product_classification]', '', 'width:192px;'); ?></td>
              <td>Color:</td><td><input type="text" id="product[color]" name="product[color]" value="<?php echo $products['color'] ?>" class="text-field" /></td>
           </tr>         
           <tr>
              <td>Production CP:</td><td><input type="text" id="product[prod_cp]" name="product[prod_cp]" class="text-field text-right"/></td>
              <td>Priority:</td><td><?php select_tag(array(0 => 'Low', 1 => 'High'), '', 'product[priority]', 'product[priority]', '', 'width:192px;') ?></td>
           </tr>     
           <tr>
           		<td>Status:</td><td><?php select_query_tag($statuses, 'id', 'description', '', 'product[status]', 'product[status]', '', 'width:192px;'); ?></td>
           		<td></td><td></td>
           </tr>      
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="product[description]" name="product[description]" class="text-field" style="width:645px" />
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
                <?php select_query_tag($suppliers, 'id', 'name', '1', 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;', TRUE); ?>
              </td>
           </tr>
           <tr>
              <td width="150">Currency:</td><td><?php select_query_tag($currencies, 'id', 'description', '24', 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
              <td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" class="text-field text-right" /></td>
           </tr>
           <tr>
              <td width="150">Unit:</td><td width="310"><?php select_query_tag($units, 'id', 'description', '', 'item_cost[unit]', 'item_cost[unit]', '', 'width:192px;'); ?></td>
              <td></td>
           </tr>    
           <tr><td height="5" colspan="99"></td></tr>
        </table>       
            
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Create" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('products.php'); ?>"/>
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