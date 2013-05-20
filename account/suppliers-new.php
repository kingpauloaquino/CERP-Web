<?php
  /*
   * Module: Materials 
  */
  $capability_key = 'add_supplier';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		if($_POST['action'] == 'add_supplier') { 
			$id = $Posts->AddSupplier($_POST['supplier']);
			if(isset($id)){ redirect_to($Capabilities->All['show_supplier']['url'].'?sid='.$id); }
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
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<input type="hidden" name="action" value="add_supplier"> 
				
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="130">Name:</td><td width="350"><input type="text" id="supplier[name]" name="supplier[name]" class="text-field" style="width:288px;" /></td>
              <td width="80">Code:</td><td><input type="text" id="supplier[supplier_code]" name="supplier[supplier_code]" value="<?php echo generate_new_code('supplier_code'); ?>" class="text-field magenta" style="width:210px;" /></td>
           </tr>
           <tr>
              <td>Product/Service:</td><td><?php select_query_tag($products_services, 'id', 'description', '', 'supplier[product_service]', 'supplier[product_service]', '', 'width:300px;'); ?></td>
              <td>Type:</td><td><?php select_query_tag($supplier_types, 'id', 'description', '', 'supplier[supplier_type]', 'supplier[supplier_type]', '', 'width:222px;'); ?></td>
           </tr>
           <tr>
              <td>Terms of Payment:</td><td><?php select_query_tag($terms_of_payment, 'id', 'description', '', 'supplier[term_of_payment]', 'supplier[term_of_payment]', '', 'width:300px;'); ?></td>
              <td>Country:</td><td><?php select_query_tag($countries, 'id', 'description', '', 'supplier[country]', 'supplier[country]', '', 'width:222px;'); ?></td>
           </tr>            
           <tr>
              <td>Address:</td>
              <td colspan="99">
                <input type="text" id="supplier[address]" name="supplier[address]" class="text-field" style="width:645px" />
              </td>
           </tr>          
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" id="supplier[description]" name="supplier[description]" class="text-field" style="width:645px" />
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        <br/>
        
				<h3 class="form-title">Contact Information</h3>
        <table>
           <tr>
              <td width="130">Representative:</td>
              <td width="80" colspan="99">
                <input type="text" id="supplier[representative]" name="supplier[representative]" class="text-field" style="width:645px" />
              </td>
           </tr>
           <tr>
              <td width="130">Email:</td><td width="350"><input type="text" id="supplier[email]" name="supplier[email]" class="text-field" style="width:288px;" /></td>
              <td width="80">Contact #1:</td><td><input type="text" id="supplier[contact_no1]" name="supplier[contact_no1]" class="text-field" style="width:210px;" /></td>
           </tr>
           <tr>
              <td>Fax #:</td><td><input type="text" id="supplier[fax_no]" name="supplier[fax_no]" class="text-field" style="width:288px;" /></td>
              <td>Contact #2:</td><td><input type="text" id="supplier[contact_no2]" name="supplier[contact_no2]" class="text-field" style="width:210px;" /></td>
           </tr>  
           <tr><td height="5" colspan="99"></td></tr>
        </table>
        
				<div class="field-command">
       	   <div class="text-post-status"></div>
       	   <input type="submit" value="Create" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('suppliers.php'); ?>"/>
         </div>
				
				</form>
		</div>
	</div>

<?php }
require('footer.php'); ?>