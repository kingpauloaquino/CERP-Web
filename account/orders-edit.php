<?php
  /* Module: Orders - Edit  */
  $capability_key = 'edit_order';
  require('header.php');
	
	$order = $Query->order_by_id($_GET['oid']);
	
	$pay_terms = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('term_of_payment').'"', 'sort_column' => 'description'));
?>
      <!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title">Orders &raquo; <span class="red"><?php echo $order['po_number']; ?></span></span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="order-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	 <input type="hidden" name="action" value="edit_order"/>
          	 <input type="hidden" name="order[id]" value="<?php echo $order['id']; ?>"/>
         <!-- BOF TEXTFIELDS -->
         <div>
         	<table>
               <tr>
                  <td width="120">Client:</td><td width="340"><input type="text" value="<?php echo $order['client'] ?>" class="text-field" disabled/></td>
                  <td width="120"></td><td width="340"></td>
               </tr>
               <tr>
                  <td>P/O Number:</td><td><input type="text" name="order[po_number]" value="<?php echo $order['po_number'] ?>" class="text-field" /></td>
                  <td>P/O Date:</td><td><input type="text" name="order[po_date]" value="<?php echo date("F d, Y", strtotime($order['po_date'])) ?>" class="text-field datepick"/></td>
               </tr>
               <tr>
                  <td>Terms:</td><td><input type="text" name="order[terms]" value="<?php echo $order['terms'] ?>" class="text-field"/></td>
                  <td>Delivery Date:</td><td><input type="text" name="order[delivery_date]" value="<?php echo date("F d, Y", strtotime($order['delivery_date'])) ?>" class="text-field datepick"/></td>
               </tr>
               <tr>
                  <td>Payment Terms:</td>
                  <td colspan="99">
                    <?php select_query_tag($pay_terms, 'id', 'description', $order['payment_terms_id'], 'order[payment_terms]', 'order[payment_terms]', '', 'width:655px;'); ?>
                  </td>
               </tr>               
               <tr>
                  <td>Description:</td>
                  <td colspan="99">
                    <input type="text" name="order[description]" value="<?php echo $order['description'] ?>" class="text-field" style="width:645px"/>
                  </td>
               </tr>
               <tr><td height="5" colspan="99"></td></tr>
            </table>
         </div>
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-order-items" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="140" class="border-right">Item Code</td>
                 <td width="50" class="border-right">Type</td>
                 <td class="border-right text-center">Remarks</td>
                 <td width="55" class="border-right text-center">Qty</td>
                 <td width="60" class="border-right text-center">Unit</td>
                 <td width="100" class="border-right text-center">Unit Price</td>
                 <td width="100" class="border-right text-center">Amount Price</td>
               </tr>
             </thead>
             <tbody id="order-materials"></tbody>
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
                     <strong><a href="#modal-materials" class="" rel="modal:open">Add Materials</a></strong>
                     &nbsp;|&nbsp;
                     <strong><a id="remove-order-materials" href="#" class="" grid="#order-materials">Remove Item</a></strong>
                  </td>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="order_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
               <tr><td colspan="2">Remarks:<br/><textarea name="order[remarks]" style="min-width:650px;width:98.9%;height:50px;"><?php echo $order['remarks'] ?></textarea></td></tr>
            </table>
         </div>
         <div class="field-command">
       	   <div class="text-post-status">
       	     <strong>Save As:</strong>&nbsp;&nbsp;<select name="order[status]"><?php echo build_select_post_status("", $order['status']); ?></select>
           </div>
       	   <input type="submit" value="Save" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('orders-show.php?oid='.$order['id']); ?>"/>
         </div>
      </form>
   </div>
       
   <!-- BOF PRODUCT MODAL -->
   <div id="modal-products" style="display:none;width:820px;">
      <div class="modal-title"><h3>Products</h3></div>
      <div class="modal-content">
        <!-- BOF Search -->
        <div class="search">
          <input type="text" name="keyword" placeholder="Search"/>
          <button>Go</button>
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
     
     <!-- BOF MATERIAL MODAL -->
     <div id="modal-materials" style="display:none;width:820px;">
      <div class="modal-title"><h3>Materials</h3></div>
      <div class="modal-content">
        <!-- BOF Search -->
        <div class="search">
          <input type="text" name="keyword" placeholder="Search"/>
          <button>Go</button>
        </div>
      
        <!-- BOF GRIDVIEW -->
        <div id="grid-materials" class="grid jq-grid grid-item">
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
				<div id="materials-pagination"></div>
       </div>
      
       <div class="modal-footer">
         <a class="btn" rel="modal:close">Close</a>
         <a id="MAT" class="btn add-item" rel="modal:close">Add</a>
         <div class="clear"></div>
       </div>
     </div>
     
	</div>
