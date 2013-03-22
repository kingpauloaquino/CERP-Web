<?php
  /*
   * Module: Product Inventory - Edit 
  */
  $capability_key = 'edit_product_inventory';
  require('header.php');
  
	if($_POST['action'] == 'edit_product_inventory') {
		$ctr=0;
		foreach ($_POST['inventory'] as $arr) {
			if($arr['id'] != NULL)
				if(md5($arr['current_level'].$arr['reorder_level'].$arr['reorder_quantity'].$arr['description'])!=$_POST['orig_hash'][$ctr]) {
					$Posts->EditInventory(array('variables' => $arr, 'conditions' => 'id='.$arr['id']));
					if($arr['location']==2){ // 2 = WH2
						$arr['inventory_id'] = $_REQUEST['iid'];
						$arr['item_id'] = $_REQUEST['pid'];
						$arr['action'] = 'Inventory Update';
						$Posts->AddInventoryHistory($arr);	
					}	
				}	
			$ctr+=1;
		}
		redirect_to($Capabilities->All['show_product_inventory']['url'].'?iid='.$_POST['iid'].'&pid='.$_POST['pid']);	
	} 
	
  if(isset($_REQUEST['iid'])) {
  	$inventory = $DB->Find('item_inventories', array(
  			'columns' => 'item_inventories.*,  
  	    				products.id AS mid, products.product_code, products.description AS p_description, brand_models.brand_model ', 
  	    'conditions' => 'item_inventories.id = '.$_REQUEST['iid'], 
  	    'joins' => 'INNER JOIN products ON item_inventories.item_id = products.id
  	    						LEFT OUTER JOIN brand_models ON products.brand_model = brand_models.id
  	    						LEFT OUTER JOIN users ON products.person_in_charge = users.id'
  	  )
		);	
  }
  $locations = $DB->Get('locations', array('columns' => 'id, location_code, location, description'));
  $inventory_locations = $DB->Get('locations', array('columns' => 'item_inventories.id AS inv_id, item_inventories.current_level, item_inventories.reorder_level, 
																										  						item_inventories.reorder_quantity, item_inventories.description, 
																										  						locations.id, locations.location_code', 
																			  						 'joins' => 'LEFT OUTER JOIN item_inventories ON locations.id = item_inventories.location
																										  						AND item_type="PRD" AND item_id = '.$_REQUEST['pid']));
  $on_hand = $DB->Find('item_inventories', array('columns' => 'SUM(current_level) AS on_hand', 
  										 'conditions' => 'item_id = '.$_REQUEST['pid']));
?>

	<div id="page">
		<div id="side-bar">
			<?php require('sidebar.php'); ?>
		</div>
				
		<div id="content">
			<div id="title">
				<h1 class="left"><?php echo $Capabilities->GetName(); ?></h1>
				<?php
				  $link = $Capabilities->All['show_product_inventory'];
				  echo '<a href="'.$link['url'].'?iid='.$_REQUEST['iid'].'&pid='.$_REQUEST['pid'].'" class="cmd">'.$link['name'].'</a>';  
				?>
			    <div class="clear"></div>
			</div>	
				<form id="inventory-form" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">	
				<h4>Basic Information</h4>
				<input type="hidden" name="action" value="edit_product_inventory">
				<input type="hidden" name="iid" value="<?php echo $_REQUEST['iid'] ?>">		
				<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>">			
				
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td width="180"><label>Product Code:</label></td>
						<td><?php echo $inventory['product_code'] ?></td>
					</tr>
					<tr>
						<td><label>Brand:</label></td>
						<td><?php echo $inventory['brand_model'] ?></td>
					</tr>
					<tr>
						<td><label>Description:</label></td>
						<td><?php echo $inventory['p_description'] ?></td>
					</tr>
					<tr><td colspan="99" height="15"></td></tr>
				</table>
				
				<h4>Inventory Information</h4>
				<div class="grid">
					<table cellpadding="0" cellspacing="0">
						<thead>
								<th>Location</th>
								<th>Current Level</th>
								<th>Re-order Level</th>
								<th>Re-order Quantity</th>
								<th>Description</th>
						</thead>
						<tbody class="list">
						<?php
							$ctr=0;
							foreach ($locations as $loc) {
								$tag .= '<tr>';
								$tag .= '<td><label>'.$loc['location_code'].'</label></td>'; 
								$tag .= '<input type="hidden" id="inventory['.$ctr.'][id]" name="inventory['.$ctr.'][id]" value="'.$inventory_locations[$ctr]['inv_id'].'"/>';
								$tag .= '<input type="hidden" id="inventory['.$ctr.'][location]" name="inventory['.$ctr.'][location]" value="'.$inventory_locations[$ctr]['id'].'"/>';
								$tag .= '<input type="hidden" id="orig_hash['.$ctr.']" name="orig_hash['.$ctr.']" 
												value="'.md5($inventory_locations[$ctr]['current_level'].$inventory_locations[$ctr]['reorder_level'].
												$inventory_locations[$ctr]['reorder_quantity'].$inventory_locations[$ctr]['description']).'"/>';
								
								$tag .= '<td><input type="text" id="inventory['.$ctr.'][current_level]" name="inventory['.$ctr.'][current_level]"';
								$tag .= ($loc['id'] == $inventory_locations[$ctr]['id'] && $loc['location_code'] == 'WH2')  
												? ' value="'.$inventory_locations[$ctr]['current_level'].'" class="text center w80 magenta" ' 
												: ' value="N/A" readonly="readonly" class="text center w80 lightgray"';
								$tag .= ' autocomplete="off" /></td>';	
								
								$tag .= '<td><input type="text" id="inventory['.$ctr.'][reorder_level]" name="inventory['.$ctr.'][reorder_level]"';
								$tag .= ($loc['id'] == $inventory_locations[$ctr]['id'] && $loc['location_code'] == 'WH2')  
												? ' value="'.$inventory_locations[$ctr]['reorder_level'].'" class="text center w80 magenta" ' 
												: ' value="N/A" readonly="readonly" class="text center w80 lightgray"';
								$tag .= ' autocomplete="off" /></td>';	
								
								$tag .= '<td><input type="text" id="inventory['.$ctr.'][reorder_quantity]" name="inventory['.$ctr.'][reorder_quantity]"';
								$tag .= ($loc['id'] == $inventory_locations[$ctr]['id'] && $loc['location_code'] == 'WH2')  
												? ' value="'.$inventory_locations[$ctr]['reorder_quantity'].'" class="text center w80 magenta" ' 
												: ' value="N/A" readonly="readonly" class="text center w80 lightgray"';
								$tag .= ' autocomplete="off" /></td>';	
								
								$tag .= '<td><input type="text" id="inventory['.$ctr.'][description]" name="inventory['.$ctr.'][description]"';
								$tag .= ($loc['id'] == $inventory_locations[$ctr]['id'] && $loc['location_code'] == 'WH2')  
												? ' value="'.$inventory_locations[$ctr]['description'].'" class="text center w300 magenta" ' 
												: ' value="N/A" readonly="readonly" class="text center w300 lightgray"';
								$tag .= ' autocomplete="off" /></td>';	
								
								
								
								$tag .= '</tr>';	
								$ctr +=1;		
							}
							echo $tag;			
						?>
						</tbody>
						<tfoot>
							<tr>
								<td><label>Stock On-hand:</label></td>
								<td><label><?php echo $on_hand['on_hand']?></label></td>
								<td colspan="3"><label>On Order:</label></td>
							</tr>
						</tfoot>
						<tr><td colspan="99" height="15"></td></tr>
					</table>
				</div>
				<hr/>
				<input type="submit" value="Update Item Inventory"/>&nbsp;<input type="button" value="Cancel"/>
				</form>
		</div>
	</div>

<?php require('footer.php'); ?>