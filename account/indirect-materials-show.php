<?php
  /*
   * Module: Indirect Materials - Show 
  */
  $capability_key = 'show_indirect_material';
  require('header.php');
  
  if(isset($_REQUEST['mid'])) {
  	$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS mid, materials.parent, materials.material_code, materials.bar_code, materials.description, 
																  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic,
																  	suppliers.id AS sup_id, suppliers.name AS supplier, lookups1.description AS unit, lookups2.code AS currency, item_costs.cost, 
																  	lookups3.description AS material_type, lookups4.description AS status, item_costs.transportation_rate', 
					  	    'conditions' 	=> 'materials.id = '.$_REQUEST['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																		LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
																		LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
																		LEFT OUTER JOIN lookups AS lookups1 ON item_costs.unit = lookups1.id
																		LEFT OUTER JOIN lookups AS lookups2 ON item_costs.currency = lookups2.id
																		LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																		LEFT OUTER JOIN lookups AS lookups4 ON materials.status = lookups4.id'
	  	  )
			);
			$address = $DB->Find('location_address_items', array(
					  			'columns' 		=> 'location_address_items.id, location_address_items.address AS add_id, location_addresses.address', 
					  			'joins'				=> 'INNER JOIN location_addresses ON location_addresses.id = location_address_items.address',
					  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.item_id = '.$_REQUEST['mid']
	  	  )
			);
			$parent_material = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS base_id, materials.material_code', 
					  	    'conditions' 	=> 'materials.base = TRUE AND materials.id = '.$materials['parent']
	  	  )
			);
			$item_costs = $DB->Find('item_costs', array('columns' => 'supplier, unit, currency, cost', 
	  																							'conditions' => 'item_id = '.$_REQUEST['mid']));
			$item_images = $DB->Get('item_images', array('columns' => 'item_images.*',
			 																			'conditions' => 'item_id='.$_REQUEST['mid']));	
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['indirect_materials']['url'].'" class="nav">'.$Capabilities->All['indirect_materials']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_indirect_material']['url'].'" class="nav">'.$Capabilities->All['add_indirect_material']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_indirect_material']['url'].'?mid='.$_REQUEST['mid'].'" class="nav">'.$Capabilities->All['edit_indirect_material']['name'].'</a>'; 
					$baseid=(isset($parent_material['base_id']))?$parent_material['base_id']:$materials['mid'];
				  echo '<a href="'.$Capabilities->All['show_material_inventory']['url'].'?id='.$_REQUEST['mid'].'" class="nav">'.$Capabilities->All['show_material_inventory']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
        <h3 class="form-title">Basic Information</h3>
        
        <div class="field">
          <label class="label">Material Code:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['material_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Bar Code:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['bar_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Type:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['material_type'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Classification:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['classification'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Person-in-charge:</label>
          <div class="input">
          	<input type="text" name="name" value="<?php echo $materials['pic'] ?>"/>
          	<?php echo $linkto = ($materials['pic']!='') ? link_to('users-show.php?uid='.$materials['user_id']) : '' ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Status:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['status'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Address:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $address['address'] ?>" readonly="readonly"/>
          	<?php echo $linkto = ($address['address']!='') ? link_to('locations-show.php?lid='.$address['add_id']) : '' ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $materials['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
				
        <br/>
        
        <h3 class="form-title">Purchase Information</h3>
        <div class="field">
          <label class="label">Supplier:</label>
          <div class="input">
          	<input type="text" name="name" value="<?php echo $materials['supplier'] ?>"/>
          	<?php echo $linkto = ($materials['supplier']!='') ? link_to('suppliers-show.php?sid='.$materials['sup_id']) : '' ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Unit:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['unit'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Currency:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['currency'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Cost:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['cost'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Transportation Rate:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $materials['transportation_rate'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
      	<br/>
      	
      	<h3 class="form-title">Material Images</h3>
        <div class="imageRow">
			  	<div class="set">
			  		<?php
			  			if(count($item_images)>0) {
				  			foreach ($item_images as $image) {
									echo '<div class="single">';
									echo '<a href="../images/inks/'.$image['name'].'.'.$image['ext'].'" rel="lightbox[inks]" title="'.$image['title'].'"><img src="../images/inks/'.$image['name'].'_thumb.'.$image['ext'].'" alt="'.$image['description'].'" /></a>';
									echo '</div>';
								}
			  			}else {
			  				echo '<div class="single">';
								echo '<img src="../images/inks/no_image.png" alt="no image available" />';
								echo '</div>';
			  			}
			  		?>
			  	</div>
		  	</div>
      </form>
				
		</div>
	</div>

<?php require('footer.php'); ?>