<script>
	$(function(){
		$('.datepick').datepicker({
			inline: true, dateFormat: 'MM dd, yy'
		});
	});
</script>
      
       <script>
				$(function() {
					var data = { 
			    	"url":"/populate/order-items.php",
			      "limit":"50",
						"data_key":"order_items",
						"row_template":"row_template_order_items",
			      "params":"order_id=<?php echo $order['id']; ?>"
					}
					
					$('#grid-order-items').grid(data);
					$('#order_amount').currency_format(<?php echo $order['total_amount']; ?>);
										
			  	var products = { 
			    	"url":"/populate/products.php",
			      "limit":"10",
						"data_key":"products",
						"row_template":"row_modal_products",
			      "pagination":"#products-pagination"
					}
					$('#grid-products').grid(products);
					
					var materials = { 
			    	"url":"/populate/materials.php",
			      "limit":"10",
						"data_key":"materials",
						"row_template":"row_modal_materials",
			      "pagination":"#materials-pagination"
					}
					$('#grid-materials').grid(materials);
					
					$('.add-item').append_item();
				  $('#remove-order-materials').remove_item();
				  $('#order_amount').formatCurrency();
				  $('.get-amount').compute_amount();
			  }) 
			  
			  function row_modal_products(row) {
           var row_id	= "mat-"+ row['id'];
           var cell		= $("<tr id=\""+ row_id +"\"></tr>");
           
           cell.append("<td class=\"border-right text-center\"><input type=\"checkbox\" value=\""+ row['id'] +"\" class=\"chk-item\"/></td>");
           cell.append("<td class=\"mat-code border-right\">"+ row['code'] +"</td>");
           cell.append("<td class=\"mat-brand border-right\">"+ row['brand'] +"</td>");
           cell.append("<td class=\"mat-description border-right\">"+ row['description'] +"</td>");
           cell.append("<td class=\"mat-unit border-right text-center\">"+ row['unit'] +"</td>");
           cell.append("<td class=\"mat-price text-right currency\">"+ row['price'] +"</td>");
           
           cell.find('.currency').formatCurrency();
           return cell;
         }
        
         function row_modal_materials(row) {
           var row_id	= "mat-"+ row['id'];
           var cell		= $("<tr id=\""+ row_id +"\"></tr>");
           
           cell.append("<td class=\"border-right text-center\"><input type=\"checkbox\" value=\""+ row['id'] +"\" class=\"chk-item\"/></td>");
           cell.append("<td class=\"mat-code border-right\">"+ row['code'] +"</td>");
           cell.append("<td class=\"mat-brand border-right\">"+ row['model'] +"</td>");
           cell.append("<td class=\"mat-description border-right\">"+ row['description'] +"</td>");
           cell.append("<td class=\"mat-unit border-right text-center\">"+ row['unit'] +"</td>");
           cell.append("<td class=\"mat-price text-right currency\">"+ row['price'] +"</td>");
           
           cell.find('.currency').formatCurrency();
           return cell;
         }
         
         $.fn.compute_amount = function() {
           this.live('keyup', function(e) {
           // this.live('blur', function(e) {
             var row		= $(this).closest('tr');
             var quantity	= row.find('.item-quantity').val();
             var price		= row.find('.item-price').val();
             var amount		= parseFloat(quantity * clean_currency(price));
             
             row.find('.item-amount').val(amount).formatCurrency();
             compute_total_amount();
           })
         }
         
         function compute_total_amount() {
           var total_amount = $('#order_amount');
           
           // Compute Total Amount Price
           total_amount.val('0.00');
           $('#order-materials').find('.item-amount').each(function() {
             var amount_price = clean_currency($(this).val());
             total_amount.val(parseFloat(total_amount.val()) + amount_price);
           })
           total_amount.formatCurrency();
         }
                  
         $.fn.append_item = function() {
           this.click(function(e) {
             var table = $('.grid-item').find('table');
             var grid = $('#order-materials');
             
             var item_type = $(this).attr('id');
             
           	 table.find('.chk-item:checked').each(function() {
           	   var id		= $(this).val()
           	  
           	   var row_id	= "mat-"+ id;
           	   
           	   $(this).prop('checked', false);
           	   
           	   if(grid.find('#'+ row_id).length == 0) {
           	     var item = $('#mat-'+ $(this).val());
           	     var data = {
           	       'id':id,
           	       'code':item.find('.mat-code').html(),
           	       'item_id':id,
           	       'item_type':item_type,
           	       'unit':item.find('.mat-unit').html(),
           	       'quantity':1,
           	       'item_price':item.find('.mat-price').html(),
           	     }
           	     var row = row_template_order_items(data);
           	     
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
       
<?php require('footer.php'); ?>