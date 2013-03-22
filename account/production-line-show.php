<?php
  /*
   * Module: Production Line - Show
  */
  $capability_key = 'show_production_line';
  require('header.php');
  
  if(isset($_REQUEST['mid'])) {
  	$materials = $DB->Find('materials', array(
					  			'columns' 		=> 'materials.id AS mid, materials.parent, materials.material_code, materials.description, brand_models.brand_model, 
																  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic,
																  	lookups3.description AS material_type, lookups4.description AS status', 
					  	    'conditions' 	=> 'materials.id = '.$_REQUEST['mid'], 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id 
																		LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																		LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																		LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
																		LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																		LEFT OUTER JOIN lookups AS lookups4 ON materials.status = lookups4.id'
	  ));
  }
	
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  // echo '<a href="'.$Capabilities->All['add_material_inventory']['url'].'" class="nav">'.$Capabilities->All['add_material_inventory']['name'].'</a>';
				  // echo '<a href="'.$Capabilities->All['edit_material_inventory']['url'].'?iid='.$_REQUEST['iid'].'&mid='.$_REQUEST['mid'].'" class="nav">'.$Capabilities->All['edit_material_inventory']['name'].'</a>'; 
			  	// echo '<a href="'.$Capabilities->All['material_inventory_history']['url'].'?iid='.$_REQUEST['iid'].'&mid='.$_REQUEST['mid'].'" class="nav" target="_blank">'.$Capabilities->All['material_inventory_history']['name'].'</a>';

				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
				<table>
           <tr>
              <td width="150">Material Code:</td><td width="340"><input type="text" value="<?php echo $materials['material_code'] ?>" class="text-field" disabled/>
          			<?php echo $linkto = ($materials['material_code']!='') ? link_to('materials-show.php?mid='.$materials['mid']) : '' ?></td>
              <td width="120"></td><td width="340"></td>
           </tr>
           <tr>
              <td>Classification:</td><td><input type="text" value="<?php echo $materials['classification'] ?>" class="text-field" disabled/></td>
              <td>Model:</td><td><input type="text" value="<?php echo $materials['brand_model'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Person-in-charge:</td><td><input type="text" value="<?php echo $materials['pic'] ?>" class="text-field" disabled/>
          			<?php echo $linkto = ($materials['pic']!='') ? link_to('users-show.php?uid='.$materials['user_id']) : '' ?></td>
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

        <h3 class="form-title">Terminals</h3>
	      <div class="grid jq-grid">
	        <table cellspacing="0" cellpadding="0">
	          <thead>
	            <tr>
	              <td class="border-right text-center"><a>Terminal</a></td>
	              <td width="10%" class="border-right text-center"><a>IN</a></td>
	              <td width="12%" class="border-right text-center"><a>Current</a></td>
	              <td width="7%" class="border-right text-center"><a>Rework</a></td>
	              <td width="7%" class="border-right text-center"><a>Additional</a></td>
	              <td width="7%" class="border-right text-center"><a>QA Sample</a></td>
	              <td width="7%" class="border-right text-center"><a>Mgr Sample</a></td>
	              <td width="7%" class="border-right text-center"><a>Defect A</a></td>
	              <td width="7%" class="border-right text-center"><a>Defect B</a></td>
	              <td width="10%" class="border-right text-center"><a>Partial OUT</a></td>
	              <td width="10%" class="border-right text-center"><a>Complete OUT</a></td>
	            </tr>
	          </thead>
	          <tbody>
	        		<?php
								
									$production = $DB->Get('terminals', array(
							  			'columns' => 'terminals.id AS terminal_id, terminals.terminal_code, terminals.terminal_name, terminals.type AS terminal_type, production_inventories.status,
							  										production_inventories.terminal_id AS loc_trml_id, production_inventories.mat_lot_no, production_inventories.qty, production_inventories.input,
							  										production_inventories.rework, production_inventories.additional, production_inventories.qa_sample, production_inventories.mgr_sample,
							  										production_inventories.defect_a, production_inventories.defect_b, production_inventories.output_partial, production_inventories.output_complete', 
							  			'joins' => 'LEFT OUTER JOIN production_inventories ON production_inventories.terminal_id = terminals.id
							  									AND production_inventories.item_type = "MAT" AND production_inventories.item_id = '.$_REQUEST['mid'].' 
							  	    						AND production_inventories.prod_lot_no ="'.$_REQUEST['prod_lot_no'].'" AND production_inventories.tracking_no="'.$_REQUEST['trk_no'].'"
							  									INNER JOIN locations ON locations.id = terminals.location_id',
							  	    'conditions' => 'locations.location_code = "WIP"',
							  	    'conditions' => 'locations.location_code = "WIP" AND terminals.type="IN"',
							  	    ));
									foreach ($production as $invt) {
										//if($invt['terminal_type'] == 'IN') {									
											if(if_contains($invt['terminal_code'],'WH1')) $url = 'terminal-wh-items.php?typ=MAT&tid='.$invt['terminal_id'];
											if(if_contains($invt['terminal_code'],'WH2')) $url = 'terminal-wh-items.php?typ=PRD&tid='.$invt['terminal_id'];
											if(if_contains($invt['terminal_code'],'WIP')) $url = 'terminal-prod-items.php?tid='.$invt['terminal_id'];			
											echo '<tr><td class="border-right text-right"><a href="'.$url.'">'.$invt['terminal_name'].'</a></td>';
											echo '<td class="border-right text-right numbers'.
														($enroute = ($invt['status'] == 148)? 'text-red': '') . ($complete = ($invt['output_complete'] > 0)? 'text-lightgray': '').
														'">'.trim_decimal($invt['input']).'</td>';
											echo '<td class="border-right text-right numbers'.
														($enroute = ($invt['status'] == 148)? 'text-red': '') . ($complete = ($invt['output_complete'] > 0)? 'text-lightgray': '').
														'">'. ($stat = ($invt['status'] == 148)? '(en route) ': '') .trim_decimal($invt['qty']).'</td>';
											echo '<td class="border-right text-right '.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['rework']).'</td>';
											echo '<td class="border-right text-right '.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['additional']).'</td>';
											echo '<td class="border-right text-right '.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['qa_sample']).'</td>';
											echo '<td class="border-right text-right '.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['mgr_sample']).'</td>';
											echo '<td class="border-right text-right '.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['defect_a']).'</td>';
											echo '<td class="border-right text-right '.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['defect_b']).'</td>';
											echo '<td class="border-right text-right numbers'.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['output_partial']).'</td>';
											echo '<td class="border-right text-right numbers'.($gray = ($invt['output_complete'] > 0)? 'text-lightgray': '').'">'.trim_decimal($invt['output_complete']).'</td></tr>';
										//} else {
											// echo '<td class="border-right text-right">'.trim_decimal($invt['qa_sample']).'</td>';
											// echo '<td class="border-right text-right">'.trim_decimal($invt['mgr_sample']).'</td>';
											// echo '<td class="border-right text-right">'.trim_decimal($invt['defect_a']).'</td>';
											// echo '<td class="border-right text-right">'.trim_decimal($invt['defect_b']).'</td>';
											// echo '<td class="border-right text-right">'.trim_decimal($invt['output_partial']).'</td>';
											// echo '<td class="border-right text-right">'.trim_decimal($invt['qty']).'</td></tr>';
										//}
									}
	        		?>
	          </tbody>
	        </table>
	      </div>	
      </form>
    	<br/>
		</div>
	</div>

<?php require('footer.php'); ?>