<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "product_code");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	
	$search = (isset($keyword) || $keyword != '') 
						? 
						'(purchase_orders.po_number LIKE "%'. $keyword .'%" OR '.
						'products.product_code LIKE "%'. $keyword .'%" OR '.
						'item_classifications.classification LIKE "%'. $keyword .'%" OR '.
						'lookups.code LIKE "%'. $keyword .'%" OR '.
						'shipment_plans.remarks LIKE "%'. $keyword .'%" OR '.
						'product_series.series LIKE "%'. $keyword .'%" )
						AND shipment_plans.ship_date="'.$_GET['sdate'].'"'
						: 'shipment_plans.ship_date="'.$_GET['sdate'].'"';
	
	$query = $DB->Fetch('shipment_plans', array(
							'columns'	=> 'shipment_plans.id, po_id, shipment_plans.item_id AS pid, shipment_plans.item_type, shipment_plans.ship_date, qty, shipment_plans.remarks, 
														lookups.code AS unit, lookup_status.description AS completion, purchase_orders.po_number,
														products.product_code AS code, product_series.series, item_classifications.classification AS pack',
							'joins' => 'INNER JOIN item_costs ON item_costs.item_id = shipment_plans.item_id AND item_costs.item_type = shipment_plans.item_type
														INNER JOIN lookups ON lookups.id = item_costs.unit
														INNER JOIN lookup_status ON lookup_status.id = shipment_plans.status
														INNER JOIN products ON products.id = shipment_plans.item_id
														INNER JOIN product_series ON product_series.id = products.series
														INNER JOIN item_classifications ON item_classifications.id = products.product_classification
														INNER JOIN purchase_orders ON purchase_orders.id = shipment_plans.po_id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
             )
           );
	return array("shipment_plans" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>