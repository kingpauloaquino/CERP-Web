<?php

class Menu {
  
  public $hello = 'hello world';
  
  /* Constructor */
  public function Main() {
	return array(
	  'dashboard'			=> 'Dashboard',
	  'production_plan'	=> 'Production Plan',
	  'materials'			=> 'Materials',
	  'products'			=> 'Products',
	  'orders'				=> 'Orders',
	  'inventory'			=> 'Inventory',
	  'material_inventory'	=> 'Material Inventory',
	  'product_inventory'	=> 'Product Inventory',
	  'purchasing'		=> 'Purchasing',
	  'production'		=> 'Production',
	  'warehouse'			=> 'Warehouse',
	  'suppliers'			=> 'Suppliers',
	  'locations'			=> 'Locations',
	  'terminals'			=> 'Terminals',
	  'devices'				=> 'Devices',
	  'reports'				=> 'Reports',
	  'users'					=> 'Users',
	  'settings'			=> 'Settings'
	);
  }
  
  function Childrens($key) {
  	$childrens = array(
	  'users'				=> array('add_user' => 'Add User', 'roles' => 'Roles'),
	  'materials'			=> array('add_material' => 'Add Material', 'add_material_rev' => 'Add Mat\'l Rev'),
	  'products'			=> array('add_product' => 'Add Products'),
	  'orders'			=> array('add_order' => 'Add Order'),
	  'inventory'			=> array('material_inventory' => 'All Materials', 'product_inventory' => 'All Products'),
	  'material_inventory'	=> array('add_material_inventory' => 'Add Entry'),
	  'product_inventory'	=> array('add_product_inventory' => 'Add Entry'),
	  'suppliers'			=> array('add_supplier' => 'Add Supplier'),
	  'locations'			=> array('add_location' => 'Add Location Address'),
	  'terminals'			=> array('add_terminal' => 'Add Terminal')
	);
    return $childrens[$key];
  }
}
