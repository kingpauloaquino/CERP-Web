<?php
require('../include/general.class.php');
  
request_populate_data();

//if ($_SESSION['timeout'] + 10 * 60 < time()) {
   //redirect_to("/".$Host."?signout=true");
//} 

function request_populate_data() {
  global $JSON;
	if(isset($_GET['format'])) {
	  if($_SERVER['REQUEST_METHOD'] == "GET" and $_GET['format'] == "json") {
	    $populate = $_GET['format'];
	    // ===============================================================
	    // Populate::Materials
	    // ===============================================================
	    $data = populate_materials();
	    $JSON->build_pretty_json($data);
	  }	
	}
}

function populate_materials() {
  global $DB;
  $data = $DB->Fetch('materials', array(
      'columns' => 'materials.id AS id, materials.material_code AS code, brand_models.brand_model AS brand, item_classifications.classification',
      'joins' => 'INNER JOIN brand_models ON materials.brand_model = brand_models.id 
                  INNER JOIN item_classifications ON materials.material_classification = item_classifications.id'
    )
  );
  
  return array("materials" => $data, "total" => $DB->totalRows());
}

function populate_users($keyword='', $paged=1, $sort='employee_id', $order='asc') {
  global $DB;
  $data = $DB->Fetch('users', array(
      'columns' => 'users.id, employee_id, first_name, last_name, position, roles.name AS role, status, users.created_at',
      'joins' => 'INNER JOIN roles ON roles.id = users.role'
    )
  );
  
  return array("users" => $data, "total" => $DB->totalRows());
}

