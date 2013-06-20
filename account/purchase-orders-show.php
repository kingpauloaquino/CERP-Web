<?php
  /* Module: Purchase Orders - Show  */
  $capability_key = 'show_purchase_order';
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
          	<input type="hidden" id="purchase-status" value="<?php echo $purchase_order['status']?>"/>
          	<input type="hidden" id="purchase-aprroved-by" value="<?php echo $purchase_order['approved_by']?>"/>
          	<input type="hidden" id="user-level" value="<?php echo $_SESSION['user']['level']?>"/>
         <div>
         	<table>
               <tr>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $purchase_order['client'] ?>" class="text-field" disabled/></td>
                  <td width="120"></td><td width="340"></td>
               </tr>
               <tr>
                  <td>P/O Number:</td><td><input type="text" value="<?php echo $purchase_order['po_number'] ?>" class="text-field magenta" disabled/></td>
                  <td>P/O Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($purchase_order['po_date']))?>" class="text-field text-date" disabled/></td>
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
                 <td width="140" class="border-right">Item Code</td>
                 <td width="50" class="border-right">Type</td>
                 <td class="border-right text-center">Remarks</td>
                 <td width="55" class="border-right text-center">Qty</td>
                 <td width="60" class="border-right text-center">Unit</td>
                 <td width="100" class="border-right text-center">Unit Price</td>
                 <td width="100" class="border-right text-center">Amount Price</td>
               </tr>
             </thead>
             <tbody id="purchase-order-materials"></tbody>
           </table>
         </div>
         
         <!-- BOF REMARKS -->
         <div>
         	<table width="100%">
               <tr><td height="5" colspan="99"></td></tr>
               <tr>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="order_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
               <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $purchase_order['remarks'] ?></textarea></td></tr>
            </table>
         </div>
         <div class="field-command">
       	   <div class="text-post-status">
       	     <strong>Save As:</strong>&nbsp;&nbsp;<?php echo $purchase_order['status']; ?>
           </div>
           <?php if($purchase_order['status'] != "Publish") { ?>
           <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('purchase-orders-edit.php?pid='. $purchase_order['id']); ?>"/>
       	   <?php } ?>
           <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('purchase-orders.php'); ?>"/>
         </div>
         <?php 
								$approval_item_id = $_GET['pid'];
								$approval_table = 'purchase_orders';
								require_once 'approval.php';
							?>
      </form>
   </div>
	</div>
	
	<!-- BOF MODAL -->
		<div id="modal-approval" class="modal" style="display:none;width:420px;">
      <div class="modal-title"><h3>Approval</h3></div>
      <div class="modal-content">
      	<form id="frm-approval" method="POST">
					<input type="hidden" name="action" value="approve_purchase_order"/>  
					<input type="hidden" name="id" value="<?php echo $purchase_order['id'] ?>"/>  
				<?php 
	 	     	$approvers = $DB->Get('users', array('columns' => 'users.id, CONCAT(users.first_name, " ", users.last_name) AS full_name', 
																						'joins' => 'INNER JOIN user_roles ON user_roles.user_id = users.id
																												INNER JOIN roles ON roles.id = user_roles.role_id',
																						'conditions'  => 'roles.id=3'));
	 	     
	 	     	select_query_tag($approvers, 'id', 'full_name', '', 'approver-id', 'approved_by', '', 'width:192px;'); ?>
      	</form>
			</div>
    	<div class="modal-footer">
      	<a class="btn parent-modal" rel="modal:close">Close</a>
      	<a id="approve-purchase" href="#frm-approval" class="btn" rel="modal:close">Approve</a>
    		<div class="clear"></div>
			</div>
		</div>
      
       <script>
				$(function() {
			  	var data = { 
			    	"url":"/populate/purchase-order-items.php?pid=<?php echo $purchase_order['id'] ?>",
			      "limit":"50",
						"data_key":"purchase_order_items",
						"row_template":"row_template_purchase_order_items_read_only",
					}
				
					$('#grid-purchase-order-items').grid(data);
					$('#order_amount').currency_format(<?php echo $purchase_order['total_amount']; ?>);
					
					if($('#purchase-status').val() == "Publish" && $('#purchase-aprroved-by').val() == '' && $('#user-level').val() == 3) {
						$('#btn-approval').show(); 
					} else {
						$('#btn-approval').hide();
					}
					$('#approve-purchase').approve();
			  }) 
			  $.fn.approve = function() {
			  	this.click(function(e) {
			  		e.preventDefault();
						var form = $(this).attr('href');
						
						$.post(document.URL, $(form).serialize(), function(data) {
			      }).done(function(data){
			      	$('#btn-approval').hide(); 
			      	window.location = document.URL;
			      });	
			  	})
			  }
       </script>

<?php }
require('footer.php'); ?>