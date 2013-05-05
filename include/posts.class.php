<?php
class Posts {
  private $DB;
  
  // Constructor
  function Posts($db) {
  	$this->DB = $db;
  }
  
  private function EscQuery($str) {
    return $str;
    // return mysql_real_escape_string($str);
  }
  
  private function DateTimeNow($format='Y-m-d H:i:s') {
  	return date($format, time());
  }
  
  // Function::IsUnique
  // Descriptions: Validate unique column value
  // Parameters: Table Name, Column Name, Parameter Value
  // Return: Boolean
  function IsUnique($table, $column, $value) {
  	$rows = $this->DB->Find($table, array('conditions' => $column.' = "'.$this->EscQuery($value).'"'));
	
	if(!empty($rows)) return 1;
	return 0;
  }
  
  // Posts::Authenticate
  // Descriptions: Validate user credentials
  // Parameters: Param[Employee ID, Password]
  // Return: User Information
  function Authenticate($params) {
  	$employee_id = $this->EscQuery($params['employee_id']);
	$password = md5($this->EscQuery($params['password']));
	$user = $this->DB->Find('users', array(
	  'columns' => 'id, employee_id, first_name, last_name, position, email, status',
	  'conditions' => "employee_id = '".$employee_id."' AND password = '".$password."'")
	);
	
    return $user;  
  }
  
  // Posts::AddUser
  // Descriptions: Use to create new user
  // Return: ID
  function AddUser($params) {
    $user = array(
		  'employee_id'	=> strtoupper($params['employee_id']),
		  'password'		=> md5($params['password']),
		  'first_name'	=> ucwords(strtolower($params['first_name'])),
		  'last_name'		=> ucwords(strtolower($params['last_name'])),
		  'email'				=> $params['email'],
		  'position'		=> ucwords(strtolower($params['position'])),
		  'description'	=> ucwords(strtolower($params['description'])),
		  'status'			=> $params['status']
		);  
    return $this->DB->InsertRecord('users', $user);
  }
	
	function EditUser($params) {
		if(isset($params['variables']['password'])) {
			$params['variables']['password'] = md5($params['variables']['password']);	
		}
    return $this->DB->UpdateRecord('users', $params);
  }
  
  function AddUserRole($params) {
    $items = array(
		  'user_id'	=> $params['user_id'],	  
		  'role_id'	=> $params['role_id']
		);
    return $this->DB->InsertRecord('user_roles', $items);
	}
	
	function EditUserRole($params) {
    return $this->DB->UpdateRecord('user_roles', $params);
	}
  
  function AddRole($params) {
    $items = array(
		  'name'	=> $params['name'],	  
		  'description'	=> $params['description']
		);
    return $this->DB->InsertRecord('roles', $items);
	}
	
	function EditRole($params) {
    return $this->DB->UpdateRecord('roles', $params);
	}
	
	function DeleteRole($params) {
    return $this->DB->DeleteRecord('roles', $params);
	}
	
	function AddCapability($params) {
    $items = array(
		  'role_id'	=> $params['role_id'],	  
		  'capability_id'	=> $params['capability_id']
		);
    return $this->DB->InsertRecord('role_capabilities', $items);
	}
	
	function DeleteCapability($params) {
    return $this->DB->DeleteRecord('role_capabilities', $params);
	}
	
	function AddSupplier($params) {
    $supplier = array(
		  'supplier_code'		=> $params['supplier_code'],
		  'name'						=> ucwords(strtolower($params['name'])),
		  'address'					=> ucwords(strtolower($params['address'])),
		  'country'					=> $params['country'],
		  'representative'	=> ucwords(strtolower($params['representative'])),
		  'contact_no1'			=> $params['contact_no1'],
		  'contact_no2'			=> $params['contact_no2'],
		  'fax_no'					=> $params['fax_no'],
		  'email'						=> $params['email'],
		  'supplier_type'		=> $params['supplier_type'],
		  'product_service'	=> $params['product_service'],
		  'term_of_payment'	=> $params['term_of_payment'],
		  'description'			=> $params['description']
		);
    return $this->DB->InsertRecord('suppliers', $supplier);
  }
	
	function EditSupplier($params) {
    return $this->DB->UpdateRecord('suppliers', $params);
  }
	
	function AddMaterial($params) {
    $material = array(
		  'base'										=> $params['base'],
		  'parent'									=> $params['parent'],
		  'material_code'						=> strtoupper($params['material_code']),
		  'bar_code'								=> $params['bar_code'],	
		  'material_type'						=> $params['material_type'],	
		  'material_classification'	=> $params['material_classification'],		  
		  'description'							=> $params['description'],
		  'brand_model'							=> $params['brand_model'],
		  'person_in_charge'				=> $params['person_in_charge'],
		  'status'									=> $params['status'],
		  'defect_rate'							=> $params['defect_rate'],
		  'production_entry_terminal_id' => $params['production_entry_terminal_id'] 
		);
    return $this->DB->InsertRecord('materials', $material);
  }
	
	function EditMaterial($params) {
    return $this->DB->UpdateRecord('materials', $params);
  }

	function AddIndirectMaterial($params) {
    $material = array(
		  'material_code'						=> strtoupper($params['material_code']),
		  'bar_code'								=> $params['bar_code'],	
		  'material_type'						=> $params['material_type'],	
		  'material_classification'	=> $params['material_classification'],		  
		  'description'							=> $params['description'],
		  'person_in_charge'				=> $params['person_in_charge'],
		  'status'									=> $params['status'],
		  'production_entry_terminal_id' => $params['production_entry_terminal_id'] 
		);
    return $this->DB->InsertRecord('materials', $material);
  }

