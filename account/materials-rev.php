<?php
  /*
   * Module: Material Revision
  */
  $capability_key = 'add_material_revision';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);
	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
  
	if($_GET['mid']) {
		$last_rev = $DB->Find('material_revisions', array(
					  			'columns' 		=> 'MAX(revision) AS revision', 
					  	    'conditions' 	=> 'base_material_id = '.$_GET['mid'] ));
		$new_rev = "A";									
		if($last_rev['revision']) {
			$new_rev = chr(ord($last_rev['revision']) + 1); // increment Revision suffix
		}
		
		$material = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.*, lookups.description AS unit',
					  			'joins' => 'INNER JOIN lookups ON lookups.id = materials.unit', 
					  	    'conditions' 	=> 'materials.id = '.$_GET['mid'] ));
	}
		
	
  $classifications = $Query->get_lookups('mat_classifications');
	$status = $Query->get_lookups('item_status');
	$models = $Query->get_lookups('models');
	$pics = $Query->get_lookups('users');
	$suppliers = $Query->get_lookups('suppliers');
	$terminals = $Query->get_lookups('terminals');
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
				<input type="hidden" name="action" value="add_material_rev">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				<input type="hidden" id="material[material_type]" name="material[material_type]" value="70" />
				<input type="hidden" id="material[parent]" name="material[parent]" value="<?php echo $_GET['mid'] ?>" />
				<input type="hidden" id="rev[revision]" name="rev[revision]" value="<?php echo $new_rev ?>" />
        
        <div class="form-container">
	        <h3 class="form-title">Details</h3>
	        <table>
	           <tr>
	              <td width="150">Material Code:</td><td width="310"><input type="text" id="material[material_code]" name="material[material_code]" value="<?php echo $material['material_code']. $new_rev; ?>" class="text-field magenta" readonly/></td>
	              <td width="150">Base Material Code:</td><td><input type="text" value="<?php echo $material['material_code'] ?>" class="text-field" disabled/>
	           </tr>
	           <tr>
	              <td>Barcode:</td><td><input type="text" id="mat-barcode" name="material[bar_code]" class="text-field" value="<?php echo $material['material_code']. $new_rev; ?>" autocomplete="false" notice="barcodestatus" required />
	              	<span id="barcodestatus" class="warning"></span>
	              </td>
	              <td>Model:</td><td><?php select_query_tag($models, 'id', 'brand_model', $material['brand_model'], 'material[brand_model]', 'material[brand_model]', '', 'width:192px;'); ?></td>
	           </tr>
	           <tr>
	           		<td>Classification:</td><td><?php select_query_tag($classifications, 'id', 'classification', $material['material_classification'], 'material[material_classification]', 'material[material_classification]', '', 'width:192px;'); ?></td>
	              <td>Status:</td><td><?php select_query_tag($status, 'id', 'description', $material['status'], 'material[status]', 'material[status]', '', 'width:192px;'); ?></td>
	           </tr>    
	           <tr>              
	              <td>Person-in-charge:</td><td><?php select_query_tag($pics, 'id', 'pic', $material['person_in_charge'], 'material[person_in_charge]', 'material[person_in_charge]', '', 'width:192px;'); ?></td>
	              <td>WIP Line Entry:</td><td><?php select_query_tag($terminals, 'id', 'terminal', $material['production_entry_terminal_id'], 'material[production_entry_terminal_id]', 'material[production_entry_terminal_id]', '', 'width:192px;'); ?></td>
	           </tr>     
	           <tr>
	              <td>Address:</td><td><input type="text"  class="text-field" /></td>
	              <td>Unit:</td><td><input type="text" value="<?php echo $material['unit'] ?>" class="text-field text-right numeric" readonly/></td>
	           </tr>   
	           <tr>
	              <td>Defect Rate %:</td><td><input id="material[defect_rate]" name="material[defect_rate]" type="text"  class="text-field text-right decimal"  />
	           		<td>Sorting %:</td><td><input type="text" id="material[sorting_percentage]" name="material[sorting_percentage]" class="text-field text-right decimal" /></td>
	           </tr>    
	           <tr>
	              <td>Min. Stock Qty.:</td><td><input type="text" name="material[msq]" value="<?php echo $material['msq'] ?>" class="text-field text-right numeric" required/></td>
	              <td></td><td></td>
	           </tr>          
	           <tr>
	              <td>Description:</td>
	              <td colspan="99">
	                <input type="text" id="material[description]" name="material[description]" value="<?php echo $material['description'] ?>" class="text-field" style="width:645px" />
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
	                <?php select_query_tag($suppliers, 'id', 'supplier_name', '', 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;'); ?>
	              </td>
	           </tr>
	           <tr>
	           		<td width="150">Currency:</td><td width="310"><?php select_query_tag($currencies, 'id', 'description', '24', 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
	           		<td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" class="text-field text-right decimal" required/></td>
	           </tr>
	           <tr>
	              <td>Transportation Rate:</td><td><input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]" class="text-field text-right decimal" /></td>
	              <td>MOQ:</td><td><input type="text" id="item_cost[moq]" name="item_cost[moq]" class="text-field text-right numeric" required/></td>
	           </tr>     
	           <tr><td height="5" colspan="99"></td></tr>
	        </table> 	
      	</div>
      	
				<div class="field-command">
					<div class="text-post-status"></div>
					<input id="submit-btn" type="submit" value="Create" class="btn"/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('materials-show.php?mid='.$_GET['mid']); ?>"/>
				</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			
			$('#mat-barcode').keyup(function() {
				($(this).is_existing('materials', 'id', '', 'bar_code="' +$(this).val()+ '"', 'bar_code')) 
					? $('#submit-btn').attr('disabled', true)
					: $('#submit-btn').attr('disabled', false);
			});
			
		});
	</script>
<?php }
require('footer.php'); ?>