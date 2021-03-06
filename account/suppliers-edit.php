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
						'order' => 'description'));
	  $products_services = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('product_service').'"', 
	  					'order' => 'description')); 
	  $terms_of_payment = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('term_of_payment').'"', 
	  					'order' => 'description')); 
	  $countries = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "'.get_lookup_code('country').'"', 
	  					'order' => 'description'));
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
			<form action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
        <input type="hidden" name="action" value="edit_supplier"> 
				<input type="hidden" name="sid" value="<?php echo $_GET['sid'] ?>"> 
				
				<div class="form-container">
					<h3 class="form-title">Details</h3>
	        <table>
	           <tr>
	              <td width="130">Name:</td><td width="350"><input type="text" id="supplier[name]" name="supplier[name]" value="<?php echo $supplier['name'] ?>" class="text-field" style="width:288px;" /></td>
	              <td width="80">Code:</td><td><input type="text" id="supplier[supplier_code]" name="supplier[supplier_code]" value="<?php echo $supplier['supplier_code'] ?>" class="text-field magenta" style="width:210px;" /></td>
	           </tr>
	           <tr>
	              <td>Product/Service:</td><td><?php select_query_tag($products_services, 'id', 'description', $supplier['product_service'], 'supplier[product_service]', 'supplier[product_service]', '', 'width:300px;'); ?></td>
	              <td>Type:</td><td><?php select_query_tag($supplier_types, 'id', 'description', $supplier['supplier_type'], 'supplier[supplier_type]', 'supplier[supplier_type]', '', 'width:222px;'); ?></td>
	           </tr>
	           <tr>
	              <td>Terms of Payment:</td><td><?php select_query_tag($terms_of_payment, 'id', 'description', $supplier['term_of_payment'], 'supplier[term_of_payment]', 'supplier[term_of_payment]', '', 'width:300px;'); ?></td>
	              <td>Country:</td><td><?php select_query_tag($countries, 'id', 'description', $supplier['country'], 'supplier[country]', 'supplier[country]', '', 'width:222px;'); ?></td>
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
				</div>
        <br/>
        <div class="form-container">
					<h3 class="form-title">Contact Information</h3>
	        <table>
	           <tr>
	              <td width="130">Representative:</td>
	              <td width="80" colspan="99">
	                <input type="text" id="supplier[representative]" name="supplier[representative]" value="<?php echo $supplier['representative'] ?>" class="text-field" style="width:645px" />
	              </td>
	           </tr>
	           <tr>
	              <td width="130">Email:</td><td width="350"><input type="text" id="supplier[email]" name="supplier[email]" value="<?php echo $supplier['email'] ?>" class="text-field" style="width:288px;" /></td>
	              <td width="80">Contact #1:</td><td><input type="text" id="supplier[contact_no1]" name="supplier[contact_no1]" value="<?php echo $supplier['contact_no1'] ?>" class="text-field" style="width:210px;" /></td>
	           </tr>
	           <tr>
	              <td>Fax #:</td><td><input type="text" id="supplier[fax_no]" name="supplier[fax_no]" value="<?php echo $supplier['fax_no'] ?>" class="text-field" style="width:288px;" /></td>
	              <td>Contact #2:</td><td><input type="text" id="supplier[contact_no2]" name="supplier[contact_no2]" value="<?php echo $supplier['contact_no2'] ?>" class="text-field" style="width:210px;" /></td>
	           </tr>  
	           <tr><td height="5" colspan="99"></td></tr>
	        </table>	
				</div>
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