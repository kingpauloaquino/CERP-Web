<?php
  /*
   * Module: Indirect Materials - Show 
  */
  $capability_key = 'show_indirect_material';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	  if(isset($_GET['mid'])) {
	  	$materials = $DB->Find('materials', array(
						  			'columns' 		=> 'materials.id AS mid, materials.parent, materials.material_code, materials.bar_code, materials.description, 
																	  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic,
																	  	suppliers.id AS sup_id, suppliers.name AS supplier, lookups1.description AS unit, lookups2.code AS currency, item_costs.cost, 
																	  	lookups3.description AS material_type, lookups4.description AS status, item_costs.transportation_rate,
																	  	terminals.id AS tid, CONCAT(terminals.terminal_code," - ", terminals.terminal_name) AS terminal', 
						  	    'conditions' 	=> 'materials.id = '.$_GET['mid'], 
						  	    'joins' 			=> 'LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																			LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																			LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
																			LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
																			LEFT OUTER JOIN lookups AS lookups1 ON item_costs.unit = lookups1.id
																			LEFT OUTER JOIN lookups AS lookups2 ON item_costs.currency = lookups2.id
																			LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																			LEFT OUTER JOIN lookups AS lookups4 ON materials.status = lookups4.id
																			LEFT OUTER JOIN terminals ON terminals.id=materials.production_entry_terminal_id'
		  	  )
				);
				$address = $DB->Find('location_address_items', array(
						  			'columns' 		=> 'location_address_items.id, location_address_items.address AS add_id, location_addresses.address', 
						  			'joins'				=> 'INNER JOIN location_addresses ON location_addresses.id = location_address_items.address',
						  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.item_id = '.$_GET['mid']
		  	  )
				);
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['indirect_materials']['url'].'" class="nav">'.$Capabilities->All['indirect_materials']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_indirect_material']['url'].'?mid='.$_GET['mid'].'" class="nav">'.$Capabilities->All['edit_indirect_material']['name'].'</a>'; 
					echo '<a href="'.$Capabilities->All['add_material_supplier']['url'].'?mid='.$_GET['mid'].'&typ=ind" class="nav">'.$Capabilities->All['add_material_supplier']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['show_material_inventory']['url'].'?id='.$_GET['mid'].'" class="nav">'.$Capabilities->All['show_material_inventory']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form>
				<div class="form-container">
					<h3 class="form-title">Details</h3>
	        <table>
	           <tr>
	              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field" disabled/></td>
	              <td width="150">Base Material Code:</td><td><input type="text" value="N/A" class="text-field" disabled/></td>
	           </tr>
	           <tr>
	              <td>Barcode:</td><td><input type="text" value="<?php echo $materials['bar_code'] ?>" class="text-field" disabled/></td>
	              <td>Model:</td><td><input type="text" value="N/A" class="text-field" disabled/></td>
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
	              <td>Address:</td><td><input type="text" value="<?php echo $address['address'] ?>" class="text-field" disabled/>
	              	<?php echo $linkto = ($address['address']!='') ? link_to('locations-show.php?lid='.$address['add_id']) : '' ?>
	              </td>
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
	        		$costs = $DB->Get('materials', array('columns' => 'suppliers.id AS sid, suppliers.name AS supplier, item_costs.id AS cost_id, item_costs.cost,  
																																item_costs.transportation_rate, lookups1.description AS unit, lookups2.description AS currency', 
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
		              	<input type="text" value="<?php echo $cost['supplier'] ?>" class="text-field" style="width:645px" disabled/>
		              	<?php echo $linkto = ($cost['supplier']!='') ? link_to('suppliers-show.php?sid='.$cost['sid']) : '' ?>
		              </td>
		           </tr>
		           <tr>
		              <td width="150">Currency:</td><td width="310"><input type="text" value="<?php echo $cost['currency'] ?>" class="text-field" disabled/></td>
		              <td width="150">Cost:</td><td><input type="text" value="<?php echo $cost['cost'] ?>" class="text-field  text-right" disabled/></td>
		           </tr>
		           <tr>
		              <td width="150">Unit:</td><td width="310"><input type="text" value="<?php echo $cost['unit'] ?>" class="text-field" disabled/></td>
		              <td>Transportation Rate:</td><td><input type="text" value="<?php echo ($cost['transportation_rate'] * 100) ?>" class="text-field text-right" disabled/></td>
		           </tr>    
		           <tr><td height="5" colspan="99"></td></tr>
						<?php
							}
	        	?>           
	        </table> 	
      	</div>
      </form>
		</div>
	</div>

<?php }
require('footer.php'); ?>