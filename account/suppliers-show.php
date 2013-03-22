<?php
  /*
   * Module: Materials 
  */
  $capability_key = 'show_supplier';
  require('header.php');
  
  if(isset($_REQUEST['sid'])) {
  	$supplier = $DB->Find('suppliers', array(
  		'columns' => 'suppliers.*', 
  	    'conditions' => 'suppliers.id = '.$_REQUEST['sid']
  	  )
	);
	
  }
  $supplier_type = $DB->Get('lookups', array('columns' => 'id, description', 
					'conditions' => 'id = '.$supplier['supplier_type']));
  $products_service = $DB->Get('lookups', array('columns' => 'id, description', 
  					'conditions' => 'id = '.$supplier['product_service'])); 
  $term_of_payment = $DB->Get('lookups', array('columns' => 'id, description', 
  					'conditions' => 'id = '.$supplier['term_of_payment'])); 
  $country = $DB->Get('lookups', array('columns' => 'id, description', 
  					'conditions' => 'id = '.$supplier['country'])); 
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['suppliers']['url'].'" class="nav">'.$Capabilities->All['suppliers']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_supplier']['url'].'" class="nav">'.$Capabilities->All['add_supplier']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_supplier']['url'].'?sid='.$_REQUEST['sid'].'" class="nav">'.$Capabilities->All['edit_supplier']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
        <h3 class="form-title">Basic Information</h3>
        
        <div class="field">
          <label class="label">Supplier Code:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $supplier['supplier_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Name:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $supplier['name'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Type:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $supplier_type[0]['description'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Product / Service:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $products_service[0]['description'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terms of Payment:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $term_of_payment[0]['description'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Country:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $country[0]['description'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Address:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $supplier['address'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $supplier['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
        <br/>
        <h3 class="form-title">Contact Information</h3>
        <div class="field">
          <label class="label">Representative:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $supplier['representative'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Contact #:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $supplier['contact_no1'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Fax #:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $supplier['fax_no'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Email:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $supplier['email'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
     </form>
				
		</div>
	</div>

<?php require('footer.php'); ?>