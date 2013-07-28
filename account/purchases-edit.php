<?php
  /* Module: Purchases - Edit  */
  $capability_key = 'edit_purchase';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
		$purchase = $Query->purchase_by_id($_GET['id']);
		
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
      <form id="purchase-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	 <input type="hidden" name="action" value="edit_purchase"/>
      	 <input type="hidden" name="purchase[id]" value="<?php echo $_GET['id']; ?>"/>
         <!-- BOF TEXTFIELDS -->
         <div>
         	<table>
               <tr>
                  <td width="120">P/O Number:</td><td width="340"><input type="text" id="po_number" name="purchase[po_number]" value="<?php echo $purchase['po_number']; ?>" class="text-field magenta" notice="po_number_status" autocomplete="off" required/>
                  	<span id="po_number_status" class="warning"></span>
                  </td>
                  <td width="120">P/O Date:</td><td width="340"><input type="text" name="purchase[po_date]" value="<?php echo date("F d, Y", strtotime($purchase['po_date'])) ?>" class="text-field date-pick-week" required /></td>
               </tr>
               <tr>
                  <td>Supplier:</td>
                  <td colspan="99">
                  	<input type="hidden" id="supplier_id" value="<?php echo $purchase['supplier_id'] ?>" />
                    <input type="text" value="<?php echo $purchase['supplier_name']; ?>" class="text-field" style="width:644px;" disabled/>
                  </td>
               </tr>
               <tr>
                  <td>Delivery Via:</td><td><input type="text" name="purchase[delivery_via]" value="<?php echo $purchase['delivery_via']; ?>" class="text-field" /></td>
                  <td>Delivery Date:</td><td><input type="text" name="purchase[delivery_date]" value="<?php echo date("F d, Y", strtotime($purchase['delivery_date'])) ?>" class="text-field date-pick-week" required /></td>
               </tr>
               <tr>
                  <td>Trade Terms:</td><td><input type="text" name="purchase[terms]" value="<?php echo $purchase['terms']; ?>" class="text-field"/></td>
                  <td>Payment Terms:</td><td><input type="text" name="purchase[payment_terms]" value="<?php echo $purchase['payment_terms']; ?>" class="text-field"/></td>
               </tr>
               <tr>
                  <td>Delivery:</td><td><input type="text" value="<?php echo $purchase['completion_status']; ?>" class="text-field" disabled/></td>
                  <td></td><td></td>
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
                 <td width="55" class="border-right text-center">MOQ</td>
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
                  <td align="right"><strong>Total Amount:</strong>&nbsp;&nbsp;<input id="purchase_amount" type="text" value="" class="text-right text-currency" style="width:95px;" disabled/></td>
               </tr>
               <tr><td colspan="2">Remarks:<br/><textarea name="purchase[remarks]" style="min-width:650px;width:98.9%;height:50px;"><?php echo $purchase['remarks']; ?></textarea></td></tr>
            </table>
         </div>
         <div class="field-command">
       	   <div class="text-post-status">
       	     <strong>Save As:</strong>&nbsp;&nbsp;<select id="purchase-status" name="purchase[status]"><?php echo build_select_post_status_by_level(getConditionByLevel($_SESSION['user']['level']), $purchase['status_id']); ?></select>
           </div>
       	   <input id="btn-submit" type="submit" value="Save" class="btn"/>
           <input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('purchases-show.php?id='.$_GET['id']); ?>"/>
         </div>
      </form>
   </div>
       
   <!-- BOF MODAL -->
   <div id="modal-product-materials" class="modal" style="display:none;width:820px;">
      <div class="modal-title"><h3>Materials</h3></div>
      <div class="modal-content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
        <!-- BOF GRIDVIEW -->
        <div id="grid-materials" class="grid jq-grid">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
								<td class="border-right text-center" width="20"><input type="checkbox" class="chk-all"/></td> 
								<td class="border-right text-center" width="140"><a class="sort default active up" column="code">Code</a></td>
								<td class="border-right text-center" width="80"><a class="sort" column="stock">Stock</a></td> 
								<td class="border-right text-center"><a class="sort" column="description">Description</a></td> 
								<td class="border-right text-center" width="60"><a class="sort" column="unit">Unit</a></td> 
								<td class="border-right text-center" width="60"><a class="sort" column="price">Price</a></td> 
               </tr>
             </thead>
             <tbody id="production-requests"></tbody>
           </table>
         </div>
         
         <!-- BOF Pagination -->
				<div id="materials-pagination"></div>
       </div>
      
       <div class="modal-footer">
         <a class="btn parent-modal" rel="modal:close">Close</a>
         <a id="add-item" class="btn" rel="modal:close">Add</a>
         <div class="clear"></div>
       </div>
     </div>
     
     <div id="modal-material-requests" class="modal" style="display:none;width:920px;">
      <div class="modal-title"><h3>Unreleased Production Requests</h3></div>
      <div class="modal-content">
      
        <div id="grid-requests" class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="3%" class="border-right text-center"><a></a></td>
	              <td width="12%" class="border-right text-center"><a>P/O No.</a></td>
	              <td width="12%" class="border-right text-center"><a>Tracking No.</a></td>
	              <td width="12%" class="border-right text-center"><a>Prod. Lot No</a></td>
	              <td class="border-right text-center"><a>Status</a></td>
	              <td width="10%" class="border-right text-center"><a>Pending</a></td>
	              <td width="10%" class="border-right text-center"><a>Released</a></td>
	              <td width="10%" class="border-right text-center"><a>Requested</a></td>
	            </tr>
	          </thead>
	          <tbody>
	          </tbody>
	        </table>
	      </div>	
	      <div id="requests-pagination"></div>
     </div>     
     <div class="modal-footer">
       <a class="btn modal-close" rel="modal:close">Close</a>
       <div class="clear"></div>
     </div>
	</div>
      
       <script>
				$(function() {
					// check po number
					$('#po_number').keyup(function() {
						if($(this).val() != '<?php echo $purchase['po_number'] ?>') {
							($(this).is_existing('purchases', 'id', '', 'po_number="' +$(this).val()+ '"', 'po_number')) 
								? $('#submit-btn').attr('disabled', true)
								: $('#submit-btn').attr('disabled', false);
						}
					});
					
					var data = { 
			    	"url":"/populate/purchases-items.php?pid=<?php echo $purchase['id']; ?>",
			      "limit":"50",
						"data_key":"purchase_items",
						"row_template":"row_template_purchase_material"
					}
					$('#grid-purchase-materials').grid(data);
					$('#purchase_amount').currency_format(<?php echo $purchase['total_amount']; ?>);
					
					populate($('#supplier_id').val());
					
					$('#add-item').click(function(){
						$('#keyword').val('');
						populate($('#supplier_id').val());
					});
					
					$('.parent-modal').click(function(){
					    $('.blocker').last().remove();
					    $('#modal-product-materials').hide();
					});	
					
				  function populate(sup_id) {
				  	var data = { 
				    	"url":"/populate/material-supplier-costs.php?sid=" + sup_id,
				      "limit":"10",
							"data_key":"material-supplier-costs",
							"row_template":"row_modal_materials",
				      "pagination":"#materials-pagination",
				      "searchable": true
						}
						$('#grid-materials').grid(data);
						
						$('#add-item').append_item();
					  $('#remove-purchase-materials').remove_item();
					  $('#purchase_amount').formatCurrency({region:"en-PH"});
					  $('.get-amount').compute_amount();	
			  	}
			  }); 
       
         function row_modal_materials(row) {
           var row_id	= "mat-"+ row['id'];
           var cell		= $("<tr id=\""+ row_id +"\"></tr>");
           
           cell.append("<td class=\"border-right text-center\"><input type=\"checkbox\" value=\""+ row['id'] +"\" class=\"chk-item\"/></td>");
           cell.append("<td class=\"mat-code border-right\"><input type='hidden' class='mat-currency' value='"+ row['currency'] +"' /><input type='hidden' class='mat-moq' value='"+ row['moq'] +"' /><a class=\"mat\" alt=\"" + row['id'] + "\" rel=\"modal:open\" href=\"#modal-material-requests\">"+ row['code'] +"</a></td>");
           cell.append("<td class=\"mat-stock border-right text-right numbers\">"+ (parseFloat(row['stock']) || '0') +"</td>");
           cell.append("<td class=\"mat-description border-right\">"+ row['description'] +"</td>");
           cell.append("<td class=\"mat-unit border-right text-center\">"+ row['unit'] +"</td>");
           cell.append("<td class=\"mat-price text-right currency\">"+ row['price'] +"</td>");
           
           cell.find('.currency').formatCurrency({region:"en-PH"});
           cell.find('.numbers').digits();
           
               
					 var a = cell.find('.mat');                               
           $(a).click(function(e){
           	var data = { 
				    	"url":"/populate/production-requests.php?mid=" + $(this).attr('alt') ,
				      "limit":"10",
							"data_key":"production-requests",
							"row_template":"row_modal_requests",
				      "pagination":"#requests-pagination"
						}
						$('#grid-requests').grid(data);
           })
           
           return cell;
         }
         
         function row_modal_requests(row) {
           var row_id	= "mat-"+ row['id'];
           var cell		= $("<tr id=\""+ row_id +"\"></tr>");
           
           cell.append("<td class=\"border-right text-center\" replace=\"#{index}\"></td>");
           cell.append("<td class=\"mat-po_number border-right text-center\">"+ row['po_number'] +"</td>");
           cell.append("<td class=\"mat-tracking_no border-right text-center\">"+ row['tracking_no'] +"</td>");
           cell.append("<td class=\"mat-prod_lot_no border-right text-center\">"+ row['prod_lot_no'] +"</td>");
           cell.append("<td class=\"mat-status border-right text-center\">"+ row['status'] +"</td>");
           cell.append("<td class=\"mat-released border-right text-right numbers\">"+ parseFloat(row['pending_qty']) +"</td>");
           cell.append("<td class=\"mat-prod_lot_no border-right text-right numbers\">"+ (parseFloat(row['plan_qty']) - parseFloat(row['pending_qty'])) +"</td>");
           cell.append("<td class=\"mat-plan_qty border-right text-right numbers\">"+ parseFloat(row['plan_qty']) +"</td>");
           
           cell.find('.currency').formatCurrency({region:"en-PH"});
           cell.find('.numbers').digits();               
           
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
           var total_amount = $('#purchase_amount');
           
           // Compute Total Amount Price
           total_amount.val('0.00');
           $('#purchase-materials').find('.item-amount').each(function() {
             var amount_price = clean_currency($(this).val());
             total_amount.val(parseFloat(total_amount.val()) + amount_price);
           })
           total_amount.formatCurrency({region:"en-PH"});
         }
                  
         $.fn.append_item = function() {
           this.click(function(e) { 
             var table = $('#grid-materials').find('table');
             var grid = $('#purchase-materials');
             
           	 table.find('.chk-item:checked').each(function() {
           	   var id		= $(this).val(); 
           	   var row_id	= "mat-"+ id;
           	   
           	   $(this).prop('checked', false);
           	   
           	   if(grid.find('#'+ row_id).length == 0) {
           	     var item = $('#mat-'+ $(this).val()); 
           	     var data = {
           	       'id':id,
           	       'item_id':id,
           	       'code':item.find('.mat-code').html(),
           	       'description':item.find('.mat-description').html(),
           	       'unit':item.find('.mat-unit').html(),
           	       'quantity':1,
           	       'item_price':item.find('.mat-price').html(),
           	       'currency':item.find('.mat-currency').val(),
           	       'moq':item.find('.mat-moq').val(),
           	     }
           	     
           	     var row = row_template_purchase_material(data);
           	     
           	     build_options_unit(row.find("select"));
           	     grid.append(row);
           	     compute_total_amount();
           	   }
           	   /*
           	   if(grid.find('#'+ row_id).length == 0) {
           	     var item = $('#mat-'+ $(this).val());
           	     var row = $('<tr id="'+ row_id +'"></tr>');
           	     
           	     var unit		= 'Liter';
           	     var quantity	= 1;
           	     var price		= 0.00;
           	     var amount		= parseFloat(quantity * clean_currency(price));
           	     
           	     row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
           	     row.append('<td class="border-right text-center" replace="#{index}"></td>');
           	     row.append('<td class="border-right">'+ item.find('.mat-code').html() +'</td>');
           	     row.append('<td class="border-right">'+ item.find('.mat-description').html() +'</td>');
           	     row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ quantity +'" class="text-field-smallest text-right get-amount item-quantity"/></td>');
           	     row.append('<td class="border-right text-center">'+ unit +'</td>');
           	     row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][price]" value="'+ price +'" class="currency text-field-price text-right get-amount item-price"/></td>');
           	     row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
           	   
           	     row.find('.currency').formatCurrency();
           	     // build_options_unit(row.find("select"));
           	     grid.append(row);
           	     compute_total_amount();
           	   }
           	   */
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