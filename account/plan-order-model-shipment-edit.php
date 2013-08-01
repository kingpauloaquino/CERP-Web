<?php
  /* Module: Plan Orders  Model Shipments - Edit  */
  $capability_key = 'edit_plan_order_model_shipment';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		if($_GET['t'] == 'P/O') {
			$order = $Query->purchase_order_item_by_id($_GET['ctrl_id'], $_GET['pid']);
		} else {
			$order = $Query->work_order_item_by_id($_GET['ctrl_id'], $_GET['pid']);
		}
?>
      <!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
            <span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
         			<a id="btn-add-plan" href="#mdl-ship-plan" rel="modal:open" class="nav">Add Plan</a>
            <div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="shipment-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
				<input type="hidden" name="action" value="edit_shipment_plan"/>  
				<input type="hidden" name="type" value="<?php echo $_GET['t'] ?>"/>  
				<input type="hidden" name="ctrl_id" value="<?php echo $_GET['ctrl_id'] ?>"/>  
				<input type="hidden" name="ctrl_no" value="<?php echo $order['order_no'] ?>"/> 
				<input type="hidden" name="pid" value="<?php echo $_GET['pid'] ?>"/>  
         <div>
         	<table>
               <tr>
                  <td width="120">Order Number:</td><td width="340"><input id="po_no" type="text" value="<?php echo $order['order_no'] ?>" class="text-field magenta" disabled/></td>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $order['client'] ?>" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>Order Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($order['order_date']))?>" class="text-field text-date" disabled/></td>
                  <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($order['ship_date']))?>" class="text-field" disabled/></td>
               </tr>
               <tr>
                  <td>Model:</td><td><input type="text" id="model" value="<?php echo $order['product_code'] ?>" class="text-field magenta" disabled/></td>
                  <td>P/O Qty:</td><td><input type="text" value="<?php echo $order['quantity'] ?>" class="text-field numbers text-right" disabled/></td>
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
                 <td width="110" class="border-right text-center">Production Plan</td>
                 <td width="110" class="border-right text-center">Shipment Plan</td>
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
               <?php if($order['status'] != "Publish") { ?>
       	   			<input type="submit" value="Save" class="btn"/>
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('plan-order-model-shipment-show.php?ctrl_id='.$_GET['ctrl_id'].'&pid='.$_GET['pid'].'&t='.$_GET['t']); ?>"/>
             </div>
      </form>
   </div>
	</div>
	
	<div id="mdl-ship-plan" class="modal">
		<div class="modal-title"><h3>New Plan</h3></div>
		<div class="modal-content">
			<form id="frm-ship-plan" method="POST">
				<span class="notice"></span>     
				<input type="hidden" name="action" value="add_shipment_plan"/>  
				<input type="hidden" name="plan[type]" value="<?php echo $_GET['t'] ?>"/>  
				<input type="hidden" name="plan[ctrl_id]" value="<?php echo $_GET['ctrl_id'] ?>"/>  
				<input type="hidden" name="plan[ctrl_no]" value="<?php echo $order['order_no'] ?>"/> 
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
				    <label>Delivery:</label>
				    <input type="text" class="text-field" value="<?php echo date("F d, Y", strtotime($order['ship_date']))?>" disabled="disabled"/>
				 </div>
				 
				 <div class="field">
				    <label>P/O Qty:</label>
				    <input type="text" class="text-field" value="<?php echo $order['quantity'] ?>" disabled="disabled"/>
				 </div>		
				 
				 <div class="field">
				    <label>Production Plan:</label>
				    <input type="text" id="prod-plan-date" name="plan[prod_date]" class="text-field date-pick-friday" readonly required/>
				 </div>		 
				 
				 <div class="field">
				    <label>Shipment Plan:</label>
				    <input type="text" id="ship-plan-date" name="plan[ship_date]" class="text-field date-pick-thursday" readonly required/>
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
			  })
			  
			  function loadData() {
					var data = { 
			    	"url":"/populate/shipment-plans.php?t=<?php echo $_GET['t'] ?>&ctrl_id=<?php echo $_GET['ctrl_id'] ?>&pid=<?php echo $_GET['pid'] ?>",
			      "limit":"50",
						"data_key":"shipment_plans",
						"row_template":"row_template_plan_po_model_shipments",
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
			  	$(form).find('#prod-plan-date').val('');
			  	$(form).find('#ship-plan-qty').val('0');
			  	$(form).find('#ship-plan-remarks').val('');
			  	
			  	$(form).find('#ship-plan-po').val($('#po_no').val());
			  	$(form).find('#ship-plan-model').val($('#model').val());
			  });
			  
			  function get_total_qty() {
			  	var total = 0;
					$('#plan-items tr').find('.qty').each(function(){
      			total += parseFloat(parseInt($(this).val().replace(/,/g, ''), 10)); 
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
				      	window.location = 'plan-order-model-shipment-show.php?t=<?php echo $_GET['t'] ?>&ctrl_id=<?php echo $_GET['ctrl_id'] ?>&pid=<?php echo $_GET['pid'] ?>';
				      });	
						}
			  	})
			  }
			  
			  //var remove_items = [];
			  
			  $.fn.remove_item = function() {
           this.click(function(e) {
             e.preventDefault();
             
             var grid = $($(this).attr('grid'));
             
             // grid.find('.chk-item:checked').closest('tr').each(function() {
             	// remove_items.push($(this).attr('id'));
             // });
             
             grid.find('.chk-item:checked').closest('tr').remove();
           	 populate_index(grid);
           	 get_total_qty();
           })
         }
         
         // $('#btn-save-plan').live('click', function(){
					// $.post(document.URL, { 'action' : 'remove_shipment_plan', 'ids[]' : remove_items })
						// .done(function(data){
				      	// loadData();
				      // });	
         // })
       </script>

<?php }
require('footer.php'); ?>