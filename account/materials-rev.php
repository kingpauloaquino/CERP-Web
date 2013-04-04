<?php
  /*
   * Module: Material - New 
  */
  $capability_key = 'add_material_revision';
  require('header.php');
	
		$allowed = $Role->isCapableByName($capability_key);
	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	if($_POST['action'] == 'add_material_rev') { 
		$rev = (strlen($_POST['material']['material_rev'])==1 && ctype_alpha($_POST['material']['material_rev'])) ? $_POST['material']['material_rev'] : 'A';
		$_POST['material']['material_code'] = $_POST['material']['base_code'] . $rev;		
		$id = $Posts->AddMaterial($_POST['material']);
		
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		
		$_POST['material_rev']['base_material_id'] = (int) $_POST['material']['item_id'];
		$_POST['material_rev']['item_id'] = $id;
		$_POST['material_rev']['revision'] = $_POST['material']['material_rev'];
		$Posts->AddMaterialRev($_POST['material_rev']);
		
		if(isset($id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$id); }
	} 
	
	$pics = $DB->Get('users', array('columns' => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic', 'sort_column' => 'first_name'));
	
	$types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('material_type').'"', 'sort_column' => 'description'));
  $classifications = $DB->Get('item_classifications', array('columns' => 'id, classification', 'sort_column' => 'classification'));
	$models = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'sort_column' => 'brand_model'));
	$suppliers = $DB->Get('suppliers', array('columns' => 'id, name', 'sort_column' => 'name'));
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'sort_column' => 'code'));
	$terminals = $DB->Get('terminals', array('columns' => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal', 'conditions' => 'location_id=4 AND type="IN"', 'sort_column' => 'id')); // location_id=4 (WIP)
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
										'materials.base = FALSE AND material_type=70 AND materials.material_code LIKE "' + searchbox + '%" ', searchbox); break;
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
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="add_material_rev">
	   		<input type="hidden" id="material[item_id]" name="material[item_id]" />
	   		<input type="hidden" id="material[material_type]" name="material[material_type]" />
	   		<input type="hidden" id="material[material_classification]" name="material[material_classification]" />
	   		<input type="hidden" id="material[brand_model]" name="material[brand_model]" />
				<input type="hidden" id="material[parent]" name="material[parent]">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="MAT">
				
        <h3 class="form-title">Details</h3>		      
        <table>
           <tr>
              <td width="150">Base Material Code:</td><td width="310"><input type="text" id="material[base_code]" name="material[base_code]" class="text-field searchbox" autocomplete="off" />
              	<div id="live_search_display" class="live_search_display"></div>
              </td>
              <td width="150">Revision:</td><td><input type="text" id="material[material_rev]" name="material[material_rev]" class="text-field" placeholder="Revision [A-Z]" /></td>
           </tr>
           <tr>
              <td>Classification:</td><td><input type="text" id="material_classification" name="material_classification" class="text-field" disabled/></td>
              <td>Model:</td><td><input type="text" id="brand_model" name="brand_model" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Type:</td><td><input type="text" id="material_type" name="material_type" class="text-field" disabled/></td>
              <td>Status:</td><td><?php select_query_tag($status, 'id', 'description', '', 'material[status]', 'material[status]', '', 'width:192px;'); ?></td>
           </tr>    
           <tr>
              <td>Barcode:</td><td><input type="text" id="material[bar_code]" name="material[bar_code]" class="text-field" /></td>
              <td>Person-in-charge:</td><td><?php select_query_tag($pics, 'id', 'pic', '', 'material[person_in_charge]', 'material[person_in_charge]', '', 'width:192px;'); ?>
              </td>
           </tr>      
           <tr>
              <td>Address:</td><td><input type="text"  class="text-field" /></td>
              <td>WIP Line Entry:</td><td><?php select_query_tag($terminals, 'id', 'terminal', '', 'material[production_entry_terminal_id]', 'material[production_entry_terminal_id]', '', 'width:192px;'); ?>
              </td>
           </tr>             
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="material[description]" name="material[description]" class="text-field" style="width:645px" />
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
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('materials.php'); ?>"/>
         </div>
				
				</form>
			</div>
		</div>
	</div>

<?php }
require('footer.php'); ?>