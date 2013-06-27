<?php
  /*
   * Module: Material Inventory - Edit
  */
  $capability_key = 'edit_material_inventory';
  require('header.php');
  
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
	  if(isset($_GET['id'])) {
	  	$materials = $Query->material_by_id($_GET['id']);
	  }	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_material_inventory']['url'].'?id='.$_GET['id'].'" class="nav">Details</a>';
				?>
     			<a id="btn-add-entry" href="#mdl-inventory-new" rel="modal:open" class="nav">New Entry</a>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Material Code:</td><td width="310"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($materials['material_code']!='') ? link_to('materials-show.php?mid='.$_GET['id']) : '' ?>
              </td>
              <td width="150">Type:</td><td><input type="text" value="<?php echo $materials['material_type'] ?>" class="text-field text-date" disabled/></td>
           </tr>
           <tr>
              <td>Classification:</td><td><input type="text" value="<?php echo $materials['classification'] ?>" class="text-field" disabled/></td>
              
              <td>Model:</td><td><input type="text" value="<?php echo $model = ($materials['material_type'] == 'Direct Material') ? $materials['brand_model'] : 'N/A' ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Person in-charge:</td><td><input type="text" value="<?php echo $materials['pic'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($materials['pic']!='') ? link_to('users-show.php?uid='.$materials['user_id']) : '' ?>
              </td>
              <td>Status:</td><td><input type="text" value="<?php echo $materials['status'] ?>" class="text-field" disabled/></td>
           </tr>             
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" value="<?php echo $materials['description'] ?>" class="text-field" style="width:645px" disabled/>
              </td>
           </tr>
           <tr><td height="5" colspan="99"></td></tr>
        </table>
      	<br/>

        <h3 class="form-title">Warehouse Stock <span id="out-of-stock" class="magenta">(Out-of-stock)</span></h3>
      	<a id="btn-inventory" href="#mdl-inventory" rel="modal:open"></a>
	      <div id="grid-materials" class="grid jq-grid" style="min-height:60px;">
           <table id="tbl-materials" cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="100" class="border-right text-center">Invoice</td>
                 <td width="100" class="border-right text-center">Lot</td>
                 <td class="border-right">Remarks</td>
                 <td width="70" class="border-right text-center">Unit</td>
                 <td width="70" class="border-right text-center">Stock</td>
                 <td width="50" class="border-right text-center"></td>
               </tr>
             </thead>
             <tbody id="materials"></tbody>
           </table>
         </div>
         <div>
	       	<table width="100%">
	             <tr><td height="5" colspan="99"></td></tr>
	             <tr>
	                <td></td>
	                <td align="right"><strong>Total:</strong>&nbsp;&nbsp;<input id="total_qty" type="text" class="text-right numbers" style="width:85px;" disabled/></td>
	             </tr>
	          </table>
	       </div>	
	      	
	      <br/>
	      <h3 class="form-title">Production Request</h3>
	      <div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td width="3%" class="border-right text-center"><a></a></td>
	              <td width="8%" class="border-right text-center"><a>P/O No.</a></td>
	              <td width="10%" class="border-right text-center"><a>Tracking No.</a></td>
	              <td width="10%" class="border-right text-center"><a>Prod. Lot No</a></td>
	              <td width="14%" class="border-right text-center"><a>Requested</a></td>
	              <td width="14%" class="border-right text-center"><a>Expected</a></td>
	              <td class="border-right text-center"><a>Status</a></td>
	              <td width="7%" class="border-right text-center"><a>UOM</a></td>
	              <td width="8%" class="border-right text-center"><a>Pending</a></td>
	              <td width="8%" class="border-right text-center"><a>Released</a></td>
	              <td width="8%" class="border-right text-center"><a>Requested</a></td>
	            </tr>
	          </thead>
	          <tbody>
	        		<?php
	        				$requests = $DB->Get('production_purchase_order_product_parts', array(
							  			'columns' => 'orders.po_number, production_purchase_order_products.lot_no AS prod_lot_no, production_purchase_orders.id AS ppoid,
																		production_purchase_order_product_parts.tracking_no, production_purchase_order_product_parts.material_id,
																		production_purchase_order_product_parts.plan_qty, production_purchase_order_product_parts.pending_qty,
																		production_purchase_order_product_parts.updated_at, lookups.description AS status, production_purchase_orders.order_id AS oid,
																		production_purchase_order_product_parts.expected_datetime, production_purchase_order_product_parts.is_requested', 
  										'joins' => 'INNER JOIN production_purchase_order_products ON production_purchase_order_products.id = production_purchase_order_product_parts.production_purchase_order_product_id
																	INNER JOIN production_purchase_orders ON production_purchase_orders.id =  production_purchase_order_products.production_purchase_order_id
																	INNER JOIN orders ON orders.id = production_purchase_orders.order_id
																	INNER JOIN lookups ON lookups.id = production_purchase_order_product_parts.status',
							  	    'conditions' => 'production_purchase_order_product_parts.material_id = '.$_GET['id'], 
							  	    'order' => 'production_purchase_order_product_parts.created_at DESC',
							  	    ));
											// status id 149 = production request pending
									$total_req = 0.0;
									$ctr = 1;
	        				foreach ($requests as $req) {
	        					echo '<tr>';
										echo '<td class="border-right text-right">'.$ctr.'</td>';
										echo '<td class="border-right text-center"><a href="production-plan-show.php?ppoid='.$req['ppoid'].'&oid='.$req['oid'].'">'.$req['po_number'].'</a></td>';
										echo '<td class="border-right text-center"><a href="#">'.$req['tracking_no'].'</a></td>';
										echo '<td class="border-right text-center"><a href="#">'.$req['prod_lot_no'].'</a></td>';
										echo '<td class="border-right">'.date("M. d, Y g:i a", strtotime($req['updated_at'])).'</td>';
										echo '<td class="border-right">'.date("M. d, Y g:i a", strtotime($req['expected_datetime'])).'</td>';
										echo '<td class="border-right text-center">'.$req['status'].'</td>';
										echo '<td class="border-right text-center">'.$materials['unit'].'</td>';
										echo '<td class="border-right text-right">'.trim_decimal($req['pending_qty']).'</td>';
										echo '<td class="border-right text-right">'.(trim_decimal($req['plan_qty']) - trim_decimal($req['pending_qty'])).'</td>';
										echo '<td class="border-right text-right">'.trim_decimal($req['plan_qty']).'</td>';
										$ctr+=1;
										$total_req += (double)$req['plan_qty'];
	        					echo '</tr>';
									}
	        		?>
	          	<tr>
	          		<td class="border-right text-right" colspan="10"><b>Total:</b></td>
	          		<td class="border-right text-right"><b><?php echo $total_req ?></b></td>
	          	</tr>
	          </tbody>
	        </table>
	      </div>	
      </form>
    	<br/>
		</div>
	</div>
	
	<div id="mdl-inventory" class="modal">
		<div class="modal-title"><h3>Adjustment</h3></div>
		<div class="modal-content">
			<form id="frm-inventory" method="POST">
				<span class="notice"></span>     
					<input type="hidden" name="action" value="edit_minventory_items"/>
					<input type="hidden" id="inventory-id" name="inventory[id]"/>
					
						 <div class="field">
						    <label>Invoice No.:</label>
						    <input type="text" id="inventory-invoice" class="text-field disabled" disabled="disabled"/>
						 </div>
						 
						 <div class="field">
						    <label>Lot No.:</label>
						    <input type="text" id="inventory-lot" class="text-field disabled" disabled="disabled"/>
						 </div>
						 
						 <div class="field">
						    <label>Unit:</label>
						    <input type="text" id="inventory-unit" class="text-field disabled" disabled="disabled"/>
						 </div>
						 
						 <div class="field">
						    <label>Current Stock:</label>
						    <input type="text" id="inventory-stock" class="text-field disabled" disabled="disabled"/>
						 </div>
						 
						 <div class="field">
						    <label>Count Date:</label>
						    <input type="text" id="inventory-date" name="inventory[entry_date]" class="text-field date-pick" value="<?php echo date("F d, Y") ?>" />
						 </div>
						 
						 <div class="field">
						    <label>Type:</label>
						    <?php 
						    	$adj_types = $DB->Get('lookups', array('columns' => 'id, description', 'conditions' => 'parent = "ADJTYP"'));
						    	select_query_tag($adj_types, 'id', 'description', '', 'inventory-type', 'inventory[type]', '', 'width:192px;'); ?>
						 </div>
						 
						 <div class="field">
						    <label>Qty:</label>
						    <input type="text" id="inventory-qty" class="text-field numeric" value="0" />
						 </div>
						 
						 <div class="field">
						    <label>New Qty:</label>
						    <input type="text" id="inventory-new-qty" name="inventory[qty]" class="text-field" readonly/>
						 </div>
						 
						 <div class="field">
						    <label>Remarks:</label>
						    <textarea rows="2" id="inventory-remarks" name="inventory[remarks]" class="text-field" style="width:220px;"></textarea>
						 </div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn" style="width:50px;">CANCEL</a>
			<a id="submit-inventory" rel="modal:close" href="#frm-inventory" class="btn" style="width:50px;">ADJUST</a>
		</div>
	</div>
	
	<div id="mdl-inventory-new" class="modal">
		<div class="modal-title"><h3>New Entry</h3></div>
		<div class="modal-content">
			<form id="frm-inventory-new" method="POST">
				<span class="notice"></span>     
					<input type="hidden" name="action" value="add_minventory_items"/>
					<input type="hidden" id="inventory-item_id" name="inventory[item_id]" value="<?php echo $_GET['id']?>"/>
					
						 <div class="field">
						    <label>Invoice No.:</label>
						    <input type="text" id="inventory-invoice" name="inventory[invoice_no]" class="text-field" />
						 </div>
						 
						 <div class="field">
						    <label>Lot No.:</label>
						    <input type="text" id="inventory-lot" name="inventory[lot_no]" class="text-field" />
						 </div>
						 
						 <div class="field">
						    <label>Unit:</label>
						    <input type="text" id="inventory-unit" class="text-field disabled" disabled="disabled" value="<?php echo $materials['unit']?>"/>
						 </div>
						 
						 <div class="field">
						    <label>Qty:</label>
						    <input type="text" id="inventory-qty" name="inventory[qty]" class="text-field numeric" />
						 </div>
						 
						 <div class="field">
						    <label>Remarks:</label>
						    <textarea rows="2" id="inventory-remarks" name="inventory[remarks]" class="text-field" style="width:220px;"></textarea>
						 </div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn" style="width:50px;">CANCEL</a>
			<a id="submit-inventory-new" rel="modal:close" href="#frm-inventory-new" class="btn" style="width:50px;">ADD</a>
		</div>
	</div>
	
	<script>
		$(function() {
	  	var data = { 
	    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
	      "limit":"15",
				"data_key":"minventory_items",
				"row_template":"row_template_minventory_items",
			}
		
			$('#grid-materials').grid(data);
			
			$('#tbl-materials').find('tbody tr .chk-item').show_inventory_modal();
			$('#submit-inventory').adjustment();
			$('#submit-inventory-new').add_entry();
			
			
			
			$(window).load(function(){
				compute_total();
			})
			
	  }) 
	  
	  function compute_total() {
	  	var total = 0;
			$('#materials tr').each(function(){
  			total += parseFloat($(this).attr('qty'));
  		});
  		$('#total_qty').val(total).digits();
  		if(total>0) {
  			$('#out-of-stock').hide();
  		}
	  }
	  
	  $.fn.show_inventory_modal = function() {
  	$()
	    this.live('click', function(e) {
	    	e.preventDefault();
	    	
	    	var row = $(this).closest('tr');
	    	var modal = $('#btn-inventory').attr('href');
	    	var id = $(this).attr('id');
	    	var invoice = $(row).find('.item-invoice').html();
	    	var lot = $(row).find('.item-lot').html();
	    	var unit = $(row).find('.item-unit').html();
	    	var stock = $(row).attr('qty');
	    	
	    	$(modal).find('#inventory-id').val(id);
	    	$(modal).find('#inventory-invoice').val(invoice);
	    	$(modal).find('#inventory-lot').val(lot);
	    	$(modal).find('#inventory-unit').val(unit);
	    	$(modal).find('#inventory-stock').val(stock);
	    	
	    	$('#inventory-new-qty').val('0');
    		$('#inventory-qty').val('0');
	    	
	    	$('#inventory-type').change(function() {
	    		$('#inventory-new-qty').val('0');
	    		$('#inventory-qty').val('0');
				})
			  
			  $('#inventory-qty').keyup(function() {
			  	switch($('#inventory-type').find(":selected").text()) {
			  		case 'Re-count':
			  			$('#inventory-new-qty').val($(this).val());	
			  			break;
		  			case 'Damage':
		  				$('#inventory-new-qty').val(parseFloat(stock) - parseFloat($(this).val()));
	  					break;	
		  			case 'Expiry':
		  				$('#inventory-new-qty').val(parseFloat(stock) - parseFloat($(this).val()));
	  					break;	
			  	}
			  })
	    	
	    	$('#btn-inventory').click();
	    })
  	}
  
  	$.fn.adjustment = function() {
    this.click(function(e) {
    	e.preventDefault();

      var form		= $(this).attr('href');
      var id = $(form).find('#inventory-id');
      var remarks = $(form).find('#inventory-remarks');
      var qty = $(form).find('#inventory-new_qty');
    	
    	$.post(document.URL, $(form).serialize(), function(data) {
    	   $('#materials').empty();
    	   
    	   var data = { 
			    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
			      "limit":"15",
						"data_key":"minventory_items",
						"row_template":"row_template_minventory_items",
					}
				
					$('#grid-materials').grid(data);
	    	
	    	})
	    	
	    	setTimeout(function() {
		    compute_total();
			}, 200);
	    })
	  }
	  
	  $.fn.add_entry = function() {
    this.click(function(e) {
    	e.preventDefault();

      var form		= $(this).attr('href');
    	
    	$.post(document.URL, $(form).serialize(), function(data) {
    	   $('#materials').empty();
    	   
    	   var data = { 
			    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
			      "limit":"15",
						"data_key":"minventory_items",
						"row_template":"row_template_minventory_items",
					}
				
					$('#grid-materials').grid(data);
	    	
	    	})
	    	
	    	setTimeout(function() {
		    compute_total();
			}, 200);
	    })
	  }
 </script>
<?php }
require('footer.php'); ?>