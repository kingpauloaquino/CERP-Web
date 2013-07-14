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
				  echo '<a href="'.$Capabilities->All['edit_actual_material_inventory']['url'].'?id='.$_GET['id'].'" class="nav">'.$Capabilities->All['edit_actual_material_inventory']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form>
				<div class="form-container">
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
				</div>
      	<br/>
				<div class="form-container">
					<h3 class="form-title">Warehouse Stock <span id="out-of-stock" class="magenta" style="display: none">(Out-of-stock)</span></h3>
		      <div id="grid-materials" class="grid jq-grid" style="min-height:100px;">
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
				</div>
	      <br/> 
				<div class="form-container">
					<h3 class="form-title">Production Request</h3>
		      <div id="grid-requests" class="grid jq-grid" style="min-height: 100px">
		        <table cellspacing="0" cellpadding="0">
		          <thead>
		            <tr>
	                 <td width="30" class="border-right text-center">No.</td>
	                 <td class="border-right text-center">Request Type</td>
	                 <td width="100" class="border-right text-center">Batch #</td>
	                 <td width="100" class="border-right text-center">Requested</td>
	                 <td width="100" class="border-right text-center">Expected</td>
	                 <td width="140" class="border-right text-center">Terminal</td>
	                 <td width="70" class="border-right text-center">Status</td>
	                 <td width="70" class="border-right text-center">Qty</td>
		            </tr>
		          </thead>
		          <tbody id="requests"></tbody>
		        </table>
					</div>	
				</div>
      </form>
    	<br/>
		</div>
	</div>
	
	<script>
		$(function() {
	  	var data1 = { 
	    	"url":"/populate/minventory-items.php?id=<?php echo $_GET['id'] ?>",
	      "limit":"15",
				"data_key":"minventory_items",
				"row_template":"row_template_minventory_items_read_only",
			}
		
			$('#grid-materials').grid(data1); 
			
			var data2 = { 
	    	"url":"/populate/minventory-requests.php?mid=<?php echo $_GET['id'] ?>",
	      "limit":"15",
				"data_key":"material_request_items",
				"row_template":"row_template_minventory_requests",
				"order":"requested_date",
				"sort":"ASC"
			}
		
			$('#grid-requests').grid(data2);
			
			// $(window).load(function(){
				// var total = 0;
				// $('#materials tr').each(function(){
    			// total += parseFloat($(this).attr('qty'));
    		// });
	  		// if(total == 0) {
	  			// $('#out-of-stock').show();
	  		// }
    		// $('#total_qty').val(total).digits();
			// })			$('#total_qty').val(get_total()).digits();
	  }) 
	  
	  function get_total(){
	  	var total_qty = 0;
	  	$('#materials tr').each(function(){
				total_qty += parseFloat($(this).find('td.item-qty').html().replace(/,/g, ''), 10);
			});
			return total_qty;
	  }
 </script>
<?php }
require('footer.php'); ?>