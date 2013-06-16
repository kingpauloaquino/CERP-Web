<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "ship_date");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = 'po_id='.$_GET['poid'].' AND shipment_plans.item_id='.$_GET['pid'];
	
	$query = $DB->Fetch('shipment_plans', array(
							'columns'	=> 'shipment_plans.id, po_id, shipment_plans.item_id, shipment_plans.item_type, ship_date, prod_date, qty, remarks, lookups.code AS unit, lookup_status.description AS completion',
							'joins' => 'INNER JOIN item_costs ON item_costs.item_id = shipment_plans.item_id AND item_costs.item_type = shipment_plans.item_type
													INNER JOIN lookups ON lookups.id = item_costs.unit
													INNER JOIN lookup_status ON lookup_status.id = shipment_plans.status',
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