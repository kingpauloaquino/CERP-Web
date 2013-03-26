<?php
require('../include/general.class.php');
  
request_populate_data();

function request_populate_data() {
  global $JSON;
  if($_SERVER['REQUEST_METHOD'] == "GET" and $_GET['format'] == "json") {
    $populate = $_GET['format'];
    // ===============================================================
    // Populate::Materials
    // ===============================================================
    $data = populate_materials();
    $JSON->build_pretty_json($data);
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

  // ===============================================================
  // Post::Add Role
  // ===============================================================
  case 'add_role':
    $role_name = ucwords(strtolower(($_POST['role']['name'])));
	  
    if($Posts->IsUnique('roles','name', $role_name) == 1) {
      echo '<p class="error">Sorry, but role name: <b>'.$role_name.'</b> is already used</p>';
	} else {
      $role_id = $Posts->AddRole($_POST['role']);
      $tag .= '<p class="success">You have successfully added: <b>'.$role_name.'</b> in your roles</p>';
	  $tag .= '<script>$("#role_name").val("");$("#roles").append("<option value=\"'.$role_id.'\">'.$role_name.'</option>");</script>';
	  echo $tag;
	}
    exit(); break;

  // ===============================================================
  // Post::Add Role
  // ===============================================================
  case 'edit_role':
    $role_id = ucwords(strtolower(($_POST['role']['id'])));
	  
	$Posts->EditRole(implode(",", $_POST['capability']), 'id = '.$role_id);
    echo '<p class="success">You have successfully updated your role capabilities</p>';
	exit(); break;
	
  // ===============================================================
  // Post::Add Purchase
  // ===============================================================
  case 'add_purchase';
    $purchase		= $_POST['purchase'];
    $items			= array();
		$total_amount	= 0.00;
	
	if(!empty($_POST['items'])) {
	  foreach ($_POST['items'] as $id => $attr) {
        $item = array('item_id' => $id, 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['price']));
	    $total_amount += (to_double($attr['quantity']) * to_double($attr['price']));
        array_push($items, $item);
	  }
	}
	
	$purchase['items']			= $items;
	$purchase['total_amount']	= $total_amount;
	
	// Insert new purchase record
	$purchase_id = $Posts->AddPurchase($purchase);
	// If successfully added new purchase, redirect to display page
	if($purchase_id > 0) redirect_to(host('purchases-show.php?id='.$purchase_id)); 
    break;
	
  // ===============================================================
  // Post::Edit Purchase
  // ===============================================================
  case 'edit_purchase';
    $purchase		= $_POST['purchase'];
    $items			= array();
		$total_amount	= 0.00;
	
	if(!empty($_POST['items'])) {
	  foreach ($_POST['items'] as $id => $attr) {
        $item = array('item_id' => $id, 'quantity' => $attr['quantity'], 'item_price' => to_double($attr['price']));
	    $total_amount += (to_double($attr['quantity']) * to_double($attr['price']));
        array_push($items, $item);
	  }
	}
	
	$purchase['items']			= $items;
	$purchase['total_amount']	= $total_amount;
	
	// Update purchase record
	$purchase_id = $Posts->EditPurchase($purchase);
	// If successfully added new purchase, redirect to display page
	if($purchase_id > 0) redirect_to(host('purchases-show.php?id='.$purchase['id']));
    break;
	
  // ===============================================================
  // Post::Add Delivery
  // ===============================================================
  case 'add_delivery';
  	echo $Posts->AddDelivery($_POST['delivery']);
  	exit();
  	break;
	
  // ===============================================================
  // Post::Update Delivery
  // ===============================================================
  case 'edit_delivery';
    $delivery			= $_POST['delivery'];
		$delivery['items']	= $_POST['items'];
	
  	$delivery_id = $Posts->UpdateDelivery($delivery);
	if($delivery_id > 0) redirect_to(host('deliveries-show.php?id='.$delivery['id']));
  	break;
  
  // ===============================================================
  // Post::Add Receiving
  // ===============================================================
  case 'add_receiving';
    echo $Posts->AddReceiving($_POST['receiving']);
    exit();
    break;
		
	// ===============================================================
  // Post::Add Parts
  // ===============================================================
  case 'add_parts_tree';
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
  case 'edit_parts_tree';
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
  // Post::Add Order
  // ===============================================================
  case 'add_order';	
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
		if($order['status'] == 137) { //published status id
			$args = array('order_id' => $order_id, 'head_time_days' => 7); //7 days before P/O shipment
			$num_of_records = $Posts->InitPurchaseOrder($args);	
		}	
		
		if($order_id > 0) redirect_to(host('orders-show.php?oid='.$order_id)); 
    break;	
		
	// ===============================================================
  // Post::Edit Order
  // ===============================================================
  case 'edit_order';
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
	if($order['status'] == 137) { //published status id
		$args = array('order_id' => $order['id'], 'head_time_days' => 7); //7 days before P/O shipment
		$num_of_records = $Posts->InitPurchaseOrder($args);	
	}	
	if($order_id > 0) redirect_to(host('orders-show.php?oid='.$order['id'])); 
    break;	
		
	// ===============================================================
  // Post::Parts Request
  // ===============================================================
  case 'parts_request';
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
  case 'delete_terminal'; 
		echo $DB->DeleteRecord('terminals', array('conditions' => 'id='.$_POST['terminal_id']));
		redirect_to(host('terminals.php')); 
    break;
  } // close switch

}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    <meta charset="utf-8">
		<title><?php echo $Title; ?> - CERP</title>
    <meta name="description" content="">
    <meta name="author" content="">
		<link rel="stylesheet" href="../stylesheets/main.css" />
		<link rel="stylesheet" href="../stylesheets/form.css" />
		<link rel="stylesheet" href="../stylesheets/grid.css" />
		<link rel="stylesheet" href="../stylesheets/jquery.css" />
    <link rel="stylesheet" href="../stylesheets/simplePagination.css">
		<link rel="stylesheet" href="../stylesheets/jquery.modal.css" />
