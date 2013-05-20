<?php
  /* Module: Invoice Show  */
  $capability_key = 'show_invoice';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  	$invoice = $Query->invoice_by_number($_GET['inv']);
?>
      <!-- BOF PAGE -->
      <div id="page">
        <div id="page-title">
          <h2>
            <span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
			      <?php
						  echo '<a href="'.$Capabilities->All['invoices']['url'].'" class="nav">'.$Capabilities->All['invoices']['name'].'</a>'; 
						?>
            <div class="clear"></div>
          </h2>
        </div>

        <div id="content">
          <form id="invoice-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
             <!-- BOF TEXTFIELDS -->
             <div>
             	<table>
                   <tr>
                      <td width="120">Invoice Number:</td><td width="340"><input type="text" value="<?php echo $invoice['invoice']; ?>" class="text-field magenta" disabled/>
                      </td>
                      <td width="120">Invoice Date:</td><td width="340"><input type="text" value="<?php echo date("F d, Y", strtotime($invoice['receive_date'])); ?>" class="text-field" disabled/>
                   </tr>
                   <tr>
                      <td>Supplier:</td>
                      <td colspan="99">
                        <input type="text" value="<?php echo $invoice['supplier']; ?>" class="text-field" style="width:644px;" disabled/>
                      </td>
                   </tr>
                   <tr>
                      <td>Trade Terms:</td><td><input type="text" value="<?php echo $invoice['terms']; ?>" class="text-field" disabled/></td>
                      <td>Payment Terms:</td><td><input type="text" value="<?php echo $invoice['payment_terms']; ?>" class="text-field" disabled/></td>
                   </tr>
                   <tr><td height="5" colspan="99"></td></tr>
                </table>
             </div>
             
             <!-- BOF GRIDVIEW -->
             <div id="grid-invoice-materials" class="grid jq-grid" style="min-height:146px;">
               <table cellspacing="0" cellpadding="0">
                 <thead>
                   <tr>
<!--                      <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all" disabled/></td> -->
                     <td width="30" class="border-right text-center">No.</td>
                     <td width="140" class="border-right">P/O Number</td>
                     <td width="120" class="border-right">Material Code</td>
                     <td class="border-right">Description</td>
                     <td width="70" class="border-right text-center">P/O Qty</td>
                     <td width="70" class="border-right text-center">Received</td>
                     <td width="70" class="border-right text-center">Price</td>
                     <td width="70" class="border-right text-center">Amount</td>
                   </tr>
                 </thead>
                 <tbody id="invoice-materials"></tbody>
               </table>
             </div>
             
           
             
             <!-- BOF REMARKS -->
             <div>
             	<table width="100%">
                   <tr><td height="5" colspan="99"></td></tr>
                   <tr>
                      <td></td>
                      <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="total_amount" type="text" class="text-right" style="width:95px;" disabled/></td>
                   </tr>
<!--                    <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $invoice['remarks']; ?></textarea></td></tr> -->
                </table>
             </div>
             
             <div class="field-command">
           	   <div class="text-post-status">
           	     <strong></strong>
               </div>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('invoices.php'); ?>"/>
             </div>
          </form>
       </div>
     </div>
       
       <script>
       	$(function() {
			  	var data = { 
			    	"url":"/populate/invoice-items.php?inv=<?php echo $_GET['inv']; ?>",
			      "limit":"100",
						"data_key":"invoice_items",
						"row_template":"row_template_invoices_items_read_only"
					}
				
					$('#grid-invoice-materials').grid(data);
					
					//compute total amount
					$(window).load(function(){
						var total = 0;
						$('#invoice-materials tr').find('.amount').each(function(){
	      			total += parseFloat($(this).val());
	      		});
	      		$('#total_amount').currency_format(total);
					})
			  }) 
      </script>

<?php }
require('footer.php'); ?>