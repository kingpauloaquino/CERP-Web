<?php
  /*
   * Module: Product - Add 
  */
  $capability_key = 'add_product';
  require('header.php');
	
	if($_POST['action'] == 'add_product') { 
		$id = $Posts->AddProduct($_POST['product']);
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		if(isset($id)){ redirect_to($Capabilities->All['show_product']['url'].'?pid='.$id); }
	} 
	
  $brands = $DB->Get('brand_models', array('columns' => 'id, brand_model', 'sort_column' => 'brand_model', 'conditions' => 'parent IS NULL'));
  $packs = $DB->Get('item_classifications', array('columns' => 'id, classification', 'sort_column' => 'classification', 'conditions' => 'item_type = "PRD"'));
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
				<input type="hidden" name="action" value="add_product">
				<input type="hidden" id="item_cost[item_type]" name="item_cost[item_type]" value="PRD">
        
        <div class="field">
          <label class="label">Product Code:</label>
          <div class="input">
            <input type="text" id="product[product_code]" name="product[product_code]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Brand:</label>
          <div class="input">
            <?php select_query_tag($brands, 'id', 'brand_model', '', 'product[brand_model]', 'product[brand_model]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Pack:</label>
          <div class="input">
            <?php select_query_tag($packs, 'id', 'classification', '', 'product[product_classification]', 'product[product_classification]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Status:</label>
          <div class="input">
            <?php select_query_tag($status, 'id', 'description', '', 'product[status]', 'product[status]', '', 'text w180'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="product[description]" name="product[description]"></textarea>
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

<?php require('footer.php'); ?>