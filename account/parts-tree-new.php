<?php
  /* Module: Parts Tree - New  */
  $capability_key = 'add_parts_tree';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
?>
      <!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_parts_tree']['url'].'?pid='.$_GET['pid'].'&code='.$_GET['code'].'" class="nav">'.$Capabilities->All['show_parts_tree']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['edit_parts_tree']['url'].'?pid='.$_GET['pid'].'&code='.$_GET['code'].'" class="nav">'.$Capabilities->All['edit_parts_tree']['name'].'</a>'; 
					echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_GET['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	 <input type="hidden" name="action" value="add_parts_tree"/>
				 <input type="hidden" name="pid" value="<?php echo $_GET['pid'] ?>">
				<input type="hidden" name="code" value="<?php echo $_GET['code'] ?>">		
         
         <!-- BOF GRIDVIEW -->
         <div id="grid-parts-materials" class="grid jq-grid" style="min-height:146px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="20" class="border-right text-center"><input type="checkbox" class="chk-all"/></td>
                 <td width="20" class="border-right text-center">No.</td>
                 <td width="140" class="border-right">Item Code</td>
                 <td width="55" class="border-right text-center">Qty</td>
                 <td width="30" class="border-right text-center">Unit</td>
                 <td width="100" class="border-right text-center">Unit Price</td>
                 <td width="100" class="border-right text-center">Amount</td>
                 <td class="text-center">Remarks</td>
               </tr>
             </thead>
             <tbody id="parts-materials"></tbody>
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
                     <strong><a id="remove-parts-materials" href="#" class="" grid="#parts-materials">Remove Item</a></strong>
                  </td>
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
            </table>
         </div>
         <div class="field-command">
       	   <input type="submit" value="Save" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('products.php'); ?>"/>
         </div>
      </form>
   </div>
       
   <!-- BOF MODAL -->
   <div id="modal-product-materials" style="display:none;width:820px;">
      <div class="modal-title"><h3>Materials</h3></div>
      <div class="modal-content">
        <!-- BOF Search -->
        <div class="search">
          <input type="text" name="keyword" placeholder="Search"/>
          <button>Go</button>
        </div>
      
        <!-- BOF GRIDVIEW -->
        <div id="grid-materials" class="grid jq-grid">
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
         <a id="add-item" class="btn" rel="modal:close">Add</a>
         <div class="clear"></div>
       </div>
     </div>
	</div>
      
       <script>
				$(function() {
			  	var data = { 
			    	"url":"/populate/materials.php",
			      "limit":"10",
						"data_key":"materials",
						"row_template":"row_modal_materials",
			      "pagination":"#materials-pagination"
					}
					$('#grid-parts-materials').grid({});
					$('#grid-materials').grid(data);
					
					$('#add-item').append_item();
				  $('#remove-parts-materials').remove_item();
				  $('#purchase_amount').formatCurrency();
				  $('.get-amount').compute_amount();
			  }) 
        
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
           var total_amount = $('#purchase_amount');
           
           // Compute Total Amount Price
           total_amount.val('0.00');
           $('#parts-materials').find('.item-amount').each(function() {
             var amount_price = clean_currency($(this).val());
             total_amount.val(parseFloat(total_amount.val()) + amount_price);
           })
           total_amount.formatCurrency();
         }
                  
         $.fn.append_item = function() {
           this.click(function(e) {
             var table = $('#grid-materials').find('table');
             var grid = $('#parts-materials');
             
           	 table.find('.chk-item:checked').each(function() {
           	   var id		= $(this).val();
           	   var row_id	= "mat-"+ id;
           	   
           	   $(this).prop('checked', false);
           	   
           	   if(grid.find('#'+ row_id).length == 0) {
           	     var item = $('#mat-'+ $(this).val());
           	     var data = {
           	       'id':id,
           	       'code':item.find('.mat-code').html(),
           	       'material_id':id,
           	       'unit':item.find('.mat-unit').html(),
           	       'quantity':1,
           	       'item_price':item.find('.mat-price').html(),
           	     }
           	     
           	     var row = row_template_parts_tree(data);
           	     
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