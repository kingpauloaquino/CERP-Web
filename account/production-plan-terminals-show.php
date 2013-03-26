<?php
  /*
   * Module: Production Plan Terminals - Show
  */
  $capability_key = 'show_production_plan_terminals';
  require('header.php');
  
  if(isset($_REQUEST['mid'])) {

  }
?>
<script>
$(function() {
    $( ".accordion" ).accordion({
        collapsible: true, active: false, autoHeight: false, heightStyle: "content"
    });
});
</script>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Basic Information</h3>
				
				<div class="field">
          <label class="label">P/O No.:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $_REQUEST['po'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Production Lot No.:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $_REQUEST['lot'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Product Code:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $_REQUEST['prod'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>

				<br/>
				<h3 class="form-title">Terminals</h3>
				<div class="accordion">
						<?php
							$terminals = $DB->Get('terminals', array(
									  			'columns' => 'terminals.id AS terminal_id, terminals.terminal_code, terminals.terminal_name, terminals.type AS terminal_type', 
									  			'joins' => 'INNER JOIN locations ON locations.id = terminals.location_id', 
									  	    'conditions' => 'locations.location_code = "WIP" AND terminals.type="IN"',
									  	    'sort_column' => 'terminals.id'
					  	    ));
							foreach ($terminals as $trml) {
								echo '<h3><a href="#">'.$trml['terminal_name'].'</a></h3>';
								echo '<div class="accordion_content">';
								$production = $DB->Get('terminals', array(
								  			'columns' => 'terminals.id AS terminal_id, terminals.terminal_code, terminals.terminal_name, terminals.type AS terminal_type, materials.material_code, materials.id AS mat_id, 
								  										production_inventories.terminal_id AS loc_trml_id, production_inventories.mat_lot_no, production_inventories.qty, production_inventories.status AS status_id, 
								  										lookups2.description AS status, lookups.description AS unit', 
								  			'joins' => 'INNER JOIN production_inventories ON production_inventories.terminal_id = terminals.id
								  									INNER JOIN locations ON locations.id = terminals.location_id
								  									INNER JOIN materials ON materials.id = production_inventories.item_id
								  									INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
								  									INNER JOIN lookups ON lookups.id = item_costs.unit
								  									INNER JOIN lookups AS lookups2 ON lookups2.id = production_inventories.status',
								  	    'conditions' => 'locations.location_code = "WIP" 
								  	    						AND production_inventories.item_type = "MAT" AND production_inventories.tracking_no = "'.$_REQUEST['trk_no'].'"
								  	    						AND production_inventories.qty > 0 AND production_inventories.prod_lot_no ="'.$_REQUEST['lot'].'" AND production_inventories.terminal_id ='.$trml['terminal_id'],
								  	    'sort_column' => 'terminals.id'
								  	    ));
									echo '<div class="grid jq-grid"><table cellspacing="0" cellpadding="0">';
									echo '<thead><tr>';
									echo '<td class="border-right">Material</td>';
									echo '<td width="15%" class="border-right text-center">Material Lot No.</td>';
									echo '<td width="15%" class="border-right text-center">UOM</td>';
									echo '<td width="15%" class="border-right text-center">Quantity</td>';
									echo '<td width="15%" class="border-right text-center">Terminal Details</td>';
									echo '</tr></thead><tbody>';
									foreach ($production as $invt) {
											echo '<tr>';
											echo $highlight = ($invt['status_id'] == 148 || $invt['status_id'] == 150) 
														? '<td class="border-right text-red">'.$invt['material_code'].'</td>
															<td class="border-right text-center text-red">'.$invt['mat_lot_no'].'</td>
															<td class="border-right text-center text-red">'.$invt['unit'].'</td>
															<td class="border-right text-right text-red">'.trim_decimal($invt['qty']).'</td>
															<td class="border-right text-center text-red">'.$invt['status'].'</td>'
														: '<td class="border-right">'.$invt['material_code'].'</td>
															<td class="border-right text-center">'.$invt['mat_lot_no'].'</td>
															<td class="border-right text-center">'.$invt['unit'].'</td>
															<td class="border-right text-right numbers">'.trim_decimal($invt['qty']).'</td>
															<td class="border-right text-center"><a href="production-line-show.php?mid='.$invt['mat_id'].'&prod_lot_no='.$_REQUEST['lot'].'&trk_no='.$_REQUEST['trk_no'].'">VIEW</a></td>';
											
									}
									echo '</tbody></table></div>';
								echo '<br/><a href="terminal-prod-items.php?tid='.$invt['terminal_id'].'" target="_blank">View all items in this terminal</a></div>';
							}
						?>
					</div>
			</div>
	</div>

<?php require('footer.php'); ?>