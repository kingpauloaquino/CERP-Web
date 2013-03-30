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
  
	if($_POST['action'] == 'add_material') {
		$_POST['material']['base'] = TRUE;
		$_POST['material']['parent'] = NULL;
		$base_id = $Posts->AddMaterial($_POST['material']);
		
		$_POST['material']['base'] = FALSE;
		$_POST['material']['parent'] = $base_id;
		$_POST['material']['material_code'] = $_POST['material']['material_code'].'-1';
		$id = $Posts->AddMaterial($_POST['material']);
				
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		if(isset($id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$id); }
	} 
	
  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'sort_column' => 'classification'));
	$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'sort_column' => 'brand_model'));
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'sort_column' => 'first_name'));	
	$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'sort_column' => 'description'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'sort_column' => 'name'));
	$terminals = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal', 'conditions' => 'location_id=4 AND type="IN"', 'sort_column' => 'id')); // location_id=4 (WIP)
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'sort_column' => 'code'));
	$types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('material_type').'"', 'sort_column' => 'code'));
  $currencies = $DB->Get('lookups', array('columns' => 'id, code', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'sort_column' => 'code'));
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">				
				<input type="hidden" name="action" value="add_material">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				<input type="hidden" id="material[material_type]" name="material[material_type]" value="70" />
        
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Material Code:</td><td width="310"><input type="text" id="material[material_code]" name="material[material_code]" class="text-field" /></td>
              <td width="150"></td>
           </tr>
           <tr>
              <td>Barcode:</td><td><input type="text" id="material[bar_code]" name="material[bar_code]" class="text-field" /></td>
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
              <td></td><td></td>
           </tr>              
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="material[description]" name="material[description]" class="text-field" style="width:645px" />
              </td>
           </tr>           
           <tr>
              <td>Keywords:</td>
              <td colspan="99">
                <input type="text" id="material[tags]" name="material[tags]" class="text-field" style="width:645px" />
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
                <?php select_query_tag($suppliers, 'id', 'name', '', 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;'); ?>
              </td>
           </tr>
           <tr>
           		<td width="150">Currency:</td><td width="310"><?php select_query_tag($currencies, 'id', 'code', '24', 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
           		<td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" class="text-field text-right" /></td>
           </tr>
           <tr>
              <td width="150">Unit:</td><td width="310"><?php select_query_tag($units, 'id', 'description', '', 'item_cost[unit]', 'item_cost[unit]', '', 'width:192px;'); ?></td>
              <td>Transportation Rate:</td><td><input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]" class="text-field text-right" /></td>
           </tr>    
           <tr><td height="5" colspan="99"></td></tr>
        </table>   
            
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Create" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('materials.php'); ?>"/>
         </div>
         
				</form>
			</div>
		</div>
	</div>

<?php }
require('footer.php'); ?>