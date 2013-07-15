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
		
		$classifications = $Query->get_lookups('prd_classifications');
		$status = $Query->get_lookups('item_status');
		$brands = $Query->get_lookups('brands');
		$suppliers = $Query->get_lookups('suppliers');
		$currencies = $Query->get_lookups('currencies');
		$units = $Query->get_lookups('uoms');
		$series = $Query->get_lookups('series');
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form method="POST">
				<input type="hidden" name="action" value="add_product">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="PRD">
				
				<div class="form-container">
					<h3 class="form-title">Details</h3>
	        <table>
	           <tr>
	              <td width="150">Product Code:</td><td width="310"><input type="text" id="prd-code" name="product[product_code]" class="text-field magenta" autocomplete="off" notice="product_codestatus" required />
	              	<span id="product_codestatus" class="warning"></span>
	              </td>
	              <td width="150">Brand:</td><td><?php select_query_tag($brands, 'id', 'brand_model', '', 'product[brand_model]', 'product[brand_model]', '', 'width:192px;'); ?>
	           </tr>
	           <tr>
	              <td>Barcode:</td><td><input type="text" id="prd-barcode" name="product[bar_code]" class="text-field" autocomplete="false" notice="barcodestatus" required />
	              	<span id="barcodestatus" class="warning"></span>
	              </td>
	              <td>Series:</td><td><?php select_query_tag($series, 'id', 'series', '', 'product[series]', 'product[series]', '', 'width:192px;'); ?></td>
	           </tr>   
	           <tr>
	              <!-- <td>Pack:</td><td><?php select_query_tag($packs, 'id', 'classification', '', 'product[product_classification]', 'product[product_classification]', '', 'width:192px;'); ?></td> -->
	              <td>Pack Qty:</td><td><input type="text" id="product[pack_qty]" name="product[pack_qty]" class="text-field text-right numeric" value="1" required /></td>
	              <td>Color:</td><td><input type="text" id="product[color]" name="product[color]" class="text-field" /></td>
	           </tr>         
	           <tr>
	              <td>Production CP:</td><td><input type="text" id="product[prod_cp]" name="product[prod_cp]" class="text-field text-right numeric " required/></td>
	              <td>Priority:</td><td><?php select_tag(array(0 => 'Low', 1 => 'High'), '', 'product[priority]', 'product[priority]', '', 'width:192px;') ?></td>
	           </tr>     
	           <tr>
	           		<td>Status:</td><td><?php select_query_tag($status, 'id', 'description', '', 'product[status]', 'product[status]', '', 'width:192px;'); ?></td>
	           		<td>Unit:</td><td><?php select_query_tag($units, 'id', 'description', 19, 'product[unit]', 'product[unit]', '', 'width:192px;'); ?></td>
	           </tr>      
	           <tr>
	              <td>Description:</td>
	              <td colspan="99">
	                <input type="text" id="product[description]" name="product[description]" class="text-field" style="width:645px" />
	              </td>
	           </tr>
	           <tr><td height="5" colspan="99"></td></tr>
	        </table>	
				</div>
        
        <br/>
        <div class="form-container">
					<h3 class="form-title">Purchase Information</h3>
	        <table>            
	           <tr>
	              <td width="150">Supplier:</td>
	              <td colspan="99">
	                <?php select_query_tag($suppliers, 'id', 'supplier_name', '1', 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;', TRUE); ?>
	              </td>
	           </tr>
	           <tr>
	              <td width="150">Currency:</td><td width="310"><?php select_query_tag($currencies, 'id', 'description', '24', 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
	              <td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" class="text-field text-right decimal" required /></td>
	           </tr>
	           <tr><td height="5" colspan="99"></td></tr>
	        </table> 	
				</div>
              
			<div class="field-command">
				<div class="text-post-status"></div>
				<input id="submit-btn" type="submit" value="Create" class="btn" disabled/>
				<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('products.php'); ?>"/>
			</div>

			</form>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#prd-code').keyup(function() {
				($(this).is_existing('products', 'id', '', 'product_code="' +$(this).val()+ '"', 'product_code')) 
					? $('#submit-btn').attr('disabled', true)
					: $('#submit-btn').attr('disabled', false);
				$('#prd-barcode').val($(this).val());
			});
			
			$('#prd-barcode').keyup(function() {
				($(this).is_existing('products', 'id', '', 'bar_code="' +$(this).val()+ '"', 'bar_code')) 
					? $('#submit-btn').attr('disabled', true)
					: $('#submit-btn').attr('disabled', false);
			});
		});
	</script>
<?php }
require('footer.php'); ?>