if(empty($Signed)) redirect_to("/".$Host);
if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['action'])) {
  $action = $_POST['action'];
  $redirect = $Capabilities->All[$_POST['redirect']]['url'];
	
  switch ($action)
  {
  // ===============================================================
  // Post::Add User
  // ===============================================================
  case 'add_user':
    // $employee_id = strtoupper($_POST['user']['employee_id']);
// 		
    // if($Posts->IsUnique('users','employee_id', $employee_id) == 1) {
      // echo '<p class="error">Sorry, but employee id <b>'.$employee_id.'</b> is already taken</p>';
    // } else {
      // $user_id = $Posts->AddUser($_POST['user']);
	  // echo '<script>window.location = "'.host($redirect).'?id='.$user_id.'"</script>';
    // }
    // exit(); break;

  // ===============================================================
  // Post::Edit User
  // ===============================================================
  case 'edit_user':
    break;
		
	case 'add_material':
		$_POST['material']['base'] = TRUE;
		$_POST['material']['parent'] = NULL;
		if(isset($_POST['material']['defect_rate'])) {
			$_POST['material']['defect_rate'] = $_POST['material']['defect_rate'] / 100;
		}
		if(isset($_POST['material']['sorting_percentage'])) {
			$_POST['material']['sorting_percentage'] = $_POST['material']['sorting_percentage'] / 100;
		}
		$base_id = $Posts->AddMaterial($_POST['material']);
				
		
		if(isset($_POST['item_cost']['transportation_rate'])) {
			$_POST['item_cost']['transportation_rate'] = $_POST['item_cost']['transportation_rate'] / 100;
		}
		$_POST['item_cost']['item_id'] = $base_id;
		$Posts->AddItemCost($_POST['item_cost']);
		if(isset($base_id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$base_id); }
		break;
		
	case 'edit_material':
		if(isset($_POST['material']['defect_rate'])) {
			$_POST['material']['defect_rate'] = $_POST['material']['defect_rate'] / 100;
		}
		if(isset($_POST['material']['sorting_percentage'])) {
			$_POST['material']['sorting_percentage'] = $_POST['material']['sorting_percentage'] / 100;
		}
		$num_of_records1 = $Posts->EditMaterial(array('variables' => $_POST['material'], 'conditions' => 'id='.$_POST['mid']));
		
		foreach($_POST['item_cost'] as $cost) {
			if(isset($cost['transportation_rate'])) {
				$cost['transportation_rate'] = $cost['transportation_rate'] / 100;
			}
			$Posts->EditItemCost(array('variables' => $cost, 'conditions' => 'item_type= "MAT" AND id='.$cost['id']));
		}
		redirect_to($Capabilities->All['show_material']['url'].'?mid='.$_POST['mid']);		
		break;
		
	case 'add_material_rev':
		if(isset($_POST['material']['defect_rate'])) {
			$_POST['material']['defect_rate'] = $_POST['material']['defect_rate'] / 100;
		}
		if(isset($_POST['material']['sorting_percentage'])) {
			$_POST['material']['sorting_percentage'] = $_POST['material']['sorting_percentage'] / 100;
		}
		$_POST['material']['base'] = FALSE;
		$id = $Posts->AddMaterial($_POST['material']);
				
		if(isset($_POST['item_cost']['transportation_rate'])) {
			$_POST['item_cost']['transportation_rate'] = $_POST['item_cost']['transportation_rate'] / 100;
		}
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
				
		$_POST['rev']['material_id'] = $id;
		$_POST['rev']['base_material_id'] = $_POST['material']['parent'];
		$Posts->AddMaterialRev($_POST['rev']);
		if(isset($id)){ redirect_to($Capabilities->All['show_material']['url'].'?mid='.$id); }
		break;
	
	case 'add_item_cost':
		if(isset($_POST['item_cost']['transportation_rate'])) {
			$_POST['item_cost']['transportation_rate'] = $_POST['item_cost']['transportation_rate'] / 100;
		}
		$Posts->AddItemCost($_POST['item_cost']);			
			
		redirect_to((($_POST['typ'] == 'ind') ? 'indirect-' : '').'materials-show.php?mid='.$_POST['item_cost']['item_id']); 
		break;
		
	case 'add_indirect_material':
		$id = $Posts->AddIndirectMaterial($_POST['material']);
					
		if(isset($_POST['item_cost']['transportation_rate'])) {
			$_POST['item_cost']['transportation_rate'] = $_POST['item_cost']['transportation_rate'] / 100;
		}
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		if(isset($id)){ redirect_to($Capabilities->All['show_indirect_material']['url'].'?mid='.$id); }
		break;
		
	case 'add_product':
		$id = $Posts->AddProduct($_POST['product']);
		$_POST['item_cost']['supplier'] = 1; // CRESC
		$_POST['item_cost']['item_id'] = $id;
		$Posts->AddItemCost($_POST['item_cost']);
		if(isset($id)){ redirect_to($Capabilities->All['show_product']['url'].'?pid='.$id); }
		
		//add forecast entry
		$args = array('forecast_year'=>date('Y'), 'product_id'=>$id);
		$Posts->AddForecast($args);
		break;
		
	case 'edit_product':
		$args = array('variables' => $_POST['product'], 'conditions' => 'id='.$_POST['pid']); 
		$num_of_records = $Posts->EditProduct($args);
		
		$num_of_records2 = $Posts->EditItemCost(array('variables' => $_POST['item_cost'], 'conditions' => 'id='.$_POST['item_cost_id']));
		redirect_to($Capabilities->All['show_product']['url'].'?pid='.$_POST['pid']);		
		break;	
		
  // ===============================================================
  // Post::Add Purchase
  // ===============================================================
  case 'add_purchase':
    $purchase		= $_POST['purchase'];
    $items			= array();
		$total_amount	= 0.00;
	
		if(!empty($_POST['items'])) {
		  foreach ($_POST['items'] as $id => $attr) {
	      $item = array('item_id' => $attr['item_id'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['item_price']), 'currency' => $attr['currency']);
		    $total_amount += (to_double($attr['quantity']) * to_double($attr['item_price']));
	        array_push($items, $item);
		  }
		}
		
		$purchase['items']			= $items;
		$purchase['total_amount']	= $total_amount;
		
		// Insert new purchase record
		if(!isset($purchase['po_number'])) $purchase['po_number'] = generate_new_code('purchase_number');
		$purchase_id = $Posts->AddPurchase($purchase);
		
		if($purchase_id > 0) redirect_to(host('purchases-show.php?id='.$purchase_id)); 
    break;
	
  // ===============================================================
  // Post::Edit Purchase
  // ===============================================================
  case 'edit_purchase':
    $purchase		= $_POST['purchase'];
    $items			= array();
		$total_amount	= 0.00;
		foreach ($_POST['items'] as $id => $attr) {
      $item = array('item_id' => $attr['item_id'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['item_price']), 'currency' => $attr['currency']);
	    $total_amount += (to_double($attr['quantity']) * to_double($attr['item_price']));
      array_push($items, $item);
	  }
		$purchase['items']			= $items;
		$purchase['total_amount']	= $total_amount;
		$Posts->EditPurchase($purchase);
		
		redirect_to(host('purchases-show.php?id='.$purchase['id']));
    break;
		
	case 'approve_purchase':
		$item = array('variables' => array('approved_by' => $_POST['approved_by'], 'approved_at' => date('Y-m-d H:i:s')), 'conditions' => 'id='.$_POST['id']);
		$Posts->ApprovePurchase($item);
		break;	
		
	// ===============================================================
  // Post::Update Delivery
  // ===============================================================
  case 'add_delivery_partial':
    $delivery		= $_POST['delivery'];
		$delivery['status'] = 13; // Open receiving status
		$delivery['completion_status'] = 19; // pending completion status
		$delivery_id = $Posts->AddDelivery($delivery);
		
		foreach ($_POST['items'] as $id => $attr) {
			$delivery_item = array();
			$delivery_item['delivery_id'] = $delivery_id;
			$delivery_item['purchase_item_id'] = $attr['purchase_item_id'];
			$delivery_item['status'] = 5; // Pending completion status
			global $DB;
    	$delivery_item_id = $DB->InsertRecord('delivery_items', $delivery_item);
	  }
		
		redirect_to(host('deliveries-show.php?id='.$delivery_id));
  	break;  
	
  // ===============================================================
  // Post::Update Delivery
  // ===============================================================
  case 'edit_delivery':
		$_POST['delivery']['delivery_date'] = date('Y-m-d', strtotime($_POST['delivery']['delivery_date']));
		$Posts->UpdateDelivery(array('variables' => $_POST['delivery'], 'conditions' => 'id='.$_POST['did']));
		redirect_to(host('deliveries-show.php?id='.$_POST['did']));
  	break;
  
  // ===============================================================
  // Post::Add Receiving
  // ===============================================================
  case 'add_receiving':
    //echo $Posts->AddReceiving($_POST['receiving']);
    exit();
    break;
		
	// ===============================================================
  // Post::Edit Receiving
  // ===============================================================
  case 'edit_receiving':
		$ctr = 0;
		$complete_flag = 0;
    $items	= $_POST['items']; 
		
		// set all items as incomplete status
		$args = array('variables' => array('status' => 22), 'conditions' => 'delivery_id='.$_POST['delivery']['id']); 
		$Posts->EditReceivingItems($args);
		unset($args);
		
		foreach($items as $item) {
			$item['invoice'] = $_POST['delivery']['invoice'];
			$item['receipt'] = $_POST['delivery']['receipt'];
			$item['receive_date'] = date('Y-m-d');
			$item['received'] = $item['received'];
			 
			//add to inventory
			$Posts->AddInventory(array('item_id' => $item['item_id'], 'item_type' => 'MAT', 'invoice' => $_POST['delivery']['invoice'], 
																'lot' => $_POST['delivery']['lot'], 'qty' => $item['received'], 'remarks' => $item['remarks'])); 
			 
			//update receiving status
			unset($item['delivered']); 
			unset($item['item_id']); 
			$args = array('variables' => $item, 'conditions' => 'id='.$item['id']);
			$num_of_records = $Posts->EditReceivingItems($args);
			
			$ctr += 1;
			if($item['status'] == 21) $complete_flag += 1;
			
			
		}
		
		$purchase_completion_status = 0;
		if($ctr > 0) {
			if($ctr == $complete_flag) {
				$purchase_completion_status = 6; //complete
			}
			if($ctr > $complete_flag) {
				$purchase_completion_status = 5; // partial
			}	
		}
		
		// status 14 = close
		$delivery = array('variables' => array('remarks' => $_POST['delivery']['remarks'], 'status' => 14, 'completion_status' => $purchase_completion_status), 'conditions' => 'id='.$_POST['delivery']['id']); 
		$Posts->EditReceiving($delivery);
		
		$purchase = array('variables' => array('completion_status' => $purchase_completion_status), 'conditions' => 'id='.$_POST['delivery']['purchase_id']);
		global $DB;
		$DB->UpdateRecord('purchases', $purchase);
		
		redirect_to($Capabilities->All['show_purchase']['url'].'?id='.$_POST['delivery']['purchase_id']);	
    break;
  
  case 'edit_receiving_items':
		$args = array('variables' => $_POST['receiving'], 'conditions' => 'id='.$_POST['rid']); 
		$num_of_records = $Posts->EditReceivingItems($args);
		
    exit();
    break;
		
	// ===============================================================
  // Post::Add Parts
  // ===============================================================
  case 'add_parts_tree':
    $pid		= $_POST['pid'];
    $code		= $_POST['code'];
    $items			= array();
		$total_amount	= 0.00;
	
	if(!empty($_POST['items'])) {
	  foreach ($_POST['items'] as $id => $attr) {
        $parts = array('product_id' => $pid, 'material_id' => $attr['material_id'], 'material_qty' => $attr['quantity'], 'remarks' => $attr['remarks']);
				// Insert new parts
				$Posts->AddPartsTree($parts);
	  }
	}
	redirect_to($Capabilities->All['show_parts_tree']['url'].'?pid='.$pid.'&code='.$code);	
    break;	
	 
	// ===============================================================
  // Post::Edit Parts
  // ===============================================================
  case 'edit_parts_tree':
    $pid		= $_POST['pid'];
    $code		= $_POST['code'];
    $items			= array();
		$total_amount	= 0.00;
	
		$Posts->RemovePartsTree(array('conditions' => 'product_id='.$pid));

		if(!empty($_POST['items'])) {
		  foreach ($_POST['items'] as $id => $attr) {
	        $parts = array('product_id' => $pid, 'material_id' => $attr['material_id'], 'material_qty' => $attr['quantity'], 'remarks' => $attr['remarks']);
					$Posts->AddPartsTree($parts);
					//$Posts->EditPartsTree(array('variables' => $parts, 'conditions' => 'id='.$pid));
		  }
		}
		redirect_to($Capabilities->All['show_parts_tree']['url'].'?pid='.$pid.'&code='.$code);	
    break;	
		
	// ===============================================================
  // Post::Add Purchase Order
  // ===============================================================
  case 'add_purchase_order':
    $purchase_order	= $_POST['purchase_order'];
    $items			= array();
		$total_amount	= 0.00;
	
		if(!empty($_POST['items'])) {
		  foreach ($_POST['items'] as $id => $attr) {
	        $item = array('item_id' => $attr['item_id'], 'item_type' => $attr['item_type'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['item_price']), 'remarks' => $attr['remarks'], 'completion_status' => 19);
		    	$total_amount += (to_double($attr['quantity']) * to_double($attr['item_price']));
	        array_push($items, $item);
		  }
		}
		
		$purchase_order['items']			= $items;
		$purchase_order['total_amount']	= $total_amount;
		
		$purchase_order_id = $Posts->AddPurchaseOrder($purchase_order);
		
		// Intialize Purchase Order
		// if($order['status'] == 11) { //published status id
			// $args = array('order_id' => $purchase_order_id, 'head_time_days' => 7); //7 days before P/O shipment
			// $num_of_records = $Posts->InitPurchaseOrder($args);	
		// }	
		
		if($purchase_order_id > 0) redirect_to(host('purchase-orders-show.php?pid='.$purchase_order_id)); 
    break;	
		
	// ===============================================================
  // Post::Edit Purchase Order
  // ===============================================================
  case 'edit_purchase_order':
    $purchase_order	= $_POST['purchase_order']; 
    $items			= array();
		$total_amount	= 0.00;
	
		if(!empty($_POST['items'])) {
		  foreach ($_POST['items'] as $id => $attr) {
	        $item = array('item_id' => $attr['item_id'], 'item_type' => $attr['item_type'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['item_price']), 'remarks' => $attr['remarks'], 'completion_status' => 19);
		    	$total_amount += (to_double($attr['quantity']) * to_double($attr['item_price']));
	        array_push($items, $item);
		  }
		}
		
		$purchase_order['items']			= $items;
		$purchase_order['total_amount']	= $total_amount;
		
		$Posts->EditPurchaseOrder($purchase_order);
		
		// Intialize Purchase Order
		// if($order['status'] == 11) { //published status id
			// $args = array('order_id' => $purchase_order_id, 'head_time_days' => 7); //7 days before P/O shipment
			// $num_of_records = $Posts->InitPurchaseOrder($args);	
		// }	
		
		redirect_to(host('purchase-orders-show.php?pid='.$purchase_order['id'])); 
    break;
		
	case 'approve_purchase_order':
		$item = array('variables' => array('approved_by' => $_POST['approved_by'], 'approved_at' => date('Y-m-d H:i:s')), 'conditions' => 'id='.$_POST['id']);
		$Posts->ApprovePurchaseOrder($item);
		break;
		
	// ===============================================================
  // Post::Add Order
  // ===============================================================
  case 'add_order':
    $order	= $_POST['order'];
    $items			= array();
		$total_amount	= 0.00;
	
		if(!empty($_POST['items'])) {
		  foreach ($_POST['items'] as $id => $attr) {
	        $item = array('item_id' => $attr['item_id'], 'item_type' => $attr['item_type'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['price']), 'remarks' => $attr['remarks']);
		    	$total_amount += (to_double($attr['quantity']) * to_double($attr['price']));
	        array_push($items, $item);
		  }
		}
		
		$order['items']			= $items;
		$order['total_amount']	= $total_amount;
		
		$order_id = $Posts->AddOrder($order);
		
		// Intialize Purchase Order
		if($order['status'] == 11) { //published status id
			$args = array('order_id' => $order_id, 'head_time_days' => 7); //7 days before P/O shipment
			$num_of_records = $Posts->InitPurchaseOrder($args);	
		}	
		
		if($order_id > 0) redirect_to(host('orders-show.php?oid='.$order_id)); 
    break;	
		
	// ===============================================================
  // Post::Edit Order
  // ===============================================================
  case 'edit_order':
    $order	= $_POST['order'];
    $items			= array();
		$total_amount	= 0.00;
			
	if(!empty($_POST['items'])) {
	  foreach ($_POST['items'] as $id => $attr) {
        $item = array('item_id' => $attr['item_id'], 'item_type' => $attr['item_type'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['price']), 'remarks' => $attr['remarks']);
	    	$total_amount += (to_double($attr['quantity']) * to_double($attr['price']));
        array_push($items, $item);
	  }
	}
	
	$order['items']			= $items;
	$order['total_amount']	= $total_amount;
	
	$order_id = $Posts->EditOrder($order);
	
	// Intialize Purchase Order
	if($order['status'] == 11) { //published status id
		$args = array('order_id' => $order['id'], 'head_time_days' => 7); //7 days before P/O shipment
		$num_of_records = $Posts->InitPurchaseOrder($args);	
	}	
	if($order_id > 0) redirect_to(host('orders-show.php?oid='.$order['id'])); 
    break;	
	
	// ===============================================================
  // Post::Add Work Order
  // ===============================================================
  case 'add_work_order':
    $work_order	= $_POST['work_order'];
    $items			= array();
		$total_amount	= 0.00;
	
		if(!empty($_POST['items'])) {
		  foreach ($_POST['items'] as $id => $attr) {
	        $item = array('product_id' => $attr['product_id'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['price']), 'remarks' => $attr['remarks']);
		    	$total_amount += (to_double($attr['quantity']) * to_double($attr['price']));
	        array_push($items, $item);
		  }
		}
		
		$work_order['items']			= $items;
		$work_order['total_amount']	= $total_amount;
		$work_order['completion_status'] = 19; // pending
		
		$work_order_id = $Posts->AddWorkOrder($work_order);
		
		// Intialize Purchase Order
		// if($order['status'] == 11) { //published status id
			// $args = array('order_id' => $order_id, 'head_time_days' => 7); //7 days before P/O shipment
			// $num_of_records = $Posts->InitPurchaseOrder($args);	
		// }	
		
		if($work_order_id > 0) redirect_to(host('work-orders-show.php?wid='.$work_order_id)); 
    break;	
	
	// ===============================================================
  // Post::Edit Work Order
  // ===============================================================
  case 'edit_work_order':
    $work_order	= $_POST['work_order'];
    $items			= array();
		$total_amount	= 0.00;
			
		if(!empty($_POST['items'])) {
		  foreach ($_POST['items'] as $id => $attr) {
	        $item = array('product_id' => $attr['product_id'], 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['price']), 'remarks' => $attr['remarks']);
		    	$total_amount += (to_double($attr['quantity']) * to_double($attr['price']));
	        array_push($items, $item);
		  }
		}
		
		$work_order['items']			= $items;
		$work_order['total_amount']	= $total_amount;
		
		$work_order_id = $Posts->EditWorkOrder($work_order);
		
		// Intialize Purchase Order
		// if($order['status'] == 137) { //published status id
			// $args = array('order_id' => $order['id'], 'head_time_days' => 7); //7 days before P/O shipment
			// $num_of_records = $Posts->InitPurchaseOrder($args);	
		// }	
		if($work_order_id > 0) redirect_to(host('work-orders-show.php?wid='.$work_order['id'])); 
    break;	
		
	// ===============================================================
  // Post::Parts Request
  // ===============================================================
  case 'parts_request':
		$parts = array();
		foreach($_POST['parts'] as $id => $attr) {
			if(isset($attr['requested'])) {
				if($attr['expected_datetime'] == '') {
					$attr['expected_datetime'] = date('Y-m-d H:i:s');
				}
				$parts = array('is_requested' => 1, 'plan_qty' => $attr['plan_qty'], 'expected_datetime' => strdate($attr['expected_datetime'], 'Y-m-d H:i:s'), 'status' => 149);
				$args = array('variables' => $parts, 'conditions' => 'id='.$attr['id']); 
				$num_of_records = $Posts->EditProductionPlanParts($args);
				
			
			}			
		}
		break;	
		
	// ===============================================================
  // Post::Delete Terminal
  // ===============================================================
  case 'delete_terminal':
		echo $DB->DeleteRecord('terminals', array('conditions' => 'id='.$_POST['terminal_id']));
		redirect_to(host('terminals.php')); 
    break;
		
	// ===============================================================
  // Post::Roles
  // ===============================================================
  // Add Role
  case 'add_role': 
		$id = $Posts->AddRole($_POST['role']);
		if(isset($id)) {
			if(isset($_POST['caps'])) {
				foreach ($_POST['caps'] as $cap) {
					$Posts->AddCapability(array('role_id' => $id, 'capability_id' => $cap));
				}
			}
			redirect_to($Capabilities->All['show_role']['url'].'?rid='.$id);
		}
    break;
	
  // Edit Role
  case 'edit_role':
		$args = array('variables' => $_POST['role'], 'conditions' => 'id='.$_POST['rid']); 
		$num_of_records = $Posts->EditRole($args);
		
		$Posts->DeleteCapability(array('conditions' => 'role_id='.$_POST['rid']));
		
		if(isset($_POST['caps'])) {
			foreach ($_POST['caps'] as $cap) {
				$Posts->AddCapability(array('role_id' => $_POST['rid'], 'capability_id' => $cap));
			}
		}
		
		redirect_to($Capabilities->All['show_role']['url'].'?rid='.$_POST['rid']);
    break;
		
  // Delte Role
  case 'delete_role':
		$Posts->DeleteCapability(array('conditions' => 'role_id='.$_POST['rid']));
		$Posts->DeleteRole(array('conditions' => 'id='.$_POST['rid']));		
		redirect_to($Capabilities->All['roles']['url']);
    break;
	
	// case 'init_forecast_calendar':
	  // global $DB;
	  // $data = $DB->Fetch('forecasts', array('columns' => 'created_at', 'conditions' => 'product_id='.$_GET['pid'], 'limit' => 1));
		// if(!empty($data)) {
			// if(date('Y', strtotime($data['created_at'])) == date('Y')) {
// 				
			// }
		// }
// 		
		// $ctr = 1; 
		// for($i=0; $i<=52; $i++){
			// if(date("Y", strtotime(' Thursday +'.$i.' week', strtotime(date('Y').'-01-01'))) == date('Y')) {
				// $Posts->InitForecastCalendar(array('week' => 'Week-'.$ctr, 'day' => date("Y-m-d", strtotime(' Thursday +'.$i.' week', strtotime(date('Y').'-01-01')))));
				// $ctr+=1;
			// }
		// }
		// break;
	case 'edit_forecast_h1':
		$forecast = array();
		$items = array();
		
		foreach($_POST['items'] as $id => $attr) {
			$item = array('product_id' => $attr['product_id'],'jan' => $attr['jan'], 'feb' => $attr['feb'], 'mar' => $attr['mar'], 
												'apr' => $attr['apr'], 'may' => $attr['may'], 'jun' => $attr['jun']);
			array_push($items, $item);
		}
		$forecast['type'] = 'h1';
		$forecast['forecast_year'] = $_POST['forecast_year'];
		$forecast['items'] = $items;

		$Posts->EditForecastCalendar($forecast); 
		redirect_to($Capabilities->All['show_forecast_calendar_h1']['url']);
		break;
		
	case 'edit_forecast_h2':
		$forecast = array();
		$items = array();
		
		foreach($_POST['items'] as $id => $attr) {
			$item = array('product_id' => $attr['product_id'],'jul' => $attr['jul'], 'aug' => $attr['aug'], 'sep' => $attr['sep'], 
												'oct' => $attr['oct'], 'nov' => $attr['nov'], 'dece' => $attr['dece']);
			array_push($items, $item);
		}
		$forecast['type'] = 'h2';
		$forecast['forecast_year'] = $_POST['forecast_year'];
		$forecast['items'] = $items;
		
		$Posts->EditForecastCalendar($forecast);
		redirect_to($Capabilities->All['show_forecast_calendar_h2']['url']);
		break;
		
	case 'init_forecast':
		$args = array('forecast_year'=>$_POST['forecast_year']);
		$Posts->InitForecast($args);
		break;
		
	case 'add_forecast':
		$args = array('forecast_year'=>$_POST['forecast_year'], 'product_id'=>$_POST['product_id']);
		$Posts->AddForecast($args);
		break;
		
	case 'edit_forecast':
		foreach ($_POST['forecast'] as $forecast) {
			if(isset($forecast['qty']) && $forecast['qty'] != 0) {
				if($forecast['delivery_date'] == NULL) {
					unset($forecast['delivery_date']); 
				} else {
					$forecast['delivery_date'] = date('Y-m-d', strtotime($forecast['delivery_date']));
				}
				if($forecast['ship_date'] == NULL) {
					unset($forecast['ship_date']); 
				} else {
					$forecast['ship_date'] = date('Y-m-d', strtotime($forecast['ship_date']));
				}
				if($forecast['prod_date'] == NULL) {
					unset($forecast['prod_date']); 
				} else {
					$forecast['prod_date'] = date('Y-m-d', strtotime($forecast['prod_date']));
				}
				
				$args = array('variables' => $forecast, 'conditions' => 'id='. $forecast['id']); 
				$num_of_records = $Posts->EditForecast($args);	
				
				
				
				//add shipment plan
				$shipment_plan = $DB->Find('shipment_plans', array(
			  		'columns' => 'id', 
		  	    'conditions' => 'type="FC" AND ctrl_id ='.$forecast['id']
			  	  ));	
				if(isset($shipment_plan)) {
					$Posts->EditShipmentPlan(array('variables'=>array('ctrl_no' => $forecast['ctrl_no'], 'ship_date' => $forecast['ship_date'], 'prod_date' => $forecast['prod_date'],'qty' => $forecast['qty'], 'remarks' => $forecast['remarks']), 
																					'conditions'=>'id='.$shipment_plan['id']));
				} else {
					$Posts->AddShipmentPlan(array('type' => 'FC', 'ctrl_no' => $forecast['ctrl_no'], 'ctrl_id' => $forecast['id'], 'item_id' => $forecast['product_id'], 'item_type' => 'PRD',
															'ship_date' => $forecast['ship_date'], 'prod_date' => $forecast['prod_date'],'qty' => $forecast['qty'], 'remarks' => $forecast['remarks']));
				}
			}
		}
		
		break;
		
	case 'add_minventory_items':
		$item = $_POST['inventory'];
		$Posts->AddInventory(array('item_id' => $item['item_id'], 'item_type' => 'MAT', 'invoice' => $item['invoice_no'],  
																'lot' => $item['lot_no'], 'qty' => $item['qty'], 'remarks' => $item['remarks'])); 
		break;
	
	case 'edit_minventory_items':
		$items = array('qty' => $_POST['inventory']['qty'], 'remarks' => $_POST['inventory']['remarks']);
		$args = array('variables' => $items, 'conditions' => 'id='.$_POST['inventory']['id']); 
		$num_of_records = $Posts->EditInventory($args);
		break;
		
	case 'add_actual_minventory_items':
		$items = $_POST['actual'];
		
		//clear same date counting
		$DB->DeleteRecord('warehouse_inventory_actual', array('conditions' => 'EXTRACT(YEAR_MONTH FROM entry_date) = EXTRACT(YEAR_MONTH FROM "'.date('Y-m-d', strtotime($_POST['entry_date'])).'")'.' AND item_id='.$_POST['mid']));
		
		foreach($items as $item) {
			$Posts->AddActualInventory(array('inventory_id' => $item['id'], 'item_id' => $_POST['mid'], 'item_type' => 'MAT', 'invoice' => $item['invoice_no'],  
																'lot' => $item['lot_no'], 'qty' => $item['qty'], 'remarks' => $item['remarks'], 'entry_date' => $_POST['entry_date']));
		}
		redirect_to($Capabilities->All['show_material_inventory']['url'].'?id='.$_POST['mid']);
		break;
		
	case 'update_inventory_count':
		// update inventory with physical counts
		$Posts->UpdateInventoryCount(array('mydate' => $_POST['mydate']));
		// update status flag
		$args = array('variables' => array('is_updated' => 1, 'status' => 'CLOSE'), 
									'conditions' => 'type="'.$_POST['type'].'" AND EXTRACT(YEAR_MONTH FROM current_month) = EXTRACT(YEAR_MONTH FROM "'.$_POST['mydate'].'")'); 
		$Posts->UpdateInventoryStatus($args); 
		break;
		
	case 'update_inventory_count_date_and_lock':
		$args = array('variables' => array('count_month' => $_POST['mydate'], 'is_locked' => 1), 
									'conditions' => 'type="'.$_POST['type'].'" AND EXTRACT(YEAR_MONTH FROM current_month) = EXTRACT(YEAR_MONTH FROM "'.$_POST['mydate'].'")');  
		$Posts->UpdateInventoryStatus($args);
		break;
	
	case 'update_inventory_and_unlock':
		$args = array('variables' => array('is_locked' => 0), 
									'conditions' => 'type="'.$_POST['type'].'" AND EXTRACT(YEAR_MONTH FROM current_month) = EXTRACT(YEAR_MONTH FROM "'.$_POST['mydate'].'")');  
		$Posts->UpdateInventoryStatus($args);
		break;
		
	case 'add_shipment_plan':
		$plan = $_POST['plan'];
		$Posts->AddShipmentPlan(array('type' => $plan['type'], 'ctrl_no' => $plan['ctrl_no'], 'ctrl_id' => $plan['ctrl_id'], 'item_id' => $plan['item_id'], 'item_type' => $plan['item_type'],
															'ship_date' => $plan['ship_date'], 'prod_date' => $plan['prod_date'],'qty' => $plan['qty'], 'remarks' => $plan['remarks'])); 
		break;
		
	case 'edit_shipment_plan':
		$DB->DeleteRecord('shipment_plans', array('conditions' => 'ctrl_id='.$_POST['ctrl_id'].' AND item_id='.$_POST['pid']));
		
		$plan = $_POST['plan'];
		foreach($plan as $item) {
			$items = array();
			$items['type'] = $_POST['type'];
			$items['ctrl_id'] = $_POST['ctrl_id'];
			$items['ctrl_no'] = $_POST['ctrl_no'];
			$items['item_id'] = $_POST['pid'];
			$items['item_type'] = 'PRD';
			$items['ship_date'] = $item['ship_date'];
			$items['prod_date'] = $item['prod_date'];
			$items['qty'] = $item['qty'];
			$items['remarks'] = $item['remarks'];
			if($item['prod_date']=='') unset($items['prod_date']);  
			$Posts->AddShipmentPlan($items);
		}
		// $plan = $_POST['plan']; 
		// foreach ($plan as $item) {
			// if($item['prod_date']=='') unset($item['prod_date']);  
			// $args = array('variables' => $item, 'conditions' => 'id='. $item['id']); 
			// $num_of_records = $Posts->EditShipmentPlan($args);
		// } 
		redirect_to($Capabilities->All['show_plan_order_model_shipment']['url'].'?ctrl_id='.$_POST['ctrl_id'].'&pid='.$_POST['pid'].'&t='.$_POST['type']);
		break;
		
	case 'edit_forecast_days':
		var_dump($_POST); 
		die();
		break;
		
	case 'add_product_inventory':
		$Posts->AddProductInventory($_POST['inventory']);
		break;
	
	case 'edit_lookup':
		$Posts->EditLookup(array('variables'=>array('description' => $_POST['lookup-title']), 'conditions' => 'id='.$_POST['lookup-id']));
		break;
		
	case 'remove_lookup':
		$Posts->DeleteLookup(array('conditions' => 'id='.$_POST['lookup-id-remove']));		
		break;
	
  } // close switch

  
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    <meta charset="utf-8">
		<title>CERP - <?php echo $Capabilities->GetTitle(); ?></title>
    <meta name="description" content="">
    <meta name="author" content="">
		<link rel="stylesheet" href="../stylesheets/main.css" />
		<link rel="stylesheet" href="../stylesheets/form.css" />
		<link rel="stylesheet" href="../stylesheets/grid.css" />
		<link rel="stylesheet" href="../stylesheets/jquery.css" />
    <link rel="stylesheet" href="../stylesheets/simplePagination.css">
		<link rel="stylesheet" href="../stylesheets/jquery.modal.css" />
