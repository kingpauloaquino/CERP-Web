<?php

class Capabilities {
  
  private $db;
  public $All = array(
	  'dashboard' => array('name' => 'Dashboard', 'url' => 'index.php', 'parent' => 'dashboard'),
	  
	  'notifications' => array('name' => 'Notifications', 'url' => 'notifications.php', 'parent' => 'notifications'),
	  
	  'orders' => array('name' => 'Orders', 'url' => 'orders.php', 'parent' => 'orders'),
	  'show_order' => array('name' => 'Order Details', 'url' => 'orders-show.php', 'parent' => 'orders'),
	  'add_order' => array('name' => 'New Order', 'url' => 'orders-new.php', 'parent' => 'orders'),
	  'edit_order' => array('name' => 'Edit Order', 'url' => 'orders-edit.php', 'parent' => 'orders'),
	  'delete_order' => array('name' => 'Delete Order', 'url' => '', 'parent' => 'orders'),
	  'publish_order' => array('name' => 'Publish Order', 'url' => '', 'parent' => 'orders'),
	    
	  'materials' => array('name' => 'Materials', 'url' => 'materials.php', 'parent' => 'materials'),
	  'show_material' => array('name' => 'Material Details', 'url' => 'materials-show.php', 'parent' => 'materials'),
	  'add_material' => array('name' => 'New Material', 'url' => 'materials-new.php', 'parent' => 'materials'),
	  'add_material_rev' => array('name' => 'New Material [New Revision]', 'url' => 'materials-rev.php', 'parent' => 'materials'),
	  'add_material_sup' => array('name' => 'New Material Supplier', 'url' => 'materials-sup.php', 'parent' => 'materials'),
	  'edit_material' => array('name' => 'Edit Material', 'url' => 'materials-edit.php', 'parent' => 'materials'),
	  'delete_material' => array('name' => 'Delete Material', 'url' => '', 'parent' => 'materials'),
	  'publish_material' => array('name' => 'Publish Material', 'url' => '', 'parent' => 'materials'),
	  
		'indirect_materials' => array('name' => 'Indirect Materials', 'url' => 'indirect-materials.php', 'parent' => 'indirect_materials'),
	  'show_indirect_material' => array('name' => 'Indirect Material Details', 'url' => 'indirect-materials-show.php', 'parent' => 'indirect_materials'),
	  'add_indirect_material' => array('name' => 'New Indirect Material', 'url' => 'indirect-materials-new.php', 'parent' => 'indirect_materials'),
	  'edit_indirect_material' => array('name' => 'Edit Indirect Material', 'url' => 'indirect-materials-edit.php', 'parent' => 'indirect_materials'),
	  
	  'products' => array('name' => 'Products', 'url' => 'products.php', 'parent' => 'products'),
	  'show_product' => array('name' => 'Product Details', 'url' => 'products-show.php', 'parent' => 'products'),
	  'add_product' => array('name' => 'New Product', 'url' => 'products-new.php', 'parent' => 'products'),
	  'edit_product' => array('name' => 'Edit Product', 'url' => 'products-edit.php', 'parent' => 'products'),
	  
	  'show_product_tree' => array('name' => 'Parts Tree', 'url' => 'productstree-show.php', 'parent' => 'products'),
	  'add_product_tree' => array('name' => 'New New Part', 'url' => 'productstree-new.php', 'parent' => 'products'),
	  'edit_product_tree' => array('name' => 'Edit Parts Tree', 'url' => 'productstree-edit.php', 'parent' => 'products'),
	  
		'show_parts_tree' => array('name' => 'Parts Tree', 'url' => 'parts-tree-show.php', 'parent' => 'products'),
	  'add_parts_tree' => array('name' => 'New Part', 'url' => 'parts-tree-new.php', 'parent' => 'products'),
	  'edit_parts_tree' => array('name' => 'Edit Parts Tree', 'url' => 'parts-tree-edit.php', 'parent' => 'products'),
	  
	  'delete_product' => array('name' => 'Delete Product', 'url' => '', 'parent' => 'products'),
	  'publish_product' => array('name' => 'Publish Product', 'url' => '', 'parent' => 'products'),
	    
	  'inventory' => array('name' => 'Inventory', 'url' => 'inventory.php', 'parent' => 'inventory'),
	  
		'production_plan' => array('name' => 'Production Plan', 'url' => 'prodplan.php', 'parent' => 'production_plan'),
		
		'production_plan' => array('name' => 'Production Plan', 'url' => 'production-plan.php', 'parent' => 'production_plan'),
		'show_production_plan' => array('name' => 'Production Plan Details', 'url' => 'production-plan-show.php', 'parent' => 'production_plan'),
		'edit_production_plan' => array('name' => 'Edit Production Plan', 'url' => 'production-plan-edit.php', 'parent' => 'production_plan'),
		
		'show_production_plan_parts' => array('name' => 'Production Plan Product Parts', 'url' => 'production-plan-parts-show.php', 'parent' => 'production_plan'),
		'edit_production_plan_parts' => array('name' => 'Edit Production Plan Product Parts', 'url' => 'production-plan-parts-edit.php', 'parent' => 'production_plan'),
		
		'show_production_plan_parts_request' => array('name' => 'Product Parts Request', 'url' => 'production-plan-parts-request.php', 'parent' => 'production_plan'),
		
		'show_production_plan_terminals' => array('name' => 'Production Plan Terminals', 'url' => 'production-plan-terminals-show.php', 'parent' => 'production_plan'),
		
		'show_production_line' => array('name' => 'Production Line', 'url' => 'production-line-show.php', 'parent' => 'production_plan'),
				
		'terminal_prod_items' => array('name' => 'Terminal Items', 'url' => 'terminal-prod-items.php', 'parent' => 'terminal_prod_items'),
		
		'locations' => array('name' => 'Locations', 'url' => 'locations.php', 'parent' => 'locations'),
	  'show_location' => array('name' => 'Location Address Details', 'url' => 'locations-show.php', 'parent' => 'locations'),
	  'add_location' => array('name' => 'New Location Address', 'url' => 'locations-new.php', 'parent' => 'locations'),
	  'edit_location' => array('name' => 'Edit Location Address', 'url' => 'locations-edit.php', 'parent' => 'locations'),
	  
		'terminals' => array('name' => 'Terminals', 'url' => 'terminals.php', 'parent' => 'terminals'),
	  'show_terminal' => array('name' => 'Terminal Details', 'url' => 'terminals-show.php', 'parent' => 'terminals'),
	  'add_terminal' => array('name' => 'New Terminal', 'url' => 'terminals-new.php', 'parent' => 'terminals'),
	  'edit_terminal' => array('name' => 'Edit Terminal', 'url' => 'terminals-edit.php', 'parent' => 'terminals'),
	  'terminal_items' => array('name' => 'Terminal Items', 'url' => 'terminal-wh-items.php', 'parent' => 'terminals'),
	  
	  'add_terminal_wh1' => array('name' => 'New Warehouse Inventory', 'url' => 'terminal-wh1.php', 'parent' => 'add_terminal_wh1'),
	  'add_terminal_production' => array('name' => 'Production Terminal', 'url' => 'terminal-production.php', 'parent' => 'add_terminal_production'),
	  
	  'material_inventory' => array('name' => 'Material Inventory', 'url' => 'minventory.php', 'parent' => 'material_inventory'),
	  'show_material_inventory' => array('name' => 'Material Inventory Details', 'url' => 'minventory-show.php', 'parent' => 'material_inventory'),
	  'add_material_inventory' => array('name' => 'Warehouse Material Inventory', 'url' => 'minventory-new.php', 'parent' => 'material_inventory'),
	  'edit_material_inventory' => array('name' => 'Edit Material Inventory', 'url' => 'minventory-edit.php', 'parent' => 'material_inventory'),
	  'delete_material_inventory' => array('name' => 'Delete Material Inventory', 'url' => '', 'parent' => 'material_inventory'),
	  'publish_material_inventory' => array('name' => 'Publish Material Inventory', 'url' => '', 'parent' => 'material_inventory'),
	  'material_inventory_history' => array('name' => 'Material Inventory History', 'url' => 'minventory-history.php', 'parent' => 'material_inventory'),
	  
	  'product_inventory' => array('name' => 'Product Inventory', 'url' => 'pinventory.php', 'parent' => 'product_inventory'),
	  'show_product_inventory' => array('name' => 'Product Inventory Details', 'url' => 'pinventory-show.php', 'parent' => 'product_inventory'),
	  'add_product_inventory' => array('name' => 'Warehouse Product Inventory', 'url' => 'pinventory-new.php', 'parent' => 'product_inventory'),
	  'edit_product_inventory' => array('name' => 'Edit Product Inventory', 'url' => 'pinventory-edit.php', 'parent' => 'product_inventory'),
	  'delete_product_inventory' => array('name' => 'Delete Product Inventory', 'url' => '', 'parent' => 'product_inventory'),
	  'publish_product_inventory' => array('name' => 'Publish Product Inventory', 'url' => '', 'parent' => 'product_inventory'),
	  'product_inventory_history' => array('name' => 'Product Inventory History', 'url' => 'pinventory-history.php', 'parent' => 'product_inventory'),
	  
	  'purchases' => array('name' => 'Purchases', 'url' => 'purchases.php', 'parent' => 'purchases'),
	  'show_purchase' => array('name' => 'Purchase Details', 'url' => 'purchases-show.php', 'parent' => 'purchases'),
	  'add_purchase' => array('name' => 'New Purchase', 'url' => 'purchases-new.php', 'parent' => 'purchases'),
	  'edit_purchase' => array('name' => 'Edit Purchase', 'url' => 'purchases-edit.php', 'parent' => 'purchases'),
	  'delete_purchase' => array('name' => 'Delete Purchase', 'url' => '', 'parent' => 'purchases'),
	  'publish_purchase' => array('name' => 'Publish Purchase', 'url' => '', 'parent' => 'purchases'),
	  
		'deliveries' => array('name' => 'Deliveries', 'url' => 'deliveries.php', 'parent' => 'deliveries'),
		'show_deliveries' => array('name' => 'Show Delivery', 'url' => 'deliveries-show.php', 'parent' => 'deliveries'),
		'add_deliveries' => array('name' => 'New Delivery', 'url' => 'deliveries-new.php', 'parent' => 'deliveries'),
		'edit_deliveries' => array('name' => 'Edit Delivery', 'url' => 'deliveries-edit.php', 'parent' => 'deliveries'),
		
	
	  'material_requests' => array('name' => 'Material Requests', 'url' => 'material-requests.php', 'parent' => 'material_requests'),	
	  'show_material_request' => array('name' => 'Material Request Details', 'url' => 'material-requests-show.php', 'parent' => 'material_requests'),
	  'add_material_request' => array('name' => 'New Material Request', 'url' => 'material-requests-new.php', 'parent' => 'material_requests'),
	  'edit_material_request' => array('name' => 'Edit Material Request', 'url' => 'material-requests-edit.php', 'parent' => 'material_requests'),
		
	  'suppliers' => array('name' => 'Suppliers', 'url' => 'suppliers.php', 'parent' => 'suppliers'),
	  'show_supplier' => array('name' => 'Supplier Details', 'url' => 'suppliers-show.php', 'parent' => 'suppliers'),
	  'add_supplier' => array('name' => 'New Supplier', 'url' => 'suppliers-new.php', 'parent' => 'suppliers'),
	  'edit_supplier' => array('name' => 'Edit Supplier', 'url' => 'suppliers-edit.php', 'parent' => 'suppliers'),
	  'delete_supplier' => array('name' => 'Delete Supplier', 'url' => '', 'parent' => 'suppliers'),
	  'publish_supplier' => array('name' => 'Publish Supplier', 'url' => '', 'parent' => 'suppliers'),
		
	  'warehouse' => array('name' => 'Warehouse', 'url' => 'inventory.php', 'parent' => 'warehouse'),

	  'receiving' => array('name' => 'Receiving', 'url' => 'receiving.php', 'parent' => 'receiving'),
	  'add_receiving' => array('name' => 'New Receiving', 'url' => 'receiving-new.php', 'parent' => 'receiving'),
	  'edit_receiving' => array('name' => 'Edit Receiving', 'url' => 'receiving-edit.php', 'parent' => 'receiving'),
	  'publish_receiving' => array('name' => 'Publish Receiving', 'url' => '', 'parent' => 'receiving'),
	  
	  'reports' => array('name' => 'Reports', 'url' => 'reports.php', 'parent' => 'reports'),
	  
	  'devices' => array('name' => 'Devices', 'url' => 'devices.php', 'parent' => 'devices'),
	  'show_device' => array('name' => 'Device Details', 'url' => 'devices-show.php', 'parent' => 'devices'),
	  'add_device' => array('name' => 'New Device', 'url' => 'devices-new.php', 'parent' => 'devices'),
	  'edit_device' => array('name' => 'Edit Device', 'url' => 'devices-edit.php', 'parent' => 'devices'),
	  'delete_device' => array('name' => 'Delete Device', 'url' => '', 'parent' => 'devices'),
	  'publish_device' => array('name' => 'Publish Device', 'url' => '', 'parent' => 'devices'),
	  
		'defects' => array('name' => 'Defects', 'url' => 'defects.php', 'parent' => 'defects'),
		'defect-reports' => array('name' => 'Reports', 'url' => 'defect-reports.php', 'parent' => 'defects'),
	  
	  'users' => array('name' => 'Users', 'url' => 'users.php', 'parent' => 'users'),
	  'show_user' => array('name' => 'User Details', 'url' => 'users-show.php', 'parent' => 'users'),
	  'add_user' => array('name' => 'New User', 'url' => 'users-new.php', 'parent' => 'users'),
	  'edit_user' => array('name' => 'Edit User', 'url' => 'users-edit.php', 'parent' => 'users'),
	  'delete_user' => array('name' => 'Delete User', 'url' => '', 'parent' => 'users'),
	  'publish_user' => array('name' => 'Publish User', 'url' => '', 'parent' => 'users'),
	  'roles' => array('name' => 'Roles', 'url' => 'users-roles.php', 'parent' => 'users'),
	  'add_role' => array('name' => 'New Role', 'url' => 'users-roles-new.php', 'parent' => 'users'),
	  'edit_role' => array('name' => 'Edit Role', 'url' => 'users-roles-edit.php', 'parent' => 'users'),
	  'delete_role' => array('name' => 'Delete Role', 'url' => '', 'parent' => 'users'),
	  'publish_role' => array('name' => 'Publish Role', 'url' => '', 'parent' => 'users'),
	  
		'settings' => array('name' => 'Preferences', 'url' => 'settings.php', 'parent' => 'settings'),
	  'show_settings' => array('name' => 'Settings', 'url' => 'settings-show.php', 'parent' => 'settings'),
	  'add_settings' => array('name' => 'New Settings', 'url' => 'settings-new.php', 'parent' => 'settings'),
	  'edit_settings' => array('name' => 'Edit Settings', 'url' => 'settings-edit.php', 'parent' => 'settings'),
	  
	  'show_settings_lookups' => array('name' => 'Lookups', 'url' => 'settings-lookups-show.php', 'parent' => 'settings'),
	  'edit_settings_lookups' => array('name' => 'Edit Lookups', 'url' => 'settings-lookups-edit.php', 'parent' => 'settings'),
	  
  );
  
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
  
  function GetUrl() {
    global $capability_key;
	return $this->All[$capability_key]['url'];
  }
  
  function GetParent() {
    global $capability_key;
	return $this->All[$capability_key]['parent'];
  }
}
