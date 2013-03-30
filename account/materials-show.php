<?php
  /*
   * Module: Products - Show 
  */
  $capability_key = 'show_material';
  require('header.php');
	
$allowed = $Role->isCapableByName($capability_key);

if(!$allowed) {
	require('inaccessible.php');	
}else{
  if(isset($_GET['mid'])) {
  	if($_GET['base']) {
  		$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS mid, materials.parent, materials.material_code, materials.description, brand_models.brand_model, 
																  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic,
																  	lookups3.description AS material_type, lookups4.description AS status, terminals.id AS tid, CONCAT(terminals.terminal_code," - ", terminals.terminal_name) AS terminal', 
					  	    'conditions' 	=> 'materials.id = '.$_GET['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id 
																		LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																		LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
																		LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																		LEFT OUTER JOIN lookups AS lookups4 ON materials.status = lookups4.id
																		LEFT OUTER JOIN terminals ON terminals.id=materials.production_entry_terminal_id'
	  	  )
			);
  	}else {
	  	$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS mid, materials.parent, materials.material_code, materials.bar_code, materials.description, brand_models.brand_model, 
																  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic,
																  	suppliers.id AS sup_id, suppliers.name AS supplier, lookups1.description AS unit, lookups2.code AS currency, item_costs.cost, 
																  	lookups3.description AS material_type, lookups4.description AS status, item_costs.transportation_rate, terminals.id AS tid, CONCAT(terminals.terminal_code," - ", terminals.terminal_name) AS terminal', 
					  	    'conditions' 	=> 'materials.id = '.$_GET['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id 
																		LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
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
			$parent_material = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS base_id, materials.material_code', 
					  	    'conditions' 	=> 'materials.base = TRUE AND materials.id = '.$materials['parent']
	  	  )
			);
			$item_costs = $DB->Find('item_costs', array('columns' => 'supplier, unit, currency, cost', 
	  																							'conditions' => 'item_id = '.$_GET['mid']));
			$is_base = $DB->Get('material_revisions', array('columns' => 'material_revisions.*, materials1.material_code AS material_code, 
				 																										 materials1.status, materials2.material_code AS base_material, lookups.description AS status', 
		 																				'joins' => 'INNER JOIN materials AS materials1 ON material_revisions.material_id = materials1.id
				 																								INNER JOIN materials AS materials2 ON material_revisions.base_material_id = materials2.id
				 																								INNER JOIN lookups ON materials1.status = lookups.id',
			 																			'conditions' => 'base_material_id = '.$_GET['mid'],
																						'sort_column' => 'materials1.material_code', 'sort_order' => 'DESC'));
			$is_rev = $DB->Get('material_revisions', array('columns' => 'material_revisions.*, materials1.material_code AS material_code, 
				 																										 materials1.status, materials2.material_code AS base_material, lookups.description AS status', 
		 																				'joins' => 'INNER JOIN materials AS materials1 ON material_revisions.material_id = materials1.id
				 																								INNER JOIN materials AS materials2 ON material_revisions.base_material_id = materials2.id
				 																								INNER JOIN lookups ON materials1.status = lookups.id',
			 																			'conditions' => 'base_material_id IN (SELECT base_material_id FROM material_revisions 
			 																											where material_id='.$_GET['mid'].')',
																						'sort_column' => 'materials1.material_code', 'sort_order' => 'DESC'));
			$base_material = $DB->Find('material_revisions', array('columns' => 'material_revisions.base_material_id, materials.material_code, lookups.description AS status', 
		 																				'joins' => 'INNER JOIN materials ON material_revisions.base_material_id = materials.id
				 																								INNER JOIN lookups ON materials.status = lookups.id',
			 																			'conditions' => 'material_id='.$_GET['mid']));		
			$item_images = $DB->Get('item_images', array('columns' => 'item_images.*',
			 																			'conditions' => 'item_id='.$_GET['mid']));			
  	}
  																																		
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['materials']['url'].'" class="nav">'.$Capabilities->All['materials']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_material']['url'].'" class="nav">'.$Capabilities->All['add_material']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_material']['url'].'?mid='.$_GET['mid'].'" class="nav">'.$Capabilities->All['edit_material']['name'].'</a>'; 
					$baseid=(isset($parent_material['base_id']))?$parent_material['base_id']:$materials['mid'];
					echo '<a href="'.$Capabilities->All['add_material_supplier']['url'].'?baseid='.$baseid.'" class="nav">'.$Capabilities->All['add_material_supplier']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['show_material_inventory']['url'].'?id='.$_GET['mid'].'" class="nav">'.$Capabilities->All['show_material_inventory']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field" disabled/></td>
              <td width="150">Base Material Code:</td><td><input type="text" value="<?php echo $parent_material['material_code'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = (isset($parent_material['material_code'])) ? link_to('materials-show.php?mid='.$parent_material['base_id'].'&base=1') : '' ?>
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
        
        <?php 
        	if(count($is_base)>0){
						echo '<div class="field"><label class="label"><b>REVISIONS</b>:</label><div class="input">';
						foreach ($is_base as $rev) {
							echo '<p><a href="'.$Capabilities->GetUrl().'?mid='.$rev['material_id'].'">'.$rev['material_code'].'</a>';
							echo (($rev['status']=='Inactive') ? ' - [ '.$rev['status'].' ]' : '').'</p>';
						}
						echo '<p>'.$materials['material_code'].' [ Base ]</p>';
          	echo '</div><div class="clear"></div></div>';
					}
					
					if(count($is_rev)>0){
						echo '<div class="field"><label class="label"><b>REVISIONS</b>:</label><div class="input">';
						foreach ($is_rev as $rev) {
							echo ($materials['material_code'] == $rev['material_code']) 
								? '<p>'.$rev['material_code'].(($rev['status']=='Inactive') ? ' - [ '.$rev['status'].' ]' : '').'</p>'
								: '<p><a href="'.$Capabilities->GetUrl().'?mid='.$rev['material_id'].'">'.$rev['material_code']
								.'</a>'.(($rev['status']=='Inactive') ? ' - [ '.$rev['status'].' ]' : '').'</p>';
							
						}
						echo '<p><a href="'.$Capabilities->GetUrl().'?mid='.$base_material['base_material_id'].'">'.$base_material['material_code'].'</a>'
											 .(($base_material['status']=='Inactive') ? ' - [ '.$base_material['status'].' ]' : '').' [ Base ]</p>';
          	echo '</div><div class="clear"></div></div>';
					}
        
				if(!$_GET['base']) {
				?>
				
        <br/>
        
        
				<h3 class="form-title">Purchase Information</h3>
        <table>
           <tr>
              <td width="150">Supplier:</td>
              <td colspan="99">
              	<input type="text" value="<?php echo $materials['supplier'] ?>" class="text-field" style="width:645px" disabled/>
              	<?php echo $linkto = ($materials['supplier']!='') ? link_to('suppliers-show.php?sid='.$materials['sup_id']) : '' ?>
              </td>
           </tr>
           <tr>
              <td width="150">Currency:</td><td width="310"><input type="text" value="<?php echo $materials['currency'] ?>" class="text-field" disabled/></td>
              <td width="150">Cost:</td><td><input type="text" value="<?php echo $materials['cost'] ?>" class="text-field  text-right" disabled/></td>
           </tr>
           <tr>
              <td width="150">Unit:</td><td width="310"><input type="text" value="<?php echo $materials['unit'] ?>" class="text-field" disabled/></td>
              <td>Transportation Rate:</td><td><input type="text" value="<?php echo $materials['transportation_rate'] ?>" class="text-field text-right" disabled/></td>
           </tr>    
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
        
			  <?php
			  	}
			  ?>
      </form>
				
		</div>
	</div>

<?php }
require('footer.php'); ?>