<!-- 		<link rel="stylesheet" href="../stylesheets/smoothness/jquery-ui-1.9.2.custom.css" /> -->
<!-- 		<link rel="stylesheet" href="../stylesheets/custom/jquery-ui-1.10.1.custom.css" /> -->
		<link rel="stylesheet" href="../stylesheets/custom/jquery-ui-1.10.3.custom.css" />
		
    <script src="../javascripts/jquery-1.7.1.min.js"></script>
<!-- 		<script src="../javascripts/jquery-ui-1.8.21.custom.min.js"></script> -->
		<script src="../javascripts/jquery-ui-1.10.1.custom.min.js"></script>
		<script src="../javascripts/jquery-ui-timepicker-addon.js"></script>		
<!-- 		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
    <script src="../javascripts/jquery.modal.min.js"></script>
    <script src="../javascripts/jquery.formatCurrency-1.4.0.js"></script>
    <script src="../javascripts/jquery.formatCurrency.all.js"></script>
    <script src="../javascripts/jquery.loadingdotdotdot.js"></script>
    <script src="../javascripts/jquery.simplePagination.js"></script>
    <script src="../javascripts/application.js"></script>
	</head>
	
	<body>
    <div id="container">
      <!-- BOF HEADER -->
      <div id="header">
				<a id="brand" href="index.php" style="text-decoration: none">CERP</a>
			</div>
			
      <div id="main-menu">
        <div class="menu">
        	<a href="#" alt="#menu-items" class="show-submenu">Items</a>
        	<div id="menu-items" class="main-sub-menu">      			
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="materials.php">Materials</a></li>
        	    <li><a href="indirect-materials.php">Indirect Materials</a></li>
        	    <li><a href="minventory.php">Materials Inventory</a></li>
        	    <li><a href="products.php">Products</a></li>
        	    <li><a href="pinventory.php">Products Inventory</a></li>
        	    <li><a href="manage-warehouse.php">Manage</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-plan" class="show-submenu">Planning</a>
        	<div id="menu-plan" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	  	<li><a href="plan-overview-show.php">Overview</a></li>
        	    <li><a href="forecasts-show.php">Forecast</a></li>
        	    <li><a href="purchase-orders.php">Purchase Orders</a></li>
        	    <li><a href="work-orders.php">Work Orders</a></li>
        	    <li><a href="plan-orders.php">Plan Orders</a></li>
        	    <li><a href="plan-shipment-calendar.php">Shipment Plan Calendar</a></li>
        	    <li><a href="plan-production-calendar.php">Production Plan Calendar</a></li>
        	    <li><a href="plan-stock-calendar.php">Stock Plan Calendar</a></li>
        	    <li><a href="material-plan.php">Material Plan</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-purchasing" class="show-submenu">Puchasing</a>
        	<div id="menu-purchasing" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="purchases.php">Purchases</a></li>
        	    <li><a href="deliveries.php">Deliveries</a></li>
        	    <li><a href="invoices.php">Invoices</a></li>
        	    <li><a href="suppliers.php">Suppliers</a></li>
        	    <li><a href="manage-purchasing.php">Manage</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-production" class="show-submenu">Production</a>
        	<div id="menu-production" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <!-- <ul>
        	    <li><a href="material-requests.php">Material Requests</a></li>
        	    <li><a href="terminal-production.php">Terminal Entry</a></li>
        	    <li><a href="#">Requests</a></li>
        	    <li><a href="#">Transfers</a></li>
        	    <li><a href="#">Sampling Logs</a></li>
        	    <li><a href="#">Monitoring</a></li>
        	    <li><a href="#">Defects</a></li>
        	    <li><a href="manage-production.php">Manage</a></li>
        	  </ul> -->
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-terminals" class="show-submenu">Terminals</a>
        	<div id="menu-terminals" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="terminals.php">Terminals</a></li>
        	    <li><a href="locations.php">Locations</a></li>
        	    <li><a href="devices.php">Devices</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-defects" class="show-submenu">Defects</a>
        	<div id="menu-defects" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <!-- <ul>
        	    <li><a href="defects.php">Defects</a></li>
        	    <li><a href="defects.php">Reworks</a></li>
        	    <li><a href="defects.php">Machines</a></li>
        	    <li><a href="defects.php">Tracking</a></li>
        	    <li><a href="defects.php">OEE</a></li>
        	    <li><a href="defect-reports.php">Reports</a></li>
        	  </ul> -->
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-preferences" class="show-submenu">Preferences</a>
        	<div id="menu-preferences" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="users.php">Users</a></li>
        	    <li><a href="roles.php">Roles</a></li>
        	    <li><a href="notifications.php">Notifications</a></li>
        	    <li><a href="settings-lookups-show.php">Lookups</a></li>
        	    <li><a href="settings-show.php">Settings</a></li>
        	    <li><a href="settings.php">Advanced</a></li>
        	  </ul>
        	</div>
        </div>
      </div>
        
      <div id="menu-profile">
         <div class="avatar small"></div>
         <span><?php echo $Signed['first_name']." ". $Signed['last_name']; ?> </span>
         
      </div>
      <div class="profile-sub-menu">
				<div class="glyphicons-halflings"></div>
					<ul>
        	    <li><a href="profile-show.php">Profile</a></li>
             <li><a href="/<?php echo $Host; ?>?signout=true">Sign Out</a></li>
          </ul>
			</div>
			
    	<div id="header_fade" class="fade_up"></div>
    </div>