  function AddMaterialRev($params) {
    $material = array(
		  'revision'					=> $params['revision'],
		  'material_id'				=> $params['material_id'],
		  'base_material_id'	=> $params['base_material_id']
		);
    return $this->DB->InsertRecord('material_revisions', $material);
  }
	
	function EditMaterialRev($params) {
    return $this->DB->UpdateRecord('material_revisions', $params);
  }
	
	function AddItemCost($params) {
    $item_cost = array(
		  'item_id'		=> $params['item_id'],
		  'item_type'	=> $params['item_type'],
		  'supplier'	=> $params['supplier'],		  
		  'unit'			=> $params['unit'],
		  'currency'	=> $params['currency'],
		  'cost'			=> $params['cost'],
		  'moq'				=> $params['moq'],
		  'transportation_rate'	=> $params['transportation_rate']
		);
    return $this->DB->InsertRecord('item_costs', $item_cost);
  }
	
	function EditItemCost($params) {
    return $this->DB->UpdateRecord('item_costs', $params);
  }
	
	function AddProduct($params) {
    $product = array(
		  'product_code'						=> $params['product_code'],		  
		  'product_classification'	=> $params['product_classification'],		  
		  'brand_model'							=> $params['brand_model'],	  
		  'description'							=> $params['description'],  
		  'color'										=> $params['color'], 
		  'bar_code'								=> $params['bar_code'],
		  'status'									=> $params['status']
		);
    return $this->DB->InsertRecord('products', $product);
  }
	
	function EditProduct($params) {
    return $this->DB->UpdateRecord('products', $params);
  }

	function AddInventory($params) {
    $inventory = array(
		  'item_id'					=> $params['item_id'],	
		  'item_type'				=> $params['item_type'],	  
		  'description'			=> $params['description'],	  
		  'current_qty'			=> $params['current_qty'],		  
		  'reorder_level'		=> $params['reorder_level'],		  
		  'reorder_qty'			=> $params['reorder_qty'],	
		);
    return $this->DB->InsertRecord('item_inventories', $inventory);
  }
	
	function EditInventory($params) {
    return $this->DB->UpdateRecord('item_inventories', $params);
  }
	
	function AddInventoryLocations($params) {
    $inventory = array(
		  'inventory_id'				=> $params['inventory_id'],	
		  'location_address_id'	=> $params['location_address_id'],	
		  'terminal_id'					=> $params['terminal_id'],	
		  'terminal_device_id'	=> $params['terminal_device_id'],	
		  'status'		=> $params['status'],
		  'quantity'	=> $params['quantity'],
		  'remarks'		=> $params['remarks'],
		);
    return $this->DB->InsertRecord('item_inventory_locations', $inventory);
  }
	
	function EditInventoryLocations($params) {
    return $this->DB->UpdateRecord('item_inventory_locations', $params);
  }
	
	function AddInventoryHistory($params) {
    $inventory = array(
		  'inventory_id'				=> $params['inventory_id'],	
		  'location_address_id'	=> $params['location_address_id'],	
		  'terminal_id'					=> $params['terminal_id'],	
		  'terminal_device_id'	=> $params['terminal_device_id'],	
		  'inventory_status'		=> $params['inventory_status'],
		  'inventory_status_value'	=> $params['inventory_status_value'],
		  'remarks'		=> $params['remarks'],
		);
    return $this->DB->InsertRecord('item_inventory_location_history', $inventory);
  }
	
	function AddPartsTree($params) {
    $parts_tree = array(
		  'product_id'		=> $params['product_id'],	  
		  'material_id'		=> $params['material_id'],	  
		  'material_qty'	=> $params['material_qty'],		  
		  //'unit'					=> $params['unit'],		  
		  //'supplier_id'		=> $params['supplier_id'],		  
		  'remarks'				=> $params['remarks'],	
		);
    return $this->DB->InsertRecord('products_parts_tree', $parts_tree);
  }
	
	function EditPartsTree($params) {
    return $this->DB->UpdateRecord('products_parts_tree', $params);
  }
	
	function RemovePartsTree($params) {
    return $this->DB->DeleteRecord('products_parts_tree', $params);
  }
	
	// Posts::AddOrder
  // Descriptions: Use to add clients order
  // Parameters: 
  // Return: ID
	
	function AddPurchaseOrder($params) {		
    $purchase_order = array(
  	  'client_id'		=> $params['client_id'],
  	  'po_number'		=> $params['po_number'],
  	  'po_date'			=> date('Y-m-d', strtotime($params['po_date'])),
  	  'payment_terms'	=> $params['payment_terms'],
  	  'terms'			=> $params['terms'],
  	  'ship_date'	=> date('Y-m-d', strtotime($params['delivery_date'])),
  	  'status'			=> $params['status'],
  	  'completion_status'			=> $params['completion_status'],
  	  'remarks'			=> $params['remarks'],
  	  'total_amount'	=> $params['total_amount'],
  	  'created_by'		=> $Signed['id'],
		);	
		$purchase_order_id = $this->DB->InsertRecord('purchase_orders', $purchase_order);
	
		if(!empty($params['items'])) {
		  // Add Each Order Item
		  foreach ($params['items'] as $index => $item) {
	        $item['purchase_order_id'] = $purchase_order_id;
	        $purchase_order_item_id = $this->DB->InsertRecord('purchase_order_items', $item);
					
					$this->AddPurchaseOrderProductParts(array('purchase_order_item_id' => $purchase_order_item_id, 'product_id' => $item['item_id']));
		  }
		}
	
    return $purchase_order_id;
  }
	
