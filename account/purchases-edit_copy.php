<?php
  $capability_key = 'users';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
	  $suppliers = $DB->Fetch('suppliers', array('columns' => 'id, name'));
	  $purchase = $Query->purchase_by_id($_GET['id']);
	  
	  if($purchase['status'] == "Publish") page_not_found();
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
        <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
        	 <input type="hidden" name="action" value="edit_purchase"/>
        	 <input type="hidden" name="purchase[id]" value="<?php echo $purchase['id']; ?>"/>
           <!-- BOF TEXTFIELDS -->
           <div>
           	<table>
                 <tr>
                    <td width="120">Purchase Number:</td><td width="340"><input type="text" name="purchase[purchase_number]" value="<?php echo $purchase['purchase_number']; ?>" class="text-field"/></td>
                    <td width="120"></td><td width="340"></td>
                 </tr>
                 <tr>
                    <td>Supplier:</td>
                    <td colspan="99">
                      <select name="purchase[supplier_id]" style="width:655px;"><?php echo build_select_suppliers($purchase['supplier_id']); ?></select>
                    </td>
                 </tr>
                 <tr>
                    <td>Delivery Via:</td><td><input type="text" name="purchase[delivery_via]" value="<?php echo $purchase['delivery_via']; ?>" class="text-field"/></td>
                    <td>Delivery Date:</td><td><input type="text" name="purchase[delivery_date]" value="<?php echo date("F d, Y", strtotime($purchase['delivery_date'])); ?>" class="text-field date-pick"/></td>
                 </tr>
                 <tr>
                    <td>Trade Terms:</td><td><input type="text" name="purchase[trade_terms]" value="<?php echo $purchase['trade_terms']; ?>" class="text-field"/></td>
                    <td>Payment Terms:</td><td><input type="text" name="purchase[payment_terms]" value="<?php echo $purchase['payment_terms']; ?>" class="text-field"/></td>
                 </tr>
                 <tr><td height="5" colspan="99"></td></tr>
              </table>
           </div>
           
           <!-- BOF GRIDVIEW -->
           <div id="grid-purchase-materials" class="grid jq-grid" style="min-height:146px;">
             <table cellspacing="0" cellpadding="0">
               <thead>
                 <tr>
                   <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
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
                    <td>
                       <strong><a href="#modal-product-materials" class="" rel="modal:open">Add Item</a></strong>
                       &nbsp;|&nbsp;
                       <strong><a id="remove-purchase-materials" href="#" class="" grid="#purchase-materials">Remove Item</a></strong>
                    </td>
                    <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" class="text-right" style="width:95px;" disabled/></td>
                 </tr>
                 <tr><td colspan="2">Remarks:<br/><textarea name="purchase[remarks]" style="min-width:650px;width:98.9%;height:50px;"><?php echo $purchase['remarks']; ?></textarea></td></tr>
              </table>
           </div>
           
           <div class="field-command">
         	   <div class="text-post-status">
         	     <strong>Save As:</strong>&nbsp;&nbsp;<select name="purchase[status]"><?php echo build_select_post_status("", $purchase['status']); ?></select>
             </div>
             <input type="submit" value="Save" class="btn"/>
             <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('purchases-show.php?id='. $_GET['id']); ?>"/>
           </div>
        </form>
     </div>
     
     <!-- BOF MODAL -->
   <div id="modal-product-materials" style="display:none;width:820px;">
      <div class="modal-title"><h3>Materials</h3></div>
      <div class="modal-content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" />
      </div>
      
        <!-- BOF GRIDVIEW -->
        <div id="grid-materials" class="grid jq-grid">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
								<td class="border-right text-center" width="20"><input type="checkbox" class="chk-all"/></td> 
								<td class="border-right text-center" width="140"><a class="sort default active up" column="code">Code</a></td>
								<td class="border-right text-center" width="100"><a class="sort down" column="model">Model</a></td>
								<td class="border-right text-center"><a class="sort" column="description">Supplier</a></td> 
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
         <a id="add-item" class="btn" rel="modal:close">Add</a>
         <div class="clear"></div>
       </div>
     </div>
	</div>
    
     
     <script>
       $(function() {
       	var data = { 
		    	"url":"/populate/purchases-items.php?pid=<?php echo $purchase['id'] ?>",
		      "limit":"50",
					"data_key":"purchase_items",
					"row_template":"row_template_purchase_material"
				}         	       	
         var grid = $('#purchase-materials');
         
         var data_materials = { 
		    	"url":"/populate/material-costs.php",
		      "limit":"10",
					"data_key":"material-costs",
					"row_template":"row_modal_materials",
		      "pagination":"#materials-pagination"
				}

         $('.jq-grid').grid(data);
         $('#grid-materials').grid(data_materials);
         $('#purchase_amount').currency_format(<?php echo $purchase['total_amount']; ?>);
         $('#add-item').append_item();
         $('#purchase_amount').formatCurrency();
         $('.get-amount').compute_amount();
       })
       
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
         var total_amount = $('#purchase_amount');
         
         // Compute Total Amount Price
         total_amount.val('0.00');
         $('#purchase-materials').find('.item-amount').each(function() {
           var amount_price = clean_currency($(this).val());
           total_amount.val(parseFloat(total_amount.val()) + amount_price);
         })
         total_amount.formatCurrency();
       }
      
       function row_modal_materials(row) {
         var row_id	= "mat-"+ row['id'];
         var cell		= $("<tr id=\""+ row_id +"\"></tr>");
         
         cell.append("<td class=\"border-right text-center\"><input type=\"checkbox\" value=\""+ row['id'] +"\" class=\"chk-item\"/></td>");
         cell.append("<td class=\"mat-code border-right\">"+ row['code'] +"</td>");
         cell.append("<td class=\"mat-brand border-right\">"+ row['model'] +"</td>");
         cell.append("<td class=\"mat-description border-right\">"+ row['supplier'] +"</td>");
         cell.append("<td class=\"mat-unit border-right text-center\">"+ row['unit'] +"</td>");
         cell.append("<td class=\"mat-price text-right currency\">"+ row['price'] +"</td>");
         
         cell.find('.currency').formatCurrency();
         return cell;
       }
            
       $.fn.append_item = function() {
         this.click(function(e) {
           var table = $('#grid-materials').find('table');
           var grid = $('#purchase-materials');
           
         	 table.find('.chk-item:checked').each(function() {
         	   var id		= $(this).val()
         	   var row_id	= "mat-"+ id;
         	   
         	   $(this).prop('checked', false);
         	     
         	   if(grid.find('#'+ row_id).length == 0) {
         	     var item = $('#mat-'+ $(this).val());
         	     var data = {
         	       'id':id,
         	       'code':item.find('.mat-code').html(),
         	       'description':item.find('.mat-description').html(),
         	       'unit':item.find('.mat-unit').html(),
         	       'quantity':1,
         	       'item_price':item.find('.mat-price').html(),
         	     }
         	     
         	     var row = row_template_purchase_material(data);
         	     
         	     build_options_unit(row.find("select"));
         	     grid.append(row);
         	     compute_total_amount();
         	   }
         	 });
         	 populate_index(grid);
         })
       }
    </script>

<?php }
require('footer.php'); ?>