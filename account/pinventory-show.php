<?php
  /*
   * Module: Product Inventory - Show
  */
  $capability_key = 'show_product_inventory';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
  
  if(isset($_GET['id'])) {
  	$products = $DB->Find('products', array(
					  			'columns' 		=> 'products.id AS pid, products.product_code, products.description, brand_models.brand_model, lookups1.description AS unit,
																  	products.color, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic, lookup_status.description AS status,
																  	pack_qty, products.bar_code', 
					  	    'conditions' 	=> 'products.id = '.$_GET['id'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON products.brand_model = brand_models.id 
																		LEFT OUTER JOIN users ON products.person_in_charge = users.id
																		LEFT OUTER JOIN item_costs ON products.id = item_costs.item_id
																		LEFT OUTER JOIN lookups AS lookups1 ON lookups1.id = products.unit
																		LEFT OUTER JOIN lookup_status ON lookup_status.id = products.status'
	  ));
  }
	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
	        <?php
	        	echo '<a href="'.$Capabilities->All['product_inventory']['url'].'" class="nav">'.$Capabilities->All['product_inventory']['name'].'</a>';
					?>
        	<a id="btn-add-inventory" href="#mdl-inventory" rel="modal:open" class="nav">Add Inventory</a>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form>
				<div class="form-container">
					<h3 class="form-title">Details</h3>
	        <table>
	           <tr>
	              <td width="150">Product Code:</td><td width="310"><input type="text" value="<?php echo $products['product_code'] ?>" class="text-field" disabled/>
	              	<?php echo $linkto = ($products['product_code']!='') ? link_to('products-show.php?pid='.$_GET['id']) : '' ?>
	              </td>
	              <td width="150">Brand:</td><td><input type="text" value="<?php echo $products['brand_model'] ?>" class="text-field" disabled/>
	              </td>
	           </tr>
	           <tr>
	              <td>Pack Qty:</td><td><input type="text" value="<?php echo $products['pack_qty'] ?>" class="text-field text-right" disabled/></td>
	              <td>Color:</td><td><input type="text" value="<?php echo $products['color'] ?>" class="text-field" disabled/></td>
	           </tr>    
	           <tr>
	              <td>Barcode:</td><td><input type="text" value="<?php echo $products['bar_code'] ?>" class="text-field" disabled/></td>
	              <td>Status:</td><td><input type="text" value="<?php echo $products['status'] ?>" class="text-field" disabled/></td>
	           </tr>             
	           <tr>
	              <td>Description:</td>
	              <td colspan="99">
	                <input type="text" value="<?php echo $products['description'] ?>" class="text-field" style="width:645px" disabled/>
	              </td>
	           </tr>
	           <tr><td height="5" colspan="99"></td></tr>
	        </table>	
				</div>
				<br/>
				<div class="form-container">
					<h3 class="form-title">Warehouse <span id="out-of-stock" style="display: none" class="magenta">(Out-of-stock)</span></h3>
		      <div id="grid-inventory-items" class="grid jq-grid" style="min-height: 140px;">
		        <table cellspacing="0" cellpadding="0">
		          <thead>
		            <tr>
		              <td width="5%" class="border-right text-center"><a></a></td>
		              <td width="10%" class="border-right text-center"><a>Prod. Lot No</a></td>
		              <td width="10%" class="border-right text-center"><a>Stamp</a></td>
		              <td class="border-right text-center"><a>Remarks</a></td>
		              <td width="10%" class="border-right text-center"><a>UOM</a></td>
		              <td width="10%" class="border-right text-center"><a>Qty</a></td>
		            </tr>
		          </thead>
		          <tbody id="inventory-items"></tbody>
		        </table>
		      </div>
					<div>
						<table width="100%">
							<tr><td height="5" colspan="99"></td></tr>
							<tr>
								<td></td>
								<td align="right"><strong>Total Qty:</strong>&nbsp;&nbsp;<input id="total_qty" type="text" class="text-right numbers" style="width:95px;" disabled/></td>
							</tr>
							<!--                    <tr><td colspan="2">Remarks:<br/><textarea style="min-width:650px;width:98.9%;height:50px;" disabled><?php echo $invoice['remarks']; ?></textarea></td></tr> -->
						</table>
					</div>	
				</div>
      	<br/>	
      </form>
		</div>
	</div>
	
	<div id="mdl-inventory" class="modal">
		<div class="modal-title"><h3>Add Inventory</h3></div>
		<div class="modal-content">
			<form id="frm-inventory" method="POST">
				<span class="notice"></span>     
				<input type="hidden" name="action" value="add_product_inventory"/>  
				<input type="hidden" name="inventory[item_id]" value="<?php echo $_GET['id'] ?>"/>  
						 
				 <div class="field">
				    <label>Production Lot #:</label>
				    <input type="text" id="inventory-prod-lot-no" name="inventory[prod_lot_no]" class="text-field" required/>
				 </div>
				 
				 <div class="field">
				    <label>Stamp:</label>
				    <input type="text" id="inventory-stamp" name="inventory[stamp]" class="text-field" required/>
				 </div>
				 
				 <div class="field">
				    <label>Endorsement Date:</label>
				    <input type="text" id="inventory-endorse-date" name="inventory[endorse_date]" class="text-field date-pick" required/>
				 </div>
				 
				 <div class="field">
				    <label>Quantity:</label>
				    <input type="text" id="inventory-qty" name="inventory[qty]" class="text-field numeric" required/>
				 </div>
				 
				 <div class="field">
				    <label>Remarks:</label>
				    <textarea rows="2" id="inventory-remarks" name="inventory[remarks]" class="text-field" style="width:220px;"></textarea>
				 </div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn" style="width:50px;">Cancel</a>
			<a id="submit-inventory" rel="modal:close" href="#frm-inventory" class="btn" style="width:50px;">Add</a>
		</div>
	</div>
	
	<script>
		$(function() {
			loadData();
	  	$('#submit-inventory').add_plan();
	  })
			  
	  function loadData() {
			var data = { 
	    	"url":"/populate/pinventory-items.php?pid=<?php echo $_GET['id'] ?>",
	      "limit":"50",
				"data_key":"product_inventory_items",
				"row_template":"row_template_product_inventory_items",
			}
			$('#grid-inventory-items').grid(data);
			
			//$(window).load(function(){
				setTimeout(function(){
					get_total_qty();
				}, 500);
			//});	
		}
		
		function get_total_qty() {
	  	var total = 0;
			$('#inventory-items tr').find('.qty').each(function(){
  			total += parseFloat(parseInt($(this).text().replace(/,/g, ''), 10)); 
  		});
  		if(total == 0) {
  			$('#out-of-stock').show();
  		}
  		$('#total_qty').val(total).digits();
	  } 
		
		$.fn.add_plan = function() {
	  	this.click(function(e) {
	  		e.preventDefault();
	  		
				var form = $(this).attr('href');
				
				if($(form).find('#inventory-prod-lot-no').val() != '' && $(form).find('#inventory-stamp').val() != '' && $(form).find('#inventory-qty').val() != '') {
					$.post(document.URL, $(form).serialize(), function(data) {
		      }).done(function(data){
		      	loadData();
		      });	
				}
	  	})
	  }
	</script>

<?php }
require('footer.php'); ?>