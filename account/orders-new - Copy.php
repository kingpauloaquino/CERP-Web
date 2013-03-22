<?php
  /*
   * Module: Orders::Add
  */
  $capability_key = 'add_order';
  require('header.php');
	
	if($_POST['action'] == 'add_order') {
		$_POST['order']['po_date'] = date('Y-m-d', strtotime($_POST['order']['po_date']));
		$_POST['order']['delivery_date'] = date('Y-m-d', strtotime($_POST['order']['delivery_date']));
		$order_id = $Posts->AddOrder($_POST['order']);
		
		$items = $_POST['items'];
		
		if(!empty($items)) {
      $fields = array('item_id', 'item_type', 'quantity', 'remarks');
		  foreach ($items as $item) {
		  	$new_items = array();
		    foreach (explode('|', $item) as $index => $field) {
		  	  $new_items[$fields[$index]] =  $field;
		    }
				$new_items['order_id'] = $order_id;
				$Posts->AddOrderItem($new_items);
		  }			
			redirect_to($Capabilities->All['show_order']['url'].'?oid='.$order_id);
		}		
	} 
	
	$client = $DB->Find('suppliers', array(
  			'columns' => 'suppliers.id, suppliers.name', 
  	  	'conditions' => 'suppliers.name LIKE "ST SANGYO%"'
  	  )
		);
  
  $roles = $DB->Get('roles', array('columns' => 'id, name', 'conditions' => 'NOT id = 1'));
	$pay_terms = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('term_of_payment').'"', 'sort_column' => 'description'));
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<input type="hidden" name="action" value="add_order"/>
				<input type="hidden" id="order[total_quantity]" name="order[total_quantity]"/>
				<input type="hidden" id="order[total_amount]" name="order[total_amount]"/>
				<input type="hidden" id="order[client_id]" name="order[client_id]" value="<?php echo $client['id'] ?>"/>
				<h3 class="form-title">Basic Information</h3>
	    
	    	<span class="notice">
		<!--           <p class="info"><strong>Notice</strong> Message</p> -->
	    	</span>

				<div class="field">
		      <label class="label">Client:</label>
		      <div class="input">
		        <input type="text" value="<?php echo $client['name'] ?>" class="magenta" readonly="readonly" />
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">P/O Number:</label>
		      <div class="input">
		        <input type="text" id="order[po_number]" name="order[po_number]" />
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">P/O Date:</label>
		      <div class="input">
		        <input type="text" id="order[po_date]" name="order[po_date]" class="datepick" />
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">Delivery:</label>
		      <div class="input">
		        <input type="text" id="order[delivery_date]" name="order[delivery_date]" class="datepick" />
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">Terms:</label>
		      <div class="input">
		        <input type="text" id="order[terms]" name="order[terms]" />
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">Payment Terms:</label>
		      <div class="input">
		        <?php select_query_tag($pay_terms, 'id', 'description', '', 'order[payment_terms]', 'order[payment_terms]', '', ''); ?>
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea id="order[description]" name="order[description]"></textarea>
          </div>
          <div class="clear"></div>
        </div>  
        
        <div class="field">
          <label class="label">Remarks:</label>
          <div class="input">
            <textarea id="order[remarks]" name="order[remarks]"></textarea>
          </div>
          <div class="clear"></div>
        </div>  
        
        <br/>
        <h3 class="form-title">Items</h3>
				<div class="grid jq-grid">
					<table id="tbl-order-items" cellpadding="0" cellspacing="0">
			      <thead>
			         <tr>
		            <td width="5%" class="border-right text-center"><a>No.</a></td>
		            <td width="15%" class="border-right text-center"><a>Code No.</a></td>
		            <td width="15%" class="border-right text-center"><a>Item</a></td>
		            <td width="10%" class="border-right text-center"><a>Unit</a></td>
		            <td width="10%" class="border-right text-center"><a>Unit Price</a></td>
		            <td width="10%" class="border-right text-center"><a>Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Amount</a></td>
		            <td class="border-right"><a>Remarks</a></td>
			         </tr>
			      </thead>
			      <tbody>
			      </tbody>
					</table>	
				</div>
				
				<br/>
				<div class="field">
		      <label class="label">Total Quantity:</label>
		      <div class="input">
		        <input type="text" id="total_qty" name="total_qty" value="<?php echo $orders['total_quantity'] ?>" class="text w180" readonly="readonly"  />
		      </div>
		      <div class="clear"></div>
		    </div>  
        
				<div class="field">
		      <label class="label">Total Amount:</label>
		      <div class="input">
		        <input type="text" id="total_amnt" name="total_amnt" value="<?php echo number_format($orders['total_amount'], 2, '.', '') ?>" class="text w180" readonly="readonly"  />
		      </div>
		      <div class="clear"></div>
		    </div>  
				<br/>
				<div class="field">
          <label class="label"></label>
          <div class="input">
            <a class="btn" href="#add-order-item-modal" rel="modal:open">Add Item</a>
            <button class="btn">Create</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
			</form>
			
			<div id="add-order-item-modal" class="modal">
			   <h4 class="title">Add Order Item</h4>
			   <div class="content">
			      <form id="add-order-item-form">
			   	     <span class="notice"></span>
			      	 <input type="hidden" id="item_action" value="1"/>
			      	 <input type="hidden" id="item_id" name="item_id"/>
			      	 <input type="hidden" id="item_type" name="item_type"/>
			         <div class="t-row">
			            <label>Code No:</label>
			            <input type="text" id="item_code" name="item_code" value="" class="text searchbox" autocomplete="off"/>
			      	 		<div id="live_search_display_modal" class="live_search_display_modal"></div>
			         </div>
			         
			         <div class="t-row">
			            <label>Item Name:</label>
			            <input type="text" id="item_name" value="" class="text w180 required"/>
			         </div>
			         
			         <div class="t-row">
			            <label>Unit:</label>
			            <input type="text" id="item_unit" value="" class="text w180 required"/>
			         </div>
			         
			         <div class="t-row">
			            <label>Unit Price:</label>
			            <input type="text" id="item_unit_price" value="" class="text w180 required"/>
			         </div>
			         
			         <div class="t-row">
			            <label>Quantity:</label>
			            <input type="text" id="item_quantity" value="" class="text w180 required"/>
			         </div>
			         
			         <div class="t-row">
			            <label>Remarks:</label>
			            <textarea id="item_remarks" value="" rows="3" class="text w-260"></textarea>
			         </div>
			         
			         <br/>
			         <div class="t-foot">
			           <input type="button" id="order-item-continue" value="Continue" alt="1" class="order-item-submit-reset"/>
			           <input type="button" id="order-item-add-close" value="Add & Close" alt="0" class="order-item-submit-reset"/>
			         </div>
			      </form>
			   </div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="../javascripts/jquery.watermarkinput.js"></script>
