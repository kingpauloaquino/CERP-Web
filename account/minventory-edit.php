<?php
  /*
   * Module: Material Inventory - Edit
  */
  $capability_key = 'edit_material_inventory';
  require('header.php');
	
	if($_POST['action'] == 'edit_material_inventory') {
		$args = array('variables' => $_POST['inventory'], 'conditions' => 'id='.$_POST['iid']); 
		$num_of_records = $Posts->EditInventory($args);
		
		$new_ivty = array();
		$new_ivty_hsty = array();
		for($i=0; $i<$_POST['terminal_count']; $i++) {
			if(isset($_POST['inventories_'.$i]['location_address_id']) && $_POST['inventories_'.$i]['location_address_id'] != 0) {
				$add_history = FALSE;
				if(isset($_POST['inventories_'.$i]['id']) && $_POST['inventories_'.$i]['id'] != 0) {
					$new_hash = md5($_POST['inventories_'.$i]['location_address_id'].$_POST['inventories_'.$i]['status'].$_POST['inventories_'.$i]['quantity'].$_POST['inventories_'.$i]['remarks']);
					
					if($_POST['inventories_'.$i]['orig_hash'] != $new_hash) {
						$ivty['location_address_id'] = $_POST['inventories_'.$i]['location_address_id'];
						$ivty['terminal_id'] = $_POST['inventories_'.$i]['terminal_id'];
						$ivty['status'] = $_POST['inventories_'.$i]['status'];
						$ivty['quantity'] = $_POST['inventories_'.$i]['quantity'];
						$ivty['remarks'] = $_POST['inventories_'.$i]['remarks'];						
						$args = array('variables' => $ivty, 'conditions' => 'id='.$_POST['inventories_'.$i]['id']); 
						$num_of_records = $Posts->EditInventoryLocations($args);
						$ivty = NULL;
						$add_history = TRUE;
					}					
				} else {
					$new_ivty['inventory_id'] = $_POST['iid'];
					$new_ivty['location_address_id'] = $_POST['inventories_'.$i]['location_address_id'];
					$new_ivty['terminal_id'] = $_POST['inventories_'.$i]['terminal_id'];
					$new_ivty['terminal_device_id'] = 1; // default id for WEB APP
					$new_ivty['status'] = $_POST['inventories_'.$i]['status'];
					$new_ivty['quantity'] = $_POST['inventories_'.$i]['quantity'];
					$new_ivty['remarks'] = $_POST['inventories_'.$i]['remarks'];			 
					$Posts->AddInventoryLocations($new_ivty);
					$new_ivty = NULL;	
					$add_history = TRUE;						
				}
				if ($add_history) {					
					$new_ivty_hsty['inventory_id'] = $_POST['iid'];
					$new_ivty_hsty['location_address_id'] = $_POST['inventories_'.$i]['location_address_id'];
					$new_ivty_hsty['terminal_id'] = $_POST['inventories_'.$i]['terminal_id'];
					$new_ivty_hsty['terminal_device_id'] = 1; // default id for WEB APP
					$new_ivty_hsty['inventory_status'] = $_POST['inventories_'.$i]['status'];
					$new_ivty_hsty['inventory_status_value'] = $_POST['inventories_'.$i]['quantity'];
					$new_ivty_hsty['remarks'] = $_POST['inventories_'.$i]['remarks'];			 
					$Posts->AddInventoryHistory($new_ivty_hsty);
					$new_ivty_hsty = NULL;	
				}
			}			
		}


		// get overall count
		$total_qty = $DB->Find('item_inventory_locations', array('columns' => 'SUM(quantity) AS total_qty', 'conditions' => 'inventory_id='.$_POST['iid']));
		$args = array('variables' => array('current_qty' => $total_qty['total_qty']), 'conditions' => 'id='.$_POST['iid']); 
		$num_of_records = $Posts->EditInventory($args);
		
		redirect_to($Capabilities->All['show_material_inventory']['url'].'?iid='.$_POST['iid'].'&mid='.$_POST['mid']);	
	} 
  
  if(isset($_REQUEST['iid'])) {
  	$inventory = $DB->Find('item_inventories', array(
  			'columns' => 'item_inventories.*,  
  	    				materials.id AS mid, materials.material_code, materials.description AS m_description, lookups1.description AS status, 
  	    				brand_models.brand_model, CONCAT(users.first_name, " ", users.last_name) AS pic,
  	    				item_classifications.classification', 
  	    'conditions' => 'item_inventories.id = '.$_REQUEST['iid'], 
  	    'joins' => 'INNER JOIN materials ON item_inventories.item_id = materials.id
  	    						INNER JOIN lookups AS lookups1 ON materials.status = lookups1.id
  	    						LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id
  	    						LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id
  	    						LEFT OUTER JOIN users ON materials.person_in_charge = users.id'
  	  )
		);	
		$trml_count = $DB->Find('locations', array(
				  			'columns' 		=> 'COUNT(id) AS trml_count', 
				  	    'conditions' 	=> 'parent like "%TRML"'));
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_material_inventory']['url'].'?iid='.$_REQUEST['iid'].'&mid='.$_REQUEST['mid'].'" class="nav">'.$Capabilities->All['show_material_inventory']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form style="width:500px;" id="inventory-form" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
			<div class="form-container">
				<input type="hidden" name="action" value="edit_material_inventory">
				<input type="hidden" name="iid" value="<?php echo $_REQUEST['iid'] ?>">
				<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid'] ?>">
				<input type="hidden" name="terminal_count" value="<?php echo $trml_count['trml_count'] ?>" />
				
        <h3 class="form-title">Basic Information</h3>
        
        <div class="field">
          <label class="label">Material Code:</label>
          <div class="input">
            <a href="materials-show.php?mid=<?php echo $_REQUEST['mid'] ?>"><?php echo $inventory['material_code'] ?></a>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Classification:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $inventory['classification'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Model:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $inventory['brand_model'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Person-in-charge:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $inventory['pic'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Status:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $inventory['status'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $inventory['m_description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
        <br/>
        <h3 class="form-title">Inventory Information</h3>
        <div class="field">
          <label class="label">Current Quantity:</label>
          <div class="input">
            <input type="text" id="inventory[current_qty]" name="inventory[current_qty]" value="<?php echo $inventory['current_qty'] ?>"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Re-order Level:</label>
          <div class="input">
            <input type="text" id="inventory[reorder_level]" name="inventory[reorder_level]" value="<?php echo $inventory['reorder_level'] ?>"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Re-order Quantity:</label>
          <div class="input">
            <input type="text" id="inventory[reorder_qty]" name="inventory[reorder_qty]" value="<?php echo $inventory['reorder_qty'] ?>"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Inventory Remarks:</label>
          <div class="input">
            <textarea id="inventory[description]" name="inventory[description]"><?php echo $inventory['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
      </div>
        <br/>
        <div class="grid jq-grid" style="width: 990px">
		      <table cellspacing="0" cellpadding="0">
		        <thead>
		          <tr>
		            <td width="100" class="border-right text-center"><a>Location</a></td>
		            <td width="100" class="border-right text-center"><a>Terminal</a></td>
		            <td width="100" class="border-right"><a>Address</a></td>
		            <td width="100" class="border-right"><a>Status</a></td>
		            <td width="80" class="border-right text-center"><a>Qty</a></td>
		            <td class="text-center"><a>Remarks</a></td>
		          </tr>
		        </thead>
		        <tbody>
		        	<?php
									$invt_locs = $DB->Get('item_inventory_locations', 
																		array('columns' => 'item_inventory_locations.*','conditions' => 'item_inventory_locations.inventory_id = '.$_REQUEST['iid']));
								
									$ctr = 0;
									$bldgs = $DB->Get('locations', array('columns' => 'id, location_code', 'conditions' => 'parent = "'.get_lookup_code('loc_bldg').'"'));
									foreach ($bldgs as $bldg) {
										$addresses = $DB->Get('location_addresses', array('columns' => 'id, address', 
												'conditions' => 'address LIKE "'.$bldg['location_code'].'%"', 'sort_column' => 'address'));
												
										echo '<tr>';
										echo '<td colspan="6" class="border-right"><b>'.$bldg['location_code'].'</b></td>';									
										echo '</tr>';
										$inventory_locations = $DB->Get('terminals', 
																		array('columns' => 'terminals.id AS trml_id, terminals.terminal_code',
																					'conditions' => 'terminals.location_id = '.$bldg['id']));
									
										foreach ($inventory_locations as $ivty) {
											$flag = FALSE;
											foreach ($invt_locs as $invt_loc) {
												if($invt_loc['terminal_id'] == $ivty['trml_id']) {
													$flag = TRUE;
													break;
												}	
											}	
												echo '<tr><td><input type="hidden" id="inventories_'.$ctr.'[id]" name="inventories_'.$ctr.'[id]" value="'.($id = ($flag) ? $invt_loc['id'] : '').'"/>
																			<input type="hidden" name="inventories_'.$ctr.'[terminal_id]" id="inventories_'.$ctr.'[terminal_id]" value="'.$ivty['trml_id'].'" />
																			<input type="hidden" name="inventories_'.$ctr.'[orig_hash]" id="inventories_'.$ctr.'[orig_hash]" value="'.md5($invt_loc['location_address_id'].$invt_loc['status'].$invt_loc['quantity'].$invt_loc['remarks']).'" />																
																			</td>';
												echo '<td class="border-right text-center"><a href="terminals-show.php?tid='.$ivty['trml_id'].'">'.$ivty['terminal_code'].'</a></td>';											
												?>										
												<td class="border-right"><?php select_query_tag($addresses, 'id', 'address', $address = ($flag) ? $invt_loc['location_address_id'] : '', 'inventories_'.$ctr.'[location_address_id]', 'inventories_'.$ctr.'[location_address_id]', '-', 'text') ?> </td>
												<td class="border-right"><?php 											
															$inv_stats = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.$bldg['location_code'].'STA"'));		
															select_query_tag($inv_stats, 'id', 'description', $status = ($flag) ? $invt_loc['status'] : '', 'inventories_'.$ctr.'[status]', 'inventories_'.$ctr.'[status]', '-', 'text');
														?> </td>
												<?php
												echo '<td class="border-right text-center"><input name="inventories_'.$ctr.'[quantity]" id="inventories_'.$ctr.'[quantity]" type="text" class="text w80" value="'.($quantity = ($flag) ? $invt_loc['quantity'] : '').'" /></td>';
												echo '<td class="border-right"><input name="inventories_'.$ctr.'[remarks]" id="inventories_'.$ctr.'[remarks]" type="text" class="text w250" value="'.($remarks = ($flag) ? $invt_loc['remarks'] : '').'" /></td>';	
												echo '</tr>';											
											$ctr+=1;
										}
									}
								?>
		        </tbody>
		      </table>
		    </div>
        <br/>
        <div class="field">
          <label class="label"></label>
          <div class="input">
            <button class="btn">Update</button>
            <button class="btn" onclick="cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
      </form>
		</div>
	</div>

<?php require('footer.php'); ?>