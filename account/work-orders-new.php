<?php
  /* Module: Work Orders - New  */
  $capability_key = 'add_work_order';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$client = $DB->Find('suppliers', array(
								  			'columns' => 'suppliers.id, suppliers.name', 
								  	  	'conditions' => 'suppliers.name LIKE "ST SANGYO%"'));
	
		$work_order = $Query->work_order_by_id($_GET['wid']);	
		
		$completion = $DB->Get('lookup_status', array('columns' => 'id, description', 'conditions'  => 'parent = "CMPLTN"'));
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
      	 <input type="hidden" name="action" value="add_work_order"/>
					<input type="hidden" id="order[client_id]" name="work_order[client_id]" value="<?php echo $client['id'] ?>"/>
         <!-- BOF TEXTFIELDS -->
         <div>
         	<table>
               <tr>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $client['name'] ?>" class="text-field" readonly/></td>
                  <td width="120"></td><td width="340"></td>
               </tr>
               <tr>
                  <td>W/O Number:</td><td><input type="text" name="work_order[wo_number]" class="text-field magenta" value="<?php echo generate_new_code('work_order_number') ?>" readonly/></td>
                  <td>W/O Date:</td><td><input type="text" name="work_order[wo_date]" value="<?php echo date("F d, Y") ?>" class="text-field date-pick-week" required/></td>
               </tr>
               <tr>
                  <td>Completion:</td><td><?php select_query_tag($completion, 'id', 'description', 19, 'work_order[completion_status]', 'work_order[completion_status]', '', 'width:192px;', TRUE); ?></td>
                  <td>Ship Date:</td><td><input type="text" name="work_order[ship_date]"  class="text-field date-pick-thursday" required/></td>
               </tr> 
               <tr><td height="5" colspan="99"></td></tr>
            </table>
         </div>
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-work-order-items" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="140" class="border-right">Product Code</td>
                 <td class="border-right text-center">Remarks</td>
                 <td width="85" class="border-right text-center">Qty</td>
                 <td width="60" class="border-right text-center">Unit</td>
                 <td width="100" class="border-right text-center">Unit Price</td>
                 <td width="100" class="border-right text-center">Amount Price</td>
               </tr>
             </thead>
             <tbody id="work-order-products"></tbody>
           </table>
         </div>
         
         <!-- BOF REMARKS -->
         <div>
         	<table width="100%">
               <tr><td height="5" colspan="99"></td></tr>
               <tr>
                  <td>
                     <strong><a href="#modal-products" class="" rel="modal:open">Add Products</a></strong>
                     &nbsp;|&nbsp;
                     <strong><a id="remove-work-order-products" href="#" class="" grid="#work-order-products">Remove Item</a></strong>
                  </td>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="order_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
               <tr><td colspan="2">Remarks:<br/><textarea name="work_order[remarks]" style="min-width:650px;width:98.9%;height:50px;"></textarea></td></tr>
            </table>
         </div>
         <div class="field-command">
       	   <div class="text-post-status">
       	     <strong>Save As:</strong>&nbsp;&nbsp;<select name="work_order[status]"><?php echo build_select_post_status("APRVL"); ?></select>
           </div>
       	   <input type="submit" value="Save" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('work-orders.php'); ?>"/>
         </div>
      </form>
   </div>
       
   <!-- BOF PRODUCT MODAL -->
   <div id="modal-products" style="display:none;width:820px;">
      <div class="modal-title"><h3>Products</h3></div>
      <div class="modal-content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
      
        <!-- BOF GRIDVIEW -->
        <div id="grid-products" class="grid jq-grid grid-item">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
								<td class="border-right text-center" width="20"><input type="checkbox" class="chk-all"/></td> 
								<td class="border-right text-center" width="140"><a class="sort default active up" column="code">Code</a></td>
								<td class="border-right text-center" width="100"><a class="sort down" column="model">Model</a></td>
								<td class="border-right text-center"><a class="sort" column="description">Description</a></td> 
								<td class="border-right text-center" width="60"><a class="sort" column="unit">Unit</a></td> 
								<td class="border-right text-center" width="60"><a class="sort" column="price">Price</a></td> 
               </tr>
             </thead>
             <tbody></tbody>
           </table>
         </div>
         
         <!-- BOF Pagination -->
				<div id="products-pagination"></div>
       </div>
      
       <div class="modal-footer">
         <a class="btn" rel="modal:close">Close</a>
         <a id="PRD" class="btn add-item" rel="modal:close">Add</a>
         <div class="clear"></div>
       </div>
     </div>
     
	</div>
      
       <script>
				$(function() {
										
			  	var products = { 
			    	"url":"/populate/products.php",
			      "limit":"10",
						"data_key":"products",
						"row_template":"row_modal_products",
			      "pagination":"#products-pagination",
			      "searchable": true
					}
					$('#grid-products').grid(products);
					$('#grid-work-order-items').grid({});
					
					$('.add-item').append_item();
				  $('#remove-work-order-products').remove_item();
				  $('#order_amount').formatCurrency({region:"en-PH"});
				  $('.get-amount').compute_amount();
			  }) 
			  
			  function row_modal_products(row) {
           var row_id	= "prd-"+ row['id'];
           var cell		= $("<tr id=\""+ row_id +"\"></tr>");
           
           cell.append("<td class=\"border-right text-center\"><input type=\"checkbox\" value=\""+ row['id'] +"\" class=\"chk-item\"/></td>");
           cell.append("<td class=\"prd-code border-right\">"+ row['code'] +"</td>");
           cell.append("<td class=\"prd-brand border-right\">"+ row['brand'] +"</td>");
           cell.append("<td class=\"prd-description border-right\">"+ (row['description'] || '') +"</td>");
           cell.append("<td class=\"prd-unit border-right text-center\">"+ row['unit'] +"</td>");
           cell.append("<td class=\"prd-price text-right currency\">"+ row['price'] +"</td>");
           
           cell.find('.currency').formatCurrency({region:"en-PH"});
           return cell;
         }
         
         $.fn.compute_amount = function() {
           this.live('keyup', function(e) {
           // this.live('blur', function(e) {
             var row		= $(this).closest('tr');
             var quantity	= row.find('.item-quantity').val();
             var price		= row.find('.item-price').val();
             var amount		= parseFloat(quantity * clean_currency(price));
             
             row.find('.item-amount').val(amount).formatCurrency({region:"en-PH"});
             compute_total_amount();
           })
         }
         
         function compute_total_amount() {
           var total_amount = $('#order_amount');
           
           // Compute Total Amount Price
           total_amount.val('0.00');
           $('#work-order-products').find('.item-amount').each(function() {
             var amount_price = clean_currency($(this).val());
             total_amount.val(parseFloat(total_amount.val()) + amount_price);
           })
           total_amount.formatCurrency({region:"en-PH"});
         }
                  
         $.fn.append_item = function() {
           this.click(function(e) {
             var table = $('.grid-item').find('table');
             var grid = $('#work-order-products');
             var item_type = $(this).attr('id');
             
           	 table.find('.chk-item:checked').each(function() {
           	   var id		= $(this).val()
           	   var row_id	= "prd-"+ id;
           	   
           	   $(this).prop('checked', false);
           	   
           	   if(grid.find('#'+ row_id).length == 0) {
           	     var item = $('#prd-'+ $(this).val());
           	     var data = {
           	       'id':id,
           	       'product_code':item.find('.prd-code').html(),
           	       'product_id':id,
           	       'unit':item.find('.prd-unit').html(),
           	       'quantity':1,
           	       'item_price':item.find('.prd-price').html(),
           	     }
           	     var row = row_template_work_order_items(data);
           	     
           	     build_options_unit(row.find("select"));
           	     grid.append(row);
           	     compute_total_amount();
           	   }
           	 });
           	 populate_index(grid);
           })
         }
         
         $.fn.remove_item = function() {
           this.click(function(e) {
             e.preventDefault();
             
             var grid = $($(this).attr('grid'));
             grid.find('.chk-item:checked').closest('tr').remove();
             compute_total_amount();
           	 populate_index(grid);
           })
         }
       </script>

<?php }
require('footer.php'); ?>