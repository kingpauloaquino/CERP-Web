<?php
  /* Module: Deliveries  */
  $capability_key = 'add_deliveries';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
		$pos = $DB->Get('purchases', array('columns' => 'id, purchase_number', 'conditions' => 'status = 2 OR status = 5'));
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
          <form id="delivery-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Purchase Number:</td><td width="340"><?php select_query_tag($pos, 'id', 'purchase_number', '', 'purchase_id', 'purchase_id', '', 'width:192px;'); ?>
                      	<span class="magenta">(Pending and Partial)</span>
                      </td>
                      <td width="120"></td><td width="340"></td>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" id="supplier_name" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input id="trade_terms" type="text" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input id="payment_terms" type="text" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input id="delivery_via" type="text" class="text-field" /></td>
                      <td>Delivery Date:</td><td><input id="delivery_date" type="text" class="text-field date-pick" /></td>
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
                   <tr><td colspan="2">Remarks:<br/><textarea id="remarks" name="remarks" style="min-width:650px;width:98.9%;height:50px;"></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Save As:</strong>
               </div>
       	   			<input type="submit" value="Create" class="btn"/>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('deliveries.php'); ?>"/>
             </div>
          </form>
       </div>
     </div>
       
       <script>
       	$(function() {
       		var purchase_id = $('#purchase_id').val();
				  loadDetails(purchase_id);
       		$('#purchase_id').on('change', function() {
					  loadDetails(this.value);
					});		
					
					function loadDetails(id){
						$.ajax({
							type: "POST",
							url: "../populate/delivery-details.php",
							data: {  
											table: 			'purchases',
											columns: 		'suppliers.name AS supplier_name, trade_terms, payment_terms, delivery_via, remarks, status ',
											joins: 			'INNER JOIN suppliers ON suppliers.id = purchases.supplier_id ',
											conditions: 'purchases.id=' + id
										},
							cache: false,
							dataType : "json",
							success: function(data) {
								$('#supplier_name').val(data.supplier_name);
								$('#trade_terms').val(data.trade_terms);
								$('#payment_terms').val(data.payment_terms);
								$('#delivery_via').val(data.delivery_via);
								return false;
							}
						});
					}
					
			  	
			  }) 
      </script>

<?php }
require('footer.php'); ?>