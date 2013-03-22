<?php
  /*
   * Module: Material - New Supplier
  */
  $capability_key = 'add_material_sup';
  require('header.php');
  
	if($_POST['action'] == 'add_material') {		
		$_POST['material']['base'] = FALSE;
		$id = $Posts->AddMaterial($_POST['material']);
		
		
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		if(isset($id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$id); }
	} 
	
	if(isset($_REQUEST['baseid'])) {
		$base = $DB->Find('materials', array(
				  			'columns' 		=> 'COUNT(id) AS cnt', 
				  	    'conditions' 	=> 'materials.parent = '.$_REQUEST['baseid']
  	  )
		);
		$code_suffix = (int) $base['cnt'] + 1;
		
		$materials = $DB->Find('materials', array(
				  			'columns' 		=> 'materials.*', 
				  	    'conditions' 	=> 'materials.id = '.$_REQUEST['baseid'], 
				  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id
																	LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id
																	LEFT OUTER JOIN users ON materials.person_in_charge = users.id'
  	  )
		);	
	}
		
	$types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('material_type').'"', 'sort_column' => 'description'));
  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'sort_column' => 'classification'));
	$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'sort_column' => 'brand_model'));
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'sort_column' => 'first_name', 'conditions' => 'role = 5'));	
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
        <h3 class="form-title">Basic Information</h3>
        
				<input type="hidden" name="action" value="add_material">
				<input type="hidden" id="material[inventory_entry]" name="material[inventory_entry]" value="0">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				<input type="hidden" id="material[parent]" name="material[parent]" value="<?php echo $_REQUEST['baseid'] ?>">
				
				<span class="notice">
<!--           <p class="info"><strong>Notice!</strong> Material codes should be unique.</p> -->
        </span>
				
				<div class="field">
          <label class="label">Base Material Code:</label>
          <div class="input">
          	<input type="text" name="name" value="<?php echo $materials['material_code'] ?>"/>
          	<?php echo $linkto = ($materials['material_code']!='') ? link_to('materials-show.php?mid='.$materials['id']) : '' ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Material Code:</label>
          <div class="input">
            <input type="text" id="material[material_code]" name="material[material_code]" value="<?php echo $materials['material_code'].'-'.$code_suffix; ?>" readonly="readonly"/>
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
            <?php select_query_tag($types, 'id', 'description', $materials['material_type'], 'material[material_type]', 'material[material_type]', '', 'text w180'); ?>
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
          <label class="label">Model:</label>
          <div class="input">
            <?php select_query_tag($models, 'id', 'brand_model', $materials['brand_model'], 'material[brand_model]', 'material[brand_model]', '', 'text w180'); ?>
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
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="material[description]" name="material[description]" class="text w250"><?php echo $materials['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>

				<br/>
				<h3 class="form-title">Purchase Information</h3>
				
				<div class="field">
          <label class="label">Supplier:</label>
          <div class="input">
            <?php select_query_tag($suppliers, 'id', 'name', '', 'item_cost[supplier]', 'item_cost[supplier]'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
				<div class="field">
          <label class="label">Unit:</label>
          <div class="input">
            <?php select_query_tag($units, 'id', 'description', '', 'item_cost[unit]', 'item_cost[unit]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
				<div class="field">
          <label class="label">Currency:</label>
          <div class="input">
            <?php select_query_tag($currencies, 'id', 'code', '24', 'item_cost[currency]', 'item_cost[currency]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Cost:</label>
          <div class="input">
            <input type="text" id="item_cost[cost]" name="item_cost[cost]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Transportation Rate:</label>
          <div class="input">
            <input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label"></label>
          <div class="input">
            <button class="btn">Create</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
        
				</form>
			</div>
		</div>
	</div>

<?php require('footer.php'); ?>