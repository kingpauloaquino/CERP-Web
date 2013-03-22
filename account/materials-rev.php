<?php
  /*
   * Module: Material - New 
  */
  $capability_key = 'add_material_rev';
  require('header.php');
  
	if($_POST['action'] == 'add_material_rev') { 
		$rev = (strlen($_POST['material']['material_rev'])==1 && ctype_alpha($_POST['material']['material_rev'])) ? $_POST['material']['material_rev'] : 'A';
		$_POST['material']['material_code'] = $_POST['material']['base_code'] . $rev;		
		$id = $Posts->AddMaterial($_POST['material']);
		$_POST['item_cost']['mid'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		$_POST['material_rev']['base_material_id'] = (int) $_POST['material']['material_id'];
		$_POST['material_rev']['material_id'] = $id;
		$_POST['material_rev']['revision'] = $_POST['material']['material_rev'];
		$Posts->AddMaterialRev($_POST['material_rev']);
		if(isset($id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$id); }
	} 
	
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'sort_column' => 'first_name',
																	'conditions' => 'role = 5'));
	
	$types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('material_type').'"', 'sort_column' => 'description'));
  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'sort_column' => 'classification'));
	$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'sort_column' => 'brand_model'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'sort_column' => 'name'));
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'sort_column' => 'code'));
  $currencies = $DB->Get('lookups', array('columns' => 'id, code', 'conditions'  => 'parent = "'.get_lookup_code('currency').'"', 'sort_column' => 'code'));
	$status = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('item_status').'"', 'sort_column' => 'description'));	
?>
<script type="text/javascript" src="../javascripts/jquery.watermarkinput.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.searchbox').keydown(function(e) { if (e.keyCode == 9) { $('.live_search_display').hide(); }});
		
		$('.searchbox').keyup(function() {
			var searchbox = $(this).val().toUpperCase();
			if(searchbox=='') {	$('.live_search_display').hide();}
			else {
				switch ($(this).attr('id')) {
					case 'material[base_code]':
					add_live_search('#live_search_display', 'revision', 
										'materials', 'materials.id, materials.parent, materials.material_code AS item_code, materials.description,  '+
										'materials.material_type, materials.material_classification, materials.brand_model AS brand_model_id, '+
										'lookups.description AS mat_typ, item_classifications.classification AS mat_class, brand_models.brand_model, '+
										'lookups2.description AS status, CONCAT(users.first_name, " ", users.last_name) AS pic ',
										'LEFT OUTER JOIN lookups ON materials.material_type = lookups.id '+
										'LEFT OUTER JOIN lookups AS lookups2 ON materials.status = lookups2.id '+
										'LEFT OUTER JOIN users ON materials.person_in_charge = users.id '+
										'LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id '+
										'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id ',
										'materials.base = FALSE AND materials.material_code LIKE "' + searchbox + '%" ', searchbox); break;
				}				
			}
			return false;    
		});
		$('[name*="material[material_rev]"]').keyup(function() {
			$(this).val($(this).val().toUpperCase());
		});
		$('[name*="material[base_code]"]').Watermark("Base Material Code");
		$('[name*="material[material_rev]"]').Watermark("Rev [A-Z]");	
	});
</script>

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
				
				<input type="hidden" name="action" value="add_material_rev">
	   		<input type="hidden" id="material[material_id]" name="material[material_id]" />
	   		<input type="hidden" id="material[material_type]" name="material[material_type]" />
	   		<input type="hidden" id="material[material_classification]" name="material[material_classification]" />
	   		<input type="hidden" id="material[brand_model]" name="material[brand_model]" />
				<input type="hidden" id="material[parent]" name="material[parent]">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				
				<span class="notice">
          <p class="info"><strong>Notice!</strong> Material codes should be unique.</p>
        </span>
				
				<div class="field">
          <label class="label">Material Code:</label>
          <div class="input">
            <input type="text" id="material[base_code]" name="material[base_code]" class="searchbox" autocomplete="off" />
						<div id="live_search_display" class="live_search_display"></div>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Revision:</label>
          <div class="input">
            <input type="text" id="material[material_rev]" name="material[material_rev]" class="text" autocomplete="off" />
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
          <label class="label">Type:</label>
          <div class="input">
            <input type="text" id="material_type" name="material_type" readonly="reaadonly"/>
          </div>
          <div class="clear"></div>
        </div>
			
        <div class="field">
          <label class="label">Class:</label>
          <div class="input">
            <input type="text" id="material_classification" name="material_classification" readonly="reaadonly"/>
          </div>
          <div class="clear"></div>
        </div>
			
        <div class="field">
          <label class="label">Model:</label>
          <div class="input">
            <input type="text" id="brand_model" name="brand_model" readonly="reaadonly"/>
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