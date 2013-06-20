<?php
  /* Module: Deliveries  */
  $capability_key = 'add_deliveries';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
		$pos = $DB->Get('purchases', array('columns' => 'id, po_number', 'conditions' => 'completion_status =5')); // status 5 = partial
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
          	<input type="hidden" name="action" value="add_delivery_partial" />
          	<input type="hidden" id="purchase_id" name="delivery[purchase_id]" value="" />
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">P/O Number:</td><td width="340">
                      	<?php 
                      		if(!isset($pos)) {
                      			echo '<span class="magenta">NO OPEN PURCHASE ORDERS</span>';
                      		} else {
                    				select_query_tag($pos, 'id', 'po_number', '', 'purchase', 'purchase', '', 'width:192px;'); 
														echo '<span class="magenta"> (with partial deliveries)</span>';
                      		}
                      		?>
                      </td>
                      <td width="120">P/O Date:</td><td width="340"><input id="po_date" type="text" class="text-field ctrl-date-string" disabled/>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" id="supplier_name" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Delivery Via:</td><td><input id="delivery_via" name="delivery[delivery_via]" type="text" class="text-field" /></td>
                      <td>Delivery Date:</td><td><input id="delivery_date" name="delivery[delivery_date]" type="text" class="text-field ctrl-date-string date-pick-week" /></td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input id="trade_terms" type="text" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input id="payment_terms" type="text" class="text-field" disabled/></td>
                   </tr>
                   <tr>
                      <td>Completion:</td><td><input id="completion_status" type="text" class="text-field" disabled/></td>
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
                     <td class="border-right">Description</td>
                     <td width="70" class="border-right text-center">P/O Qty</td>
                     <td width="70" class="border-right text-center">Delivered</td>
                     <td width="70" class="border-right text-center">Undelivered</td>
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
                   <tr><td colspan="2">Remarks:<br/><textarea id="remarks" name="delivery[remarks]" style="min-width:650px;width:98.9%;height:50px;"></textarea></td></tr>
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     
               </div>
       	   			<input id="btn-submit" type="submit" value="Create" class="btn" disabled/>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('deliveries.php'); ?>"/>
             </div>
          </form>
       </div>
     </div>
       
       <script>
       	$(function() {
       		var purchase_id = $('#purchase').val();
				  loadDetails(purchase_id);
       		$('#purchase').on('change', function() {
					  loadDetails(this.value);
					});	
					if(purchase_id) {
						$('#btn-submit').removeAttr('disabled');
					}

					function loadDetails(id){
						$.ajax({
							type: "POST",
							url: "../populate/delivery-details.php",
							data: {  
											table: 			'purchases',
											columns: 		'suppliers.name AS supplier_name, po_date, terms, payment_terms, delivery_via, delivery_date, remarks, status, lookup_status.description AS completion_status ',
											joins: 			'INNER JOIN suppliers ON suppliers.id = purchases.supplier_id INNER JOIN lookup_status ON lookup_status.id = purchases.completion_status',
											conditions: 'purchases.id=' + id
										},
							cache: false,
							dataType : "json",
							success: function(data) {
								$('#supplier_name').val(data.supplier_name);
								$('#po_date').val(data.po_date).format_ctrl_date_string();
								$('#trade_terms').val(data.terms);
								$('#payment_terms').val(data.payment_terms);
								$('#delivery_via').val(data.delivery_via);
								$('#delivery_date').val(data.delivery_date).format_ctrl_date_string();
								$('#completion_status').val(data.completion_status);
								$('#purchase_id').val(id);
								
								
								return false;
							}
						});
						
						var data = { 
				    	"url":"/populate/delivery-items-partial.php?id=" + id,
				      "limit":"50",
							"data_key":"delivery_items",
							"row_template":"row_template_delivery_items_partial_read_only"
						}
					
						$('#grid-delivery-materials').grid(data);
						$('#purchase_amount').currency_format(<?php echo $delivery['total_amount']; ?>);
					}
					
			  	
			  }) 
      </script>

<?php }
require('footer.php'); ?>