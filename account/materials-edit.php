<?php
  /*
   * Module: Material - Edit 
  */
  $capability_key = 'edit_material';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);
	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	if($_POST['action'] == 'edit_material') {
		$_POST['material']['defect_rate'] = $_POST['material']['defect_rate'] / 100;
		$_POST['material']['sorting_percentage'] = $_POST['material']['sorting_percentage'] / 100;
		$num_of_records1 = $Posts->EditMaterial(array('variables' => $_POST['material'], 'conditions' => 'id='.$_POST['mid']));
		
		echo '<br/><br/>';
		foreach($_POST['item_cost'] as $cost) {
			$Posts->EditItemCost(array('variables' => $cost, 'conditions' => 'item_type= "MAT" AND id='.$cost['id']));
		}
		redirect_to($Capabilities->All['show_material']['url'].'?mid='.$_POST['mid']);		
	} 
	
  if(isset($_GET['mid'])) {
  	$materials = $DB->Find('materials', array(
				  			'columns' 		=> 'materials.*', 
				  	    'conditions' 	=> 'materials.id = '.$_GET['mid'] ));	
								
		$item_costs = $DB->Find('item_costs', array('columns' => 'supplier, unit, currency, cost, transportation_rate', 
  							'conditions' => 'item_id = '.$_GET['mid'].' AND item_type="MAT"'));  
		
		$address = $DB->Find('location_address_items', array(
				  			'columns' 		=> 'location_address_items.id, location_address_items.address AS add_id, location_addresses.address', 
				  			'joins'				=> 'INNER JOIN location_addresses ON location_addresses.id = location_address_items.address',
				  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.item_id = '.$_GET['mid'] ));
								
		if($materials['base']) {
			$revisions = $DB->Get('material_revisions', array('columns' => 'materials.id, materials.material_code', 
	 																				'joins' => 'INNER JOIN materials ON materials.id = material_revisions.material_id',
		 																			'conditions' => 'base_material_id = '.$materials['id']));
			$base_code = 'N/A';																
		} else {
			$base = $DB->Find('materials', array(
				  			'columns' 		=> 'id, material_code', 
				  	    'conditions' 	=> 'id = '.$materials['parent']));
			$base_id = $base['id']; 
			$base_code = $base['material_code'];
		}	
  }
	
  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'order' => 'classification'));
	$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'order' => 'brand_model'));
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'order' => 'first_name'));	
	$status = $DB->Get('lookup_status', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'order' => 'description'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'order' => 'name'));
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'order' => 'code'));
	$types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('material_type').'"', 'order' => 'code'));
  $currencies = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'order' => 'code'));
	$terminals = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal', 'conditions' => 'location_id=4 AND type="IN"', 'order' => 'id')); // location_id=4 (WIP)
	$has_inventory = $DB->Find('item_inventories', array('columns' => 'id, item_id', 'conditions' => 'item_type="MAT" AND item_id = '.$_GET['mid']));																									
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['show_material']['url'].'?mid='.$_GET['mid'].'" class="nav">'.$Capabilities->All['show_material']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<input type="hidden" name="action" value="edit_material">
				<input type="hidden" name="mid" value="<?php echo $_GET['mid'] ?>">
				<input type="hidden" id="material[material_type]" name="material[material_type]" value="70" />
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field magenta"/>
              	<span id="material_codestatus" class="warning"></span>
              </td>
              <td width="150">Base Material Code:</td><td><input type="text" value="<?php echo $base_code ?>" class="text-field" disabled/>
              	<?php echo $linkto = (isset($base_id)) ? link_to('materials-show.php?mid='.$base_id) : '' ?>
              </td>
           </tr>
           <tr>
              <td>Barcode:</td><td><input type="text" id="material[bar_code]" name="material[bar_code]" value="<?php echo $materials['bar_code'] ?>" class="text-field" />
              	<span id="bar_codestatus" class="warning"></span>
              </td>
              <td>Model:</td><td><?php select_query_tag($models, 'id', 'brand_model', $materials['brand_model'], 'material[brand_model]', 'material[brand_model]', '', 'width:192px;'); ?></td>
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
              <td>Defect Rate %:</td><td><input id="material[defect_rate]" name="material[defect_rate]" type="text" value="<?php echo ($materials['defect_rate'] * 100) ?>" class="text-field text-right"/></td>
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
        	<?php
        		$costs = $DB->Get('materials', array('columns' => 'item_costs.*', 
		 																				'joins' => 'INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
																												INNER JOIN suppliers ON suppliers.id = item_costs.supplier
																												INNER JOIN lookups AS lookups1 ON lookups1.id = item_costs.unit
																												INNER JOIN lookups AS lookups2 ON lookups2.id = item_costs.currency',
			 																			'conditions' => 'materials.id = '.$_GET['mid']));
						foreach($costs as $cost) {
						?>
							<tr>
	              <td width="150">Supplier:</td>
	              <td colspan="99">
	              	<?php select_query_tag($suppliers, 'id', 'name', $cost['supplier'], 'item_cost['.$cost['id'].'][supplier]', 'item_cost['.$cost['id'].'][supplier]', '', 'width:655px;'); ?>
	              	<input type="hidden" id="<?php echo 'item_cost['.$cost['id'].'][id]' ?>" name="<?php echo 'item_cost['.$cost['id'].'][id]' ?>" value="<?php echo $cost['id'] ?>"  />
	              </td>
	           </tr>
	           <tr>
	              <td width="150">Currency:</td><td width="310"><?php select_query_tag($currencies, 'id', 'description', $cost['currency'], 'item_cost['.$cost['id'].'][currency]', 'item_cost['.$cost['id'].'][currency]', '', 'width:192px;'); ?></td>
	              <td width="150">Cost:</td><td><input type="text" id="<?php echo 'item_cost['.$cost['id'].'][cost]' ?>" name="<?php echo 'item_cost['.$cost['id'].'][cost]' ?>" value="<?php echo $cost['cost'] ?>" class="text-field text-right" /></td>
	           </tr>
	           <tr>
	              <td width="150">Unit:</td><td width="310"><?php select_query_tag($units, 'id', 'description', $cost['unit'], 'item_cost['.$cost['id'].'][unit]', 'item_cost['.$cost['id'].'][unit]', '', 'width:192px;'); ?></td>
	              <td>MOQ:</td><td><input type="text" id="<?php echo 'item_cost['.$cost['id'].'][moq]' ?>" name="<?php echo 'item_cost['.$cost['id'].'][moq]' ?>" value="<?php echo $cost['moq'] ?>" class="text-field text-right" /></td>
	           </tr>    
	           <tr>
	              <td width="150">Transportation Rate:</td><td width="310"><input type="text" id="<?php echo 'item_cost['.$cost['id'].'][transportation_rate]' ?>" name="<?php echo 'item_cost['.$cost['id'].'][transportation_rate]' ?>" value="<?php echo $cost['transportation_rate'] ?>" class="text-field text-right" /></td>
	              <td>Sorting %</td><td><input id="material[defect_rate]" name="material[sorting_percentage]" type="text" value="<?php echo ($materials['sorting_percentage'] * 100) ?>" class="text-field text-right"/></td>
	           </tr>   
	           <tr><td height="5" colspan="99"></td></tr>
						<?php
						}
        	?>           
        </table>
            
         <div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('materials-show.php?mid='.$_GET['mid']); ?>"/>
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