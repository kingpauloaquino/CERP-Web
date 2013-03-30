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
  
		if($_POST['action'] == 'add_material') {		
			$_POST['material']['base'] = FALSE;
			$id = $Posts->AddMaterial($_POST['material']);
			
			
			$_POST['item_cost']['item_id'] = $id;
			$Posts->AddItemCost($_POST['item_cost']);
			if(isset($id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$id); }
		} 
		
		if(isset($_GET['baseid'])) {
			$base = $DB->Find('materials', array(
					  			'columns' 		=> 'COUNT(id) AS cnt', 
					  	    'conditions' 	=> 'materials.parent = '.$_GET['baseid']
	  	  )
			);
			$code_suffix = (int) $base['cnt'] + 1;
			
			$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.*, lookups.description AS mat_typ, item_classifications.classification AS classification, brand_models.brand_model AS model, 
					  												CONCAT(terminals.terminal_code," - ", terminals.terminal_name) AS terminal', 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id
																		LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																		LEFT OUTER JOIN lookups ON materials.material_type = lookups.id
																		LEFT OUTER JOIN terminals ON terminals.id=materials.production_entry_terminal_id',
					  	    'conditions' 	=> 'materials.id = '.$_GET['baseid']
	  	  )
			);	
		}	
		
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'sort_column' => 'first_name'));	
	$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'sort_column' => 'description'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'sort_column' => 'name'));
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'sort_column' => 'code'));
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
				<input type="hidden" id="material[inventory_entry]" name="material[inventory_entry]" value="0">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				<input type="hidden" id="material[parent]" name="material[parent]" value="<?php echo $_GET['baseid'] ?>">
				<input type="hidden" id="material[material_code]" name="material[material_code]" value="<?php echo ($materials['material_code'].'-'.$code_suffix) ?>">
				<input type="hidden" id="material[material_classification]" name="material[material_classification]" value="<?php echo $materials['material_classification'] ?>">
				<input type="hidden" id="material[brand_model]" name="material[brand_model]" value="<?php echo $materials['brand_model'] ?>">
				<input type="hidden" id="material[material_type]" name="material[material_type]" value="<?php echo $materials['material_type'] ?>">
				<input type="hidden" id="material[production_entry_terminal_id]" name="material[production_entry_terminal_id]" value="<?php echo $materials['production_entry_terminal_id'] ?>">
				
				<h3 class="form-title">Details</h3>						
        <table>
           <tr>
              <td width="150">Base Material Code:</td><td width="310"><input type="text" name="name" value="<?php echo $materials['material_code'] ?>" class="text-field " disabled/>
              	<?php echo $linkto = ($materials['material_code']!='') ? link_to('materials-show.php?mid='.$materials['id']) : '' ?>
              </td>
              <td width="150">Material Code:</td><td><input type="text" value="<?php echo ($materials['material_code'].'-'.$code_suffix) ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Classification:</td><td><input type="text" value="<?php echo $materials['classification'] ?>" class="text-field" disabled/></td>
              <td>Model:</td><td><input type="text" value="<?php echo $materials['model'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Type:</td><td><input type="text" value="<?php echo $materials['mat_typ'] ?>" class="text-field" disabled/></td>
              <td>Status:</td><td><?php select_query_tag($status, 'id', 'description', '', 'material[status]', 'material[status]', '', 'width:192px;'); ?></td>
           </tr>    
           <tr>
              <td>Barcode:</td><td><input type="text" id="material[bar_code]" name="material[bar_code]" class="text-field" value="<?php echo ($materials['material_code'].'-'.$code_suffix) ?>" /></td>
              <td>Person-in-charge:</td><td><?php select_query_tag($pics, 'id', 'pic', '', 'material[person_in_charge]', 'material[person_in_charge]', '', 'width:192px;'); ?>
              </td>
           </tr>      
           <tr>
              <td>Address:</td><td><input type="text"  class="text-field" /></td>
              <td>WIP Line Entry:</td><td><input type="text" value="<?php echo $materials['terminal'] ?>" class="text-field" disabled/></td>
              </td>
           </tr>             
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="material[description]" name="material[description]" class="text-field" style="width:645px"  />
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
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('materials-show.php?mid='.$materials['id']); ?>"/>
         </div>
        
				</form>
			</div>
		</div>
	</div>

<?php }
require('footer.php'); ?>