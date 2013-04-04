<?php
  /*
   * Module: Materials 
  */
  $capability_key = 'edit_supplier';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
		if($_POST['action'] == 'edit_supplier') {
			$args = array('variables' => $_POST['supplier'], 'conditions' => 'id='.$_POST['sid']); 
			$num_of_records = $Posts->EditSupplier($args);
			redirect_to($Capabilities->All['show_supplier']['url'].'?sid='.$_POST['sid']);		
		} 
		
	  if(isset($_GET['sid'])) {
	  	$supplier = $DB->Find('suppliers', array(
	  		'columns' => 'suppliers.*', 'conditions' => 'suppliers.id = '.$_GET['sid']));
	  }
		
	  $supplier_types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('supplier_type').'"', 
						'sort_column' => 'description'));
	  $products_services = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('product_service').'"', 
	  					'sort_column' => 'description')); 
	  $terms_of_payment = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('term_of_payment').'"', 
	  					'sort_column' => 'description')); 
	  $countries = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('country').'"', 
	  					'sort_column' => 'description'));
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_supplier']['url'].'?sid='.$_GET['sid'].'" class="nav">'.$Capabilities->All['show_supplier']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_supplier']['url'].'" class="nav">'.$Capabilities->All['add_supplier']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
        <input type="hidden" name="action" value="edit_supplier"> 
				<input type="hidden" name="sid" value="<?php echo $_GET['sid'] ?>"> 
				
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Supplier Code:</td><td width="310"><input type="text" id="supplier[supplier_code]" name="supplier[supplier_code]" value="<?php echo $supplier['supplier_code'] ?>" class="text-field magenta" /></td>
              <td width="150">Name:</td><td><input type="text" id="supplier[name]" name="supplier[name]" value="<?php echo $supplier['name'] ?>" class="text-field" /></td>
           </tr>
           <tr>
              <td>Type:</td><td><?php select_query_tag($supplier_types, 'id', 'description', $supplier['supplier_type'], 'supplier[supplier_type]', 'supplier[supplier_type]', '', 'width:192px;'); ?></td>
              <td>Product/Service:</td><td><?php select_query_tag($products_services, 'id', 'description', $supplier['product_service'], 'supplier[product_service]', 'supplier[product_service]', '', 'width:192px;'); ?></td>
           </tr>
           <tr>
              <td>Terms of Payment:</td><td><?php select_query_tag($terms_of_payment, 'id', 'description', $supplier['term_of_payment'], 'supplier[term_of_payment]', 'supplier[term_of_payment]', '', 'width:192px;'); ?></td>
              <td>Country:</td><td><?php select_query_tag($countries, 'id', 'description', $supplier['country'], 'supplier[country]', 'supplier[country]', '', 'width:192px;'); ?></td>
           </tr>            
           <tr>
              <td>Address:</td>
              <td colspan="99">
                <input type="text" id="supplier[address]" name="supplier[address]" value="<?php echo $supplier['address'] ?>" class="text-field" style="width:645px" />
              </td>
           </tr>          
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="supplier[description]" name="supplier[description]" value="<?php echo $supplier['description'] ?>" class="text-field" style="width:645px" />
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        <br/>
        
				<h3 class="form-title">Contact Information</h3>
        <table>
           <tr>
              <td width="150">Representative:</td><td width="310"><input type="text" id="supplier[representative]" name="supplier[representative]" value="<?php echo $supplier['representative'] ?>" class="text-field" /></td>
              <td width="150"></td><td></td>
           </tr>
           <tr>
              <td>Contact #1:</td><td><input type="text" id="supplier[contact_no1]" name="supplier[contact_no1]" value="<?php echo $supplier['contact_no1'] ?>" class="text-field" /></td>
              <td>Contact #2:</td><td><input type="text" id="supplier[contact_no2]" name="supplier[contact_no2]" value="<?php echo $supplier['contact_no2'] ?>" class="text-field" /></td>
           </tr>
           <tr>
              <td>Fax #:</td><td><input type="text" id="supplier[fax_no]" name="supplier[fax_no]" value="<?php echo $supplier['fax_no'] ?>" class="text-field" /></td>
              <td>Email:</td><td><input type="text" id="supplier[email]" name="supplier[email]" value="<?php echo $supplier['email'] ?>" class="text-field" /></td>
           </tr>  
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
				<div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Update" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('suppliers-show.php?sid='.$_GET['sid']); ?>"/>
         </div>
				</form>
		</div>
	</div>

<?php }
require('footer.php'); ?>