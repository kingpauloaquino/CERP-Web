<?php
  /*
   * Module: Materials 
  */
  $capability_key = 'show_supplier';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	  if(isset($_GET['sid'])) {
	  	$supplier = $DB->Find('suppliers', array(
	  		'columns' => 'suppliers.*, lookups1.description AS supplier_type, lookups2.description AS product_service, 
											lookups3.description AS term_of_payment, lookups4.description AS country', 
				'joins' => 'LEFT OUTER JOIN lookups AS lookups1 ON lookups1.id = suppliers.supplier_type
										LEFT OUTER JOIN lookups AS lookups2 ON lookups2.id = suppliers.product_service
										LEFT OUTER JOIN lookups AS lookups3 ON lookups3.id = suppliers.term_of_payment
										LEFT OUTER JOIN lookups AS lookups4 ON lookups4.id = suppliers.country',
	  	    'conditions' => 'suppliers.id = '.$_GET['sid']
	  	  ));	
	  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['suppliers']['url'].'" class="nav">'.$Capabilities->All['suppliers']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_supplier']['url'].'" class="nav">'.$Capabilities->All['add_supplier']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_supplier']['url'].'?sid='.$_GET['sid'].'" class="nav">'.$Capabilities->All['edit_supplier']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="130">Name:</td><td width="350"><input type="text" value="<?php echo $supplier['name'] ?>" class="text-field" style="width:288px;" disabled/></td>
              <td width="80">Code:</td><td><input type="text" value="<?php echo $supplier['supplier_code'] ?>" class="text-field" style="width:210px;" disabled/></td>
           </tr>
           <tr>
              <td>Product/Service:</td><td><input type="text" value="<?php echo $supplier['product_service'] ?>" class="text-field" style="width:288px;" disabled/></td>
              <td>Type:</td><td><input type="text" value="<?php echo $supplier['supplier_type'] ?>" class="text-field" style="width:210px;" disabled/></td>
           </tr>
           <tr>
              <td>Terms of Payment:</td><td><input type="text" value="<?php echo $supplier['term_of_payment'] ?>" class="text-field" style="width:288px;" disabled/></td>
              <td>Country:</td><td><input type="text" value="<?php echo $supplier['country'] ?>" class="text-field" style="width:210px;" disabled/></td>
           </tr>            
           <tr>
              <td>Address:</td>
              <td colspan="99">
                <input type="text" value="<?php echo $supplier['address'] ?>" class="text-field" style="width:645px" disabled/>
              </td>
           </tr>          
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" value="<?php echo $supplier['description'] ?>" class="text-field" style="width:645px" disabled/>
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
                <input type="text" value="<?php echo $supplier['representative'] ?>" class="text-field" style="width:645px" disabled/>
              </td>
           </tr>
           <tr>
              <td width="130">Email:</td><td width="350"><input type="text" value="<?php echo $supplier['email'] ?>" class="text-field" style="width:288px;" disabled/></td>
              <td width="80">Contact #1:</td><td><input type="text" value="<?php echo $supplier['contact_no1'] ?>" class="text-field" style="width:210px;" disabled/></td>
           </tr>
           <tr>
              <td>Fax #:</td><td><input type="text" value="<?php echo $supplier['fax_no'] ?>" class="text-field" style="width:288px;" disabled/></td>
              <td>Contact #2:</td><td><input type="text" value="<?php echo $supplier['contact_no2'] ?>" class="text-field" style="width:210px;" disabled/></td>
           </tr>  
           <tr><td height="5" colspan="99"></td></tr>
        </table>
     </form>
				
		</div>
	</div>

<?php }
require('footer.php'); ?>