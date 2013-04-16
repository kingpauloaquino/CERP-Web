<?php
  /* Module: Edit Deliveries  */
  $capability_key = 'edit_deliveries';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  	$delivery = $Query->delivery_by_id($_GET['id']);
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title">Delivery &raquo; <span class="red"><?php echo $delivery['purchase_number']; ?></span></span>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
          <form id="delivery-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
	      	 <input type="hidden" name="action" value="edit_delivery"/>
	      	 <input type="hidden" name="did" value="<?php echo $delivery['id']; ?>"/>
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Purchase Number:</td><td width="340"><input type="text" value="<?php echo $delivery['purchase_number']; ?>" class="text-field" disabled/></td>
                      <td width="120">Receipt:</td><td width="340"><input type="text" id="delivery[receipt]" name="delivery[receipt]" value="<?php echo $delivery['receipt']; ?>" class="text-field magenta" /></td>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $delivery['supplier_name']; ?>" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input type="text" id="delivery[delivery_via]" name="delivery[delivery_via]" value="<?php echo $delivery['delivery_via']; ?>" class="text-field" /></td>
                      <td>Delivery Date:</td><td><input type="text" id="delivery[delivery_date]" name="delivery[delivery_date]" value="<?php echo date("F d, Y", strtotime($delivery['delivery_date'])) ?>" class="text-field date-pick" /></td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" value="<?php echo $delivery['trade_terms']; ?>" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input type="text" value="<?php echo $delivery['payment_terms']; ?>" class="text-field" disabled/></td>
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
                     <td class="border-right">Description</td>
                     <td width="70" class="border-right text-center">Qty</td>
                     <td width="60" class="border-right text-center">Unit</td>
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
                   <tr><td colspan="2">Remarks:<br/><textarea id="delivery[remarks]" name="delivery[remarks]" style="min-width:650px;width:98.9%;height:50px;" ><?php echo $delivery['remarks']; ?></textarea></td></tr>
                </table>
             </div>
             
		         <div class="field-command">
		       	   <div class="text-post-status">
		       	     <strong>Save As:</strong>&nbsp;&nbsp;<select name="delivery[status]"><?php echo build_select_post_status("", $delivery['status']); ?></select>
		           </div>
		       	   <input type="submit" value="Save" class="btn"/>
		           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('deliveries-show.php?id='. $delivery['id']); ?>"/>
		         </div>
          </form>
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
			  }) 
      </script>

<?php }
require('footer.php'); ?>