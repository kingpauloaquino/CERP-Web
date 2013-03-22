<?php
  /*
   * Module: Materials 
  */
  $capability_key = 'add_supplier';
  require('header.php');
	
	if($_POST['action'] == 'add_supplier') { 
		$id = $Posts->AddSupplier($_POST['supplier']);
		if(isset($id)){ redirect_to($Capabilities->All['show_supplier']['url'].'?sid='.$id); }
	} 
	
  $supplier_types = $DB->Get('lookups', array('columns' => 'id, description', 
					'conditions' => 'parent = "'.get_lookup_code('supplier_type').'"', 
					'sort_column' => 'description'));
  $products_services = $DB->Get('lookups', array('columns' => 'id, description', 
  					'conditions' => 'parent = "'.get_lookup_code('product_service').'"', 
  					'sort_column' => 'description')); 
  $terms_of_payment = $DB->Get('lookups', array('columns' => 'id, description', 
  					'conditions' => 'parent = "'.get_lookup_code('term_of_payment').'"', 
  					'sort_column' => 'description')); 
  $countries = $DB->Get('lookups', array('columns' => 'id, description', 
  					'conditions' => 'parent = "'.get_lookup_code('country').'"', 
  					'sort_column' => 'description'));

?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
        <h3 class="form-title">Basic Information</h3>

				<input type="hidden" name="action" value="add_supplier"> 
				
				<span class="notice">
<!--           <p class="info"><strong>Notice!</strong> Material codes should be unique.</p> -->
        </span>
				
				<div class="field">
          <label class="label">Supplier Code:</label>
          <div class="input">
            <input type="text" id="supplier[supplier_code]" name="supplier[supplier_code]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Name:</label>
          <div class="input">
            <input type="text" id="supplier[name]" name="supplier[name]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Type:</label>
          <div class="input">
            <?php select_query_tag($supplier_types, 'id', 'description', '', 'supplier[supplier_type]', 'supplier[supplier_type]', '', 'text w250'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Product / Service:</label>
          <div class="input">
            <?php select_query_tag($products_services, 'id', 'description', '', 'supplier[product_service]', 'supplier[product_service]', '', 'text w250'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terms of Payment:</label>
          <div class="input">
            <?php select_query_tag($terms_of_payment, 'id', 'description', '', 'supplier[term_of_payment]', 'supplier[term_of_payment]', '', 'text w250'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Country:</label>
          <div class="input">
            <?php select_query_tag($countries, 'id', 'description', '', 'supplier[country]', 'supplier[country]', '', 'text w250'); ?>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Address:</label>
          <div class="input">
            <textarea id="supplier[address]" name="supplier[address]"></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="supplier[description]" name="supplier[description]"></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
        <br/>
        <h3 class="form-title">Contact Information</h3>
        <div class="field">
          <label class="label">Representative:</label>
          <div class="input">
            <input type="text" id="supplier[representative]" name="supplier[representative]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Contact #:</label>
          <div class="input">
            <input type="text" id="supplier[contact_no1]" name="supplier[contact_no1]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Fax #:</label>
          <div class="input">
            <input type="text" id="supplier[fax_no]" name="supplier[fax_no]"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Email:</label>
          <div class="input">
            <input type="text" id="supplier[email]" name="supplier[email]"/>
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

<?php require('footer.php'); ?>