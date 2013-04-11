<?php

class Capabilities {
  private $DB;
	public $All;
	
	function Capabilities($db) {
		$this->DB = $db;
		$this->GetCapabilities();
	}
	
	function Childrens($key='') {
  	$childs = array(
	  'users' => array('add_user', 'roles')
	);
	return $childs[$key];
  }

  // Function: GetName
  // Description: Use to get capability name
  // Return: Capability name
  function GetName() {
    global $capability_key;
	return $this->All[$capability_key]['name'];
  }
	
  function GetTitle() {
    global $capability_key;
	return $this->All[$capability_key]['title'];
  }
  
  function GetUrl() {
    global $capability_key;
	return $this->All[$capability_key]['url'];
  }
  
  function GetParent() {
    global $capability_key;
	return $this->All[$capability_key]['parent'];
  }
	
	function GetCapabilities() {
		$capabilities = $this->DB->Get('capabilities', array(
					  			'columns' 		=> 'id, capability, title, name, parent, url',
					  	    'conditions' 	=> 'parent IS NOT null ORDER BY parent'));
		
		$all = array();																		
		foreach ($capabilities as $cap) {
			$all[$cap['capability']] = array('title' => $cap['title'],'name' => $cap['name'], 'url' => $cap['url'], 'parent' => $cap['parent']);
		}
		$this->All = $all;
	}	
	