<script>
	$(document).ready(function(){
		$('.datepick').datepicker({inline: true, dateFormat: 'MM dd, yy'});
		
		$('.searchbox').keydown(function(e) { if (e.keyCode == 9) { $('.live_search_display').hide(); }});
		
		$('.searchbox').keyup(function() {
			var searchbox = $(this).val().toUpperCase();
			if(searchbox=='') {	$('.live_search_display').hide();}
			else {
				switch ($(this).attr('id')) {
					case 'item_code':
					add_live_search('#live_search_display_modal', 'order', 
										'products', 'products.id, products.product_code AS item_code, product_classification AS class, '+
										'lookups1.description AS unit, lookups2.code AS currency, item_costs.cost, item_costs.item_type ',
										'LEFT OUTER JOIN item_costs ON products.id = item_costs.item_id '+
										'LEFT OUTER JOIN lookups AS lookups1 ON item_costs.unit = lookups1.id '+
										'LEFT OUTER JOIN lookups AS lookups2 ON item_costs.currency = lookups2.id ',
										'item_costs.item_type="PRD" AND products.product_code LIKE "%' + searchbox + '%" '+
										'UNION SELECT '+
										'materials.id, materials.material_code AS item_code, material_classification AS class, '+
										'lookups1.description AS unit, lookups2.code AS currency, item_costs.cost, item_costs.item_type '+
										'FROM materials '+
										'LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id '+
										'LEFT OUTER JOIN lookups AS lookups1 ON item_costs.unit = lookups1.id '+
										'LEFT OUTER JOIN lookups AS lookups2 ON item_costs.currency = lookups2.id '+
										'WHERE item_costs.item_type="MAT" AND materials.material_code LIKE "%'+ searchbox +'%" ', searchbox); break;
				}				
			}
			return false;    
		});
										
		$('#item_code').Watermark("Material Code");			
	});		

  $('#add-order-item-form').ready(function() {
  	$('#add-order-item-form').add_order_item();
    $('.order-item-submit-reset').submit_reset_order_item();
  });      
  
  jQuery.fn.submit_reset_order_item = function() {
  	this.click(function() {
  	  var x			= $(this).attr('alt');
  	  var form		= $(this).closest('form');
  	  var notice	= form.find('.notice');
  	  var complete	= true;
  	  
  	  notice.empty();
  	  form.find('#item_action').val(x);
  	  
  	  form.find('.required').each(function() {
        if($(this).val() == '') complete = false;
      });
      
      if(complete == false) {
      	notice.html('<p class="error">Please complete all the required fields</p>');
      	return false;
      }
      
  	  form.submit();
  	  form.find('.text').val('');
  	  $('#item_code').focus();
  	  if(x == 0) $('a.close-modal').click();
  	});
  }
  
  jQuery.fn.add_order_item = function() {
    this.submit(function(e) {
      e.preventDefault();
      
      var item_id				= $('#item_id').val() || '';
      var item_type			= $('#item_type').val() || '';
      var item_code			= $('#item_code').val() || '';
      var item_name			= $('#item_name').val() || '';
      var item_unit			= $('#item_unit').val() || '';
      var item_unit_price	= $('#item_unit_price').val() || '';
      var item_quantity		= $('#item_quantity').val() || '';
      var item_amount			= (item_quantity * item_unit_price) || '';
      var item_remarks		= $('#item_remarks').val() || '';
      
            
      var item_values		= item_id+'|'+item_type+'|'+item_quantity+'|'+item_remarks;
      var tr				= $('<tr></tr>');
     
  	  tr.append('<td class="highlight_red border-right text-right"><input type="hidden" name="items[]" value="'+item_values+'"/></td>');
  	  tr.append('<td class="highlight_red border-right">'+item_code+'</td>');
  	  tr.append('<td class="highlight_red border-right">'+item_name+'</td>');
  	  tr.append('<td class="highlight_red border-right text-right">'+item_unit+'</td>');
  	  tr.append('<td class="highlight_red border-right text-right">'+item_unit_price+'</td>');
  	  tr.append('<td class="highlight_red border-right text-right">'+item_quantity+'</td>');
  	  tr.append('<td class="highlight_red border-right text-right">'+item_amount.toFixed(2)+'</td>');
  	  tr.append('<td class="highlight_red border-right">'+item_remarks+'</td>');
  	  
      $('#tbl-order-items').find('tbody').append(tr);
      
      var rows = $("#tbl-order-items tr:gt(0)"); 
      var total_qty = 0;
      var total_amnt = 0.00;
	    rows.each(function(index) {
        var qty = parseInt($("td:nth-child(6)", this).text()) || 0;
        var amnt = parseFloat($("td:nth-child(7)", this).text()) || 0.00; 
        total_qty += qty;
        total_amnt += amnt
        $('#total_qty').val(total_qty);
        $('[name*="order[total_quantity]"]').val(total_qty);
        $('#total_amnt').val(total_amnt.toFixed(2));
        $('[name*="order[total_amount]"]').val(total_amnt.toFixed(2));
	    });
	    
    });
  }
</script>

<?php require('footer.php'); ?>