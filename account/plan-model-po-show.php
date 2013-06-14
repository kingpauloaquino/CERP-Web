<?php
  /* Module: Plan Model POs - Show  */
  $capability_key = 'show_plan_model_pos';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$product = $Query->product_by_id($_GET['pid']);
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
      <form id="purchase-order-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
         <div>
         	<table>
						<tr>
						   <td width="120">Product Code:</td><td width="310"><input type="text" value="<?php echo $product['product_code'] ?>" class="text-field magenta" disabled/></td>
						   <td width="120">Brand:</td><td><input type="text" value="<?php echo $product['brand'] ?>" class="text-field" disabled/>
						   </td>
						</tr>
						<tr>
						   <td>Series:</td><td><input type="text" value="<?php echo $product['series'] ?>" class="text-field" disabled/></td>
						   <td></td>
						</tr>  
               <tr><td height="5" colspan="99"></td></tr>
            </table>
         </div>
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-purchase-order-items" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="140" class="border-right text-center">P/O No.</td>
                 <td width="90" class="border-right text-center">P/O Date</td>
                 <td class="border-right text-center">Remarks</td>
                 <td width="90" class="border-right text-center">Ship Date</td>
                 <td width="65" class="border-right text-center">P/O Qty</td>
               </tr>
             </thead>
             <tbody id="purchase-order-materials"></tbody>
           </table>
         </div>
         
         <div class="field-command">
           	   <!-- <div class="text-post-status">
           	     <strong>Save As:</strong>&nbsp;&nbsp;<?php echo $purchase_order['status']; ?>
               </div> -->
               <?php if($purchase_order['status'] != "Publish") { ?>
<!--                <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('purchase-orders-edit.php?pid='. $purchase_order['id']); ?>"/> -->
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('plan-models.php'); ?>"/>
             </div>
      </form>
   </div>
       
     
	</div>
      
       <script>
				$(function() {
			  	var data = { 
			    	"url":"/populate/product-pos.php?pid=<?php echo $_GET['pid'] ?>",
			      "limit":"50",
						"data_key":"product_pos",
						"row_template":"row_template_plan_product_pos_read_only",
					}
				
					$('#grid-purchase-order-items').grid(data);
					//$('#order_amount').currency_format(<?php echo $purchase_order['total_amount']; ?>);
			  }) 
			  
       </script>

<?php }
require('footer.php'); ?>