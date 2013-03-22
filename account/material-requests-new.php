<?php
  /*
   * Module: Material Requests - New
  */
  $capability_key = 'add_material_request';
  require('header.php');
	
	if($_POST['action'] == 'add_material_request') {
		$req = array(
  	  'request_type'	=> $_POST['request_type'],
  	  'product_id'	=> $_POST['product_id'],
  	  'lot_no'	=> $_POST['lot_no'],
  	  'production_purchase_order_id'	=> $_POST['production_purchase_order_id'],
  	  'request_qty'	=> $_POST['request_qty'],
  	  'request_date'	=> date('Y-m-d'),
  	  'requestor_id'	=> $_SESSION['user']['id'],
  	  'remarks'	=> $_POST['remarks']
		);					
		$mat_req_id = $Posts->AddMaterialRequest($req);
		
		$prod = array('production_purchase_order_id'	=> $_POST['production_purchase_order_id'],
			  	  'lot_no'	=> $_POST['lot_no'],
			  	  'product_id'	=> $_POST['product_id'],
			  	  'order_qty'	=> $_POST['request_qty'],
			  	  'produce_qty'	=> ($_POST['request_qty'] * 0.05) + $_POST['request_qty'],
			  	  'prod_ship_date'	=> date('Y-m-d'),
			  	  'type'	=> 123, //lookups id for 'Request'
			  	  'init'	=> 1,
			  	  'request_id' => $mat_req_id
		);	
		$prod_pur_ordr_id = $Posts->AddPurchaseOrderProducts($prod);
		
		$items = $_POST['items'];
		
		if(!empty($items)) {
      $fields = array('material_id', 'request_qty', 'issue_qty', 'remarks');
		  foreach ($items as $item) {
		  	$new_items = array();
		    foreach (explode('|', $item) as $index => $field) {
		  	  $new_items[$fields[$index]] =  $field;
		    }
				$new_items['material_request_id'] = $mat_req_id;
				$Posts->AddMaterialRequestItem($new_items);
				
				$mats = array('production_purchase_order_product_id'	=> $prod_pur_ordr_id,
								  	  'material_id'	=> $new_items['material_id'],
								  	  'qty'	=> $new_items['request_qty'],
								  	  'plan_qty'	=> ($new_items['request_qty'] * 0.05) + $new_items['request_qty']
				);	
				$ppops = $Posts->AddPurchaseOrderProductMaterials($mats);
				
				$args = array('item_id' => $new_items['material_id'], 'prod_lot_no' => $_POST['lot_no'], 'ppopid' => $prod_pur_ordr_id); 
				$num_of_records = $Posts->InitProductionInventory($args);	
		  }			
			redirect_to($Capabilities->All['show_material_request']['url'].'?mrid='.$mat_req_id);
		}
	} 
  
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
				<input type="hidden" name="action" value="add_material_request"/>
				<h3 class="form-title">Basic Information</h3>
	    
	    	<span class="notice">
		<!--           <p class="info"><strong>Notice</strong> Message</p> -->
	    	</span>
	    	
	    	<div class="field">
		      <label class="label">Type:</label>
		      <div class="input">
		        <?php 
		        	$types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('mat_req_type').'"', 'sort_column' => 'description'));
		        	select_query_tag($types, 'id', 'description', '', 'request_type', 'request_type', '', ''); ?>
		      </div>
		      <div class="clear"></div>
		    </div>

				<div class="field">
		      <label class="label">P/O No.:</label>
		      <div class="input">
		        <input type="hidden" id="production_purchase_order_id" name="production_purchase_order_id" />
		        <input type="text" id="po_number" name="po_number" class="searchbox text" autocomplete="off" />
						<div id="live_search_display1" class="live_search_display"></div>
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">Lot No.:</label>
		      <div class="input">
		        <input type="text" id="lot_no" name="lot_no" class="searchbox text" autocomplete="off" />
						<div id="live_search_display2" class="live_search_display"></div>
		      </div>
		      <div class="clear"></div>
		    </div>	
		    
		    <div class="field">
		      <label class="label">Product:</label>
		      <div class="input">
		        <input type="hidden" id="product_id" name="product_id" />
		        <input type="text" id="product_name" name="product_name" class="searchbox text" autocomplete="off" />
						<div id="live_search_display3" class="live_search_display"></div>
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">Quantity:</label>
		      <div class="input">
		        <input type="text" id="request_qty" name="request_qty" autocomplete="off" />
		      </div>
		      <div class="clear"></div>
		    </div>
        
        <div class="field">
          <label class="label">Remarks:</label>
          <div class="input">
            <textarea id="remarks" name="remarks"></textarea>
          </div>
          <div class="clear"></div>
        </div>  
        
        <br/>
        <h3 class="form-title">Items</h3>
				<div class="grid jq-grid">
					<table id="tbl-req-items" cellpadding="0" cellspacing="0">
			      <thead>
			         <tr>
		            <td width="5%" class="border-right text-center"><a>No.</a></td>
		            <td width="20%" class="border-right text-center"><a>Item</a></td>
		            <td width="10%" class="border-right text-center"><a>UOM</a></td>
		            <td width="15%" class="border-right text-center"><a>Request Qty</a></td>
		            <td width="15%" class="border-right text-center"><a>Issue Qty</a></td>
		            <td class="border-right"><a>Remarks</a></td>
			         </tr>
			      </thead>
			      <tbody>
			      </tbody>
					</table>	
				</div>
				
				<br/>
				<div class="field">
          <label class="label"></label>
          <div class="input">
            <a id="btn_modal" class="btn" href="#add-req-item-modal" rel="modal:open">Add Item</a>
            <button class="btn">Create</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
			</form>
			
			<div id="add-req-item-modal" class="modal">
			   <h4 class="title">Add Request Material</h4>
			   <div class="content">
			      <form id="add-req-item-form">
			   	     <span class="notice"></span>
			      	 <input type="hidden" id="item_action" value="1"/>
			      	 <input type="hidden" id="item_id" name="item_id"/>
			         <div class="t-row">
			            <label>Material Code:</label>
			            <input type="text" id="item_code" name="item_code" value="" class="text searchbox" autocomplete="off"/>
			      	 		<div id="live_search_display_modal" class="live_search_display"></div>
			         </div>
			         
			         <div class="t-row">
			            <label>Unit:</label>
			            <input type="text" id="item_unit" value="" class="text w180" readonly="reaadonly" />
			         </div>
			         			         
			         <div class="t-row">
			            <label>Request Qty:</label>
			            <input type="text" id="item_req_qty" value="" class="text w180 required"/>
			         </div>			         
			         			         
			         <div class="t-row">
			            <label>Issue Qty:</label>
			            <input type="text" id="item_issue_qty" value="" class="text w180 required"/>
			         </div>
			         
			         <div class="t-row">
			            <label>Remarks:</label>
			            <textarea id="item_remarks" value="" rows="3" class="text w-260"></textarea>
			         </div>
			         
			         <br/>
			         <div class="t-foot">
			           <input type="button" id="req-item-continue" value="Continue" alt="1" class="req-item-submit-reset"/>
			           <input type="button" id="req-item-add-close" value="Add & Close" alt="0" class="req-item-submit-reset"/>
			         </div>
			      </form>
			   </div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="../javascripts/jquery.watermarkinput.js"></script>
