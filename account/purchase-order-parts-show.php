<?php
  /* Module: Purchase Orders - Show  */
  $capability_key = 'show_purchase_order_parts';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$purchase_order = $Query->purchase_order_by_id($_GET['pid']);
		$product = $Query->product_by_purchase_order_item($_GET['popid'])
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
      <form id="order-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
         <div>
         	<table>
               <tr>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $purchase_order['client'] ?>" class="text-field" disabled/></td>
                  <td width="120"></td><td width="340"></td>
               </tr>
               <tr>
                  <td width="120">P/O Number:</td><td width="340"><input type="text" value="<?php echo $purchase_order['order_no'] ?>" class="text-field magenta" disabled/></td>
                  <td width="120">P/O Date:</td><td width="340"><input type="text" value="<?php echo date("F d, Y", strtotime($purchase_order['order_date']))?>" class="text-field text-date" disabled/></td>
               </tr>
               <tr>
                  <td>Terms:</td><td><input type="text" value="<?php echo $purchase_order['terms'] ?>" class="text-field" disabled/></td>
                  <td>Ship Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($purchase_order['ship_date']))?>" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>Payment Terms:</td>
                  <td colspan="99">
                    <input type="text" value="<?php echo $purchase_order['payment_terms'] ?>" class="text-field" style="width:645px" disabled/>
                  </td>
               </tr>    
               <tr>
                  <td>Completion:</td><td><input type="text" value="<?php echo $purchase_order['completion_status'] ?>" class="text-field" disabled/></td>
                  <td></td><td></td>
               </tr>  
               <tr>
                  <td>Product:</td><td><input type="text" value="<?php echo $product['product_code'] ?>" class="text-field" disabled/></td>
                  <td></td><td></td>
               </tr>  
               <tr><td height="5" colspan="99"></td></tr>
            </table>
         </div>
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-purchase-order-item-parts" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="140" class="border-right text-center">Material Code</td>
                 <td class="border-right text-center">Description</td>
                 <td width="60" class="border-right text-center">Part Qty</td>
                 <td width="90" class="border-right text-center">Qty</td>
                 <td width="90" class="border-right text-center">Total Qty</td>
                 <td width="60" class="border-right text-center">Unit</td>
                 <td width="60" class="border-right text-center">MOQ</td>
                 <td width="80" class="border-right text-center">Unit Price</td>
                 <td width="80" class="border-right text-center">P/O Qty</td>
                 <td width="100" class="text-center">P/O Price</td>
               </tr>
             </thead>
             <tbody id="purchase-order-item-parts"></tbody>
           </table>
         </div>
         
         <!-- BOF REMARKS -->
         <div>
         	<table width="100%">
               <tr><td height="5" colspan="99"></td></tr>
               <tr>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="order_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
            </table>
         </div>
         <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Save As:</strong>&nbsp;&nbsp;<?php echo $purchase_order['status']; ?>
               </div>
               <?php if($purchase_order['status'] != "Publish") { ?>
<!--                <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('purchase-orders-edit.php?wid='. $_GET['wid']); ?>"/> -->
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('purchase-orders.php'); ?>"/>
             </div>
      </form>
   </div>
       
     
	</div>
      
       <script>
				$(function() {
			  	var data = { 
			    	"url":"/populate/purchase-order-item-parts.php?poid=<?php echo $_GET['popid'] ?>",
			      "limit":"50",
						"data_key":"purchase_order_item_parts",
						"row_template":"row_template_purchase_order_item_parts_read_only",
					}
				
					$('#grid-purchase-order-item-parts').grid(data);
					// var x = compute_total_amount();
					// alert(x);
			  }) 
			  
			  function compute_total_amount() {
	        row_total = 0; 
			    $("#purchase-order-item-parts").find('tr').each(function() {
           row_total += Number($(this).find('td.amount').html());
			    });
			    return row_total;
				}
			  
			  
       </script>

<?php }
require('footer.php'); ?>