	function EditPurchaseOrder($params) {
    $order = array(
      'variables' => array(
		  	  'po_number'		=> $params['po_number'],
		  	  'po_date'			=> strdate($params['po_date'], 'Y-m-d'),
		  	  'payment_terms'	=> $params['payment_terms'],
		  	  'terms'			=> $params['terms'],
		  	  'delivery_date'	=> strdate($params['delivery_date'], 'Y-m-d'),    
  	  		'status'			=> $params['status'],
  	  		'completion_status'			=> $params['completion_status'],
		  	  'remarks'			=> $params['remarks'],
		  	  'date_received'	=> strdate($params['date_received'], 'Y-m-d'),
		  	  'total_quantity'	=> $params['total_quantity'],
		  	  'unit'			=> $params['unit'],
		  	  'total_amount'	=> $params['total_amount']
	  ),
	  'conditions' => 'id = '.$params['id']
    );
	
		$row = $this->DB->UpdateRecord('orders', $order);
	if($row > 0) {
	  $this->DB->DeleteRecord('order_items', array('conditions' => 'order_id ='.$params['id']));
			
	  	if(!empty($params['items'])) {
	    	foreach ($params['items'] as $index => $item) {
          $item['order_id'] = $params['id'];
          $this->DB->InsertRecord('order_items', $item); 
	    	}
	  	}
		}
	
    return $row;
  }

	function AddPurchaseOrderProductParts($params) {
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @purchase_order_item_id = '.$params['purchase_order_item_id'],
			'set_3' 	=> 'SET @product_id = '.$params['product_id'],
			'query_1' => 'INSERT INTO purchase_order_item_parts (purchase_order_item_id, material_id, parts_tree_qty, created_at) 
										SELECT @purchase_order_item_id, material_id, material_qty, @created_at FROM products_parts_tree WHERE products_parts_tree.product_id=@product_id'
		);		
		// echo '<br/><br/>';
		// var_dump($query); die();
		return $this->DB->ExecuteQuery($query);
	}

	function AddWorkOrder($params) {		
    $work_order = array(
  	  'wo_number'		=> $params['wo_number'],
  	  'wo_date'			=> date('Y-m-d', strtotime($params['wo_date'])),
  	  'ship_date'	=> date('Y-m-d', strtotime($params['ship_date'])),
  	  'status'			=> $params['status'],
  		'completion_status'	=> $params['completion_status'],
  	  'remarks'			=> $params['remarks'],
  	  'total_amount'	=> $params['total_amount'],
		);	
		$work_order_id = $this->DB->InsertRecord('work_orders', $work_order);
	
		if(!empty($params['items'])) {
		  // Add Each Work Order Item
		  foreach ($params['items'] as $index => $item) {
	        $item['work_order_id'] = $work_order_id;
	        $work_order_item_id = $this->DB->InsertRecord('work_order_items', $item);
					
					$this->AddWorkOrderProductParts(array('work_order_item_id' => $work_order_item_id, 'product_id' => $item['product_id']));
		  }
		}
    return $work_order_id;
  }

