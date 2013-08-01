<?php
  /*
   * Module: Indirect Material - Edit 
  */
  $capability_key = 'edit_indirect_material';
  require('header.php');
  
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
	  if(isset($_GET['mid'])) {
	  	$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.*, materials.address AS address_id, location_addresses.address AS address_name', 
					  	    'conditions' 	=> 'materials.id = '.$_GET['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN location_addresses ON location_addresses.id = materials.address
					  	    									LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id'
	  	  )
			);	
			$item_costs = $DB->Find('item_costs', array('columns' => 'supplier, currency, cost, transportation_rate', 
	  							'conditions' => 'item_id = '.$_GET['mid'].' AND item_type="MAT"'));  
			
	  }

		$classifications = $Query->get_lookups('mat_classifications');
		$status = $Query->get_lookups('item_status');
		$models = $Query->get_lookups('models');
		$pics = $Query->get_lookups('users');
		$suppliers = $Query->get_lookups('suppliers');
		$terminals = $Query->get_lookups('terminals');
		$units = $Query->get_lookups('uoms');
		$types = $Query->get_lookups('material_types');
		$currencies = $Query->get_lookups('currencies');																																					
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
					echo '<a href="'.$Capabilities->All['show_indirect_material']['url'].'?mid='.$_GET['mid'].'" class="nav">'.$Capabilities->All['show_indirect_material']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">        
				<input type="hidden" name="action" value="edit_indirect_material">
				<input type="hidden" name="mid" value="<?php echo $_GET['mid'] ?>">
				<input type="hidden" name="old-address" value="<?php echo $materials['address_id'] ?>" />
				<input type="hidden" id="address-id" name="material[address]" />
				
				<div class="form-container">
					<h3 class="form-title">Details</h3>
	        <table>
	           <tr>
	              <td width="150">Material Code:</td><td width="310"><input type="text" id="mat-code" name="material[material_code]" value="<?php echo $materials['material_code'] ?>" class="text-field magenta" autocomplete="off" notice="material_codestatus" required />
	              	<span id="material_codestatus" class="warning"></span>
	              </td>
	              <td width="150">Base Material Code:</td><td><input type="text" value="N/A" class="text-field" disabled/></td>
	           </tr>
	           <tr>
	              <td>Barcode:</td><td><input type="text" id="mat-barcode" name="material[bar_code]" value="<?php echo $materials['bar_code'] ?>" class="text-field" autocomplete="false" notice="barcodestatus" required />
	              	<span id="barcodestatus" class="warning"></span>
	              </td>
	              <td>Model:</td><td><input type="text" value="N/A" class="text-field" disabled/></td>
	           </tr>
	           <tr>
	              <td>Classification:</td><td><?php select_query_tag($classifications, 'id', 'classification', $materials['material_classification'], 'material[material_classification]', 'material[material_classification]', '', 'width:192px;'); ?></td>
	              <td>Status:</td><td><?php select_query_tag($status, 'id', 'description', $materials['status'], 'material[status]', 'material[status]', '', 'width:192px;'); ?></td>
	           </tr>    
	           <tr>
	              <td>Person-in-charge:</td><td><?php select_query_tag($pics, 'id', 'pic', $materials['person_in_charge'], 'material[person_in_charge]', 'material[person_in_charge]', '', 'width:192px;'); ?></td>
	              <td>WIP Line Entry:</td><td><?php select_query_tag($terminals, 'id', 'terminal', $materials['production_entry_terminal_id'], 'material[production_entry_terminal_id]', 'material[production_entry_terminal_id]', '', 'width:192px;'); ?></td>
	           </tr>      
	           <tr>
	              <td>Address:</td><td><input id="address-name" type="text"  value="<?php echo $materials['address_name'] ?>" class="text-field" />
	          			<a id="btn-id" href="#modal-locations" rel="modal:open">Set</a>
	              </td>
	              <td width="150">Unit:</td><td width="310"><?php select_query_tag($units, 'id', 'description', $materials['unit'], 'material[unit]', 'material[unit]', '', 'width:192px;'); ?></td>
	           </tr>              
	           <tr>
	              <td>Description:</td>
	              <td colspan="99">
	                <input type="text" id="material[description]" name="material[description]" value="<?php echo $materials['description'] ?>" class="text-field" style="width:645px" />
	              </td>
	           </tr>
	           <tr><td height="5" colspan="99"></td></tr>
	        </table>	
				</div>
        <br/>
        <div class="form-container">
					<h3 class="form-title">Purchase Information</h3>
	        <table>            
	           <tr>
	              <td width="150">Supplier:</td>
	              <td colspan="99">
	                <?php select_query_tag($suppliers, 'id', 'supplier_name', $item_costs['supplier'], 'item_cost[supplier]', 'item_cost[supplier]', '', 'width:655px;'); ?>
	              </td>
	           </tr>
	           <tr>
	           		<td width="150">Currency:</td><td width="310"><?php select_query_tag($currencies, 'id', 'description', $item_costs['currency'], 'item_cost[currency]', 'item_cost[currency]', '', 'width:192px;'); ?></td>
	           		<td width="150">Cost:</td><td><input type="text" id="item_cost[cost]" name="item_cost[cost]" value="<?php echo $item_costs['cost'] ?>" class="text-field text-right decimal" required/></td>
	           </tr>
	           <tr>
	              
	              <td>Transportation Rate:</td><td><input type="text" id="item_cost[transportation_rate]" name="item_cost[transportation_rate]" value="<?php echo ($item_costs['transportation_rate'] * 100) ?>" class="text-field text-right decimal" /></td>
	           </tr>    
	           <tr><td height="5" colspan="99"></td></tr>
	        </table> 	
				</div>
				<div class="field-command">
					<div class="text-post-status"></div>
					<input id="submit-btn" type="submit" value="Update" class="btn"/>
					<input type="button" value="Cancel" class="btn redirect-to" rel="<?php echo host('indirect-materials-show.php?mid='.$_GET['mid']); ?>"/>
				</div>
				</form>
		</div>
	</div>
	
	<div id="modal-locations" class="modal" style="display:none;width:920px;">
		<div class="modal-title"><h3>Warehouse Address</h3></div>
		<div class="modal-content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
			<div id="grid-locations" class="grid jq-grid">
				<table cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td width="20" class="border-right text-center"></td>
              <td width="110" class="border-right text-center"><a class="sort default active up" code="address">Address</a></td>
              <td width="200" class="border-right text-center"><a class="sort" code="item">Assigned Item</a></td>
              <td width="100" class="border-right text-center"><a class="sort" code="bldg">Building</a></td>
              <td class="border-right text-center"><a class="sort" code="description">Description</a></td>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>	
			<div id="materials"></div>
      <!-- BOF Pagination -->
			<div id="locations-pagination"></div>
		</div>     
		<div class="modal-footer">
			<a class="btn modal-close" rel="modal:close">Close</a>
			<div class="clear"></div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			var data = { 
	    	"url":"/populate/locations.php",
	      "limit":"10",
				"data_key":"location_addresses",
				"row_template":"row_template_locations_modal",
	      "pagination":"#locations-pagination",
	      "searchable":true
				}
				$('#grid-locations').grid(data);
				
			$('#btn-id').click(function(){
				// clear all checked
				// var group = "input:checkbox[name='materials[1]']";
	      // $(group).prop("checked", false);
	        
				$('#materials').find('tr.one-chk').each(function(){
					$(this).prop("checked", false);
				})
			})
				
			$('.one-chk').live('click', function() {
				// allow single selection only
		    if ($(this).is(":checked")) {
	        var group = "input:checkbox[name='" + $(this).attr("name") + "']";
	        $(group).prop("checked", false);
	        $(this).prop("checked", true);
		    } else {
	        $(this).prop("checked", false);
		    }
		    
		    $('#address-name').val($(this).attr('address-name'));
		    $('#address-id').val($(this).attr('address-id'));
		    $('.modal-close').click();
			});
			
			$('#mat-code').keyup(function() {
				if($(this).val() != '<?php echo $materials['material_code'] ?>') {
					($(this).is_existing('materials', 'id', '', 'material_code="' +$(this).val()+ '"', 'material_code')) 
						? $('#submit-btn').attr('disabled', true)
						: $('#submit-btn').attr('disabled', false);	
				}
				$('#mat-barcode').val($(this).val());
			});
			
			$('#mat-barcode').keyup(function() {
				($(this).is_existing('materials', 'id', '', 'bar_code="' +$(this).val()+ '"', 'bar_code')) 
					? $('#submit-btn').attr('disabled', true)
					: $('#submit-btn').attr('disabled', false);
			});
		});
	</script>
<?php }
require('footer.php'); ?>