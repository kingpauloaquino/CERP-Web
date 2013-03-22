<?php
  /*
   * Module: Material - New 
  */
  $capability_key = 'add_material';
  require('header.php');
  
	if($_POST['action'] == 'add_material') {
		$_POST['material']['base'] = TRUE;
		$_POST['material']['parent'] = NULL;
		$base_id = $Posts->AddMaterial($_POST['material']);
		
		$_POST['material']['base'] = FALSE;
		$_POST['material']['parent'] = $base_id;
		$_POST['material']['material_code'] = $_POST['material']['material_code'].'-1';
		$id = $Posts->AddMaterial($_POST['material']);
				
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		if(isset($id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$id); }
	} 
	
  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'sort_column' => 'classification'));
	$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'sort_column' => 'brand_model'));
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'sort_column' => 'first_name', 'conditions' => 'role = 5'));	
	$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'sort_column' => 'description'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'sort_column' => 'name'));
	$terminals = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal', 'conditions' => 'location_id=4 AND type="IN"', 'sort_column' => 'id')); // location_id=4 (WIP)
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
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				<input type="hidden" id="material[material_type]" name="material[material_type]" value="70" />
				
				<span class="notice">
          <p class="info"><strong>Notice!</strong> Material codes should be unique.</p>
        </span>
				
				<div class="field">
          <label class="label">Material Code:</label>
          <div class="input">
            <input type="text" id="material[material_code]" name="material[material_code]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Bar Code:</label>
          <div class="input">
            <input type="text" id="material[bar_code]" name="material[bar_code]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Classification:</label>
          <div class="input">
            <?php select_query_tag($classifications, 'id', 'classification', '', 'material[material_classification]', 'material[material_classification]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Model:</label>
          <div class="input">
            <?php select_query_tag($models, 'id', 'brand_model', '', 'material[brand_model]', 'material[brand_model]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Person-in-charge:</label>
          <div class="input">
            <?php select_query_tag($pics, 'id', 'pic', '', 'material[person_in_charge]', 'material[person_in_charge]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Status:</label>
          <div class="input">
            <?php select_query_tag($status, 'id', 'description', '', 'material[status]', 'material[status]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">WIP Line Entry:</label>
          <div class="input">
            <?php select_query_tag($terminals, 'id', 'terminal', '', 'material[production_entry_terminal_id]', 'material[production_entry_terminal_id]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="material[description]" name="material[description]"></textarea>
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
            <?php select_query_tag($currencies, 'id', 'code', '', 'item_cost[currency]', 'item_cost[currency]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Cost:</label>
          <div class="input">
            <input type="text" id="item_cost[cost]" name="item_cost[cost]" value=""/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Transportation Rate:</label>
          <div class="input">
            <input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]" value=""/>
          </div>
          <div class="clear"></div>
        </div>
				<br/>
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