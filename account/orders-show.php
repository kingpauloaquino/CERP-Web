<?php
  /* Module: Orders - Show  */
  $capability_key = 'show_order';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$order = $Query->order_by_id($_GET['oid']);
?>
      <!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
            <span class="title">Orders &raquo; <span class="red"><?php echo $order['po_number']; ?></span></span>
            <div class="clear"></div>
      </h2>
		</div>

    <div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" value="<?php echo $order['id']; ?>" style="display: none" />
      </div>
      <form id="order-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
         <div>
         	<table>
               <tr>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $order['client'] ?>" class="text-field" disabled/></td>
                  <td width="120"></td><td width="340"></td>
               </tr>
               <tr>
                  <td>P/O Number:</td><td><input type="text" value="<?php echo $order['po_number'] ?>" class="text-field" disabled/></td>
                  <td>P/O Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($order['po_date']))?>" class="text-field text-date" disabled/></td>
               </tr>
               <tr>
                  <td>Terms:</td><td><input type="text" value="<?php echo $order['terms'] ?>" class="text-field" disabled/></td>
                  <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($order['delivery_date']))?>" class="text-field text-date" disabled/></td>
               </tr>
               <tr>
                  <td>Payment Terms:</td>
                  <td colspan="99">
                    <input type="text" value="<?php echo $order['payment_terms'] ?>" class="text-field" style="width:645px" disabled/>
                  </td>
               </tr>               
               <tr>
                  <td>Description:</td>
                  <td colspan="99">
                    <input type="text" value="<?php echo $order['description'] ?>" class="text-field" style="width:645px" disabled/>
                  </td>
               </tr>
               <tr><td height="5" colspan="99"></td></tr>
            </table>
         </div>
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-order-items" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="140" class="border-right">Item Code</td>
                 <td width="50" class="border-right">Type</td>
                 <td class="border-right text-center">Remarks</td>
                 <td width="55" class="border-right text-center">Qty</td>
                 <td width="60" class="border-right text-center">Unit</td>
                 <td width="100" class="border-right text-center">Unit Price</td>
                 <td width="100" class="border-right text-center">Amount Price</td>
               </tr>
             </thead>
             <tbody id="order-materials"></tbody>
           </table>
         </div>
         
         <!-- BOF REMARKS -->
         <div>
         	<table width="100%">
               <tr><td height="5" colspan="99"></td></tr>
               <tr>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="order_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
               <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $order['remarks'] ?></textarea></td></tr>
            </table>
         </div>
         <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Save As:</strong>&nbsp;&nbsp;<?php echo $order['status']; ?>
               </div>
           	   <input type="button" value="Download" class="btn btn-download" rel="<?php echo excel_file('?category=order&id='. $order['id']); ?>"/>
               <?php if($order['status'] != "Publish") { ?>
               <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('orders-edit.php?oid='. $order['id']); ?>"/>
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('orders.php'); ?>"/>
             </div>
      </form>
   </div>
       
     
	</div>
      
       <script>
				$(function() {
			  	var data = { 
			    	"url":"/populate/order-items.php",
			      "limit":"50",
						"data_key":"order_items",
						"row_template":"row_template_order_items_read_only",
					}
				
					$('#grid-order-items').grid(data);
					$('#order_amount').currency_format(<?php echo $order['total_amount']; ?>);
			  }) 
			  
       </script>

<?php }
require('footer.php'); ?>