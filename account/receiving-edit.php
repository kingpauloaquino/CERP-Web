<?php
  /* Module: Dashboard  */
  $capability_key = 'users';
  require('header.php');
  
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
  	$receiving = $Query->receive_purchase_id($_GET['id']);
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title">Material Receiving &raquo; <span class="red"><?php echo $receiving['purchase_number']; ?></span></span>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
          <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
          	 <input type="hidden" name="action" value="add_receiving"/>
          	 <input type="hidden" name="receiving[purchase_id]" value="<?php echo $receiving['purchase_id']; ?>"/>
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Purchase Number:</td><td width="340"><input type="text" value="<?php echo $receiving['purchase_number']; ?>" class="text-field" disabled/></td>
                      <td width="120"></td><td width="340"></td>
                   </tr>
                   <tr>
                      <td>Invoice Number:</td><td><input type="text" name="receiving[invoice_number]" value="<?php echo $receiving['invoice_number']; ?>" class="text-field" autofocus/></td>
                      <td>Invoice Date:</td><td><input type="text" name="receiving[invoice_date]" value="<?php echo $receiving['invoice_date']; ?>" class="text-field text-date"/></td>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $receiving['supplier_name']; ?>" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Receipt:</td><td><input type="text" name="receiving[delivery_receipt]" value="<?php echo $receiving['delivery_receipt']; ?>" class="text-field"/></td>
                      <td>Delivery Date:</td><td><input type="text" name="receiving[delivery_date]" value="<?php echo dformat($receiving['delivery_date'], 'm/d/Y'); ?>" class="text-field text-date"/></td>
                   </tr>
                   <tr>
                      <td>Delivery By:</td><td><input type="text" name="receiving[delivery_by]" value="<?php echo $receiving['delivery_by']; ?>" class="text-field"/></td>
                      <td>Shipment Status:</td><td><input type="text" name="receiving[shipment_status]" value="<?php echo $receiving['shipment_status']; ?>" class="text-field"/></td>
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
                     <td width="70" class="border-right text-center">Quantity</td>
                     <td width="70" class="border-right text-center">Received</td>
                     <td width="70" class="border-right text-center">Discrepancy</td>
                     <td width="100" class="text-center">Remarks</td>
                   </tr>
                 </thead>
                 <tbody id="purchase-materials"></tbody>
               </table>
             </div>
             
             <!-- BOF REMARKS -->
             <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr><td colspan="2">Remarks:<br/><textarea name="receiving[remarks]" style="min-width:650px;width:98.9%;height:50px;"><?php echo $receiving['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
               </div>
           	   <input type="submit" value="Save" class="btn"/>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('receiving.php'); ?>"/>
             </div>
          </form>
       </div>
       
       <script>
        $(function() {
          var grid = $('#purchase-materials');
          var data = { 
            "url":"/populate/purchases-items.php?purchase_id=<?php echo $receiving['purchase_id']; ?>",
            "data_key":"purchase_items",
            "row_template":"row_template_receiving_materials"
          }
  
          $('.jq-grid').grid(data);
          $('#purchase_amount').currency_format(<?php echo $receiving['total_amount']; ?>);
        })
      </script>

<?php }
require('footer.php'); ?>