  public $manual = array(
  //dashboard
	  'dashboard' => array('name' => 'Dashboard', 'url' => 'index.php', 'parent' => 'dashboard'),	  
	//defect
		'defects' => array('name' => 'Defects', 'url' => 'defects.php', 'parent' => 'defects'),
		'show_defect' => array('name' => 'Defect Details', 'url' => 'defects-show.php', 'parent' => 'defects'),
		'add_defect' => array('name' => 'New Defect', 'url' => 'defects-new.php', 'parent' => 'defects'),
		'edit_defect' => array('name' => 'Update Defect', 'url' => 'defects-edit.php', 'parent' => 'defects'),
		'delete_defect' => array('name' => 'Remove Defect', 'url' => 'defects-delete.php', 'parent' => 'defects'),
		'defect-reports' => array('name' => 'Reports', 'url' => 'defect-reports.php', 'parent' => 'defects'),
	//delivery
		'deliveries' => array('name' => 'Deliveries', 'url' => 'deliveries.php', 'parent' => 'deliveries'),
		'show_deliveries' => array('name' => 'Show Delivery', 'url' => 'deliveries-show.php', 'parent' => 'deliveries'),
		'add_deliveries' => array('name' => 'New Delivery', 'url' => 'deliveries-new.php', 'parent' => 'deliveries'),
		'edit_deliveries' => array('name' => 'Update Delivery', 'url' => 'deliveries-edit.php', 'parent' => 'deliveries'),
		'delete_deliveries' => array('name' => 'Remove Delivery', 'url' => 'deliveries-delete.php', 'parent' => 'deliveries'),
	//device
		'devices' => array('name' => 'Devices', 'url' => 'devices.php', 'parent' => 'devices'),
	  'show_device' => array('name' => 'Device Details', 'url' => 'devices-show.php', 'parent' => 'devices'),
	  'add_device' => array('name' => 'New Device', 'url' => 'devices-new.php', 'parent' => 'devices'),
	  'edit_device' => array('name' => 'Edit Device', 'url' => 'devices-edit.php', 'parent' => 'devices'),
	  'delete_device' => array('name' => 'Remove Device', 'url' => 'devices-delete.php', 'parent' => 'devices'),
	  'publish_device' => array('name' => 'Publish Device', 'url' => '', 'parent' => 'devices'),
	//indirect material
		'indirect_materials' => array('name' => 'Indirect Materials', 'url' => 'indirect-materials.php', 'parent' => 'indirect_materials'),
	  'show_indirect_material' => array('name' => 'Indirect Material Details', 'url' => 'indirect-materials-show.php', 'parent' => 'indirect_materials'),
	  'add_indirect_material' => array('name' => 'New Indirect Material', 'url' => 'indirect-materials-new.php', 'parent' => 'indirect_materials'),
	  'edit_indirect_material' => array('name' => 'Update Indirect Material', 'url' => 'indirect-materials-edit.php', 'parent' => 'indirect_materials'),
	  'delete_indirect_material' => array('name' => 'Remove Indirect Material', 'url' => 'indirect-materials-delete.php', 'parent' => 'indirect_materials'),
	//location
		'locations' => array('name' => 'Locations', 'url' => 'locations.php', 'parent' => 'locations'),
	  'show_location' => array('name' => 'Location Address Details', 'url' => 'locations-show.php', 'parent' => 'locations'),
	  'add_location' => array('name' => 'New Location Address', 'url' => 'locations-new.php', 'parent' => 'locations'),
	  'edit_location' => array('name' => 'Update Location Address', 'url' => 'locations-edit.php', 'parent' => 'locations'),
	  'delete_location' => array('name' => 'Remove Location Address', 'url' => 'locations-delete.php', 'parent' => 'locations'),
	//material
		'materials' => array('name' => 'Materials', 'url' => 'materials.php', 'parent' => 'materials'),
	  'show_material' => array('name' => 'Material Details', 'url' => 'materials-show.php', 'parent' => 'materials'),
	  'add_material' => array('name' => 'New Material', 'url' => 'materials-new.php', 'parent' => 'materials'),
	  'edit_material' => array('name' => 'Update Material', 'url' => 'materials-edit.php', 'parent' => 'materials'),
	  'delete_material' => array('name' => 'Remove Material', 'url' => 'materials-delete.php', 'parent' => 'materials'),
	  'add_material_rev' => array('name' => 'Material Revision', 'url' => 'materials-rev.php', 'parent' => 'materials'),
	  'add_material_sup' => array('name' => 'New Material Supplier', 'url' => 'materials-sup.php', 'parent' => 'materials'),
	  'publish_material' => array('name' => 'Publish Material', 'url' => '', 'parent' => 'materials'),
	//material inventory
		'material_inventory' => array('name' => 'Material Inventory', 'url' => 'minventory.php', 'parent' => 'material_inventory'),
	  'show_material_inventory' => array('name' => 'Material Inventory Details', 'url' => 'minventory-show.php', 'parent' => 'material_inventory'),
	//notification
	  'notifications' => array('name' => 'Notifications', 'url' => 'notifications.php', 'parent' => 'notifications'),
	//order
	  'orders' => array('name' => 'Orders', 'url' => 'orders.php', 'parent' => 'orders'),
	  'show_order' => array('name' => 'Order Details', 'url' => 'orders-show.php', 'parent' => 'orders'),
	  'add_order' => array('name' => 'New Order', 'url' => 'orders-new.php', 'parent' => 'orders'),
	  'edit_order' => array('name' => 'Update Order', 'url' => 'orders-edit.php', 'parent' => 'orders'),
	  'delete_order' => array('name' => 'Remove Order', 'url' => '', 'parent' => 'orders'),
	  'publish_order' => array('name' => 'Publish Order', 'url' => '', 'parent' => 'orders'),
	//product
	  'products' => array('name' => 'Products', 'url' => 'products.php', 'parent' => 'products'),
	  'show_product' => array('name' => 'Product Details', 'url' => 'products-show.php', 'parent' => 'products'),
	  'add_product' => array('name' => 'New Product', 'url' => 'products-new.php', 'parent' => 'products'),
	  'edit_product' => array('name' => 'Update Product', 'url' => 'products-edit.php', 'parent' => 'products'),
	  'delete_product' => array('name' => 'Remove Product', 'url' => '', 'parent' => 'products'),	  
		'show_parts_tree' => array('name' => 'Parts Tree', 'url' => 'parts-tree-show.php', 'parent' => 'products'),
	  'edit_parts_tree' => array('name' => 'Update Parts Tree', 'url' => 'parts-tree-edit.php', 'parent' => 'products'),
	//product inventory
		'product_inventory' => array('name' => 'Product Inventory', 'url' => 'pinventory.php', 'parent' => 'product_inventory'),
	  'show_product_inventory' => array('name' => 'Product Inventory Details', 'url' => 'pinventory-show.php', 'parent' => 'product_inventory'),
  //production plan	
		'production_plan' => array('name' => 'Production Plan', 'url' => 'production-plan.php', 'parent' => 'production_plan'),
		'show_production_plan' => array('name' => 'Production Plan Details', 'url' => 'production-plan-show.php', 'parent' => 'production_plan'),
		'edit_production_plan' => array('name' => 'Update Production Plan', 'url' => 'production-plan-edit.php', 'parent' => 'production_plan'),				
		'show_production_plan_parts' => array('name' => 'Production Plan Product Parts', 'url' => 'production-plan-parts-show.php', 'parent' => 'production_plan'),		
		'show_production_plan_parts_request' => array('name' => 'Product Parts Request', 'url' => 'production-plan-parts-request.php', 'parent' => 'production_plan'),				
		'show_production_plan_terminals' => array('name' => 'Production Plan Terminals', 'url' => 'production-plan-terminals-show.php', 'parent' => 'production_plan'),			
		'show_production_line' => array('name' => 'Production Line', 'url' => 'production-line-show.php', 'parent' => 'production_plan'),			
			
			
		'terminal_prod_items' => array('name' => 'Terminal Items', 'url' => 'terminal-prod-items.php', 'parent' => 'terminal_prod_items'),
	//profile
		'show_profile' => array('name' => 'Profile', 'url' => 'profile-show.php', 'parent' => 'profile'),
	  'edit_profile' => array('name' => 'Update Profile', 'url' => 'profile-edit.php', 'parent' => 'profile'),
  //purchase
		'purchases' => array('name' => 'Purchases', 'url' => 'purchases.php', 'parent' => 'purchases'),
	  'show_purchase' => array('name' => 'Purchase Details', 'url' => 'purchases-show.php', 'parent' => 'purchases'),
	  'add_purchase' => array('name' => 'New Purchase', 'url' => 'purchases-new.php', 'parent' => 'purchases'),
	  'edit_purchase' => array('name' => 'Update Purchase', 'url' => 'purchases-edit.php', 'parent' => 'purchases'),
	  'delete_purchase' => array('name' => 'Remove Purchase', 'url' => '', 'parent' => 'purchases'),
	  'publish_purchase' => array('name' => 'Publish Purchase', 'url' => '', 'parent' => 'purchases'),
	//receiving
		'receiving' => array('name' => 'Receiving', 'url' => 'receiving.php', 'parent' => 'receiving'),
	  'add_receiving' => array('name' => 'New Receiving', 'url' => 'receiving-new.php', 'parent' => 'receiving'),
	  'edit_receiving' => array('name' => 'Update Receiving', 'url' => 'receiving-edit.php', 'parent' => 'receiving'),
	  'publish_receiving' => array('name' => 'Publish Receiving', 'url' => '', 'parent' => 'receiving'),
	//role
		'roles' => array('name' => 'Roles', 'url' => 'roles.php', 'parent' => 'roles'),
	  'add_role' => array('name' => 'New Role', 'url' => 'roles-new.php', 'parent' => 'roles'),
	  'show_role' => array('name' => 'Role Details', 'url' => 'roles-show.php', 'parent' => 'roles'),
	  'edit_role' => array('name' => 'Update Role', 'url' => 'roles-edit.php', 'parent' => 'roles'),
	  'delete_role' => array('name' => 'Remove Role', 'url' => 'roles-delete.php', 'parent' => 'roles'),
	//supplier 
		'suppliers' => array('name' => 'Suppliers', 'url' => 'suppliers.php', 'parent' => 'suppliers'),
	  'show_supplier' => array('name' => 'Supplier Details', 'url' => 'suppliers-show.php', 'parent' => 'suppliers'),
	  'add_supplier' => array('name' => 'New Supplier', 'url' => 'suppliers-new.php', 'parent' => 'suppliers'),
	  'edit_supplier' => array('name' => 'Update Supplier', 'url' => 'suppliers-edit.php', 'parent' => 'suppliers'),
	  'delete_supplier' => array('name' => 'Remove Supplier', 'url' => '', 'parent' => 'suppliers'),
	  'publish_supplier' => array('name' => 'Publish Supplier', 'url' => '', 'parent' => 'suppliers'),	
	//terminal
		'terminals' => array('name' => 'Terminals', 'url' => 'terminals.php', 'parent' => 'terminals'),
	  'show_terminal' => array('name' => 'Terminal Details', 'url' => 'terminals-show.php', 'parent' => 'terminals'),
	  'add_terminal' => array('name' => 'New Terminal', 'url' => 'terminals-new.php', 'parent' => 'terminals'),
	  'edit_terminal' => array('name' => 'Update Terminal', 'url' => 'terminals-edit.php', 'parent' => 'terminals'),
	  'delete_terminal' => array('name' => 'Remove Terminal', 'url' => 'terminals-delete.php', 'parent' => 'terminals'),
	  
		
	  'terminal_items' => array('name' => 'Terminal Items', 'url' => 'terminal-wh-items.php', 'parent' => 'terminals'),
	//user
		'users' => array('name' => 'Users', 'url' => 'users.php', 'parent' => 'users'),
	  'show_user' => array('name' => 'User Details', 'url' => 'users-show.php', 'parent' => 'users'),
	  'add_user' => array('name' => 'New User', 'url' => 'users-new.php', 'parent' => 'users'),
	  'edit_user' => array('name' => 'Update User', 'url' => 'users-edit.php', 'parent' => 'users'),
	  'delete_user' => array('name' => 'Remove User', 'url' => '', 'parent' => 'users'),
	  'publish_user' => array('name' => 'Publish User', 'url' => '', 'parent' => 'users'),
	  
		
		
	  
		
	  
	  'add_terminal_production' => array('name' => 'Production Terminal', 'url' => 'terminal-production.php', 'parent' => 'add_terminal_production'),	    	
	
	  'material_requests' => array('name' => 'Material Requests', 'url' => 'material-requests.php', 'parent' => 'material_requests'),	
	  'show_material_request' => array('name' => 'Material Request Details', 'url' => 'material-requests-show.php', 'parent' => 'material_requests'),
	  'add_material_request' => array('name' => 'New Material Request', 'url' => 'material-requests-new.php', 'parent' => 'material_requests'),
	  'edit_material_request' => array('name' => 'Update Material Request', 'url' => 'material-requests-edit.php', 'parent' => 'material_requests'),	  
			  
	  
		'settings' => array('name' => 'Preferences', 'url' => 'settings.php', 'parent' => 'settings'),
	  'show_settings' => array('name' => 'Settings', 'url' => 'settings-show.php', 'parent' => 'settings'),
	  'add_settings' => array('name' => 'New Settings', 'url' => 'settings-new.php', 'parent' => 'settings'),
	  'edit_settings' => array('name' => 'Update Settings', 'url' => 'settings-edit.php', 'parent' => 'settings'),
	  
	  'show_settings_lookups' => array('name' => 'Lookups', 'url' => 'settings-lookups-show.php', 'parent' => 'settings'),
	  'edit_settings_lookups' => array('name' => 'Update Lookups', 'url' => 'settings-lookups-edit.php', 'parent' => 'settings'),
  );
  
  
	
}
