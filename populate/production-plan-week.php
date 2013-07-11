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
						'(shipment_plans.ctrl_no LIKE "%'. $keyword .'%" OR '.
						'products.product_code LIKE "%'. $keyword .'%" OR '.
						'lookups.code LIKE "%'. $keyword .'%" OR '.
						'shipment_plans.remarks LIKE "%'. $keyword .'%" OR '.
						'product_series.series LIKE "%'. $keyword .'%" )
						AND shipment_plans.prod_date="'.$_GET['pdate'].'"'
						: 'shipment_plans.prod_date="'.$_GET['pdate'].'"';
	
	$query = $DB->Fetch('shipment_plans', array(
							'columns'	=> 'shipment_plans.id, ctrl_id, shipment_plans.item_id AS pid, shipment_plans.item_type, 
														shipment_plans.prod_date, lookups.code AS unit, lookup_status.description AS completion, products.pack_qty,
														products.product_code AS code, product_series.series, 
														SUM(qty) AS ttl, SUM(qty * products.pack_qty) AS ttls',
							'joins' => 'INNER JOIN item_costs ON item_costs.item_id = shipment_plans.item_id AND item_costs.item_type = shipment_plans.item_type
														
														INNER JOIN lookup_status ON lookup_status.id = shipment_plans.status
														INNER JOIN products ON products.id = shipment_plans.item_id
														INNER JOIN lookups ON lookups.id = products.unit
														INNER JOIN product_series ON product_series.id = products.series',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group' => 'shipment_plans.item_id'
             )
           );
	return array("production_plans" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>