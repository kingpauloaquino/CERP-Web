<?php
  /*
   * Module: Orders - Show
  */
  $capability_key = 'show_order';
  require('header.php');
	
	if($_POST['action']=="publish") {
		$args = array('order_id' => $_POST['oid'], 'head_time_days' => 7); //7 days before P/O shipment
		$num_of_records = $Posts->InitPurchaseOrder($args);	
	}
	
	if(isset($_REQUEST['oid'])) {
  	$orders = $DB->Find('orders', array(
  			'columns' => 'orders.*, suppliers.name AS client', 
  	  	'conditions' => 'orders.id = '.$_REQUEST['oid'],
  	  	'joins' => 'INNER JOIN suppliers ON suppliers.id = orders.client_id'
  	  )
		);
		$order_items = $DB->Get('order_items', array(
  			'columns' => 'products.id AS item_id, products.product_code AS item_code, products.description, item_costs.cost, lookups.code AS unit, order_items.item_id, 
  										order_items.item_type, order_items.quantity, (item_costs.cost * order_items.quantity) AS amount, order_items.remarks', 
  			'joins'		=> 'INNER JOIN products ON products.id=order_items.item_id
											INNER JOIN item_costs ON item_costs.item_id = products.id
											INNER JOIN lookups ON lookups.id = item_costs.unit',
  	  	'conditions' => 'item_costs.item_type="PRD" AND order_items.item_type="PRD" AND order_items.order_id='.$_REQUEST['oid']. ' 
  	  								UNION SELECT
  	  								materials.id AS item_id, materials.material_code AS item_code, materials.description, item_costs.cost, lookups.code AS unit, order_items.item_id,
  	  								order_items.item_type, order_items.quantity, (item_costs.cost * order_items.quantity) AS amount, order_items.remarks
  	  								FROM order_items
  	  								INNER JOIN materials ON materials.id=order_items.item_id
											INNER JOIN item_costs ON item_costs.item_id = materials.id
											INNER JOIN lookups ON lookups.id = item_costs.unit
											WHERE item_costs.item_type="MAT" AND order_items.item_type="MAT" AND order_items.order_id='.$_REQUEST['oid']
  	  )
		);
	}	  
  $roles = $DB->Get('roles', array('columns' => 'id, name', 'conditions' => 'NOT id = 1'));
  $payment_terms = $DB->Get('lookups', array('columns' => 'id, description', 
  					'conditions' => 'id = '.$orders['payment_terms'])); 
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['orders']['url'].'" class="nav">'.$Capabilities->All['orders']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_order']['url'].'" class="nav">'.$Capabilities->All['add_order']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_order']['url'].'?oid='.$_REQUEST['oid'].'" class="nav">'.$Capabilities->All['edit_order']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container" method="post">
        <h3 class="form-title">Basic Information</h3>
        
        <span class="notice">
<!--           <p class="info"><strong>Notice</strong> Message</p> -->
        </span>
        
        <div class="field">
          <label class="label">Client:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $orders['client'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">P/O Number:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $orders['po_number'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">P/O Date:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo date("F d, Y", strtotime($orders['po_date'])) ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Delivery:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo date("F d, Y", strtotime($orders['delivery_date'])) ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terms:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $orders['terms'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Payment Terms:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $payment_terms[0]['description'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
				
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $orders['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
				
        <div class="field">
          <label class="label">Remarks:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $orders['remarks'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
        <br/>
        <h3 class="form-title">Items</h3>
				<div class="grid jq-grid">
		      <table id="tbl-order-items" cellspacing="0" cellpadding="0">
		        <thead>
		          <tr>
		            <td width="5%" class="border-right text-center"><a>No.</a></td>
		            <td width="15%" class="border-right text-center"><a>Code No.</a></td>
		            <td width="15%" class="border-right text-center"><a>Item</a></td>
		            <td width="10%" class="border-right text-center"><a>Unit</a></td>
		            <td width="10%" class="border-right text-center"><a>Unit Price</a></td>
		            <td width="10%" class="border-right text-center"><a>Qty</a></td>
		            <td width="10%" class="border-right text-center"><a>Amount</a></td>
		            <td class="border-right"><a>Remarks</a></td>
		          </tr>
		        </thead>
		        <tbody>
		        	<?php
								$ctr=1;
								// $total_qty = 0;
								// $tota_amnt = 0.00;
			      		foreach ($order_items as $item) {
									echo '<tr>';
									echo '<td class="border-right text-right">'.$ctr.'</td>';
									$url = ($item['item_type'] == "PRD") ? 'products-show.php?pid=' : 'materials-show.php?mid=';
									echo '<td class="border-right"><a href="'.$url.$item['item_id'].'">'.$item['item_code'].'</a></td>';
									echo '<td class="border-right">'.$item['description'].'</td>';
									echo '<td class="border-right text-center">'.$item['unit'].'</td>';
									echo '<td class="border-right text-right">'.$item['cost'].'</td>';
									echo '<td class="border-right text-right">'.$item['quantity'].'</td>';
									echo '<td class="border-right text-right">'.number_format($item['amount'], 2, '.', '').'</td>';
									echo '<td class="border-right">'.$item['remarks'].'</td>';									
									echo '</tr>';
									$ctr+=1;
									// $total_qty+=(int)$item['quantity'];
									// $tota_amnt+=(float)$item['amount'];
								}
							?>
						</tbody>
					</table>
				</div>	
					
				<br/>
				<h3 class="form-title">Summary</h3>        
        <div class="field">
          <label class="label">Total Quantity:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $orders['total_quantity'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Total Amount:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $orders['total_amount'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Received:</label>
          <div class="input">
            <input type="checkbox" disabled="disabled"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Approved:</label>
          <div class="input">
            <input type="checkbox" disabled="disabled"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <br/>
        <div class="field">
          <label class="label"></label>
          <div class="input">
          	<input type="hidden" name="action" value="publish"/>
          	<input type="hidden" name="oid" value="<?php echo $_REQUEST['oid'] ?>"/>
            <button class="btn">Publish</button>
            <button class="btn" onclick="return cancel_btn();">Cancel</button>
          </div>
          <div class="clear"></div>
        </div>
        
			</form>
		</div>
	</div>
<?php require('footer.php'); ?>