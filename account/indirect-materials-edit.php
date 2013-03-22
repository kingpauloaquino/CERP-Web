<?php
  /*
   * Module: Indirect Material - Edit 
  */
  $capability_key = 'edit_indirect_material';
  require('header.php');
  
	if($_POST['action'] == 'edit_indirect_material') {
		$num_of_records1 = $Posts->EditMaterial(array('variables' => $_POST['material'], 'conditions' => 'id='.$_POST['mid']));
		$num_of_records2 = $Posts->EditItemCost(array('variables' => $_POST['item_cost'], 'conditions' => 'item_id='.$_POST['mid']));
		redirect_to($Capabilities->All['show_indirect_material']['url'].'?mid='.$_POST['mid']);		
	} 
	
  if(isset($_REQUEST['mid'])) {
  	$materials = $DB->Find('materials', array(
				  			'columns' 		=> 'materials.*', 
				  	    'conditions' 	=> 'materials.id = '.$_REQUEST['mid'], 
				  	    'joins' 			=> 'LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id
																	LEFT OUTER JOIN users ON materials.person_in_charge = users.id'
  	  )
		);	
		$item_costs = $DB->Find('item_costs', array('columns' => 'supplier, unit, currency, cost, transportation_rate', 
  							'conditions' => 'item_id = '.$_REQUEST['mid'].' AND item_type="MAT"'));  
		
			$address = $DB->Find('location_address_items', array(
					  			'columns' 		=> 'location_address_items.id, location_address_items.address AS add_id, location_addresses.address', 
					  			'joins'				=> 'INNER JOIN location_addresses ON location_addresses.id = location_address_items.address',
					  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.item_id = '.$_REQUEST['mid']
	  	  )
			);
  }
	
  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'sort_column' => 'classification'));
	$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'sort_column' => 'brand_model'));
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'sort_column' => 'first_name','conditions' => 'role = 5'));	
	$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'sort_column' => 'description'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'sort_column' => 'name'));
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'sort_column' => 'code'));
  $currencies = $DB->Get('lookups', array('columns' => 'id, code', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'sort_column' => 'code'));
	$item_images = $DB->Get('item_images', array('columns' => 'item_images.*',
		 																			'conditions' => 'item_id='.$_REQUEST['mid']));	
	$has_inventory = $DB->Find('item_inventories', array('columns' => 'id, item_id', 
  																							'conditions' => 'item_type="MAT" AND item_id = '.$_REQUEST['mid']));																									
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['show_indirect_material']['url'].'?mid='.$_REQUEST['mid'].'" class="nav">'.$Capabilities->All['show_indirect_material']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
        <h3 class="form-title">Basic Information</h3>
        
				<input type="hidden" name="action" value="edit_indirect_material">
				<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid'] ?>">
                
        <div class="field">
          <label class="label">Material Code:</label>
          <div class="input">
            <input type="text" id="material[material_code]" name="material[material_code]" value="<?php echo $materials['material_code'] ?>"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Bar Code:</label>
          <div class="input">
            <input type="text" id="material[bar_code]" name="material[bar_code]" value="<?php echo $materials['bar_code'] ?>"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Classification:</label>
          <div class="input">
            <?php select_query_tag($classifications, 'id', 'classification', $materials['material_classification'], 'material[material_classification]', 'material[material_classification]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
                
        <div class="field">
          <label class="label">Person-in-charge:</label>
          <div class="input">
            <?php select_query_tag($pics, 'id', 'pic', $materials['person_in_charge'], 'material[person_in_charge]', 'material[person_in_charge]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Status:</label>
          <div class="input">
            <?php select_query_tag($status, 'id', 'description', $materials['status'], 'material[status]', 'material[status]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Address:</label>
          <div class="input">
            <input type="text"  value="<?php echo $address['address'] ?>"/>
          	<?php echo $linkto = ($address['add_id']!='') ? '&nbsp;<a href="locations-edit.php?lid='.$address['add_id'].'">change</a>' : '' ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="material[description]" name="material[description]"><?php echo $materials['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
				<br/>
				<h3 class="form-title">Purchase Information</h3>
        <div class="field">
          <label class="label">Status:</label>
          <div class="input">
            <?php select_query_tag($suppliers, 'id', 'name', $item_costs['supplier'], 'item_cost[supplier]', 'item_cost[supplier]'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Unit:</label>
          <div class="input">
            <?php select_query_tag($units, 'id', 'description', $item_costs['unit'], 'item_cost[unit]', 'item_cost[unit]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Currency:</label>
          <div class="input">
            <?php select_query_tag($currencies, 'id', 'code', $item_costs['currency'], 'item_cost[currency]', 'item_cost[currency]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Cost:</label>
          <div class="input">
            <input type="text" id="item_cost[cost]" name="item_cost[cost]" value="<?php echo $item_costs['cost'] ?>"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Transportation Rate:</label>
          <div class="input">
            <input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]" value="<?php echo $item_costs['transportation_rate'] ?>"/>
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
				  
			  <div class="field">
              <label class="label"></label>
              <div class="input">
                <button class="btn">Update</button>
                <button class="btn" onclick="return cancel_btn();">Cancel</button>
              </div>
              <div class="clear"></div>
            </div>
				</form>
		</div>
	</div>

<?php require('footer.php'); ?>