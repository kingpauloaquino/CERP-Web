<?php
  /* Module: Dashboard  */
  $capability_key = 'show_purchase';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  	$purchase = $Query->purchase_by_id($_GET['id']);
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
		        <?php
		        	echo '<a href="'.$Capabilities->All['purchases']['url'].'" class="nav">'.$Capabilities->All['purchases']['name'].'</a>';
		        	echo '<a href="'.$Capabilities->All['deliveries_po']['url'].'?pid='.$_GET['id'].'" class="nav">Deliveries</a>';
						?>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
          <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
          	<input type="hidden" id="purchase-status" value="<?php echo $purchase['status']?>"/>
          	<input type="hidden" id="purchase-aprroved-by" value="<?php echo $purchase['approved_by']?>"/>
          	<input type="hidden" id="user-level" value="<?php echo $_SESSION['user']['level']?>"/>
          	
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">P/O Number:</td><td width="340"><input type="text" value="<?php echo $purchase['po_number']; ?>" class="text-field magenta" readonly/></td>
                      <td width="120">P/O Date:</td><td width="340"><input type="text" value="<?php echo date("F d, Y", strtotime($purchase['po_date'])); ?>" class="text-field" readonly/>
                      	<?php
                      		//if(isset($_GET['did'])) echo '<a target="_blank" href="deliveries-show.php?id='.$_GET['did'].'" class="magenta">[ NEW DELIVERY CREATED ]</a>';
                      	?>
                      </td>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $purchase['supplier_name']; ?>" class="text-field" style="width:644px;" readonly/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input type="text" value="<?php echo $purchase['delivery_via']; ?>" class="text-field" readonly/>
                      </td>
                      <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($purchase['delivery_date'])) ?>" class="text-field text-date" readonly/></td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" value="<?php echo $purchase['terms']; ?>" class="text-field" readonly/></td>
                      <td>Payment Terms:</td><td><input type="text" value="<?php echo $purchase['payment_terms']; ?>" class="text-field" readonly/></td>
                   </tr>
                   <tr>
                      <td>Delivery:</td><td><input type="text" value="<?php echo $purchase['completion_status']; ?>" class="text-field" readonly/></td>
                      <td></td><td></td>
                   </tr>
                   <tr><td height="5" colspan="99"></td></tr>
                </table>
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-purchase-materials" class="grid jq-grid" style="min-height:146px;">
               <table cellspacing="0" cellpadding="0">
                 <thead>
                   <tr>
                     <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all" disabled/></td>
                     <td width="30" class="border-right text-center">No.</td>
                     <td width="140" class="border-right">Item Code</td>
                     <td class="border-right">Description</td>
                 			<td width="55" class="border-right text-center">MOQ</td>
                     <td width="55" class="border-right text-center">Qty</td>
                     <td width="60" class="border-right text-center">Unit</td>
                     <td width="100" class="border-right text-center">Unit Price</td>
                     <td width="100" class="text-center">Amount Price</td>
                   </tr>
                 </thead>
                 <tbody id="purchase-materials"></tbody>
               </table>
             </div>
             
           
             
             <!-- BOF REMARKS -->
             <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr>
                      <td></td>
                      <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" class="text-right" style="width:95px;" readonly/></td>
                   </tr>
                   <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" readonly><?php echo $purchase['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Saved As:</strong>&nbsp;&nbsp;<?php echo $purchase['status']; ?>
									
               </div>
               <?php if($purchase['status'] != "Publish") { ?>
               <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('purchases-edit.php?id='. $purchase['id']); ?>"/>
           	   <?php } ?>
               <?php if($purchase['status'] == "Publish") { ?>
		           <input type="button" value="To Excel" class="btn btn-download" rel="<?php echo export_file('?type=xls&cat=purchases&id='. $purchase['id']); ?>"/>
		       	   <?php } ?>
             </div>
             
							<?php 
								$approval_item_id = $_GET['id'];
								$approval_table = 'purchases';
								require_once 'approval.php';
							?>
          </form>
       </div>
       
     <!-- BOF MODAL -->
		<div id="modal-approval" class="modal" style="display:none;width:420px;">
      <div class="modal-title"><h3>Approval</h3></div>
      <div class="modal-content">
      	<form id="frm-approval" method="POST">
					<input type="hidden" name="action" value="approve_purchase"/>  
					<input type="hidden" name="id" value="<?php echo $purchase['id'] ?>"/>  
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
			    	"url":"/populate/purchases-items.php?pid=<?php echo $purchase['id']; ?>",
			      "limit":"50",
						"data_key":"purchase_items",
						"row_template":"row_template_purchase_material_read_only"
					}
				
					$('#grid-purchase-materials').grid(data);
					$('#purchase_amount').currency_format(<?php echo $purchase['total_amount']; ?>);
					
					
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