<?php
  /* Module: Material Plan  */
  $capability_key = 'show_material_plan';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		if(isset($_GET['sid'])) {
	  	$supplier = $DB->Find('suppliers', array(
	  		'columns' => 'suppliers.*', 
	  	    'conditions' => 'suppliers.id = '.$_GET['sid']
	  	  ));	
	  }
  	
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
          <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	 <input type="hidden" name="action" value="add_purchase"/>
      	 <input type="hidden" name="purchase[supplier_id]" value="<?php echo $_GET['sid'] ?>"/>
      	 <input type="hidden" name="purchase[po_date]" value="<?php echo date("F d, Y") ?>"/>
      	 <input type="hidden" name="purchase[delivery_date]" value="<?php echo date("F d, Y") ?>"/>
             <!-- BOF TEXTFIELDS -->
             <div>
			        <table>
			           <tr>
			              <td width="130">Name:</td><td width="350"><input type="text" value="<?php echo $supplier['name'] ?>" class="text-field" style="width:288px;" disabled/></td>
			              <td width="80">Code:</td><td><input type="text" value="<?php echo $supplier['supplier_code'] ?>" class="text-field" style="width:210px;" disabled/></td>
			           </tr>
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
        
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-material-plan" class="grid jq-grid" style="min-height:146px;">
               <table cellspacing="0" cellpadding="0">
                 <thead>
                   <tr>
                     <td width="160" class="border-right text-center">Item Code</td>
                     <td class="border-right text-center">Model</td>
                     <td width="50" class="border-right text-center">DR</td>
                     <td width="100" class="border-right text-center">Prod. Plan</td>
                     <td width="100" class="border-right text-center">Inventory</td>
                     <td width="100" class="border-right text-center">Open P/O</td>
                     <td width="100" class="border-right text-center">Balance</td>
                     <td width="90" class="border-right text-center">MOQ</td>
                     <td width="90" class="border-right text-center">Unit Price</td>
                     <td width="90" class="text-center">P/O Qty</td>
                   </tr>
                 </thead>
                 <tbody id="material-plan"></tbody>
               </table>
             </div>
             
           
             
             <!-- BOF REMARKS -->
             <!-- <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr>
                      <td></td>
                      <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" class="text-right" style="width:95px;" disabled/></td>
                   </tr>
                </table>
             </div> -->
             
             <div class="field-command">
           	   <!-- <div class="text-post-status">
           	     <strong>Saved As:</strong>&nbsp;&nbsp;<?php echo $purchase['status']; ?>
               </div> -->
<!--            	   <input type="button" value="Download" class="btn btn-download" rel="<?php echo excel_file('?category=purchase&id='. $purchase['id']); ?>"/> -->
               <?php if($purchase['status'] != "Publish") { ?>
<!--                <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('material-plan-edit.php?sid='. $_GET['sid']); ?>"/> -->
           	   <?php } ?>
               <input type="submit" value="Create P/O" class="btn"/>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('material-plan.php'); ?>"/>
             </div>
          </form>
       </div>
       
       <script>
       	$(function() {
			  	var data = { 
			    	"url":"/populate/material-plan.php?sid=<?php echo $_GET['sid']; ?>",
			      "limit":"50",
						"data_key":"material_plan",
						"row_template":"row_template_material_plan"
					}
				
					$('#grid-material-plan').grid(data);
					//$('#purchase_amount').currency_format(<?php echo $purchase['total_amount']; ?>);
			  }) 
      </script>

<?php }
require('footer.php'); ?>