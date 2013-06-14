<?php
  /* Module: Plan Purchase Orders - Show  */
  $capability_key = 'show_plan_po_models';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$purchase_order = $Query->purchase_order_by_id($_GET['pid']);
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
                  <td width="120">P/O Number:</td><td width="340"><input type="text" value="<?php echo $purchase_order['po_number'] ?>" class="text-field magenta" disabled/></td>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $purchase_order['client'] ?>" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>P/O Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($purchase_order['po_date']))?>" class="text-field text-date" disabled/></td>
                  <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($purchase_order['ship_date']))?>" class="text-field" disabled/></td>
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
                 <td width="140" class="border-right">Model Code</td>
                 <td class="border-right text-center">Remarks</td>
                 <td width="55" class="border-right text-center">Qty</td>
                 <td width="60" class="border-right text-center">Unit</td>
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
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('plan-pos.php'); ?>"/>
             </div>
      </form>
   </div>
       
     
	</div>
      
       <script>
				$(function() {
			  	var data = { 
			    	"url":"/populate/purchase-order-items.php?pid=<?php echo $purchase_order['id'] ?>",
			      "limit":"50",
						"data_key":"purchase_order_items",
						"row_template":"row_template_plan_po_models_read_only",
					}
				
					$('#grid-purchase-order-items').grid(data);
					//$('#order_amount').currency_format(<?php echo $purchase_order['total_amount']; ?>);
			  }) 
			  
       </script>

<?php }
require('footer.php'); ?>