<!-- 		<link rel="stylesheet" href="../stylesheets/smoothness/jquery-ui-1.9.2.custom.css" /> -->
		<link rel="stylesheet" href="../stylesheets/smoothness/jquery-ui-1.10.1.custom.css" />
		
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
        	<a href="#" alt="#menu-materials" class="show-submenu">Materials</a>
        	<div id="menu-materials" class="main-sub-menu">

      			
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="materials.php">Materials</a></li>
        	    <li><a href="materials-rev.php">Material Revision</a></li>
        	    <li><a href="indirect-materials.php">Indirect Materials</a></li>
        	    <li><a href="minventory.php">Warehouse Inventory</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
        	<a href="#" alt="#menu-products" class="show-submenu">Products</a>
        	<div id="menu-products" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="products.php">List</a></li>
        	    <li><a href="pinventory.php">Warehouse Inventory</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-purchasing" class="show-submenu">Puchasing</a>
        	<div id="menu-purchasing" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="orders.php">Orders</a></li>
        	    <li><a href="deliveries.php">Deliveries</a></li>
        	    <li><a href="receiving.php">Receiving</a></li>
        	    <li><a href="purchases.php">Purchases</a></li>
        	    <li><a href="suppliers.php">Suppliers</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-production" class="show-submenu">Production</a>
        	<div id="menu-production" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="production-plan.php">Production Plan</a></li>
        	    <li><a href="material-requests.php">Material Requests</a></li>
        	    <li><a href="terminal-production.php">Terminal Entry</a></li>
        	    <li><a href="#">Requests</a></li>
        	    <li><a href="#">Transfers</a></li>
        	    <li><a href="#">Sampling Logs</a></li>
        	    <li><a href="#">Monitoring</a></li>
        	    <li><a href="#">Defects</a></li>
        	    <li><a href="#">Reports</a></li>
        	  </ul>
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
        	  <ul>
        	    <li><a href="defects.php">Defects</a></li>
        	    <li><a href="defects.php">Reworks</a></li>
        	    <li><a href="defects.php">Machines</a></li>
        	    <li><a href="defects.php">Tracking</a></li>
        	    <li><a href="defects.php">OEE</a></li>
        	    <li><a href="defect-reports.php">Reports</a></li>
        	  </ul>
        	</div>
        </div>
        <div class="menu">
          <a href="#" alt="#menu-preferences" class="show-submenu">Preferences</a>
        	<div id="menu-preferences" class="main-sub-menu">
            <div class="glyphicons-halflings"></div>
        	  <ul>
        	    <li><a href="users.php">Users</a></li>
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
             <li><a>Account Settings</a></li>
             <li><a href="/<?php echo $Host; ?>?signout=true">Sign Out</a></li>
          </ul>
			</div>
    </div>