function EditWorkOrder($params) {
    $work_order = array(
      'variables' => array(
		  	  //'wo_number'		=> $params['wo_number'],
		  	  'wo_date'			=> strdate($params['wo_date'], 'Y-m-d'),
		  	  'ship_date'	=> strdate($params['ship_date'], 'Y-m-d'),
  	  		'status'			=> $params['status'],
  	  		'completion_status'	=> $params['completion_status'],
		  	  'remarks'			=> $params['remarks'],
		  	  'total_amount'	=> $params['total_amount']
	  ),
	  'conditions' => 'id = '.$params['id']
    );
	
		$row = $this->DB->UpdateRecord('work_orders', $work_order);
	if($row > 0) {
	  $this->DB->DeleteRecord('work_order_items', array('conditions' => 'work_order_id ='.$params['id']));
			
	  	if(!empty($params['items'])) {
	    	foreach ($params['items'] as $index => $item) {
          $item['work_order_id'] = $params['id'];
          $this->DB->InsertRecord('work_order_items', $item); 
	    	}
	  	}
		}
	
    return $row;
  }

	function AddWorkOrderProductParts($params) {
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @work_order_item_id = '.$params['work_order_item_id'],
			'set_3' 	=> 'SET @product_id = '.$params['product_id'],
			'query_1' => 'INSERT INTO work_order_item_parts (work_order_item_id, material_id, parts_tree_qty, created_at) 
										SELECT @work_order_item_id, material_id, material_qty, @created_at FROM products_parts_tree WHERE products_parts_tree.product_id=@product_id'
		);		
		return $this->DB->ExecuteQuery($query);
	}
	
	function EditOrder2($params) {
    return $this->DB->UpdateRecord('orders', $params);
  }
	
	function AddOrderItem($params) {
    $items = array(
		  'order_id'	=> $params['order_id'],	  
		  'item_id'		=> $params['item_id'],	  
		  'item_type'	=> $params['item_type'],	  
		  'quantity'	=> $params['quantity'],			  
		  'remarks'		=> $params['remarks']
		);
    return $this->DB->InsertRecord('order_items', $items);
  }
	
	function EditOrderItem($params) {
    return $this->DB->UpdateRecord('order_items', $params);
  }
	
	function RemoveOrderItem($params) {
    return $this->DB->DeleteRecord('order_items', $params);
  }

	function AddLocation($params) {
  	$location = array(
  	  'bldg'	=> $params['bldg'],
  	  'bldg_no'	=> $params['bldg_no'],
  	  'item_classification'	=> $params['item_classification'],
  	  'deck'				=> $params['deck'],
  	  // 'area'				=> $params['area'],
  	  'rack'				=> $params['rack'],
  	  'number'			=> $params['number'],
  	  'address'			=> $params['address'],
  	  'description'	=> $params['description'],
  	  'terminal_id'	=> $params['terminal_id']
  	  //'color'				=> $params['color']
		);	
		return $this->DB->InsertRecord('location_addresses', $location);
  }
	
	function EditLocation($params) {
    return $this->DB->UpdateRecord('location_addresses', $params);
  }
	
	function AddLocationAddressItem($params) {
  	$location = array(
  	  'address'	=> $params['address']
		);	
		return $this->DB->InsertRecord('location_address_items', $location);
  }
	
	function EditLocationAddressItem($params) {
    return $this->DB->UpdateRecord('location_address_items', $params);
  }

	function AddTerminal($params) {
  	$terminal = array(
  	  'location_id'	=> $params['location_id'],
  	  'terminal_code'	=> $params['terminal_code'],
  	  'terminal_name'	=> $params['terminal_name'],
  	  'type'	=> $params['type'],
  	  'description'	=> $params['description']
		);	
		return $this->DB->InsertRecord('terminals', $terminal);
  }
	
	function EditTerminal($params) {
    return $this->DB->UpdateRecord('terminals', $params);
  }
	
	function AddTerminalDevice($params) {
  	$device_terminal = array(
  	  'terminal_id'	=> $params['terminal_id'],
  	  'device_id'	=> $params['device_id']
		);	
		return $this->DB->InsertRecord('terminal_devices', $device_terminal);
  }
	
	function EditTerminalDevice($params) {
    return $this->DB->UpdateRecord('terminal_devices', $params);
  }
	
	function AddDevice($params) {
  	$device = array(
  	  'device_code'	=> $params['device_code'],
  	  'make'	=> $params['make'],
  	  'model'	=> $params['model'],
  	  'serial_no'	=> $params['serial_no'],
  	  'description'	=> $params['description']
		);	
		return $this->DB->InsertRecord('devices', $device);
  }
	
	function EditDevice($params) {
    return $this->DB->UpdateRecord('devices', $params);
  }

	function AddDeviceUser($params) {
  	$device = array(
  	  'device_id'	=> $params['device_id'],
  	  'user_id'	=> $params['user_id']
		);	
		return $this->DB->InsertRecord('device_users', $device);
  }
	
	function EditDeviceUser($params) {
    return $this->DB->UpdateRecord('device_users', $params);
  }
	
	function AddMaterialRequest($params) {
  	$req = array(
  	  'request_type'	=> $params['request_type'],
  	  'product_id'	=> $params['product_id'],
  	  'lot_no'	=> $params['lot_no'],
  	  'production_purchase_order_id'	=> $params['production_purchase_order_id'],
  	  'request_qty'	=> $params['request_qty'],
  	  'request_date'	=> $params['request_date'],
  	  'requestor_id'	=> $params['requestor_id'],
  	  'remarks'	=> $params['remarks']
		);	
		return $this->DB->InsertRecord('material_requests', $req);
  }
	
	function EditMaterialRequest($params) {
    return $this->DB->UpdateRecord('material_requests', $params);
  }
	
	function AddMaterialRequestItem($params) {
    $items = array(
		  'material_request_id'	=> $params['material_request_id'],	  
		  'material_id'		=> $params['material_id'],	  
		  'request_qty'	=> $params['request_qty'],	  
		  'issue_qty'	=> $params['issue_qty'],			  
		  'remarks'		=> $params['remarks']
		);
    return $this->DB->InsertRecord('material_request_items', $items);
  }
	
	function EditMaterialRequestItem($params) {
    return $this->DB->UpdateRecord('material_request_items', $params);
  }
	
	function AddSettings($params) {
  	$terminal = array(
  	  'name'	=> $params['name'],
  	  'value'	=> $params['value'],
  	  'options'	=> $params['options']
		);	
		return $this->DB->InsertRecord('settings', $terminal);
  }
	
	function EditSettings($params) {
    return $this->DB->UpdateRecord('settings', $params);
  }
	
	function InitPurchaseOrder($params) {		
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @order_id = '.$params['order_id'],
			'set_3' 	=> 'SET @head_time_days = '.$params['head_time_days'],
			'set_4' 	=> 'SET @status = 16', // lookups active status id
			'query_1' => 'INSERT INTO production_purchase_orders (order_id, target_date, status, created_at) 
										SELECT @order_id, DATE_SUB(delivery_date,INTERVAL @head_time_days DAY), @status, @created_at FROM orders WHERE orders.id=@order_id'
		);		
		return $this->DB->ExecuteQuery($query);
	}
	
	function InitPurchaseOrderProducts($params) {		
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @ppoid = '.$params['ppoid'],
			'set_3' 	=> 'SET @oid = '.$params['oid'],
			'set_4' 	=> 'SET @target_date = "'.$params['target_date'].'"',
			'set_5' 	=> 'SET @prod_type = 122', // lookups plan production product type
			'query_1' => 'INSERT INTO production_purchase_order_products (production_purchase_order_id, product_id, order_qty, produce_qty, prod_ship_date, type, created_at) 
										SELECT @ppoid, order_items.item_id, order_items.quantity, (order_items.quantity + (order_items.quantity * 0.05)) AS produce_qty, 
										@target_date, @prod_type, @created_at FROM order_items WHERE order_items.order_id=@oid'
		);		
		return $this->DB->ExecuteQuery($query);
	}

	function AddPurchaseOrderProducts($params) {
  	$prods = array(
  	  'production_purchase_order_id'	=> $params['production_purchase_order_id'],
  	  'lot_no'	=> $params['lot_no'],
  	  'product_id'	=> $params['product_id'],
  	  'order_qty'	=> $params['order_qty'],
  	  'produce_qty'	=> $params['produce_qty'],
  	  'prod_ship_date'	=> $params['prod_ship_date'],
  	  'type'	=> $params['type'],
  	  'init'	=> $params['init'],
  	  'request_id'	=> $params['request_id']
		);	
		return $this->DB->InsertRecord('production_purchase_order_products', $prods);
  }

	function AddPurchaseOrderProductMaterials($params) {
  	$mats = array(
  	  'production_purchase_order_product_id'	=> $params['production_purchase_order_product_id'],
  	  'material_id'	=> $params['material_id'],
  	  'qty'	=> $params['qty'],
  	  'plan_qty'	=> $params['plan_qty']
		);	
		return $this->DB->InsertRecord('production_purchase_order_product_parts', $mats);
  }
	
	function EditProductionPlan($params) {
    return $this->DB->UpdateRecord('production_purchase_order_products', $params);
  }
	
	function EditProductionPlanParts($params) {
    return $this->DB->UpdateRecord('production_purchase_order_product_parts', $params);
  }
	
	function EditProductRequest($params) {
    return $this->DB->UpdateRecord('warehouse_material_requests', $params);
  }
	
	function InitPurchaseOrderProductMaterials($params) {
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @ppopid = '.$params['ppopid'],
			'set_3' 	=> 'SET @product_id = '.$params['product_id'],
			'set_4' 	=> 'SET @plan_qty = '.$params['plan_qty'],
			'set_5' 	=> 'SET @tracking_no = "'.$params['tracking_no'].'"',
			'query_1' => 'INSERT INTO production_purchase_order_product_parts (production_purchase_order_product_id, tracking_no, material_id, qty, plan_qty, pending_qty, created_at) 
										SELECT @ppopid, @tracking_no, material_id, material_qty, (@plan_qty * material_qty), (@plan_qty * material_qty), @created_at FROM products_parts_tree WHERE products_parts_tree.product_id=@product_id'
		);		
		return $this->DB->ExecuteQuery($query);
	}
	
	function AddWarehouseInventory($params) {
  	$invt = array(
  	  'item_id'		=> $params['item_id'],
  	  'item_type'	=> $params['item_type'],
  	  'invoice_no'=> $params['invoice_no'],
  	  'lot_no'		=> $params['lot_no'],
  	  'terminal_id'	=> $params['terminal'],
  	  //'status'		=> $params['status'],
  	  'qty'	=> $params['qty'],
  	  'remarks'		=> $params['remarks']
		);	
		return $this->DB->InsertRecord('warehouse_inventories', $invt);
  }

	function EditWarehouseInventory($params) {
		$qty = 0;
		$terminal_id = $params['terminal_id'];
		$item_type = $params['item_type'];
		switch($params['type']) {
			case 'Input' :
				$qty = '(qty + '.(double)$params['qty'].')';
				break;
			case 'Output' :
				$terminal_id = ($item_type=='MAT') ? 1 : 11;
				$qty = '(qty - '.$params['qty'].')';
				
				$invt = array('qty'	=> $qty, 'remarks' => $params['remarks']);
				$args = array('variables' => $invt, 'conditions'  => 'item_type="'.$item_type.'" AND terminal_id='.$terminal_id.' AND item_id='.$params['item_id'].' AND 
																															invoice_no ="'.$params['invoice_no'].'" AND lot_no="'.$params['lot_no'].'" '); 
				
				$this->DB->UpdateRecord('warehouse_inventories', $args);
				
				$qty = '(qty + '.$params['qty'].')';
				$terminal_id = ($item_type=='MAT') ? 2 : 12;
				break;			
		}
		
		$invt = array('qty'	=> $qty, 'remarks' => $params['remarks']);
		$args = array('variables' => $invt, 'conditions'  => 'item_type="'.$item_type.'" AND terminal_id='.$terminal_id.' AND item_id='.$params['item_id'].' AND 
																													invoice_no ="'.$params['invoice_no'].'" AND lot_no="'.$params['lot_no'].'" '); 
		return $this->DB->UpdateRecord('warehouse_inventories', $args);
  }
	
	function InitProductionInventory($params) {		
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @item_type = "MAT"',
			'set_3' 	=> 'SET @location_code = "WIP"',
			'set_4' 	=> 'SET @item_id = '.$params['item_id'],
			'set_5' 	=> 'SET @prod_lot_no = '.$params['prod_lot_no'],
			'set_6' 	=> 'SET @ppopid = '.$params['ppopid'],
			'set_7' 	=> 'SET @tracking_no = '.$params['tracking_no'],
			'query_1' => 'INSERT INTO production_inventories (production_purchase_order_product_id, tracking_no, item_id, item_type, terminal_id, prod_lot_no, created_at) 
										SELECT @ppopid, @tracking_no, @item_id, @item_type, terminals.id, @prod_lot_no, @created_at FROM terminals INNER JOIN locations ON locations.id=terminals.location_id 
										WHERE locations.location_code=@location_code',
			'query_2' => 'INSERT INTO production_inventory_logs (production_purchase_order_product_id, tracking_no, item_id, item_type, terminal_id, prod_lot_no, created_at) 
										SELECT @ppopid, @tracking_no, @item_id, @item_type, terminals.id, @prod_lot_no, @created_at FROM terminals INNER JOIN locations ON locations.id=terminals.location_id 
										WHERE locations.location_code=@location_code'
		);		
		return $this->DB->ExecuteQuery($query);
	}

	function AdjustProductionInventory($params) {
		//var_dump($params);
		return $this->DB->UpdateRecord('production_inventories', $params);
		
		// if($params['type'] == 'OUTPUT') {
// 			
		// } else {
			// if($params['terminal_id'] == 3){ //Pre-Prod IN
				// $status = 103;
			// } 
		// }
		// $invt = array(
  	  // 'item_id'			=> $params['item_id'],
  	  // 'item_type'		=> 'MAT',
  	  // 'tracking_no'	=> $params['tracking_no'],
  	  // 'prod_lot_no'	=> $params['prod_lot_no'],
  	  // 'mat_lot_no'	=> $params['mat_lot_no'],
  	  // 'src_terminal_id'	=> $params['src_terminal_id'],
  	  // 'terminal_id'	=> $params['terminal_id'],
  	  // 'terminal_device_id'	=> 2,
  	  // 'status'		=> $status,
  	  // 'qty'			=> $params['qty'],
  	  // 'remarks'	=> $params['remarks']
		// );	
		// $id = $this->DB->InsertRecord('production_inventories', $invt);
// 		
// 		
		// $request_target_date = array('prod_ship_date' => date('Y-m-d', strtotime($arr['prod_ship_date'])));
		// $Posts->EditProductionPlan(array('variables' => $request_target_date, 'conditions' => 'id='.$arr['id']));	
	}

	function AddProductionInventory($params) {
		$invt = array(
  	  'item_id'			=> $params['item_id'],
  	  'item_type'		=> 'MAT',
  	  'tracking_no'	=> $params['tracking_no'],
  	  'prod_lot_no'	=> $params['prod_lot_no'],
  	  'mat_lot_no'	=> $params['mat_lot_no'],
  	  'src_terminal_id'	=> $params['src_terminal_id'],
  	  'terminal_id'	=> $params['terminal_id'],
  	  'terminal_device_id'	=> $params['terminal_device_id'],
  	  'status'		=> $params['status'],
  	  'qty'			=> $params['qty'],
  	  'remarks'	=> $params['remarks']
		);	
		var_dump($params); die();
		$id = $this->DB->InsertRecord('production_inventories', $params);

		
// 		
// 		
		// if($params['type'] == 'Input') {
			// $trml_prod_in = array('qty' => '(qty - '.$params['qty'].')', 'remarks' => $params['remarks']);
			// $args_in = array('variables' => $trml_prod_in, 'conditions' => 'item_id='.$params['item_id'].' AND 
																															// prod_lot_no="'.$params['lot_no'].'" AND 
																															// terminal_id='.$params['terminal_from']. ' AND 
																															// tracking_no='.$params['tracking_no']);
			// $this->DB->UpdateRecord('production_inventories', $args_in);
		// }
// 		
		// if($params['type'] == 'Output') {
			// $out_terminal = $this->DB->Find('terminals', array('columns' => 'terminal_name', 'conditions'  => 'id = '.$params['terminal_id']));
			// $in_terminal = $this->DB->Find('terminals', array('columns' => 'id', 'conditions'  => 'terminal_name ="'.$out_terminal['terminal_name'].'" AND type="IN"'));
// 			
			// $trml_prod_in = array('qty' => '(qty - '.$params['qty'].')', 'remarks' => $params['remarks']);
			// $args_in = array('variables' => $trml_prod_in, 'conditions' => 'item_id='.$params['item_id'].' AND 
																															// prod_lot_no="'.$params['lot_no'].'" AND 
																															// terminal_id='.$in_terminal['id']. ' AND 
																															// tracking_no='.$params['tracking_no']);
// 
			// $this->DB->UpdateRecord('production_inventories', $args_in);
		// }
		// $trml_prod = array('qty' => $params['qty'], 'remarks' => $params['remarks']);
		// $args = array('variables' => $trml_prod, 'conditions' => 'item_id='.$params['item_id'].' AND 
																														// prod_lot_no="'.$params['lot_no'].'" AND 
																														// terminal_id='.$params['terminal_id']. ' AND 
																														// tracking_no='.$params['tracking_no']);
		// return $this->DB->UpdateRecord('production_inventories', $args);
		
		
		//TODO: add history log
	}	

	function EditProductionInventory($params) {
		if($params['type'] == 'Input') {
			$trml_prod_in = array('qty' => '(qty - '.$params['qty'].')', 'remarks' => $params['remarks']);
			$args_in = array('variables' => $trml_prod_in, 'conditions' => 'item_id='.$params['item_id'].' AND 
																															prod_lot_no="'.$params['lot_no'].'" AND 
																															terminal_id='.$params['terminal_from']. ' AND 
																															tracking_no='.$params['tracking_no']);
			$this->DB->UpdateRecord('production_inventories', $args_in);
		}
		
		if($params['type'] == 'Output') {
			$out_terminal = $this->DB->Find('terminals', array('columns' => 'terminal_name', 'conditions'  => 'id = '.$params['terminal_id']));
			$in_terminal = $this->DB->Find('terminals', array('columns' => 'id', 'conditions'  => 'terminal_name ="'.$out_terminal['terminal_name'].'" AND type="IN"'));
			
			$trml_prod_in = array('qty' => '(qty - '.$params['qty'].')', 'remarks' => $params['remarks']);
			$args_in = array('variables' => $trml_prod_in, 'conditions' => 'item_id='.$params['item_id'].' AND 
																															prod_lot_no="'.$params['lot_no'].'" AND 
																															terminal_id='.$in_terminal['id']. ' AND 
																															tracking_no='.$params['tracking_no']);
			var_dump($args_in); die();
			$this->DB->UpdateRecord('production_inventories', $args_in);
		}
		$trml_prod = array('qty' => $params['qty'], 'remarks' => $params['remarks']);
		$args = array('variables' => $trml_prod, 'conditions' => 'item_id='.$params['item_id'].' AND 
																														prod_lot_no="'.$params['lot_no'].'" AND 
																														terminal_id='.$params['terminal_id']. ' AND 
																														tracking_no='.$params['tracking_no']);
		return $this->DB->UpdateRecord('production_inventories', $args);
		
		
		//TODO: add history log
	}	
	
	function AddItemTracks($params) {
  	$track = array(
  	  'item_type'	=> $params['item_type'],
  	  'item_id'		=> $params['item_id'],
  	  'track_type'=> $params['track_type'],
  	  'track_no'	=> $params['track_no'],
  	  'terminal_id'	=> $params['terminal_id'],
  	  'qty'				=> $params['qty'],
  	  'remarks'		=> $params['remarks']
		);	
		return $this->DB->InsertRecord('item_tracks', $track);
  }
	
	//test
	function AddInventoryLocations2($params) {
    $inventory = array(
		  'inventory_id'				=> $params['inventory_id'],	
		  'location_address_id'	=> $params['location_address_id'],
		  'terminal_id'					=> $params['terminal_id'],	
		  'terminal_device_id'	=> $params['terminal_device_id'],	
		  'status'		=> $params['status'],
		  'lot_no'	=> $params['lot_no'],
		  'input'	=> $params['input'],
		  'rework'	=> $params['rework'],
		  'additional'	=> $params['additional'],
		  'qa_sample'	=> $params['qa_sample'],
		  'mgr_sample'	=> $params['mgr_sample'],
		  'defect_a'	=> $params['defect_a'],
		  'defect_b'	=> $params['defect_b'],
		  'output_partial'	=> $params['output_partial'],
		  'output'	=> $params['output'],
		  'remarks'		=> $params['remarks'],
		);
    return $this->DB->InsertRecord('item_inventory_locations', $inventory);
  }

	function InitWarehouseInventory($params) {		
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @item_type = "MAT"',
			'set_3' 	=> 'SET @location_code = "WH1"',
			'set_4' 	=> 'SET @item_id = '.$params['item_id'],
			'query_1' => 'INSERT INTO warehouse_inventories (item_id, item_type, terminal_id, created_at) 
										SELECT @item_id, @item_type, terminals.id, @created_at FROM terminals INNER JOIN locations ON locations.id=terminals.location_id 
										WHERE locations.location_code=@location_code',
		);		
		return $this->DB->ExecuteQuery($query);
	}
	
	// Posts::AddPurchase
  // Descriptions: Use to add purchase order
  // Parameters: 
  // Return: ID
  function AddPurchase($params) {
    $purchase = array(
      'purchase_number'	=> strtoupper(trim($params['purchase_number'])),
      'supplier_id'		=> $params['supplier_id'],
      'delivery_via'	=> trim($params['delivery_via']),
      'delivery_date' => date('Y-m-d', strtotime($params['delivery_date'])),
      'trade_terms'		=> trim($params['trade_terms']),
      'payment_terms'	=> trim($params['payment_terms']),
      'total_amount'	=> $params['total_amount'],
      'status'			=> $params['status'],
      'completion_status'			=> 2, //pending
      'remarks'			=> trim($params['remarks'])
    );
	
		$purchase_id = $this->DB->InsertRecord('purchases', $purchase);
		
		if(!empty($params['items'])) {
		  // Add Each Purchase Item
		  foreach ($params['items'] as $index => $item) {
	        $item['purchase_id'] = $purchase_id;
	        $this->DB->InsertRecord('purchase_items', $item);
		  }
		}
	
    return $purchase_id;
  }

  // Posts::EditPurchase
  // Descriptions: Use to add purchase order
  // Parameters: 
  // Return: ID
  function EditPurchase($params) {
    $purchase = array(
      'variables' => array(
        'delivery_via'	=> trim($params['delivery_via']),
      	'delivery_date' => date('Y-m-d', strtotime($params['delivery_date'])),
        'trade_terms'		=> trim($params['trade_terms']),
        'payment_terms'	=> trim($params['payment_terms']),
        'total_amount'	=> $params['total_amount'],
        'status'			=> $params['status'],
        'remarks'			=> trim($params['remarks'])
	  ),
	  'conditions' => 'id = '.$params['id']
    );
	
	$row = $this->DB->UpdateRecord('purchases', $purchase);
	
	if($row > 0) {
	  $this->DB->DeleteRecord('purchase_items', array('conditions' => 'purchase_id ='.$params['id']));
	  if(!empty($params['items'])) {
	    // Add Each Purchase Item
	    foreach ($params['items'] as $index => $item) {
          $item['purchase_id'] = $params['id'];
          $this->DB->InsertRecord('purchase_items', $item);
	    }
	  }
	}
    return $row;
  }

  // Posts::AddReceiving
  // Descriptions: Use to received purchase materials
  // Parameters: 
  // Return: ID
  function AddReceiving($params) {
  	$data = array(
	   'delivery_id'	=> $params['delivery_id'],
	   'receive_item'	=> $params['item_id'],
	   'quantity'		=> $params['quantity'],
	   'remarks'		=> $params['remarks']
		);
	
		$receive_id = $this->DB->InsertRecord('receive_items', $data);
    return $receive_id;
  	/*
    $data = array(
      'purchase_id'			=> $params['purchase_id'],
      'invoice_number'		=> strtoupper(trim($params['invoice_number'])),
      'invoice_date'		=> strdate($params['invoice_date'], 'Y-m-d'),
      'delivery_receipt'	=> strtoupper(trim($params['delivery_receipt'])),
      'delivery_date'		=> strdate($params['delivery_date'], 'Y-m-d'),
      'delivery_by'			=> trim($params['delivery_by']),
      'shipment_status'		=> trim($params['shipment_status']),
      'remarks'				=> trim($params['remarks'])
    );
	
	$received = $this->DB->Fetch('receiving', array('columns' => 'id', 'conditions' => 'purchase_id = '. $params['purchase_id']));
	
	if(empty($received)) {
      $row = $this->DB->InsertRecord('receiving', $data);
	} else {
      $row = $this->DB->UpdateRecord('receiving', array('variables' => $data, 'conditions' => 'purchase_id = '. $params['purchase_id']));
	}
	
    return $row;
	 */
  }
  
  
  // Posts::AddDelivery
  // Return: ID
  // function AddDelivery($params) {
  	// global $Query;
// 	
		// if(trim($params['receipt']) == "") return -1;
// 		
		// $delivery = $Query->uniqueness_of_delivery(trim($params['receipt']));
// 		
		// if(!empty($delivery)) return 0;
// 		
	    // $data = array(
	      // 'delivery_receipt'	=> $params['receipt'],
	      // 'delivery_date'		=> strdate($params['date'], 'Y-m-d'),
	      // 'supplier_id'			=> $params['supplier'],
	      // 'delivery_via'		=> strtoupper(trim($params['via'])),
	      // 'trade_terms'			=> strtoupper(trim($params['trade_terms'])),
	      // 'payment_terms'		=> strtoupper(trim($params['payment_terms']))
	    // );
// 		
		// $delivery_id = $this->DB->InsertRecord('deliveries', $data);
		// return $delivery_id;
  // }
  
  function EditReceiving($params) {
    return $this->DB->UpdateRecord('deliveries', $params);
  }
	
	function EditReceivingItems($params) {
    return $this->DB->UpdateRecord('delivery_items', $params);
  }
  
  function AddDelivery($params) {
  	$delivery = array(
      'purchase_id'	=> $params['purchase_id'],
      'delivery_date' => date('Y-m-d', strtotime($params['delivery_date'])),
      'delivery_via'	=> trim($params['delivery_via']),
      'status'			=> 2, //pending status
      'remarks'			=> trim($params['remarks'])
    );
	
		$delivery_id = $this->DB->InsertRecord('deliveries', $delivery);

		if(!empty($params['items'])) {
		  foreach ($params['items'] as $index => $item) {
	        $item['delivery_id'] = $delivery_id;
	        $item['status'] = 2; //pending status
	        $this->DB->InsertRecord('delivery_items', $item);
		  }
		}
	
    return $delivery_id;
  }

  // Posts::UpdateDelivery
  // Return: ID
  
    
   function UpdateDelivery($params) {
		return $this->DB->UpdateRecord('deliveries', $params);
	 }
  // function UpdateDelivery($params) {
    // $data = array(
      // 'variables' => array(
        // 'delivery_receipt'	=> $params['receipt'],
        // 'delivery_date'		=> strdate($params['date'], 'Y-m-d'),
        // 'delivery_via'		=> strtoupper(trim($params['via'])),
        // 'trade_terms'		=> strtoupper(trim($params['trade_terms'])),
        // 'payment_terms'		=> strtoupper(trim($params['payment_terms'])),
        // 'remarks'			=> strtoupper(trim($params['remarks'])),
	    // 'status'			=> $params['status']),
	  // 'conditions' => 'id = '. $params['id']
    // );
// 	
	// // Reset Received Items
		// $this->DB->UpdateRecord('receive_items', array('variables' => array('passed' => 0), 
		                        // 'conditions' => 'delivery_id ='. $params['id']));
// 		
		// if(!empty($params['items'])) {
	      // foreach ($params['items'] as $key => $value) {
		    // $this->DB->UpdateRecord('receive_items', array('variables' => array('passed' => 1), 
		                            // 'conditions' => 'id ='. $key));
		  // }
		// }
// 		
		// return $this->DB->UpdateRecord('deliveries', $data);
  // }

	
	
}