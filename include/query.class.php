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
               'columns'  => 'purchases.id, po_number, purchases.po_date, suppliers.id AS supplier_id, suppliers.name AS supplier_name, 
                              suppliers.address AS supplier_address, suppliers.contact_no1 AS supplier_phone, suppliers.fax_no AS supplier_fax,
                              suppliers.representative AS supplier_person, delivery_date, delivery_via, terms, 
                              purchases.status AS status_id, purchases.completion_status AS completion_status_id,
                              payment_terms, total_amount, remarks, lookup_status.description AS status,lookup_status2.description AS completion_status,
                              purchases.created_by, purchases.created_at, purchases.checked_by, purchases.checked_at, purchases.approved_by, purchases.approved_at',
               'joins' => 'INNER JOIN suppliers ON suppliers.id = purchases.supplier_id 
                           INNER JOIN lookup_status ON lookup_status.id = purchases.status
                           INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = purchases.completion_status',
               'conditions' => 'purchases.id = '. $id)
             );
	
		if(!empty($query)) return $query[0];
		return null;
  }
  
  // Get Purchase Items By ID
  function purchase_items_by_id($id) {
  	$query = $this->DB->Fetch('purchase_items', array(
               'columns' => 'purchase_items.id, purchase_items.item_id, material_code AS code, materials.description, quantity,
														lookups.description AS unit, item_price, item_costs.currency',
               'joins' => 'INNER JOIN purchases ON purchases.id = purchase_items.purchase_id
														INNER JOIN materials ON materials.id = purchase_items.item_id
														INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type="MAT" AND item_costs.supplier = purchases.supplier_id
														INNER JOIN lookups ON lookups.id = item_costs.unit',
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
	
  // Module:Purchase Orders
  // Get Purchase Order By ID
  function purchase_order_by_id($id) {
		$query = $this->DB->Fetch('purchase_orders', array(
			  			'columns' => 'purchase_orders.id, purchase_orders.po_number, purchase_orders.po_date, purchase_orders.terms, purchase_orders.ship_date, 
			  										purchase_orders.payment_terms AS payment_terms_id, purchase_orders.completion_status AS completion_status_id, lookup_status2.description AS completion_status,
			  										purchase_orders.status AS status_id, lookup_status.description AS status, purchase_orders.total_amount, purchase_orders.remarks, 
			  										suppliers.name AS client, lookups.description AS payment_terms', 
			  	  	'joins' => 'INNER JOIN suppliers ON suppliers.id = purchase_orders.client_id
			  	  							INNER JOIN lookups ON lookups.id = purchase_orders.payment_terms
			  	  							INNER JOIN lookup_status ON lookup_status.id = purchase_orders.status
			  	  							INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = purchase_orders.completion_status',
			  	  	'conditions' => 'purchase_orders.id = '.$id)
						);	 
						 
	if(!empty($query)) return $query[0];
	return null;
  }

  function delivery_by_id($id) {
  	$query = $this->DB->Fetch('deliveries', array(
               'columns'  => 'deliveries.id, purchases.id AS pid, purchases.po_number, suppliers.id AS supplier_id, suppliers.name AS supplier_name, 
                              deliveries.delivery_date, deliveries.delivery_via, purchases.terms, purchases.payment_terms, 
                              purchases.total_amount, deliveries.remarks, lookup_status.description AS status, lookup_status1.description AS completion_status,
                              purchases.po_date, deliveries.status AS status_id',
               'joins' => 'INNER JOIN purchases ON purchases.id = deliveries.purchase_id 
               						 INNER JOIN suppliers ON suppliers.id = purchases.supplier_id 
                           INNER JOIN lookup_status ON lookup_status.id = deliveries.status
                           INNER JOIN lookup_status AS lookup_status1 ON lookup_status1.id = deliveries.completion_status',
               'conditions' => 'deliveries.id = '. $id)
             );
	
		if(!empty($query)) return $query[0];
		return null;
  }
	
	
	function work_order_by_id($id) {
		$query = $this->DB->Fetch('work_orders', array(
			  			'columns' => 'work_orders.id, work_orders.wo_number, work_orders.ship_date, work_orders.remarks, lookup_status.description AS status, work_orders.total_amount, 
			  										lookup_status2.description AS completion_status, work_orders.wo_date, work_orders.completion_status AS completion_status_id, work_orders.status AS status_id', 
			  	  	'joins' => 'INNER JOIN lookup_status ON lookup_status.id = work_orders.status
			  	  							INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = work_orders.completion_status',
			  	  	'conditions' => 'work_orders.id = '.$id)
						);	 
						 
		if(!empty($query)) return $query[0];
		return null;
  }
	
	function product_by_work_order_item($id) {
		$query = $this->DB->Fetch('work_order_items', array(
			  			'columns' => 'work_order_items.id, work_order_items.product_id, products.product_code', 
			  			'joins' => 'INNER JOIN products ON products.id = work_order_items.product_id',
			  	  	'conditions' => 'work_order_items.id = '.$id)
						);	 
						 
		if(!empty($query)) return $query[0];
		return null;
	}

	function product_by_purchase_order_item($id) {
		$query = $this->DB->Fetch('purchase_order_items', array(
			  			'columns' => 'purchase_order_items.id, purchase_order_items.item_id, products.product_code', 
			  			'joins' => 'INNER JOIN products ON products.id = purchase_order_items.item_id AND purchase_order_items.item_type = "PRD"',
			  	  	'conditions' => 'purchase_order_items.id = '.$id)
						);	 
						 
		if(!empty($query)) return $query[0];
		return null;
	}

	function invoice_by_number($number) {
  	$query = $this->DB->Fetch('delivery_items', array(
               'columns'  => 'invoice, suppliers.name AS supplier, purchases.terms, purchases.payment_terms, receive_date',
               'joins' => 'INNER JOIN deliveries ON deliveries.id = delivery_id
														INNER JOIN purchases ON purchases.id = deliveries.purchase_id
														INNER JOIN suppliers ON suppliers.id = purchases.supplier_id',
               'conditions' => 'invoice = "'. $number .'"')
             );
	
		if(!empty($query)) return $query[0];
		return null;
  }
	
	// function product_by_id($id) {
		// $query = $this->DB->Fetch('products', array(
			  			// 'columns' => 'products.*', 
			  	  	// 'conditions' => 'products.id = '.$id)
						// );	 
// 						 
		// if(!empty($query)) return $query[0];
		// return null;
	// }
	
	function product_by_id($id) {
		$query = $this->DB->Fetch('products', array(
					  			'columns' 		=> 'products.product_code, products.description, brand_models.brand_model AS brand, lookups.description AS status, item_classifications.classification,
					  												products.bar_code, products.color, products.production_qty', 
					  	    'conditions' 	=> 'products.id = '.$id, 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON products.brand_model = brand_models.id
																		LEFT OUTER JOIN lookups ON products.status = lookups.id
																		LEFT OUTER JOIN item_classifications ON item_classifications.id = products.product_classification'
	  	  ));
				
		if(!empty($query)) return $query[0];
		return null;
	}
	
}