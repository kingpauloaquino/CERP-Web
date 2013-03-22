<?php
  /*
   * Module: ProductTree - Edit 
  */
  $capability_key = 'edit_product_tree';
  require('header.php');
	
	if(isset($_REQUEST['rem_pid'])){
		$Posts->RemovePartsTree(array('conditions' => 'id='.$_REQUEST['rem_pid']));
	}
  
	if($_POST['action'] == 'edit_product_tree') {
		foreach ($_POST['product_tree'] as $arr) {
			$Posts->EditPartsTree(array('variables' => $arr, 'conditions' => 'id='.$arr['id']));
		}
		redirect_to($Capabilities->All['show_product']['url'].'?pid='.$_POST['pid']);	
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
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['add_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['add_product_tree']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['show_product_tree']['url'].'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'" class="nav">'.$Capabilities->All['show_product_tree']['name'].'</a>';
					echo '<a href="'.$Capabilities->All['show_product']['url'].'?pid='.$_REQUEST['pid'].'" class="nav">'.$Capabilities->All['show_product']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" id="producttree-form" action="<?php echo host($Capabilities->GetUrl()) ?>" method="POST">
				<h3 class="form-title"><?php echo $_REQUEST['code']?> Materials</h3>
				<input type="hidden" name="action" value="edit_product_tree">
				<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>">
				<input type="hidden" name="code" value="<?php echo $_REQUEST['code'] ?>">

				<div class="grid jq-grid">
				  <table id="tbl-parts-tree" cellspacing="0" cellpadding="0">
				    <thead>
				      <tr>
				      	<td width="3%" class="border-right"><a></a></td>
		            <td width="20%" class="border-right text-center"><a>Material Code</a></td>
		            <!-- <td class="border-right text-center"><a>Description</a></td> -->
		            <td width="5%" class="border-right text-center"><a>Unit</a></td>
		            <td width="10%" class="border-right text-center"><a>Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Cost</a></td>
		            <td width="10%" class="border-right text-center"><a>Amount</a></td>
<!-- 		            <td width="20%" class="border-right text-center"><a>Supplier</a></td> -->
		            <td class="border-right text-center"><a>Remarks</a></td>
				        <td class="border-right"><a></a></td>
				      </tr>
				    </thead>
				    <tbody>
				    	<?php
									$ctr=1;
									foreach ($product_tree as $material) {
										echo '<tr>';
										echo '<td class="border-right text-right">'.$ctr.'<input type="hidden" id="product_tree['.$ctr.'][id]" name="product_tree['.$ctr.'][id]" value="'.$material['id'].'" /></td>';
										echo '<td class="border-right">'.$material['material_code'].'</td>';
										//echo '<td class="border-right">'.$material['description'].'</td>';
										echo '<td class="border-right text-center">'.$material['unit'].'</td>';
										echo '<td class="border-right"><input type="text" id="product_tree['.$ctr.'][material_qty]" name="product_tree['.$ctr.'][material_qty]" value="'.$material['material_qty'].'" class="auto_width_right" autocomplete="off"/></td>';
										echo '<td class="border-right text-right">'.$material['cost'].'</td>';
										
										if(strpos($material['material_qty'], '/') == FALSE) {
											$amount = $material['material_qty'] * $material['cost'];
										} else {
											$frac = explode("/", $material['material_qty']);
											$amount = ($frac[0] / $frac[1]) * $material['cost'];										
										}													
										echo '<td class="border-right text-right">'.number_format($amount, 2, '.', '').'</td>';
										//echo '<td class="border-right">'.$material['name'].'</td>';
										echo '<td class="border-right"><input type="text" id="product_tree['.$ctr.'][remarks]" name="product_tree['.$ctr.'][remarks]" value="'.$material['remarks'].'" class="auto_width" autocomplete="off"/></td>';
										echo '<td><a href="'.$Capabilities->GetUrl().'?pid='.$_REQUEST['pid'].'&code='.$_REQUEST['code'].'&rem_pid='.$material['id'].'"><img style="width: 12px; height:12px" src="../images/remove.png" /></a></td>';
										echo '</tr>';
										$ctr+=1;
									}
								?>
			    	</tbody>
		    	</table>
				</div>	
				<br/>
				<div class="input">
					<input type="submit" value="Update Parts Tree" class="submit"/>
	        <button class="btn" onclick="return cancel_btn();">Cancel</button>
			</div>
		</form> 
	</div>
</div>

<?php require('footer.php'); ?>