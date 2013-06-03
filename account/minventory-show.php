<?php
  /*
   * Module: Material Inventory - Show
  */
  $capability_key = 'show_material_inventory';
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
				  echo '<a href="'.$Capabilities->All['material_inventory']['url'].'" class="nav">'.$Capabilities->All['material_inventory']['name'].'</a>';
				  echo '<a href="'.$Capabilities->All['edit_material_inventory']['url'].'?id='.$_GET['id'].'" class="nav">'.$Capabilities->All['edit_material_inventory']['name'].'</a>';
				?>
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
	      <div id="grid-materials" class="grid jq-grid" style="min-height:60px;">
           <table cellspacing="0" cellpadding="0">
             <thead>
               <tr>
                 <td width="30" class="border-right text-center">No.</td>
                 <td width="100" class="border-right text-center">Invoice</td>
                 <td width="100" class="border-right text-center">Lot</td>
                 <td class="border-right">Remarks</td>
                 <td width="70" class="border-right text-center">Unit</td>
                 <td width="70" class="border-right text-center">Stock</td>
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
	
	<script>
		$(function() {
	  	var data = { 
	    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
	      "limit":"15",
				"data_key":"minventory_items",
				"row_template":"row_template_minventory_items_read_only",
			}
		
			$('#grid-materials').grid(data);
			
			$(window).load(function(){
				var total = 0;
				$('#materials tr').each(function(){
    			total += parseFloat($(this).attr('qty'));
    		});
    		$('#total_qty').val(total).digits();
    		if(total>0) {
    			$('#out-of-stock').hide();
    		}
			})
	  }) 
 </script>
<?php }
require('footer.php'); ?>