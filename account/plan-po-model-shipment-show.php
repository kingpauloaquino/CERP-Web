<?php
  /* Module: Plan Purchase Orders  Model Shipments - Show  */
  $capability_key = 'show_plan_po_model_shipment';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$purchase_order = $Query->purchase_order_item_by_id($_GET['poid'], $_GET['pid']);
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
                  <td width="120">P/O Number:</td><td width="340"><input id="po_no" type="text" value="<?php echo $purchase_order['po_number'] ?>" class="text-field magenta" disabled/></td>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $purchase_order['client'] ?>" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>P/O Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($purchase_order['po_date']))?>" class="text-field text-date" disabled/></td>
                  <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($purchase_order['ship_date']))?>" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>Model:</td><td><input type="text" id="model" value="<?php echo $purchase_order['product_code'] ?>" class="text-field magenta" disabled/></td>
                  <td>P/O Qty:</td><td><input type="text" value="<?php echo $purchase_order['quantity'] ?>" class="text-field numbers text-right" disabled/></td>
               </tr>
               <tr><td height="5" colspan="99"></td></tr>
            </table>
         </div>
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-ship-plan-items" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all" disabled/></td>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="110" class="border-right text-center">Shipment Plan</td>
                 <td width="110" class="border-right text-center">Production Plan</td>
                 <td class="border-right text-center">Remarks</td>
                 <td width="100" class="border-right text-center">Status</td>
                 <td width="60" class="border-right text-center">Unit</td>
                 <td width="55" class="border-right text-center">Qty</td>
               </tr>
             </thead>
             <tbody id="plan-items"></tbody>
           </table>
         </div>
         
         <div>
         	<table width="100%">
               <tr><td height="5" colspan="99"></td></tr>
               <tr>
                  <td>
                  </td>
                  <td align="right"><strong>Total Qty:</strong>&nbsp;&nbsp;<input id="total_qty" type="text" class="text-right" style="width:95px;" disabled/></td>
               </tr>
<!--                    <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $invoice['remarks']; ?></textarea></td></tr> -->
            </table>
         </div>
         <div class="field-command">
           	   <!-- <div class="text-post-status">
           	     <strong>Save As:</strong>&nbsp;&nbsp;<?php echo $purchase_order['status']; ?>
               </div> -->
               
               <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('plan-po-model-shipment-edit.php?poid='.$_GET['poid'].'&pid='.$_GET['pid']); ?>"/>
           	   
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('plan-pos.php'); ?>"/>
             </div>
      </form>
   </div>
	</div>
      
       <script>
				$(function() {
					loadData();
					//$('#order_amount').currency_format(<?php echo $purchase_order['total_amount']; ?>);
			  })
			  
			  function loadData() {
					var data = { 
			    	"url":"/populate/shipment-plans.php?poid=<?php echo $_GET['poid'] ?>&pid=<?php echo $_GET['pid'] ?>",
			      "limit":"50",
						"data_key":"shipment_plans",
						"row_template":"row_template_plan_po_model_shipments_read_only",
					}
					$('#grid-ship-plan-items').grid(data);
					
					//$(window).load(function(){
						setTimeout(function(){
							get_total_qty();
						}, 1000);
					//});	
				} 
			  
			  
			  function get_total_qty() {
			  	var total = 0;
					$('#plan-items tr').find('.qty').each(function(){
      			total += parseFloat(parseInt($(this).text().replace(/,/g, ''), 10)); 
      		});
      		$('#total_qty').val(total);	
			  }
			  
       </script>

<?php }
require('footer.php'); ?>