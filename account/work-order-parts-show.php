<?php
  /* Module: Work Orders - Show  */
  $capability_key = 'show_work_order_parts';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
	
		$work_order = $Query->work_order_by_id($_GET['wid']);
		$product = $Query->product_by_work_order_item($_GET['wopid'])
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
      <form id="order-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
         <div>
         	<table>
               <tr>
                  <td width="120">W/O Number:</td><td width="340"><input type="text" value="<?php echo $work_order['wo_number'] ?>" class="text-field magenta" disabled/></td>
                  <td width="120">W/O Date:</td><td width="340"><input type="text" value="<?php echo date("F d, Y", strtotime($work_order['wo_date']))?>" class="text-field text-date" disabled/></td>
               </tr>
               <tr>
                  <td>Completion:</td><td><input type="text" value="<?php echo $work_order['completion_status'] ?>" class="text-field" disabled/></td>
                  <td>Ship Date:</td><td><input type="text" value="<?php echo date("F d, Y", strtotime($work_order['ship_date']))?>" class="text-field" disabled/></td>
               </tr>  
               <tr>
                  <td>Product:</td><td><input type="text" value="<?php echo $product['product_code'] ?>" class="text-field" disabled/></td>
                  <td></td><td></td>
               </tr>  
               <tr><td height="5" colspan="99"></td></tr>
            </table>
         </div>
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-work-order-item-parts" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="140" class="border-right text-center">Material Code</td>
                 <td class="border-right text-center">Description</td>
                 <td width="60" class="border-right text-center">Part Qty</td>
                 <td width="90" class="border-right text-center">Qty</td>
                 <td width="90" class="border-right text-center">Total Qty</td>
                 <td width="60" class="border-right text-center">Unit</td>
                 <td width="60" class="border-right text-center">MOQ</td>
                 <td width="80" class="border-right text-center">Unit Price</td>
                 <td width="80" class="border-right text-center">P/O Qty</td>
                 <td width="100" class="text-center">P/O Price</td>
               </tr>
             </thead>
             <tbody id="work-order-item-parts"></tbody>
           </table>
         </div>
         
         <!-- BOF REMARKS -->
         <div>
         	<table width="100%">
               <tr><td height="5" colspan="99"></td></tr>
               <tr>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="order_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
            </table>
         </div>
         <div class="field-command">
           	   <div class="text-post-status">
           	     <strong>Save As:</strong>&nbsp;&nbsp;<?php echo $work_order['status']; ?>
               </div>
<!--            	   <input type="button" value="Download" class="btn btn-download" rel="<?php echo excel_file('?category=order&id='. $work_order['id']); ?>"/> -->
               <?php if($work_order['status'] != "Publish") { ?>
<!--                <input type="button" value="Edit" class="btn redirect-to" rel="<?php echo host('work-orders-edit.php?wid='. $_GET['wid']); ?>"/> -->
           	   <?php } ?>
               <input type="button" value="Back" class="btn redirect-to" rel="<?php echo host('work-orders.php'); ?>"/>
             </div>
      </form>
   </div>
       
     
	</div>
      
       <script>
				$(function() {
			  	var data = { 
			    	"url":"/populate/work-order-item-parts.php?woid=<?php echo $_GET['wopid'] ?>",
			      "limit":"50",
						"data_key":"work_order_item_parts",
						"row_template":"row_template_work_order_item_parts_read_only",
					}
				
					$('#grid-work-order-item-parts').grid(data);
					// var x = compute_total_amount();
					// alert(x);
			  }) 
			  
			  function compute_total_amount() {
	        row_total = 0; 
			    $("#work-order-item-parts").find('tr').each(function() {
           row_total += Number($(this).find('td.amount').html());
			    });
			    return row_total;
				}
			  
			  
       </script>

<?php }
require('footer.php'); ?>