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
            <span class="title">Purchases &raquo; <span class="red"><?php echo $purchase['purchase_number']; ?></span></span>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
				<!-- BOF Search -->
	      <div class="search">
	        <input type="text" id="keyword" name="keyword" placeholder="Search" value="<?php echo $purchase['id']; ?>" style="display: none" />
	      </div>
          <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Purchase Number:</td><td width="340"><input type="text" value="<?php echo $purchase['purchase_number']; ?>" class="text-field" disabled/></td>
                      <td width="120"></td><td width="340"></td>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $purchase['supplier_name']; ?>" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input type="text" value="<?php echo $purchase['delivery_via']; ?>" class="text-field" disabled/></td>
                      <td>Delivery Date:</td><td><input type="text" value="<?php echo dformat($purchase['delivery_date'], 'm/d/Y'); ?>" class="text-field text-date" disabled/></td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" value="<?php echo $purchase['trade_terms']; ?>" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input type="text" value="<?php echo $purchase['payment_terms']; ?>" class="text-field" disabled/></td>
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
                      <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" class="text-right" style="width:95px;" disabled/></td>
                   </tr>
                   <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $purchase['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Save As:</strong>&nbsp;&nbsp;<?php echo $purchase['status']; ?>
               </div>
           	   <input type="button" value="Download" class="btn btn-download" rel="<?php echo excel_file('?category=purchase&id='. $purchase['id']); ?>"/>
               <?php if($purchase['status'] != "Publish") { ?>
               <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('purchases-edit.php?id='. $purchase['id']); ?>"/>
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('purchases.php'); ?>"/>
             </div>
          </form>
       </div>
       
       <script>
       	$(function() {
			  	var data = { 
			    	"url":"/populate/purchases-items.php",
			      "limit":"50",
						"data_key":"purchase_items",
						"row_template":"row_template_purchase_material_read_only",
			      "params":"purchase_id=<?php echo $purchase['id']; ?>"
					}
				
					$('#grid-purchase-materials').grid(data);
					$('#purchase_amount').currency_format(<?php echo $purchase['total_amount']; ?>);
			  }) 
      </script>

<?php }
require('footer.php'); ?>