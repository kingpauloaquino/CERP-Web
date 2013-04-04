<?php
  /*
   * Module: Material Requests - Show
  */
  $capability_key = 'show_material_request';
  require('header.php');
	
	if(isset($_REQUEST['mrid'])) {
		$req = $DB->Find('material_requests', array(
  			'columns' => 'lookups.description AS type, orders.po_number, material_requests.lot_no, products.product_code, material_requests.lot_no, material_requests.request_qty,
  										material_requests.request_date, CONCAT(users.first_name, " ", users.last_name) AS requestor, material_requests.remarks,
  										material_requests.product_id, material_requests.production_purchase_order_id,orders.id AS oid, users.id AS uid ', 
  			'joins' =>	'INNER JOIN lookups ON lookups.id = material_requests.request_type
  									INNER JOIN production_purchase_orders ON production_purchase_orders.id = material_requests.production_purchase_order_id
  									INNER JOIN orders ON orders.id = production_purchase_orders.order_id
  									INNER JOIN products ON products.id = material_requests.product_id
  									INNER JOIN users ON users.id = material_requests.requestor_id',
  	  	'conditions' => 'material_requests.id = '.$_REQUEST['mrid']
  	  )
		);
	}
  
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="POST">
				<h3 class="form-title">Basic Information</h3>
	    
	    	<span class="notice">
		<!--           <p class="info"><strong>Notice</strong> Message</p> -->
	    	</span>
	    	
	    	<div class="field">
		      <label class="label">Type:</label>
		      <div class="input">
		        	<input type="text" value="<?php echo $req['type'] ?>" readonly="readonly" />
		      </div>
		      <div class="clear"></div>
		    </div>

				<div class="field">
		      <label class="label">P/O No.:</label>
		      <div class="input">
		        <input type="text" value="<?php echo $req['po_number'] ?>" readonly="readonly" />
          	<?php echo $linkto = ($req['po_number']!='') ? link_to('production-plan-show.php?ppoid='.$req['production_purchase_order_id'].'&oid='.$req['oid']) : '' ?>
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		    <div class="field">
		      <label class="label">Lot No.:</label>
		      <div class="input">
		        <input type="text" value="<?php echo $req['lot_no'] ?>" readonly="readonly" />
		      </div>
		      <div class="clear"></div>
		    </div>	
		    
		    <div class="field">
		      <label class="label">Product:</label>
		      <div class="input">
		        <input type="text" value="<?php echo $req['product_code'] ?>" readonly="readonly" />
          	<?php echo $linkto = ($req['product_code']!='') ? link_to('products-show.php?pid='.$req['product_id']) : '' ?>
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		     <div class="field">
		      <label class="label">Quantity:</label>
		      <div class="input">
		        <input type="text" value="<?php echo $req['request_qty'] ?>" readonly="readonly" />
		      </div>
		      <div class="clear"></div>
		    </div>
        
        <div class="field">
          <label class="label">Remarks:</label>
          <div class="input">
            <textarea id="remarks" name="remarks"><?php echo $req['remarks'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>  
        
         <div class="field">
		      <label class="label">Request Date:</label>
		      <div class="input">
		        <input type="text" value="<?php echo date("F d, Y", strtotime($req['request_date'])) ?>" readonly="readonly" />
		      </div>
		      <div class="clear"></div>
		    </div>
		    
		     <div class="field">
		      <label class="label">Requested by:</label>
		      <div class="input">
		        <input type="text" value="<?php echo $req['requestor'] ?>" readonly="readonly" />
          	<?php echo $linkto = ($req['requestor']!='') ? link_to('users-show.php?uid='.$req['uid']) : '' ?>
		      </div>
		      <div class="clear"></div>
		    </div>
        
        <br/>
        <h3 class="form-title">Items</h3>
				<div class="grid jq-grid">
					<table id="tbl-req-items" cellpadding="0" cellspacing="0">
			      <thead>
			         <tr>
		            <td width="5%" class="border-right text-center"><a>No.</a></td>
		            <td width="20%" class="border-right text-center"><a>Item</a></td>
		            <td width="10%" class="border-right text-center"><a>UOM</a></td>
		            <td width="15%" class="border-right text-center"><a>Request Qty</a></td>
		            <td width="15%" class="border-right text-center"><a>Issue Qty</a></td>
		            <td class="border-right text-center"><a>Remarks</a></td>
			         </tr>
			      </thead>
			      <tbody>
			      	<?php
				      	$req_items = $DB->Get('material_request_items', array(
						  			'columns' => 'material_request_items.material_id, materials.material_code, lookups.code AS unit, material_request_items.request_qty, 
						  										material_request_items.issue_qty, material_request_items.remarks', 
						  			'joins' =>	'INNER JOIN materials ON materials.id = material_request_items.material_id
						  										INNER JOIN item_costs ON item_costs.item_id = material_request_items.material_id
						  										INNER JOIN lookups ON lookups.id = item_costs.unit',
						  	  	'conditions' => 'material_request_items.material_request_id = '.$_REQUEST['mrid']
						  	  ));
								$ctr=1;
			      		foreach ($req_items as $item) {
									echo '<tr>';
									echo '<td class="border-right text-right">'.$ctr.'</td>';
									echo '<td class="border-right"><a href="materials-show.php?mid='.$item['material_id'].'">'.$item['material_code'].'</a></td>';
									echo '<td class="border-right text-center">'.$item['unit'].'</td>';
									echo '<td class="border-right text-right">'.$item['request_qty'].'</td>';
									echo '<td class="border-right text-right">'.$item['issue_qty'].'</td>';
									echo '<td class="border-right">'.$item['remarks'].'</td>';							
									echo '</tr>';
									$ctr+=1;
								}
							?>
			      </tbody>
					</table>	
				</div>
			</form>
		</div>
	</div>

<?php require('footer.php'); ?>