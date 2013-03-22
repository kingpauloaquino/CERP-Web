<?php
  /*
   * Module: Parts Tree - Add 
  */
  $capability_key = 'add_product_tree';
  require('header.php');
  
	if($_POST['action'] == 'add_product_tree') {
		$pid = $_POST['pid'];
		$code = $_POST['code'];
		$parts = $_POST['parts'];
		if(!empty($parts)) {
      $fields = array('product_id', 'material_id', 'material_qty', 'remarks');
		  foreach ($parts as $part) {
		  	$new_parts = array();
		    foreach (explode('|', $part) as $index => $field) {
		  	  $new_parts[$fields[$index]] =  $field;
		    }
				$Posts->AddPartsTree($new_parts);
		  }
			redirect_to($Capabilities->All['add_product_tree']['url'].'?pid='.$pid.'&code='.$code);
		}		
	} 
	
  if(isset($_REQUEST['pid'])) {
  	$product_tree = $DB->Get('products_parts_tree', array(
				  			'columns' 		=> 'products_parts_tree.id, materials.id AS mat_id, materials.material_code, materials.description,
				  												lookups.code AS unit, item_costs.cost, products_parts_tree.material_qty, 
				  												(item_costs.cost * products_parts_tree.material_qty) AS amount, suppliers.name, products_parts_tree.remarks',
				  	    'conditions' 	=> 'item_costs.item_type = "MAT" AND products_parts_tree.product_id = '.$_REQUEST['pid'], 
				  	    'joins' 			=> 'LEFT OUTER JOIN materials ON products_parts_tree.material_id = materials.id
				  	    									LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
				  	    									LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
				  	    									LEFT OUTER JOIN lookups ON item_costs.unit = lookups.id',
								'sort_column'	=> 'products_parts_tree.id'
  	  )
		);	
  }
	$units = $DB->Get('lookups', array('columns' => 'id, description', 'conditions'  => 'parent = "'.get_lookup_code('unit_of_measure').'"', 'sort_column' => 'code'));
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['show_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['show_product_tree']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['edit_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['edit_product_tree']['name'].'</a>'; 
					echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST" action="productstree-new.php">
				<h3 class="form-title"><?php echo $_REQUEST['code']?> Materials</h3>
				<input type="hidden" name="action" value="add_product_tree">	
				<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>">
				<input type="hidden" name="code" value="<?php echo $_REQUEST['code'] ?>">			

		    <div class="grid jq-grid" style="min-height:146px;">
		      <table id="tbl-parts-tree" cellspacing="0" cellpadding="0">
		        <thead>
		          <tr>
		            <td class="border-right"><a></a></td>
		            <td width="150px" class="border-right"><a>Material Code</a></td>
		            <td class="border-right"><a>Description</a></td>
		            <td width="30px" class="border-right text-center"><a>Unit</a></td>
		            <td width="50px" class="border-right text-center"><a>Qty</a></td>
		            <td width="50px" class="border-right text-center"><a>Cost</a></td>
		            <td width="50px" class="border-right text-center" class="border-right"><a>Amount</a></td>
		            <td class="border-right"><a>Supplier</a></td>
		            <td class="border-right"><a>Remarks</a></td>
		          </tr>
		        </thead>
		        <tbody>
		        	<?php
									$ctr=1;
									foreach ($product_tree as $material) {
										echo '<tr>';
										echo '<td class="border-right">'.$ctr.'</td>';
										echo '<td class="border-right">'.$material['material_code'].'</td>';
										echo '<td class="border-right">'.$material['description'].'</td>';
										echo '<td class="border-right text-center">'.$material['unit'].'</td>';
										echo '<td class="border-right text-right">'.$material['material_qty'].'</td>';
										echo '<td class="border-right text-right">'.$material['cost'].'</td>';
										
										if(strpos($material['material_qty'], '/') == FALSE) {
											$amount = $material['material_qty'] * $material['cost'];
										} else {
											$frac = explode("/", $material['material_qty']);
											$amount = ($frac[0] / $frac[1]) * $material['cost'];										
										}										
										echo '<td class="border-right text-right">'.number_format($amount, 2, '.', '').'</td>';
										echo '<td class="border-right">'.$material['name'].'</td>';
										echo '<td class="border-right">'.$material['remarks'].'</td>';
										echo '</tr>';
										$ctr+=1;
									}
								?>
		        </tbody>
	       </table>
			</div>
			<br/>
			<div class="input">
				<a href="#add-part-modal" rel="modal:open" class="btn_modal">ADD ITEM</a>
				<input type="submit" value="Add New Parts" class="submit"/>
        <button class="btn" onclick="return cancel_btn();">Cancel</button>
			</div>
			</form>
			  
		  <div id="add-part-modal" class="modal">
       <div class="modal-title"><h3>Add New Part</h3></div>
		   <div class="modal-content">
					<form id="add-part-form">
						<span class="notice"></span>
		      	<input type="hidden" id="item_action" value="1"/>
			   		<input type="hidden" id="product_id" name="product_id" value="<?php echo $_REQUEST['pid'] ?>"/>
			   		<input type="hidden" id="material_id" name="material_id" />
			   		<input type="hidden" id="supplier" name="supplier" />		      	 
						 
		         <div class="field">
		            <label>Material Code:</label>
		            <input type="text" id="material_code" name="material_code" class="searchbox text-field" autocomplete="off" placeholder="Material Code"/>
		            <div id="live_search_display_modal" class="live_search_display_modal"></div>
		         </div>
		         
		         <div class="field">
		            <label>Description:</label>
		            <input type="text" id="description" name="description" value="" class="text-field" readonly="readonly" />
		         </div>
		         
		         <div class="field">
		            <label>Unit:</label>
		            <input type="text" id="unit" name="unit" class="text-field required" readonly="readonly" />
		         </div>	
		         
		         <div class="field">
		            <label>Unit Price:</label>
		            <input type="text" id="cost" name="cost" class="text-field required" readonly="readonly" />
		         </div>			         
		         
		         <div class="field">
		            <label>Quantity:</label>
		            <input type="text" id="material_qty" name="material_qty" class="text-field required" autocomplete="off" />
		         </div>
		         			         
		         <div class="field">
		            <label>Remarks:</label>
		           <textarea id="remarks" name="remarks" class="text-field" style="width:180px;"></textarea>
		         </div>		         
		      </form>				
				</div>
				<div class="modal-footer">
	      	<!-- <input type="button" id="part-continue" value="Continue" alt="1" class="part-submit-reset btn"/>
	       	<input type="button" id="part-add-close" value="Add & Close" alt="0" class="part-submit-reset btn"/> -->
				<a id="part-continue" class="part-submit-reset btn" title="1">Continue</a>
				<a id="part-add-close" class="part-submit-reset btn" title="0">Add & Close</a> 
				<div class="clear"></div>
			</div>
		</div>
		
		
		
		</div>
	</div>
<script>
	$(document).ready(function(){
		$('.searchbox').keydown(function(e) { if (e.keyCode == 9) { $('.live_search_display').hide(); }});
		
		$('.searchbox').keyup(function() {
			var searchbox = $(this).val().toUpperCase();
			if(searchbox=='') {	$('.live_search_display').hide();}
			else {
				switch ($(this).attr('id')) {
					case 'material_code':
					add_live_search('#live_search_display_modal', 'producttree', 
										'materials', 'materials.id AS mat_id, materials.material_code AS item_code, materials.description, ' +
										'suppliers.name AS supplier, lookups1.description AS unit, item_costs.cost ', 
										'LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id '+
										'LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id '+
										'LEFT OUTER JOIN lookups AS lookups1 ON item_costs.unit = lookups1.id ',
										'item_costs.item_type="MAT" AND materials.material_code LIKE "%' + searchbox + '%" ', searchbox); break;
				}				
			}
			return false;    
		});	
	});
	
  $('#add-part-form').ready(function() {
  	$('#add-part-form').add_part();
    $('.part-submit-reset').submit_reset_part();
  });      
  
  jQuery.fn.submit_reset_part = function() { 
  	this.click(function() { 
  	  var x			= $(this).attr('title');
  	  var form		= $('#add-part-form');
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
  	  form.find('.text-field').val('');
  	  $('#material_code').focus();
  	  if(x == 0) $('.close-modal').click();
  	});
  }
  
  jQuery.fn.add_part = function() {
    this.submit(function(e) { 
      e.preventDefault(); 
      
      var prod_id				= $('#product_id').val() || '';
      var mat_id				= $('#material_id').val() || '';
      var mat_code			= $('#material_code').val() || '';
      var mat_desc			= $('#description').val() || '';
      var mat_unit			= $('#unit').val() || '';
      var mat_unit_price= $('#cost').val() || '';
      var mat_qty				= $('#material_qty').val() || '';
      var mat_amount		= (eval(mat_qty) * mat_unit_price) || '';
      var mat_remarks		= $('#remarks').val() || '';
      var mat_supplier	= $('#supplier').val() || '';
      
      var mat_values		= prod_id+'|'+mat_id+'|'+mat_qty+'|'+mat_remarks;
      var tr				= $('<tr></tr>');
     
  	  tr.append('<td class="border-right highlight_red"><input type="hidden" name="parts[]" value="'+mat_values+'"/></td>');
  	  tr.append('<td class="border-right highlight_red">'+mat_code+'</td>');
  	  tr.append('<td class="border-right highlight_red">'+mat_desc+'</td>');
  	  tr.append('<td class="border-right text-center highlight_red">'+mat_unit+'</td>');
  	  tr.append('<td class="border-right text-right highlight_red">'+mat_unit_price+'</td>');
  	  tr.append('<td class="border-right text-right highlight_red">'+mat_qty+'</td>');
  	  tr.append('<td class="border-right text-right highlight_red">'+mat_amount.toFixed(2)+'</td>');
  	  tr.append('<td class="border-right highlight_red">'+mat_supplier+'</td>');
  	  tr.append('<td class="border-right highlight_red">'+mat_remarks+'</td>');
  	  
      $('#tbl-parts-tree').find('tbody').append(tr);
    });
  }
</script>

<?php require('footer.php'); ?>