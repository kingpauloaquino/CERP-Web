<?php
  /*
   * Module: Material - New 
  */
  $capability_key = 'add_material';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);
	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$classifications = $Query->get_lookups('mat_classifications');
		$status = $Query->get_lookups('item_status');
		$models = $Query->get_lookups('models');
		$pics = $Query->get_lookups('users');
		$suppliers = $Query->get_lookups('suppliers');
		$terminals = $Query->get_lookups('terminals');
		$units = $Query->get_lookups('uoms');
		$types = $Query->get_lookups('material_types');
		$currencies = $Query->get_lookups('currencies');
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
				<input type="hidden" name="action" value="add_material">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				<input type="hidden" id="material[material_type]" name="material[material_type]" value="70" />
        
        <div class="form-container">
	        <h3 class="form-title">Details</h3>
	        <table>
	           <tr>
	              <td width="150">Material Code:</td><td width="310"><input type="text" id="mat-code" name="material[material_code]" class="text-field magenta" autocomplete="off" notice="material_codestatus" required />
	              	<span id="material_codestatus" class="warning"></span>
	              </td>
	              <td width="150"></td><td></td>
	           </tr>
	           <tr>
	              <td>Barcode:</td><td><input type="text" id="mat-barcode" name="material[bar_code]" class="text-field" autocomplete="false" notice="barcodestatus" required />
	              	<span id="barcodestatus" class="warning"></span>
	              </td>
	              <td>Model:</td><td><?php select_query_tag($models, 'id', 'brand_model', '', 'material[brand_model]', 'material[brand_model]', '', 'width:192px;'); ?></td>
	           </tr>
	           <tr>
	           		<td>Classification:</td><td><?php select_query_tag($classifications, 'id', 'classification', '', 'material[material_classification]', 'material[material_classification]', '', 'width:192px;'); ?></td>
	              <td>Status:</td><td><?php select_query_tag($status, 'id', 'description', '', 'material[status]', 'material[status]', '', 'width:192px;'); ?></td>
	           </tr>    
	           <tr>              
	              <td>Person-in-charge:</td><td><?php select_query_tag($pics, 'id', 'pic', '', 'material[person_in_charge]', 'material[person_in_charge]', '', 'width:192px;'); ?></td>
	              <td>WIP Line Entry:</td><td><?php select_query_tag($terminals, 'id', 'terminal', '', 'material[production_entry_terminal_id]', 'material[production_entry_terminal_id]', '', 'width:192px;'); ?></td>
	           </tr>     
	           <tr>
	              <td>Address:</td><td><input type="text"  class="text-field" />
	              </td>
	              <td>Unit:</td><td><?php select_query_tag($units, 'id', 'description', 19, 'material[unit]', 'material[unit]', '', 'width:192px;'); ?></td>
	           </tr>   
	           <tr>
	              <td>Defect Rate %:</td><td><input id="material[defect_rate]" name="material[defect_rate]" type="text"  class="text-field text-right decimal"/>
	              <td>Sorting %:</td><td><input type="text" id="materials[sorting_percentage]" name="materials[sorting_percentage]" class="text-field text-right decimal" /></td>
	           </tr>   
	           <tr>
	              <td>Min. Stock Qty.:</td><td><input id="msq" name="material[msq]" type="text" class="text-field text-right decimal" required/></td>
	              <td></td><td></td>
	           </tr>            
	           <tr>
	              <td>Description:</td>
	              <td colspan="99">
	                <input type="text" id="mat-description" name="material[description]" class="text-field" style="width:645px" />
	              </td>
	           </tr>          
	           <!-- <tr>
	              <td>Keywords:</td>
	              <td colspan="99">
	                <input type="text" id="material[tags]" name="material[tags]" class="text-field" style="width:645px" />
	              </td>
	           </tr> -->
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
	                <?php select_query_tag($suppliers, 'id', 'supplier_name', '', 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;'); ?>
	              </td>
	           </tr>
	           <tr>
	           		<td width="150">Currency:</td><td width="310"><?php select_query_tag($currencies, 'id', 'description', '24', 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
	           		<td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" class="text-field text-right decimal" required/></td>
	           </tr>
	           <tr>
	              <td width="150">Transportation Rate %:</td><td width="310"><input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]" class="text-field text-right decimal" /></td>
	              <td>MOQ:</td><td><input type="text" id="item_cost[moq]" name="item_cost[moq]" class="text-field text-right numeric" required/></td>
	           </tr>    
	           <tr><td height="5" colspan="99"></td></tr>
	        </table>	
      	</div>
            
				<div class="field-command">
					<div class="text-post-status"></div>
					<input id="submit-btn" type="submit" value="Create" class="btn" disabled/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('materials.php'); ?>"/>
				</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			
			$('#mat-code').keyup(function() {
				($(this).is_existing('materials', 'id', '', 'material_code="' +$(this).val()+ '"', 'material_code')) 
					? $('#submit-btn').attr('disabled', true)
					: $('#submit-btn').attr('disabled', false);
				$('#mat-barcode').val($(this).val());
			});
			
			$('#mat-barcode').keyup(function() {
				($(this).is_existing('materials', 'id', '', 'bar_code="' +$(this).val()+ '"', 'bar_code')) 
					? $('#submit-btn').attr('disabled', true)
					: $('#submit-btn').attr('disabled', false);
			});
			
		});
	</script>
<?php }
require('footer.php'); ?>