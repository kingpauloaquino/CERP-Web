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
														INNER JOIN lookups ON lookups.id = materials.unit',
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
                           LEFT OUTER JOIN lookups ON lookups.id = materials.unit')
             );
	
	if(!empty($query)) return $query;
	return null;
  }
	
  // Module:Purchase Orders
  // Get Purchase Order By ID
  function purchase_order_by_id($id) {
		$query = $this->DB->Fetch('purchase_orders', array(
			  			'columns' => 'purchase_orders.id, purchase_orders.po_number AS order_no, purchase_orders.po_date AS order_date, purchase_orders.terms, purchase_orders.ship_date, 
			  										purchase_orders.payment_terms AS payment_terms_id, purchase_orders.completion_status AS completion_status_id, lookup_status2.description AS completion_status,
			  										purchase_orders.status AS status_id, lookup_status.description AS status, purchase_orders.total_amount, purchase_orders.remarks, 
			  										suppliers.name AS client, lookups.description AS payment_terms, purchase_orders.created_by, purchase_orders.created_at, 
			  										purchase_orders.checked_by, purchase_orders.checked_at, purchase_orders.approved_by, purchase_orders.approved_at', 
			  	  	'joins' => 'INNER JOIN suppliers ON suppliers.id = purchase_orders.client_id
			  	  							INNER JOIN lookups ON lookups.id = purchase_orders.payment_terms
			  	  							INNER JOIN lookup_status ON lookup_status.id = purchase_orders.status
			  	  							INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = purchase_orders.completion_status',
			  	  	'conditions' => 'purchase_orders.id = '.$id)
						);	 
						 
	if(!empty($query)) return $query[0];
	return null;
  }

	function purchase_order_item_by_id($poid, $pid) {
		$query = $this->DB->Fetch('purchase_orders', array(
			  			'columns' => 'purchase_orders.id, purchase_orders.po_number AS order_no, purchase_orders.po_date AS order_date, 
			  										purchase_orders.ship_date, suppliers.name AS client,
														products.product_code, purchase_order_items.quantity', 
			  	  	'joins' => 'INNER JOIN suppliers ON suppliers.id = purchase_orders.client_id
													INNER JOIN purchase_order_items ON purchase_order_items.purchase_order_id = purchase_orders.id
													INNER JOIN products ON products.id = purchase_order_items.item_id',
			  	  	'conditions' => 'purchase_orders.id = '. $poid .' AND purchase_order_items.item_id = '.$pid)
						);	 
						 
	if(!empty($query)) return $query[0];
	return null;
  }

	function work_order_by_id($id) {
		$query = $this->DB->Fetch('work_orders', array(
			  			'columns' => 'work_orders.id, work_orders.wo_number AS order_no, work_orders.wo_date AS order_date, work_orders.ship_date, 
			  										work_orders.completion_status AS completion_status_id, lookup_status2.description AS completion_status,
			  										work_orders.status AS status_id, lookup_status.description AS status, work_orders.total_amount, work_orders.remarks, 
			  										suppliers.name AS client, work_orders.created_by, work_orders.created_at, 
			  										work_orders.checked_by, work_orders.checked_at, work_orders.approved_by, work_orders.approved_at', 
			  	  	'joins' => 'INNER JOIN suppliers ON suppliers.id = work_orders.client_id
			  	  							INNER JOIN lookup_status ON lookup_status.id = work_orders.status
			  	  							INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = work_orders.completion_status',
			  	  	'conditions' => 'work_orders.id = '.$id)
						);	 
						 
	if(!empty($query)) return $query[0];
	return null;
  }

	function work_order_item_by_id($woid, $pid) {
		$query = $this->DB->Fetch('work_orders', array(
			  			'columns' => 'work_orders.id, work_orders.wo_number AS order_no, work_orders.wo_date AS order_date, work_orders.ship_date, 
			  										suppliers.name AS client, products.product_code, work_order_items.quantity', 
			  	  	'joins' => 'INNER JOIN suppliers ON suppliers.id = work_orders.client_id
													INNER JOIN work_order_items ON work_order_items.work_order_id = work_orders.id
													INNER JOIN products ON products.id = work_order_items.product_id',
			  	  	'conditions' => 'work_orders.id = '. $woid .' AND work_order_items.product_id = '.$pid)
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
					  			'columns' 		=> 'products.product_code, products.description, brand_models.brand_model AS brand, lookup_status.description AS status, product_series.series,
					  												pack_qty, products.bar_code, products.color, products.prod_cp, products.priority, 
					  												suppliers.id AS sup_id, suppliers.name AS supplier, lookups1.description AS unit, lookups2.description AS currency, item_costs.cost', 
					  	    'conditions' 	=> 'products.id = '.$id, 
					  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON products.brand_model = brand_models.id
																		LEFT OUTER JOIN lookup_status ON lookup_status.id = products.status
																		INNER JOIN product_series ON product_series.id = products.series
																		LEFT OUTER JOIN item_costs ON products.id = item_costs.item_id AND item_costs.item_type = "PRD"
																		LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
																		LEFT OUTER JOIN lookups AS lookups1 ON products.unit = lookups1.id
																		LEFT OUTER JOIN lookups AS lookups2 ON item_costs.currency = lookups2.id'
	  	  ));
				
		if(!empty($query)) return $query[0];
		return null;
	}
	
	function material_by_id($id) {
		$query = $this->DB->Fetch('materials', array(
						  			'columns' 		=> 'materials.material_code, materials.description, brand_models.brand_model, lookups1.description AS unit,
																	  	item_classifications.classification, users.id AS user_id, CONCAT(users.first_name, " ", users.last_name) AS pic,
																	  	lookups3.description AS material_type, lookup_status.description AS status', 
						  	    'conditions' 	=> 'materials.id = '.$id, 
						  	    'joins' 			=> 'LEFT OUTER JOIN brand_models ON materials.brand_model = brand_models.id 
																			LEFT OUTER JOIN item_classifications ON materials.material_classification = item_classifications.id 
																			LEFT OUTER JOIN users ON materials.person_in_charge = users.id
																			LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
																			LEFT OUTER JOIN lookups AS lookups1 ON lookups1.id = materials.unit
																			LEFT OUTER JOIN lookups AS lookups3 ON materials.material_type = lookups3.id
																			LEFT OUTER JOIN lookup_status ON materials.status = lookup_status.id'));
				
		if(!empty($query)) return $query[0];
		return null;
	}
	
	function forecast_calendar_by_product_id_year($id, $yr) {
		$query = $this->DB->Fetch('forecast_calendar', array(
					  			'columns' 		=> 'forecast_calendar.id, products.id AS product_id, products.product_code AS code, products.description, forecast_calendar.forecast_year, 
														jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dece', 
					  	    'conditions' 	=> 'products.id = '.$id, 
					  	    'joins' 			=> 'RIGHT OUTER JOIN products ON products.id = forecast_calendar.product_id AND forecast_calendar.forecast_year='.$yr
	  	  ));
				
		if(!empty($query)) return $query;
		return null;
	}

	function minventory_end_by_month_year($month_year) {
  	$query = $this->DB->Fetch('materials AS m', array(
               'columns' => 'm.id, m.material_code AS code, item_classifications.classification AS classification, 
														m.description AS description, lookups.description AS uom, 
														COALESCE(wh1.qty,0) AS qty, COALESCE(wh2.qty,0) AS physical_qty',
               'joins' => 'LEFT OUTER JOIN 
															(
															SELECT warehouse_inventories.item_id,sum(warehouse_inventories.qty) as qty
															FROM warehouse_inventories
															WHERE (EXTRACT(YEAR_MONTH FROM warehouse_inventories.created_at) <= EXTRACT(YEAR_MONTH FROM "'.$month_year.'")) AND warehouse_inventories.status=16
															GROUP BY warehouse_inventories.item_id
															) AS wh1 ON wh1.item_id = m.id
														LEFT OUTER JOIN
															(
															SELECT warehouse_inventory_actual.item_id, sum(warehouse_inventory_actual.qty) as qty
															FROM warehouse_inventory_actual
															WHERE EXTRACT(YEAR_MONTH FROM warehouse_inventory_actual.entry_date) = EXTRACT(YEAR_MONTH FROM "'.$month_year.'")
															GROUP BY warehouse_inventory_actual.item_id
															) AS wh2 ON wh2.item_id = m.id
														INNER JOIN item_classifications ON m.material_classification = item_classifications.id
														INNER JOIN item_costs ON item_costs.item_id = m.id AND item_costs.item_type = "MAT"
														INNER JOIN lookups ON lookups.id = m.unit',
               'group' => 'm.id',
							 'conditions' => 'm.material_type = 70 AND m.status=16
							 							',
 								'order' => 'code'
							 )
             );
	
	if(!empty($query)) return $query;
	return null;
  }

	function pinventory_end_by_month_year($month_year) {
  	$query = $this->DB->Fetch('products as p', array(
               'columns' => 'p.id, p.product_code AS code, brand_models.brand_model AS brand, product_series.series, p.pack_qty,
														p.description AS description, lookups.description AS uom, 
														COALESCE(wh1.qty,0) AS qty, COALESCE(wh2.qty,0) AS physical_qty',
               'joins' => 'LEFT OUTER JOIN 
															(
															SELECT warehouse2_inventories.item_id,sum(warehouse2_inventories.qty) as qty
															FROM warehouse2_inventories
															WHERE EXTRACT(YEAR_MONTH FROM warehouse2_inventories.endorse_date) <= EXTRACT(YEAR_MONTH FROM "'.$month_year.'")
															GROUP BY warehouse2_inventories.item_id
															) AS wh1 ON wh1.item_id = p.id
														LEFT OUTER JOIN
															(
															SELECT warehouse2_inventory_actual.item_id, sum(warehouse2_inventory_actual.qty) as qty
															FROM warehouse2_inventory_actual
															WHERE EXTRACT(YEAR_MONTH FROM warehouse2_inventory_actual.entry_date) = EXTRACT(YEAR_MONTH FROM "'.$month_year.'")
															GROUP BY warehouse2_inventory_actual.item_id
															) AS wh2 ON wh2.item_id = p.id
														INNER JOIN brand_models ON brand_models.id = p.brand_model
														INNER JOIN product_series ON product_series.id = p.series
														INNER JOIN item_costs ON item_costs.item_id = p.id AND item_costs.item_type = "PRD"
														INNER JOIN lookups ON lookups.id = p.unit',
               'group' => 'p.id',
 								'order' => 'code'
							 )
             );
	
	if(!empty($query)) return $query;
	return null;
  }

	function inventory_status_by_current_date($type, $count_date) {
		$query = $this->DB->Fetch('inventory_status', array(
						  			'columns' 		=> 'current_month, count_month, is_updated, is_locked, status', 
						  	    'conditions' 	=> 'type="'.$type.'" AND EXTRACT(YEAR_MONTH FROM current_month) = EXTRACT(YEAR_MONTH FROM "'.$count_date.'")'));
				
		if(!empty($query)) return $query[0];
		return null;
	}

	function latest_inventory_count($type) {
		$query = $this->DB->Fetch('inventory_status', array(
						  			'columns' 		=> 'MAX(count_month) AS count_month', 
						  	    'conditions' 	=> 'type="'.$type.'"'));
				
		if(!empty($query)) return $query[0];
		return null;
	}

	function received_by_date_by_month_year($month_year) {
  	$query = $this->DB->Fetch('delivery_items', array(
               'columns' => 'delivery_items.id, delivery_items.delivery_id, delivery_items.invoice, delivery_items.receipt, 
														delivery_items.receive_date, suppliers.id AS supplier_id, suppliers.name AS supplier_name',
               'joins' => 'INNER JOIN deliveries ON deliveries.id = delivery_items.delivery_id
														INNER JOIN purchases ON purchases.id = deliveries.purchase_id
														INNER JOIN suppliers ON suppliers.id = purchases.supplier_id',
               'group' => 'delivery_items.delivery_id',
							 'conditions' => 'EXTRACT(YEAR_MONTH FROM delivery_items.receive_date) = EXTRACT(YEAR_MONTH FROM "'.$month_year.'") 
							 							'
							 )
             );
	
	if(!empty($query)) return $query;
	return null;
  }

	function received_by_supplier_by_month_year($month_year, $sid) {
  	$query = $this->DB->Fetch('delivery_items', array(
               'columns' => 'delivery_items.id, delivery_items.delivery_id, delivery_items.invoice, delivery_items.receipt, 
														delivery_items.receive_date',
               'joins' => 'INNER JOIN deliveries ON deliveries.id = delivery_items.delivery_id
														INNER JOIN purchases ON purchases.id = deliveries.purchase_id',
               'group' => 'delivery_items.delivery_id',
							 'conditions' => 'EXTRACT(YEAR_MONTH FROM delivery_items.receive_date) = EXTRACT(YEAR_MONTH FROM "'.$month_year.'") 
							 							AND purchases.supplier_id='.$sid
							 )
             );
	
	if(!empty($query)) return $query;
	return null;
  }

	function received_by_all_supplier_by_month_year($month_year) {
  	$query = $this->DB->Fetch('delivery_items', array(
               'columns' => 'suppliers.id AS supplier_id, suppliers.name AS supplier_name',
               'joins' => 'INNER JOIN deliveries ON deliveries.id = delivery_items.delivery_id
														INNER JOIN purchases ON purchases.id = deliveries.purchase_id
														INNER JOIN suppliers ON suppliers.id = purchases.supplier_id',
               'group' => 'suppliers.id',
							 'conditions' => 'EXTRACT(YEAR_MONTH FROM delivery_items.receive_date) = EXTRACT(YEAR_MONTH FROM "'.date('Y-m-d', strtotime($month_year)).'")',
							 'order' => 'suppliers.name'
							 )
             );
	
	if(!empty($query)) return $query;
	return null;
  }
	
	function material_request_by_id($id) {
  	$query = $this->DB->Fetch('material_requests', array(
               'columns' => 'material_requests.id,material_requests.request_no, lookups.description AS type, batch_no, remarks, expected_date, 
               							requested_date, received_date, lookup_status.description AS status, lookup_status2.description AS completion_status,
               							CONCAT(users1.first_name, " ", users1.last_name) AS requested_by,
               							CONCAT(users2.first_name, " ", users2.last_name) AS received_by',
               'joins' => 'INNER JOIN lookups ON lookups.id = material_requests.request_type
               						INNER JOIN lookup_status ON lookup_status.id = material_requests.status
               						INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = material_requests.completion_status
               						LEFT OUTER JOIN users AS users1 ON users1.id = material_requests.requested_by
               						LEFT OUTER JOIN users AS users2 ON users2.id = material_requests.received_by',
							 'conditions' => 'material_requests.id='.$id,
							 'order' => 'expected_date'
							 )
             );
	
	if(!empty($query)) return $query[0];
	return null;
  }

	function get_notifications($type) {
  	$query = $this->DB->Fetch('notifications', array(
               'columns' => '*',
							 'conditions' => 'type='.$type,
							 'order' => 'created_at',
							 'sort' => 'DESC'
							 )
             );
	
		if(!empty($query)) return $query;
		return null;
  }

	function get_lookup_parents() {
  	$query = $this->DB->Fetch('lookups', array(
               'columns' => '*',
							 'conditions' => 'parent="0"',
							 'order' => 'description'
							 )
             );
	
		if(!empty($query)) return $query;
		return null;
  }

	// ID-VALUE-PAIR LOOKUP QUERIES

	function get_lookups($type) {
		switch($type) {
			case 'suppliers':
				$query = $this->DB->Fetch('suppliers', array(
           'columns'  => 'id, name AS supplier_name',
           'order' => 'name')
         ); break;
			case 'mat_classifications':
				$query = $this->DB->Fetch('item_classifications', array(
           'columns'  => 'id, classification',
           'conditions' => 'item_type="MAT"',
           'order' => 'classification')
         ); break;
			case 'prd_classifications':
				$query = $this->DB->Fetch('item_classifications', array(
           'columns'  => 'id, classification',
           'conditions' => 'item_type="PRD"',
           'order' => 'classification')
         ); break;
			case 'brands':
				$query = $this->DB->Fetch('brand_models', array(
           'columns'  => 'id, brand_model',
           'conditions' => 'parent IS NULL',
           'order' => 'brand_model')
         ); break;
			case 'models':
				$query = $this->DB->Fetch('brand_models', array(
           'columns'  => 'id, brand_model',
           'order' => 'brand_model')
         ); break;
			case 'series':
				$query = $this->DB->Fetch('product_series', array(
           'columns'  => 'id, series',
           'order' => 'series')
         ); break;
			case 'users':
				$query = $this->DB->Fetch('users', array(
           'columns'  => 'id, CONCAT(users.first_name, " ", users.last_name) AS pic',
           'order' => 'first_name')
         ); break;
			case 'terminals':
				$query = $this->DB->Fetch('terminals', array(
           'columns'  => 'id, CONCAT(terminal_code," - ", terminal_name) AS terminal',
           'conditions' => 'location_id=4 AND type="IN"',
           'order' => 'terminal_name')
         ); break;
			case 'uoms':
				$query = $this->DB->Fetch('lookups', array(
           'columns'  => 'id, description',
           'conditions' => 'parent="UNITS"',
           'order' => 'code')
         ); break;
			case 'material_types':
				$query = $this->DB->Fetch('lookups', array(
           'columns'  => 'id, description',
           'conditions' => 'parent="MATTYP"',
           'order' => 'code')
         ); break;
			case 'currencies':
				$query = $this->DB->Fetch('lookups', array(
           'columns'  => 'id, description',
           'conditions' => 'parent="CURNCY"',
           'order' => 'code')
         ); break;
			case 'request_type':
				$query = $this->DB->Fetch('lookups', array(
           'columns'  => 'id, description',
           'conditions' => 'parent="REQTYP"',
           'order' => 'description')
         ); break;
			case 'item_status':
				$query = $this->DB->Fetch('lookup_status', array(
           'columns'  => 'id, description',
           'conditions' => 'parent="ITEM"',
           'order' => 'description')
         ); break;
		}
  	
	
		if(!empty($query)) return $query;
		return null;
  }

	function get_lookups_value($type, $id) {
		switch($type) {
			case 'suppliers':
				$query = $this->DB->Fetch('suppliers', array(
           'columns'  => 'name AS supplier_name',
           'conditions' => 'id='.$id)
         ); break;
		}
  	
	
		if(!empty($query)) return $query[0];
		return null;
  }
	
}