<script>
	$(document).ready(function(){
		$('.searchbox').keydown(function(e) { if (e.keyCode == 9) { $('.live_search_display').hide(); }});
		
		$('.searchbox').keyup(function() {
			var searchbox = $(this).val().toUpperCase();
			if(searchbox=='') {	$('.live_search_display').hide();}
			else {
				switch ($(this).attr('id')) {
					case 'po_number':
					add_live_search('#live_search_display1', 'mat_req_po', 
											'production_purchase_orders', 'orders.po_number AS item_code, production_purchase_orders.id AS ppoid ', 
											'INNER JOIN orders ON orders.id = production_purchase_orders.order_id INNER JOIN lookups ON lookups.id = production_purchase_orders.status',
											'lookups.description = "Active" AND orders.po_number LIKE "' + searchbox + '%" ', searchbox);	break;				
					case 'lot_no':
					add_live_search('#live_search_display2', 'mat_req_lot', 
											'production_purchase_order_products', 'DISTINCT(production_purchase_order_products.lot_no) AS item_code ', 
											' ',
											'production_purchase_order_products.lot_no LIKE "' + searchbox + '%" ', searchbox);	break;	
					case 'product_name':
					add_live_search('#live_search_display3', 'mat_req_prd', 
											'production_purchase_order_products', 'products.product_code AS item_code, production_purchase_order_products.product_id AS pid ', 
											'INNER JOIN products ON products.id = production_purchase_order_products.product_id ' +
											'INNER JOIN lookups ON lookups.id = production_purchase_order_products.type ',
											'(production_purchase_order_products.lot_no = "' + $('#lot_no').val() + 
											'" AND products.product_code LIKE "' + searchbox + '%" ' +
											' AND lookups.description="Plan") ', searchbox); break;	
					case 'item_code':
					add_live_search('#live_search_display_modal', 'mat_req_items', 
											'materials', 'materials.id, materials.material_code AS item_code, lookups1.description AS unit ', 
											'LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id LEFT OUTER JOIN lookups AS lookups1 ON item_costs.unit = lookups1.id ',
											'item_costs.item_type="MAT" AND materials.material_code LIKE "'+ searchbox +'%" ', searchbox);	break;	
				}				
			}
			return false;    
		});
												
		$('#po_number').Watermark("P/O Number");
		$('#lot_no').Watermark("Lot Number");
		$('#product_name').Watermark("Product Code");
		$('#item_code').Watermark("Item Code");			
	});

  $('#add-req-item-form').ready(function() {
  	$('#add-req-item-form').add_req_item();
    $('.req-item-submit-reset').submit_reset_req_item();
  });  
  
  jQuery.fn.submit_reset_req_item = function() {
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
  
  jQuery.fn.add_req_item = function() {
    this.submit(function(e) {
      e.preventDefault();
      
      var item_id				= $('#item_id').val() || '';
      var item_code			= $('#item_code').val() || '';
      var item_unit			= $('#item_unit').val() || '';
      var item_req_qty	= $('#item_req_qty').val() || '';
      var item_issue_qty	= $('#item_issue_qty').val() || '';
      var item_remarks		= $('#item_remarks').val() || '';      
            
      var item_values		= item_id+'|'+item_req_qty+'|'+item_issue_qty+'|'+item_remarks;
      var tr				= $('<tr></tr>');
     
  	  tr.append('<td class="highlight_red border-right text-right"><input type="hidden" name="items[]" value="'+item_values+'"/></td>');
  	  tr.append('<td class="highlight_red border-right">'+item_code+'</td>');
  	  tr.append('<td class="highlight_red border-right text-right">'+item_unit+'</td>');
  	  tr.append('<td class="highlight_red border-right text-right">'+item_req_qty+'</td>');
  	  tr.append('<td class="highlight_red border-right text-right">'+item_issue_qty+'</td>');
  	  tr.append('<td class="highlight_red border-right">'+item_remarks+'</td>');
  	  
      $('#tbl-req-items').find('tbody').append(tr);
	    
    });
  }
</script>

<?php require('footer.php'); ?>