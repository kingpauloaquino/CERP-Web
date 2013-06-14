<?php
  /* Module: Plan Purchase Orders  Model Shipments - Show  */
  $capability_key = 'show_plan_po_model_ship';
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
         			<a id="btn-add-plan" href="#mdl-ship-plan" rel="modal:open" class="nav">Add Ship Plan</a>
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
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="140" class="border-right">Shipment Plan</td>
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
                     <strong><a id="remove-plan-item" href="#" class="" grid="#grid-ship-plan-items">Remove Item</a></strong>
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
               <?php if($purchase_order['status'] != "Publish") { ?>
               <input id="btn-save-plan" type="button" value="Save" class="btn" />
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('plan-pos.php'); ?>"/>
             </div>
      </form>
   </div>
	</div>
	
	<div id="mdl-ship-plan" class="modal">
		<div class="modal-title"><h3>New Plan Ship Date</h3></div>
		<div class="modal-content">
			<form id="frm-ship-plan" method="POST">
				<span class="notice"></span>     
				<input type="hidden" name="action" value="add_shipment_plan"/>  
				<input type="hidden" name="plan[po_id]" value="<?php echo $_GET['poid'] ?>"/>  
				<input type="hidden" name="plan[item_id]" value="<?php echo $_GET['pid'] ?>"/>  
				<input type="hidden" name="plan[item_type]" value="PRD"/>
						 
				 <div class="field">
				    <label>P/O No.:</label>
				    <input type="text" id="ship-plan-po" class="text-field magenta" default="0" disabled="disabled"/>
				 </div>
				 
				 <div class="field">
				    <label>Model:</label>
				    <input type="text" id="ship-plan-model" class="text-field" default="0" disabled="disabled"/>
				 </div>
				 
				 <div class="field">
				    <label>Ship Plan:</label>
				    <input type="text" id="ship-plan-date" name="plan[ship_date]" class="text-field date-pick" required/>
				 </div>
				 
				 <div class="field">
				    <label>Quantity:</label>
				    <input type="text" id="ship-plan-qty" name="plan[qty]" class="text-field numeric" default="0" required/>
				 </div>
				 
				 <div class="field">
				    <label>Remarks:</label>
				    <textarea rows="2" id="ship-plan-remarks" name="plan[remarks]" class="text-field" style="width:220px;"></textarea>
				 </div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn" style="width:50px;">Cancel</a>
			<a id="submit-ship-plan" rel="modal:close" href="#frm-ship-plan" class="btn" style="width:50px;">Add</a>
		</div>
	</div>
      
       <script>
				$(function() {
					loadData();
			  	$('#submit-ship-plan').add_plan();
					  $('#remove-plan-item').remove_item();
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
			  
			  $('#btn-add-plan').live('click', function(){
			  	var form = $('#frm-ship-plan');
			  	//reset
			  	$(form).find('#ship-plan-date').val('');
			  	$(form).find('#ship-plan-qty').val('0');
			  	$(form).find('#ship-plan-remarks').val('');
			  	
			  	$(form).find('#ship-plan-po').val($('#po_no').val());
			  	$(form).find('#ship-plan-model').val($('#model').val());
			  });
			  
			  function get_total_qty() {
			  	var total = 0;
					$('#plan-items tr').find('.qty').each(function(){
      			total += parseFloat(parseInt($(this).text().replace(/,/g, ''), 10)); 
      		});
      		$('#total_qty').val(total);	
			  }
			  
			  $.fn.add_plan = function() {
			  	this.click(function(e) {
			  		e.preventDefault();
			  		
						var form = $(this).attr('href');
						
						if($(form).find('#ship-plan-date').val() != '' && $(form).find('#ship-plan-qty').val() != '') {
							$.post(document.URL, $(form).serialize(), function(data) {
				      }).done(function(data){
				      	loadData();
				      });	
						}
			  	})
			  }
			  
			  var remove_items = [];
			  
			  $.fn.remove_item = function() {
           this.click(function(e) {
             e.preventDefault();
             
             var grid = $($(this).attr('grid'));
             
             grid.find('.chk-item:checked').closest('tr').each(function() {
             	remove_items.push($(this).attr('id'));
             });
             
             
             grid.find('.chk-item:checked').closest('tr').remove();
           	 populate_index(grid);
           	 get_total_qty();
           })
         }
         
         $('#btn-save-plan').live('click', function(){
					$.post(document.URL, { 'action' : 'remove_shipment_plan', 'ids[]' : remove_items })
						.done(function(data){
				      	loadData();
				      });	
         })
       </script>

<?php }
require('footer.php'); ?>