<?php
  /*
   * Module: Material - New Supplier
  */
  $capability_key = 'add_material_supplier';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);
	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		if(isset($_GET['mid'])) {
			
			$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS mid, materials.parent, materials.material_code, materials.bar_code, materials.description, brand_models.brand_model, msq, lookups1.description AS unit,
																  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic, materials.defect_rate, materials.sorting_percentage,																  	
																  	lookups3.description AS material_type, lookup_status.description AS status, terminals.id AS tid, CONCAT(terminals.terminal_code," - ", terminals.terminal_name) AS terminal,
																  	location_addresses.address, materials.address AS address_id', 
					  	    'conditions' 	=> 'materials.id = '.$_GET['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id 
																		LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																		LEFT OUTER JOIN lookups AS lookups1 ON materials.unit = lookups1.id
																		LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																		LEFT OUTER JOIN lookup_status ON materials.status = lookup_status.id
																		LEFT OUTER JOIN terminals ON terminals.id=materials.production_entry_terminal_id
																		LEFT OUTER JOIN location_addresses ON location_addresses.id = materials.address'
	  	  )
			);
		}	
		
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'order' => 'first_name'));	
	$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'order' => 'description'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'order' => 'name'));
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'order' => 'code'));
  $currencies = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'order' => 'code'));
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
				<input type="hidden" name="action" value="add_item_cost">
				<input type="hidden" id="item_cost[item_id]" name="item_cost[item_id]" value="<?php echo $_GET['mid']?>">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				<input type="hidden" id="typ" name="typ" value="<?php echo $_GET['typ'] ?>">
				
				<div class="form-container">
					<h3 class="form-title">Details</h3>						
	        <table>
	           <tr>
	              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field magenta" disabled/></td>
	              <td width="150">Base Material Code:</td><td><input type="text" value="<?php echo $parent_material['material_code'] ?>" class="text-field" disabled/>
	              	<?php //echo $linkto = (isset($parent_material['material_code'])) ? link_to('materials-show.php?mid='.$parent_material['base_id'].'&base=1') : '' ?>
	              </td>
	           </tr>
	           <tr>
	              <td>Barcode:</td><td><input type="text" value="<?php echo $materials['bar_code'] ?>" class="text-field" disabled/></td>
	              <td>Model:</td><td><input type="text" value="<?php echo $materials['brand_model'] ?>" class="text-field" disabled/></td>
	           </tr>
	           <tr>
	              <td>Classification:</td><td><input type="text" value="<?php echo $materials['classification'] ?>" class="text-field" disabled/></td>
	              <td>Status:</td><td><input type="text" value="<?php echo $materials['status'] ?>" class="text-field" disabled/></td>
	           </tr>    
	           <tr>
	              <td>Person-in-charge:</td><td><input type="text" value="<?php echo $materials['pic'] ?>" class="text-field" disabled/>
	              	<?php echo $linkto = ($materials['pic']!='') ? link_to('users-show.php?uid='.$materials['user_id']) : '' ?>
	              </td>
	              <td>WIP Line Entry:</td><td><input type="text" value="<?php echo $materials['terminal'] ?>" class="text-field" disabled/>
	              	<?php echo $linkto = ($materials['terminal_code']!='') ? link_to('terminals-show.php?tid='.$materials['tid']) : '' ?>
	              </td>
	           </tr>      
	           <tr>
	              <td>Address:</td><td><input type="text" value="<?php echo $materials['address'] ?>" class="text-field" disabled/>
	              	<?php echo $linkto = ($materials['address']!='') ? link_to('locations-show.php?lid='.$materials['address_id']) : '' ?>
	              </td>
	              <td>Unit:</td><td><input type="text" value="<?php echo $materials['unit'] ?>" class="text-field" disabled/></td>
	           </tr>   
	           <tr>
	              <td>Defect Rate %:</td><td><input value="<?php echo ($materials['defect_rate'] * 100) ?>" id="material[defect_rate]" name="material[defect_rate]" type="text"  class="text-field text-right" disabled/>
								<td>Sorting %:</td><td><input type="text" value="<?php echo ($materials['sorting_percentage'] * 100) ?>" class="text-field text-right" disabled/></td>
	           </tr>   
	           <tr>
	              <td>Min. Stock Qty.:</td><td><input type="text" value="<?php echo $materials['msq'] ?>" class="text-field text-right number" disabled/></td>
	              <td></td><td></td>
	           </tr>         
	           <tr>
	              <td>Description:</td>
	              <td colspan="99">
	                <input type="text" value="<?php echo $materials['description'] ?>" class="text-field" style="width:645px" disabled/>
	              </td>
	           </tr> 
	           <tr><td height="5" colspan="99"></td></tr>
	        </table>	
				</div>
        <br/>
        <div class="form-container">
					<h3 class="form-title">Purchase Information</h3>
	        <table>
	        	<?php
	        		$costs = $DB->Get('materials', array('columns' => 'suppliers.id AS sid, suppliers.name AS supplier, item_costs.id AS cost_id, item_costs.cost, item_costs.moq,
																																item_costs.transportation_rate, lookups2.description AS currency', 
			 																				'joins' => 'INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
																													INNER JOIN suppliers ON suppliers.id = item_costs.supplier
																													INNER JOIN lookups AS lookups2 ON lookups2.id = item_costs.currency',
				 																			'conditions' => 'materials.id = '.$_GET['mid']));
							foreach($costs as $cost) {
							?>
								<tr>
		              <td width="150">Supplier:</td>
		              <td colspan="99">
		              	<input type="text" value="<?php echo $cost['supplier'] ?>" class="text-field" style="width:645px" disabled/>
		              	<?php echo $linkto = ($cost['supplier']!='') ? link_to('suppliers-show.php?sid='.$cost['sid']) : '' ?>
		              </td>
		           </tr>
		           <tr>
		              <td width="150">Currency:</td><td width="310"><input type="text" value="<?php echo $cost['currency'] ?>" class="text-field" disabled/></td>
		              <td width="150">Cost:</td><td><input type="text" value="<?php echo $cost['cost'] ?>" class="text-field  text-right" disabled/></td>
		           </tr>
		           <tr>
		              <td>Transportation Rate:</td><td><input type="text" value="<?php echo ($cost['transportation_rate'] * 100) ?>" class="text-field text-right" disabled/></td>
		              <td>MOQ:</td><td><input type="text" value="<?php echo $cost['moq'] ?>" class="text-field text-right" disabled/></td>
		           </tr>    
		           <tr><td height="5" colspan="99"></td></tr>
							<?php
							}
	        	?>           
	        </table>	
				</div>
        <br/>
        <div class="form-container">
					<h3 class="form-title">New Supplier</h3>
					<table>            
	           <tr>
	              <td width="150">New Supplier:</td>
	              <td colspan="99">
	                <?php select_query_tag($suppliers, 'id', 'name', '', 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;'); ?>
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
					<input type="submit" value="Create" class="btn"/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host((($_GET['typ'] == 'ind') ? 'indirect-' : '').'materials-show.php?mid='.$_GET['mid']); ?>"/>
				</div>
				</form>
			</div>
		</div>
	</div>

<?php }
require('footer.php'); ?>