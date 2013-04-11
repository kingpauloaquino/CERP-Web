<?php
class Query {
  private $DB;
  
  // Constructor
  function Query($db) {
  	$this->DB = $db;
  }
  
  // Module:Purchasing
  // Get Purchase By ID
  function purchase_by_id($id) {
  	$query = $this->DB->Fetch('purchases', array(
               'columns'  => 'purchases.id, purchase_number, suppliers.id AS supplier_id, suppliers.name AS supplier_name, 
                              suppliers.address AS supplier_address, suppliers.contact_no1 AS supplier_phone, suppliers.fax_no AS supplier_fax,
                              suppliers.representative AS supplier_person, delivery_date, delivery_via, trade_terms, 
                              payment_terms, total_amount, remarks, lookups.description AS status,
                              purchases.created_at',
               'joins' => 'INNER JOIN suppliers ON suppliers.id = purchases.supplier_id 
                           INNER JOIN lookups ON lookups.id = purchases.status',
               'conditions' => 'purchases.id = '. $id)
             );
	
	if(!empty($query)) return $query[0];
	return null;
  }
  
  // Get Purchase Items By ID
  function purchase_items_by_id($id) {
  	$query = $this->DB->Fetch('purchase_items', array(
               'columns' => 'purchase_items.id, purchase_items.item_id, material_code AS code, materials.description, 
                             lookups.description AS unit, brand_model, material_type, quantity, item_price',
               'joins' => 'INNER JOIN materials ON materials.id = purchase_items.item_id
                           LEFT OUTER JOIN item_costs ON item_type = "MAT" AND item_costs.item_id = purchase_items.item_id
                           LEFT OUTER JOIN lookups ON lookups.id = item_costs.unit',
               'conditions' => 'purchase_id ='. $id)
             );
	
	if(!empty($query)) return $query;
	return null;
  }

  // Module::Delivery
  function uniqueness_of_delivery($receipt) {
  	return $this->DB->Fetch('deliveries', array('columns' => 'id, delivery_receipt', 'conditions' => 'delivery_receipt = "'. $receipt .'"'));
  }
  
  function get_delivery_detail($id) {
    $query = $this->DB->Fetch('deliveries', array(
               'columns' => 'deliveries.id, delivery_receipt, delivery_date, name AS supplier_name, 
                             status, lookups.description AS status_description, delivery_via, trade_terms, payment_terms, remarks',
               'joins' => 'INNER JOIN suppliers ON suppliers.id = supplier_id
                           INNER JOIN lookups ON lookups.id = status',
		       'conditions' => 'deliveries.id = '. $id)
             );
	
	if(!empty($query)) return $query[0];
	return null;
  }

  // Module::Receiving
  
  // Get Purchase By ID
  function receive_purchase_id($id) {
  	$query = $this->DB->Fetch('purchases', array(
               'columns'  => 'purchases.id AS purchase_id, purchase_number, invoice_number, invoice_date, purchases.received,
                              delivery_receipt, receiving.delivery_date AS receive_date, suppliers.name AS supplier_name,
                              delivery_by, shipment_status, receiving.remarks, purchases.delivery_date, purchases.created_at',
               'joins' => 'INNER JOIN suppliers ON suppliers.id = purchases.supplier_id
                           LEFT OUTER JOIN receiving ON receiving.purchase_id = purchases.id',
               'conditions' => 'purchases.id = '. $id)
             );
	
	if(!empty($query)) return $query[0];
	return null;
  }

  function delivery_items() {
  	$query = $this->DB->Fetch('purchase_items', array(
               'columns' => 'purchase_items.id, purchase_items.item_id, material_code AS code, materials.description, 
                             lookups.description AS unit, brand_model, material_type, quantity, item_price',
               'joins' => 'INNER JOIN materials ON materials.id = purchase_items.item_id
                           LEFT OUTER JOIN item_costs ON item_type = "MAT" AND item_costs.item_id = purchase_items.item_id
                           LEFT OUTER JOIN lookups ON lookups.id = item_costs.unit')
             );
	
	if(!empty($query)) return $query;
	return null;
  }
	
  // Module:Orders
  // Get Order By ID
  function order_by_id($id) {
		$query = $this->DB->Fetch('orders', array(
			  			'columns' => 'orders.id, orders.po_number, orders.po_date, orders.terms, orders.delivery_date, orders.description, orders.payment_terms AS payment_terms_id,
			  										orders.status AS status_id, lookups2.description AS status, orders.total_amount, orders.remarks, suppliers.name AS client, lookups.description AS payment_terms', 
			  	  	'joins' => 'INNER JOIN suppliers ON suppliers.id = orders.client_id
			  	  							INNER JOIN lookups ON lookups.id = orders.payment_terms
			  	  							INNER JOIN lookups AS lookups2 ON lookups2.id = orders.status',
			  	  	'conditions' => 'orders.id = '.$id)
						);	 
						 
	if(!empty($query)) return $query[0];
	return null;
  }
}