<?php
  /*
   * Module: ProductTree - Show 
  */
  $capability_key = 'show_product_tree';
  require('header.php');
  
  if(isset($_REQUEST['pid'])) {
		$product_tree = $DB->Get('products_parts_tree', array(
				  			'columns' 		=> 'products_parts_tree.id, materials.id AS mat_id, materials.material_code, materials.description,
				  												lookups.code AS unit, item_costs.cost, products_parts_tree.material_qty, 
				  												suppliers.name, products_parts_tree.remarks, item_costs.transportation_rate',
				  	    'conditions' 	=> 'item_costs.item_type = "MAT" AND products_parts_tree.product_id = '.$_REQUEST['pid'], 
				  	    'joins' 			=> 'LEFT OUTER JOIN materials ON products_parts_tree.material_id = materials.id
				  	    									LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
				  	    									LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
				  	    									LEFT OUTER JOIN lookups ON item_costs.unit = lookups.id',
								'sort_column'	=> 'products_parts_tree.id'
  	  )
		);
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['add_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['add_product_tree']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['edit_product_tree']['name'].'</a>';
					echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<div class="form-container">
				<h3 class="form-title"><?php echo $_REQUEST['code']?> Materials</h3>
		    <div class="grid jq-grid">
		      <table id="tbl-order-items" cellspacing="0" cellpadding="0">
		        <thead>
		          <tr>
		            <td width="3%" class="border-right"><a></a></td>
		            <td width="20%" class="border-right text-center"><a>Material Code</a></td>
		            <!-- <td class="border-right text-center"><a>Description</a></td> -->
		            <td width="5%" class="border-right text-center"><a>Unit</a></td>
		            <td width="10%" class="border-right text-center"><a>Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Cost</a></td>
		            <td width="10%" class="border-right text-center"><a>Amount</a></td>
		            <td width="5%" class="border-right text-center"><a>TC</a></td>
		            <td width="5%" class="border-right text-center"><a>Rate</a></td>
<!-- 		            <td width="20%" class="border-right text-center"><a>Supplier</a></td> -->
		            <td class="border-right text-center"><a>Remarks</a></td>
		          </tr>
		        </thead>
		        <tbody>
		        	<?php
									$ctr=1;
									$a1=0;$a2=0;
									foreach ($product_tree as $material) {
										echo '<tr>';
										echo '<td class="border-right text-right">'.$ctr.'</td>';
										echo '<td class="border-right"><a href="materials-show.php?mid='.$material['mat_id'].'">'.$material['material_code'].'</a></td>';
										//echo '<td class="border-right">'.$material['description'].'</td>';
										echo '<td class="border-right text-center">'.$material['unit'].'</td>';
										echo '<td class="border-right text-right">'.$material['material_qty'].'</td>';
										echo '<td class="border-right text-right">'.$material['cost'].'</td>';
																			
										if(strpos($material['material_qty'], '/') == FALSE) {
											$amount = $material['material_qty'] * $material['cost'];
										} else {
											$frac = explode("/", $material['material_qty']);
											$amount = ($frac[0] / $frac[1]) * $material['cost'];										
										}									
										echo '<td class="border-right text-right">'.number_format(round($amount,4), 4, '.', '').'</td>';
										echo '<td class="border-right text-right">'.$trans_cost = (isset($material['transportation_rate']))?round(($amount*((float)$material['transportation_rate']/100)),4):'0'.'</td>';
										echo '<td class="border-right text-right">'.$trans_rate = (isset($material['transportation_rate']))?$material['transportation_rate']:'0'.'</td>';
										//echo '<td class="border-right">'.$material['name'].'</td>';
										echo '<td class="border-right">'.$material['remarks'].'</td>';
										echo '</tr>';
										$ctr+=1;
										$a1+=round($amount,4)+$trans_cost;
									}
								?>
	        	</tbody>
					</table>
				</div>	
				
				<br/>
				<form>
					<h3 class="form-title">Summary</h3>
					<div class="field">
	          <label class="label">Materials (A):</label>
	          <div class="input">
	            <?php echo number_format($a1,4, '.', '') ?>
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Labor (B):</label>
	          <div class="input">
	            <?php echo number_format($a1,4, '.', '') ?>
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Indirect Materials (C):</label>
	          <div class="input">
	            <?php echo number_format($a1,4, '.', '') ?>
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Administration (D):</label>
	          <div class="input">
	            <?php echo number_format($a1,4, '.', '') ?>
	          </div>
	          <div class="clear"></div>
	        </div>
	        
	        <div class="field">
	          <label class="label">Total (A):(E):</label>
	          <div class="input">
	            <?php echo number_format($a1,4, '.', '') ?>
	          </div>
	          <div class="clear"></div>
	        </div>		
				</form>							
			</div>
		</div>
	</div>

<?php require('footer.php'); ?>