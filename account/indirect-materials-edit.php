<?php
  /*
   * Module: Indirect Material - Edit 
  */
  $capability_key = 'edit_indirect_material';
  require('header.php');
  
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		if($_POST['action'] == 'edit_indirect_material') {
			$num_of_records1 = $Posts->EditMaterial(array('variables' => $_POST['material'], 'conditions' => 'id='.$_POST['mid']));
			$num_of_records2 = $Posts->EditItemCost(array('variables' => $_POST['item_cost'], 'conditions' => 'item_id='.$_POST['mid']));
			redirect_to($Capabilities->All['show_indirect_material']['url'].'?mid='.$_POST['mid']);				
		} 
		
	  if(isset($_GET['mid'])) {
	  	$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.*', 
					  	    'conditions' 	=> 'materials.id = '.$_GET['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id'
	  	  )
			);	
			$item_costs = $DB->Find('item_costs', array('columns' => 'supplier, unit, currency, cost, transportation_rate', 
	  							'conditions' => 'item_id = '.$_GET['mid'].' AND item_type="MAT"'));  
			
				$address = $DB->Find('location_address_items', array(
						  			'columns' 		=> 'location_address_items.id, location_address_items.address AS add_id, location_addresses.address', 
						  			'joins'				=> 'INNER JOIN location_addresses ON location_addresses.id = location_address_items.address',
						  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.item_id = '.$_GET['mid']
		  	  )
				);
	  }
		
	  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'order' => 'classification'));
		$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'order' => 'brand_model'));
		$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'order' => 'first_name'));	
		$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'order' => 'description'));
		$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'order' => 'name'));
		$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'order' => 'code'));
	  $currencies = $DB->Get('lookups', array('columns' => 'id, code', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'order' => 'code'));
		$terminals = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal', 'conditions' => 'location_id=4 AND type="IN"', 'order' => 'id')); // location_id=4 (WIP)
		$item_images = $DB->Get('item_images', array('columns' => 'item_images.*',
			 																			'conditions' => 'item_id='.$_GET['mid']));	
		$has_inventory = $DB->Find('item_inventories', array('columns' => 'id, item_id', 
	  																							'conditions' => 'item_type="MAT" AND item_id = '.$_GET['mid']));	
																																																
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['show_indirect_material']['url'].'?mid='.$_GET['mid'].'" class="nav">'.$Capabilities->All['show_indirect_material']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">        
				<input type="hidden" name="action" value="edit_indirect_material">
				<input type="hidden" name="mid" value="<?php echo $_GET['mid'] ?>">
				
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field magenta" />
              	<span id="material_codestatus" class="warning"></span>
              </td>
              <td width="150">Base Material Code:</td><td><input type="text" value="N/A" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Barcode:</td><td><input type="text" id="material[bar_code]" name="material[bar_code]" value="<?php echo $materials['bar_code'] ?>" class="text-field" />
              	<span id="bar_codestatus" class="warning"></span>
              </td>
              <td>Model:</td><td><input type="text" value="N/A" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Classification:</td><td><?php select_query_tag($classifications, 'id', 'classification', $materials['material_classification'], 'material[material_classification]', 'material[material_classification]', '', 'width:192px;'); ?></td>
              <td>Status:</td><td><?php select_query_tag($status, 'id', 'description', $materials['status'], 'material[status]', 'material[status]', '', 'width:192px;'); ?></td>
           </tr>    
           <tr>
              <td>Person-in-charge:</td><td><?php select_query_tag($pics, 'id', 'pic', $materials['person_in_charge'], 'material[person_in_charge]', 'material[person_in_charge]', '', 'width:192px;'); ?></td>
              <td>WIP Line Entry:</td><td><?php select_query_tag($terminals, 'id', 'terminal', $materials['production_entry_terminal_id'], 'material[production_entry_terminal_id]', 'material[production_entry_terminal_id]', '', 'width:192px;'); ?></td>
           </tr>      
           <tr>
              <td>Address:</td><td><input type="text"  value="<?php echo $address['address'] ?>" class="text-field" />
          			<?php echo $linkto = ($address['add_id']!='') ? '&nbsp;<a href="locations-edit.php?lid='.$address['add_id'].'">change</a>' : '' ?>
              </td>
              <td></td><td></td>
           </tr>              
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="material[description]" name="material[description]" value="<?php echo $materials['description'] ?>" class="text-field" style="width:645px" />
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
                <?php select_query_tag($suppliers, 'id', 'name', $item_costs['supplier'], 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;'); ?>
              </td>
           </tr>
           <tr>
           		<td width="150">Currency:</td><td width="310"><?php select_query_tag($currencies, 'id', 'code', $item_costs['currency'], 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
           		<td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" value="<?php echo $item_costs['cost'] ?>" class="text-field text-right" /></td>
           </tr>
           <tr>
              <td width="150">Unit:</td><td width="310"><?php select_query_tag($units, 'id', 'description', $item_costs['unit'], 'item_cost[unit]', 'item_cost[unit]', '', 'width:192px;'); ?></td>
              <td>Transportation Rate:</td><td><input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]" value="<?php echo $item_costs['transportation_rate'] ?>" class="text-field text-right" /></td>
           </tr>    
           <tr><td height="5" colspan="99"></td></tr>
        </table>  
				  
			  
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('indirect-materials-show.php?mid='.$_GET['mid']); ?>"/>
         </div>
				</form>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('[name*="material[material_code]"]').keyup(function() {
				$(this).val($(this).val().toUpperCase());
				$('[name*="material[bar_code]"]').val($(this).val());
				is_existing('materials', 'id', '', 'material_code=\"' +$(this).val().toUpperCase()+ '\"', 'material_code');
				return false;    
			});
			$('[name*="material[bar_code]"]').keyup(function() {
				$(this).val($(this).val().toUpperCase());
				is_existing('materials', 'id', '', 'bar_code=\"' +$(this).val().toUpperCase()+ '\"', 'bar_code');
				return false;    
			});
		});
	</script>
<?php }
require('footer.php'); ?>