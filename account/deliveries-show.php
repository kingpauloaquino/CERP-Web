<?php
  /* Module: Dashboard  */
  $capability_key = 'show_deliveries';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  	$delivery = $Query->delivery_by_id($_GET['id']);
		$mat_inventory_status = $Query->inventory_status_by_current_date('MAT', date('Y-m-d'));
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
			      <?php
						  echo '<a href="'.$Capabilities->All['deliveries']['url'].'" class="nav">'.$Capabilities->All['deliveries']['name'].'</a>'; 
						?>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
          <form id="delivery-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
					<input type="hidden" id="mat-locked" value="<?php echo $mat_inventory_status['is_locked']?>" />
					<input type="hidden" id="mat-updated" value="<?php echo $mat_inventory_status['is_updated']?>" />
					<a id="btn-notice" href="#mdl-notice" rel="modal:open" class="nav" style="display:none">modal</a>
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">P/O Number:</td><td width="340"><input type="text" value="<?php echo $delivery['po_number']; ?>" class="text-field magenta" disabled/>
                      	<?php echo $linkto = (isset($delivery['pid'])) ? link_to('purchases-show.php?id='.$delivery['pid']) : '' ?>
                      </td>
                      <td width="120">P/O Date:</td><td width="340"><input type="text" value="<?php echo date("F d, Y", strtotime($delivery['po_date'])); ?>" class="text-field" disabled/>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $delivery['supplier_name']; ?>" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input type="text" value="<?php echo $delivery['delivery_via']; ?>" class="text-field" disabled/></td>
                      <td>Delivery Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($delivery['delivery_date'])) ?>" class="text-field text-date" disabled/></td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" value="<?php echo $delivery['terms']; ?>" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input type="text" value="<?php echo $delivery['payment_terms']; ?>" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Completion:</td><td><input type="text" value="<?php echo $delivery['completion_status']; ?>" class="text-field" disabled/></td>
                      <td></td><td></td>
                   </tr>
                   <tr><td height="5" colspan="99"></td></tr>
                </table>
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-delivery-materials" class="grid jq-grid" style="min-height:146px;">
               <table cellspacing="0" cellpadding="0">
                 <thead>
                   <tr>
<!--                      <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all" disabled/></td> -->
                     <td width="30" class="border-right text-center">No.</td>
                     <td width="140" class="border-right">Item Code</td>
                     <td width="120" class="border-right">Invoice</td>
                     <td class="border-right">Description</td>
                     <td width="70" class="border-right text-center">P/O Qty</td>
                     <td width="70" class="border-right text-center">Delivered</td>
                     <td width="60" class="border-right text-center">Unit</td>
                     <td width="70" class="border-right text-center">Status</td>
                   </tr>
                 </thead>
                 <tbody id="delivery-materials"></tbody>
               </table>
             </div>
             
           
             
             <!-- BOF REMARKS -->
             <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr>
                      <td></td>
                      <td align="right"></td>
                   </tr>
                   <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $delivery['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong></strong>
               </div>
               
               <?php if($delivery['status'] != "Close") { ?>
               
           	   <input id="btn-receive" type="button" value="RECEIVE" class="btn redirect-to" rel="<?php echo host('receiving-new.php?id='.$_GET['id']); ?>"/>
           	   <?php } ?>
               <input type="button" value="CANCEL" class="btn redirect-to" rel="<?php echo host('deliveries.php'); ?>"/>
               
               
             </div>
          </form>
       </div>
     </div>
     
     <div id="mdl-notice" class="modal">
			<div class="modal-title"><h3>NOTICE</h3></div>
			<div class="modal-content" style="min-height: 60px;">
          Inventory is currently <span class="red">LOCKED</span>. Receiving is temporarily on-hold.
			</div>     
			<div class="modal-footer">
				<a rel="modal:close" class="close btn" style="width:50px;">OK</a>
			</div>
		</div>
       
       <script>
       	$(function() {
			  	var data = { 
			    	"url":"/populate/delivery-items.php?did=<?php echo $delivery['id']; ?>",
			      "limit":"50",
						"data_key":"delivery_items",
						"row_template":"row_template_delivery_items_read_only"
					}
				
					$('#grid-delivery-materials').grid(data);
					$('#purchase_amount').currency_format(<?php echo $delivery['total_amount']; ?>);
					
					// if inventory is locked
					if($('#mat-locked').val() == 0) {
						$('#btn-receive').attr('disabled', false)
					} else {
						$('#btn-notice').click();
						$('#btn-receive').attr('disabled', true)
					}
			  }) 
      </script>

<?php }
require('footer.php'); ?>