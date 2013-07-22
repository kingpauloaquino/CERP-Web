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
		  'columns' => 'users.id, employee_id, first_name, last_name, position, email, status, user_roles.role_id, roles.level, roles.name AS role, roles.level',
		  'joins' => 'INNER JOIN user_roles ON user_roles.user_id = users.id
									INNER JOIN roles ON roles.id = user_roles.role_id',
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
		  'first_name'	=> mysql_real_escape_string(ucwords(strtolower($params['first_name']))),
		  'last_name'	=> mysql_real_escape_string(ucwords(strtolower($params['last_name']))),
		  'email'				=> $params['email'],
		  'position'	=> mysql_real_escape_string(ucwords(strtolower($params['position']))),
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
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
		  'name'	=> mysql_real_escape_string(ucwords(strtolower($params['name']))),
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
		  'level' => $params['level']
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
		  'name'	=> mysql_real_escape_string(ucwords(strtolower($params['name']))),
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
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
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
		  'description'							=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
		  'brand_model'							=> $params['brand_model'],
		  'person_in_charge'				=> $params['person_in_charge'],
		  'status'									=> $params['status'],
		  'defect_rate'							=> $params['defect_rate'],
		  'sorting_percentage'			=> $params['sorting_percentage'],
		  'production_entry_terminal_id' => $params['production_entry_terminal_id'] ,
		  'msq' => $params['msq'], 
		  'unit' => $params['unit'], 
		  'created_by' => $_SESSION['user']['id'], 
		);
    return $this->DB->InsertRecord('materials', $material);
  }
	
	function EditMaterial($params) {
		$params['variables']['updated_by'] = $_SESSION['user']['id'];
    return $this->DB->UpdateRecord('materials', $params);
  }

	function AddIndirectMaterial($params) {
    $material = array(
		  'material_code'						=> strtoupper($params['material_code']),
		  'bar_code'								=> $params['bar_code'],	
		  'material_type'						=> $params['material_type'],	
		  'material_classification'	=> $params['material_classification'],	
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
		  'person_in_charge'				=> $params['person_in_charge'],
		  'status'									=> $params['status'],
		  'production_entry_terminal_id' => $params['production_entry_terminal_id'],
		  'created_by' => $_SESSION['user']['id'], 
		);
    return $this->DB->InsertRecord('materials', $material);
  }

  function AddMaterialRev($params) {
    $material = array(
		  'revision'					=> $params['revision'],
		  'material_id'				=> $params['material_id'],
		  'base_material_id'	=> $params['base_material_id'],
		  'created_by' => $_SESSION['user']['id'], 
		);
    return $this->DB->InsertRecord('material_revisions', $material);
  }
	
	function EditMaterialRev($params) {
		$params['variables']['updated_by'] = $_SESSION['user']['id'];
    return $this->DB->UpdateRecord('material_revisions', $params);
  }
	
	function AddItemCost($params) {
    $item_cost = array(
		  'item_id'		=> $params['item_id'],
		  'item_type'	=> $params['item_type'],
		  'supplier'	=> $params['supplier'],		
		  'currency'	=> $params['currency'],
		  'cost'			=> $params['cost'],
		  'moq'				=> $params['moq'],
		  'transportation_rate'	=> $params['transportation_rate'],
		  'created_by' => $_SESSION['user']['id'], 
		);
    return $this->DB->InsertRecord('item_costs', $item_cost);
  }
	
	function EditItemCost($params) {
		$params['variables']['updated_by'] = $_SESSION['user']['id'];
    return $this->DB->UpdateRecord('item_costs', $params);
  }
	
	function AddProduct($params) {
    $product = array(
		  'product_code'						=> $params['product_code'],	
		  'brand_model'							=> $params['brand_model'],	
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
		  'color'										=> $params['color'], 
		  'bar_code'								=> $params['bar_code'],
		  'status'									=> $params['status'],
		  'prod_cp'									=> $params['prod_cp'],
		  'priority'								=> $params['priority'],
		  'series'									=> $params['series'],	  
		  'pack_qty'				=> $params['pack_qty'],
		  'unit'				=> $params['unit'],
		  'created_by' => $_SESSION['user']['id'], 
		);
    return $this->DB->InsertRecord('products', $product);
  }
	
	function EditProduct($params) {
		$params['variables']['updated_by'] = $_SESSION['user']['id'];
    return $this->DB->UpdateRecord('products', $params);
  }
	
	function AddProductInventory($params) {
    $inventory = array(
		  'item_id'						=> $params['item_id'],
  		'prod_lot_no'				=> $params['prod_lot_no'],	
		  'stamp'							=> $params['stamp'],	
			'endorse_date'			=> date('Y-m-d', strtotime($params['endorse_date'])),
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),  
		  'qty'			=> $params['qty'],		  
		);
    return $this->DB->InsertRecord('warehouse2_inventories', $inventory);
  }

	function AddInventory($params) {
    $inventory = array(
		  'item_id'					=> $params['item_id'],	
		  'item_type'				=> $params['item_type'],	
		  'invoice_no'				=> $params['invoice'],	
		  'lot_no'				=> $params['lot'],	
		  'qty'				=> $params['qty'],	
		  'status'				=> 16, // status16 = active	 
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
		);
    return $this->DB->InsertRecord('warehouse_inventories', $inventory);
  }
	
	function EditInventory($params) {
    return $this->DB->UpdateRecord('warehouse_inventories', $params);
  }
	
	function DeleteInventory($params) {
    return $this->DB->DeleteRecord('warehouse_inventories', $params);
  }
	
	function AddActualInventory($params) {
    $inventory = array(
		  'item_id'					=> $params['item_id'],	
		  'item_type'				=> $params['item_type'],	
		  'invoice_no'				=> $params['invoice'],	
		  'lot_no'				=> $params['lot'],	
		  'qty'				=> $params['qty'],	 
		  'entry_date'				=> date('Y-m-d', strtotime($params['entry_date'])),
		  'device_id'				=> 1, // Web UI	 
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
  		'inventory_id'	=> $params['inventory_id'],
		);
    return $this->DB->InsertRecord('warehouse_inventory_actual', $inventory);
  }

	//update inventory with physical count
	function UpdateInventoryCount($params) { 
		// update qtys in system inventory
		$query = array(
			'query_1' => 'UPDATE warehouse_inventories AS wh
										INNER JOIN
											(
										    SELECT inventory_id,qty, remarks
										    FROM warehouse_inventory_actual
										    WHERE is_updated = 0 AND EXTRACT(YEAR_MONTH FROM entry_date) = EXTRACT(YEAR_MONTH FROM "'.date('Y-m-d', strtotime($params['mydate'])).'")
											) AS ct ON ct.inventory_id = wh.id
										SET wh.qty=ct.qty, wh.remarks=ct.remarks, wh.updated_at="'.date('Y-m-d H:i:s').'"'
		);		
		$this->DB->ExecuteQuery($query);
		// update actual update flags
		$args = array('variables' => array('is_updated' => 1), 'conditions' => 'is_updated=0 AND EXTRACT(YEAR_MONTH FROM entry_date) = EXTRACT(YEAR_MONTH FROM "'.date('Y-m-d', strtotime($params['mydate'])).'")'); 
		$this->DB->UpdateRecord('warehouse_inventory_actual', $args);
	}

	function UpdateInventoryStatus($params) {
		return $this->DB->UpdateRecord('inventory_status', $params);
	}
	
	function AddInventoryLocations($params) {
    $inventory = array(
		  'inventory_id'				=> $params['inventory_id'],	
		  'location_address_id'	=> $params['location_address_id'],	
		  'terminal_id'					=> $params['terminal_id'],	
		  'terminal_device_id'	=> $params['terminal_device_id'],	
		  'status'		=> $params['status'],
		  'quantity'	=> $params['quantity'],
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
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
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
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
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
		);
    return $this->DB->InsertRecord('products_parts_tree', $parts_tree);
  }
	
	function EditPartsTree($params) {
    return $this->DB->UpdateRecord('products_parts_tree', $params);
  }
	
	function RemovePartsTree($params) {
    return $this->DB->DeleteRecord('products_parts_tree', $params);
  }
	
	
	function AddPurchaseOrder($params) {		
    $purchase_order = array(
  	  'client_id'		=> $params['client_id'],
  	  'po_number'		=> $params['po_number'],
  	  'po_date'			=> date('Y-m-d', strtotime($params['po_date'])),
  	  'payment_terms'	=> $params['payment_terms'],
  	  'terms'			=> $params['terms'],
  	  'ship_date'	=> date('Y-m-d', strtotime($params['ship_date'])),
  	  'status'			=> $params['status'],
  	  'completion_status'			=> $params['completion_status'],
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
  	  'total_amount'	=> $params['total_amount'],
		  'created_by' => $_SESSION['user']['id'],
		  'checked_by' => $_SESSION['user']['id'],
		  'checked_at' => date('Y-m-d')
		);	
		$purchase_order_id = $this->DB->InsertRecord('purchase_orders', setApproval($purchase_order, $params['status']));
	
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
    $purchase_order = array(
      'variables' => array(
		  	  'po_date'			=> strdate($params['po_date'], 'Y-m-d'),
		  	  'payment_terms'	=> $params['payment_terms'],
		  	  'terms'			=> $params['terms'],
		  	  'ship_date'	=> strdate($params['ship_date'], 'Y-m-d'),    
  	  		'status'		=> $params['status'],
  	  		'completion_status'			=> $params['completion_status'],
		  		'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
		  	  'total_amount'	=> $params['total_amount'],
		  	  'created_by' => $_SESSION['user']['id'],
				  'checked_by' => $_SESSION['user']['id'],
				  'checked_at' => date('Y-m-d')
	  ),
	  'conditions' => 'id = '.$params['id']
    );
		
		$row = $this->DB->UpdateRecord('purchase_orders', setApproval($purchase_order, $params['status'], FALSE));
	if($row > 0) {
		$purchase_order_item_ids = $this->DB->Get('purchase_order_items', array('columns' => 'id', 'conditions' => 'purchase_order_id ='. $params['id']));
		foreach($purchase_order_item_ids as $prod) {
			// delete parts under product
			$this->DB->DeleteRecord('purchase_order_item_parts', array('conditions' => 'purchase_order_item_id ='. $prod['id']));
		}
		
	  $this->DB->DeleteRecord('purchase_order_items', array('conditions' => 'purchase_order_id ='.$params['id']));
			
	  	if(!empty($params['items'])) {
			  // Add Each Order Item
			  foreach ($params['items'] as $index => $item) {
		        $item['purchase_order_id'] = $params['id'];
		        $purchase_order_item_id = $this->DB->InsertRecord('purchase_order_items', $item);
						
						$this->AddPurchaseOrderProductParts(array('purchase_order_item_id' => $purchase_order_item_id, 'product_id' => $item['item_id']));
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
  	  'client_id'		=> $params['client_id'],
  	  'wo_number'		=> $params['wo_number'],
  	  'wo_date'			=> date('Y-m-d', strtotime($params['wo_date'])),
  	  'ship_date'	=> date('Y-m-d', strtotime($params['ship_date'])),
  	  'status'			=> $params['status'],
  		'completion_status'	=> $params['completion_status'],
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
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
		  		'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
		  	  'total_amount'	=> $params['total_amount']
	  ),
	  'conditions' => 'id = '.$params['id']
    );
	
		$row = $this->DB->UpdateRecord('work_orders', $work_order);
	if($row > 0) {
		$work_order_item_ids = $this->DB->Get('work_order_items', array('columns' => 'id', 'conditions' => 'work_order_id ='. $params['id']));
		foreach($work_order_item_ids as $prod) {
			// delete parts under product
			$this->DB->DeleteRecord('work_order_item_parts', array('conditions' => 'work_order_item_id ='. $prod['id']));
		}
		
	  $this->DB->DeleteRecord('work_order_items', array('conditions' => 'work_order_id ='.$params['id']));
			
	  	if(!empty($params['items'])) {
			  // Add Each Order Item
			  foreach ($params['items'] as $index => $item) {
		        $item['work_order_id'] = $params['id'];
		        $work_order_item_id = $this->DB->InsertRecord('work_order_items', $item);
						
						$this->AddWorkOrderProductParts(array('work_order_item_id' => $work_order_item_id, 'product_id' => $item['product_id']));
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

  // Posts::AddPurchase
  // Descriptions: Used to add purchases from suppliers
  // Parameters: 
  // Return: ID
	function AddPurchase($params) {
    $purchase = array(
      'supplier_id'		=> $params['supplier_id'],
      'po_number'	=> strtoupper(trim($params['po_number'])),
      'po_date' => date('Y-m-d', strtotime($params['po_date'])),
      'payment_terms'	=> trim($params['payment_terms']),
      'terms'		=> trim($params['terms']),
      'delivery_via'	=> trim($params['delivery_via']),
      'delivery_date' => date('Y-m-d', strtotime($params['delivery_date'])),
      'total_amount'	=> $params['total_amount'],
      'status'			=> $params['status'],
      'completion_status'			=> 2, //pending
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
		  'created_by' => $_SESSION['user']['id'],
		  'checked_by' => $_SESSION['user']['id'],
		  'checked_at' => date('Y-m-d')
    );
		
		$purchase_id = $this->DB->InsertRecord('purchases', setApproval($purchase, $params['status']));
		
		// Add delivery if status = Published
		$delivery_id = 0;
		if($params['status'] == '11') {
			$delivery = array();
			$delivery['purchase_id'] = $purchase_id;
			$delivery['delivery_via'] = trim($params['delivery_via']);
			$delivery['delivery_date'] = date('Y-m-d', strtotime($params['delivery_date']));
			$delivery['remarks'] = mysql_real_escape_string(ucwords(strtolower($params['remarks'])));
			$delivery['status'] = 13; // Open receiving status
			$delivery['completion_status'] = 19; // pending completion status
			$delivery_id = $this->AddDelivery($delivery);
		}
		
		// Add Each Purchase Item
	  foreach ($params['items'] as $index => $item) {
      $item['purchase_id'] = $purchase_id;
      $purchase_item_id = $this->DB->InsertRecord('purchase_items', $item);
			if($delivery_id > 0) {
				$delivery_item = array();
				$delivery_item['delivery_id'] = $delivery_id;
				$delivery_item['purchase_item_id'] = $purchase_item_id;
				$delivery_item['status'] = 19; // Pending completion status
      	$delivery_item_id = $this->DB->InsertRecord('delivery_items', $delivery_item);
			}
	  }
		
		if($delivery_id > 0) redirect_to(host('purchases-show.php?id='.$purchase_id.'&did='.$delivery_id));
	
    return $purchase_id;
  }

  // Posts::EditPurchase
  // Descriptions: Used to update purchases from suppliers
  // Parameters: 
  // Return: ID
  function EditPurchase($params) {
    $purchase = array(
      'variables' => array(
	      'po_date' => date('Y-m-d', strtotime($params['po_date'])),
	      'payment_terms'	=> trim($params['payment_terms']),
	      'terms'		=> trim($params['terms']),
	      'delivery_via'	=> trim($params['delivery_via']),
	      'delivery_date' => date('Y-m-d', strtotime($params['delivery_date'])),
	      'total_amount'	=> $params['total_amount'],
	      'status'			=> $params['status'],
      	'completion_status'		=> $params['completion_status'],
		  	'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
			  'created_by' => $_SESSION['user']['id'],
			  'checked_by' => $_SESSION['user']['id'],
			  'checked_at' => date('Y-m-d')
	  ),
	  'conditions' => 'id = '.$params['id']
    );
		
		$row = $this->DB->UpdateRecord('purchases', setApproval($purchase, $params['status'], FALSE));
		
		// Add delivery if status = Published
		$delivery_id = 0;
		if($params['status'] == '11') {
			$delivery = array();
			$delivery['purchase_id'] = $params['id'];
			$delivery['delivery_via'] = trim($params['delivery_via']);
			$delivery['delivery_date'] = date('Y-m-d', strtotime($params['delivery_date']));
			$delivery['remarks'] = mysql_real_escape_string(ucwords(strtolower($params['remarks'])));
			$delivery['status'] = 13; // Open receiving status
			$delivery['completion_status'] = 19; // pending completion status
			$delivery_id = $this->AddDelivery($delivery);
		}
		
		$this->DB->DeleteRecord('purchase_items', array('conditions' => 'purchase_id ='.$params['id']));
		// Add Each Purchase Item
	  foreach ($params['items'] as $index => $item) {
      $item['purchase_id'] = $params['id'];
      $purchase_item_id = $this->DB->InsertRecord('purchase_items', $item);
			if($delivery_id > 0) {
				$delivery_item = array();
				$delivery_item['delivery_id'] = $delivery_id;
				$delivery_item['purchase_item_id'] = $purchase_item_id;
				$delivery_item['status'] = 19; // Pending completion status
      	$delivery_item_id = $this->DB->InsertRecord('delivery_items', $delivery_item);
			}
	  }
		
	  return $params['id'];
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
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
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
      'status'			=> $params['status'],
      'completion_status'			=> $params['completion_status'],
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
    );
		$delivery_id = $this->DB->InsertRecord('deliveries', $delivery);
	
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
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
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
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
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
		  'description'	=> mysql_real_escape_string(ucwords(strtolower($params['description']))),
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
  	  'request_no'	=> $params['request_no'],
  	  'request_type'	=> $params['request_type'],
  	  'batch_no'	=> $params['batch_no'],
      'requested_date' => date('Y-m-d'),
		  'requested_by' => $params['requested_by'],
      'expected_date' => date('Y-m-d', strtotime($params['expected_date'])),
      'received_date' => date('Y-m-d', strtotime($params['received_date'])),
		  'received_by' => $params['received_by'],
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
		  'completion_status'	=> 19,
		  'status'	=> $params['status']
		);	
		
		if(!isset($params['expected_date'])) unset($req['expected_date']);
		if(!isset($params['received_date'])) unset($req['received_date']);
		
		return $this->DB->InsertRecord('material_requests', $req);
  }
	
	function EditMaterialRequest($params) {
    return $this->DB->UpdateRecord('material_requests', $params);
  }
	
	function AddMaterialRequestItem($params) {
    $items = array(
		  'request_id'	=> $params['request_id'],	  
		  'material_id'		=> $params['material_id'],	  
		  'qty'	=> $params['qty'],	  
		);
    return $this->DB->InsertRecord('material_request_items', $items);
  }
	
	function EditMaterialRequestItem($params) {
    return $this->DB->UpdateRecord('material_request_items', $params);
  }

	function AddMaterialRequestItemIssue($params) {
    $items = array(
		  'request_item_id'	=> $params['request_item_id'],	  
		  'warehouse_inventory_id'		=> $params['warehouse_inventory_id'],	  
		  'qty'	=> $params['qty'],	  
		);
    return $this->DB->InsertRecord('material_request_item_issuances', $items);
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
	
	function EditProductionPlan($params) {
    return $this->DB->UpdateRecord('production_purchase_order_products', $params);
  }
	
	function EditProductionPlanParts($params) {
    return $this->DB->UpdateRecord('production_purchase_order_product_parts', $params);
  }
	
	function EditProductRequest($params) {
    return $this->DB->UpdateRecord('warehouse_material_requests', $params);
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
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
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
	
	function AddItemTracks($params) {
  	$track = array(
  	  'item_type'	=> $params['item_type'],
  	  'item_id'		=> $params['item_id'],
  	  'track_type'=> $params['track_type'],
  	  'track_no'	=> $params['track_no'],
  	  'terminal_id'	=> $params['terminal_id'],
  	  'qty'				=> $params['qty'],
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
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
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
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
	
  function InitForecastCalendar() {
		$query = array(
			'set_1' 	=> 'SET @created_at = "'.date('Y-m-d H:i:s').'"',
			'set_2' 	=> 'SET @forecast_year = '.date('Y'),
			'query_1' => 'INSERT INTO forecast_calendar (product_id, forecast_year, created_at) 
										SELECT id, @forecast_year, @created_at FROM products'
		);		
		return $this->DB->ExecuteQuery($query);
	}
	
	function EditForecastCalendar($params) {
		foreach($params['items'] as $id => $attr) {
			if($params['type'] == 'h1') {
				// do not allow zero
				//if(!($attr['jan'] == 0 && $attr['feb'] == 0 && $attr['mar'] == 0 && $attr['apr'] == 0 && $attr['may'] == 0 && $attr['jun'] == 0)) {
					$forecast = array();
					$item = $this->DB->Find('forecast_calendar', array('columns' => 'id', 'conditions' => 'product_id = '.$attr['product_id'].' AND forecast_year='.$params['forecast_year']));
					
					$forecast = array('jan' => $attr['jan'], 'feb' => $attr['feb'], 'mar' => $attr['mar'],
														'apr' => $attr['apr'], 'may' => $attr['may'], 'jun' => $attr['jun']);
					if(isset($item)) {
						// update
						$args = array('variables' => $forecast, 'conditions' => 'product_id='.$attr['product_id'].' AND forecast_year='.$params['forecast_year']);
						$this->DB->UpdateRecord('forecast_calendar', $args); 
					} else {
						// add entry
						$forecast['product_id'] = $attr['product_id'];
						$forecast['forecast_year'] = $params['forecast_year'];
						$this->DB->InsertRecord('forecast_calendar', $forecast); 
					}
					unset($forecast);	
				//} 	
			}
			if($params['type'] == 'h2') {
				// do not allow zero
				//if(!($attr['jul'] == 0 && $attr['aug'] == 0 && $attr['sep'] == 0 && $attr['oct'] == 0 && $attr['nov'] == 0 && $attr['dece'] == 0)) {
					$forecast = array();
					$item = $this->DB->Find('forecast_calendar', array('columns' => 'id', 'conditions' => 'product_id = '.$attr['product_id'].' AND forecast_year='.$params['forecast_year']));
					
					$forecast = array('jul' => $attr['jul'], 'aug' => $attr['aug'], 'sep' => $attr['sep'],
														'oct' => $attr['oct'], 'nov' => $attr['nov'], 'dece' => $attr['dece']);
					if(isset($item)) {
						// update
						$args = array('variables' => $forecast, 'conditions' => 'product_id='.$attr['product_id'].' AND forecast_year='.$params['forecast_year']);
						$this->DB->UpdateRecord('forecast_calendar', $args); 
					} else {
						// add entry
						$forecast['product_id'] = $attr['product_id'];
						$forecast['forecast_year'] = $params['forecast_year'];
						$this->DB->InsertRecord('forecast_calendar', $forecast); 
					}
					unset($forecast);	
				//} 	
			}
		}
		
		//$args = array('variables' => $item, 'conditions' => 'product_id='.$attr['product_id'].' AND forecast_year='.$_POST['forecast_year']); 
		//$num_of_records = $Posts->AddForecastCalendar($args);	
	}

	function InitForecast($params) {
		$forecast = $this->DB->Find('forecasts', array('columns' => 'id', 'conditions' => 'forecast_year='. $params['forecast_year']));
		if(!isset($forecast)) {
			$products = $this->DB->Get('products', array('columns' => 'id'));
			foreach ($products as $product) {
				for($i=1; $i<=12; $i++) {
					$this->DB->InsertRecord('forecasts', array('forecast_year'	=> $params['forecast_year'], 
																											'forecast_month'	=> $i,
																											'product_id'	=> $product['id'],));
				}		
			}
		}
	}

	function AddForecast($params) {
		for($i=1; $i<=12; $i++) {
			$this->DB->InsertRecord('forecasts', array('forecast_year'	=> $params['forecast_year'], 
																									'forecast_month'	=> $i,
																									'product_id'	=> $params['product_id'],));
		}
	}

	function EditForecast($params) {
		$this->DB->UpdateRecord('forecasts', $params); 
	}
	
	function AddShipmentPlan($params) {
		$plan = array(
  	  'type'	=> $params['type'],
  	  'ctrl_id'	=> $params['ctrl_id'],
  	  'ctrl_no'	=> $params['ctrl_no'],
  	  'item_id'		=> $params['item_id'],
  	  'item_type'=> $params['item_type'],
  	  'ship_date'	=> date('Y-m-d', strtotime($params['ship_date'])),
  	  'prod_date'	=> date('Y-m-d', strtotime($params['prod_date'])),
  	  'qty'	=> $params['qty'],
  	  'status'	=> 19, // PENDING status
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
		);	
		return $this->DB->InsertRecord('shipment_plans', $plan);
	}
	
	function EditShipmentPlan($params) { 
		$this->DB->UpdateRecord('shipment_plans', $params); 
	}
	
	function ApprovePurchase($params) {
    return $this->DB->UpdateRecord('purchases', $params);
  }
	
	function ApprovePurchaseOrder($params) {
    return $this->DB->UpdateRecord('purchase_orders', $params);
  }
	
	function EditLookup($params) {
		return $this->DB->UpdateRecord('lookups', $params);
	}
	
	function DeleteLookup($params) {
    return $this->DB->DeleteRecord('lookups', $params);
	}
	
	function AddNotification($params) {
		$noti = array(
  	  'type'	=> $params['type'],
  	  'title'	=> $params['title'],
		  'remarks'	=> mysql_real_escape_string(ucwords(strtolower($params['remarks']))),
  	  'url'	=> $params['url'],
  	  'status'		=> 163, // UNREAD
		);	
		return $this->DB->InsertRecord('notifications', $noti);
	}
}