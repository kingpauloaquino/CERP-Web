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
	$search = 'shipment_plans.type = "'.$_GET['t'].'" AND ctrl_id='.$_GET['ctrl_id'].' AND shipment_plans.item_id='.$_GET['pid'];
	
	$query = $DB->Fetch('shipment_plans', array(
							'columns'	=> 'shipment_plans.id, ctrl_id, shipment_plans.item_id, shipment_plans.item_type, ship_date, prod_date, qty, remarks, lookups.code AS unit, lookup_status.description AS completion',
							'joins' => 'INNER JOIN products ON products.id = shipment_plans.item_id
													INNER JOIN lookups ON lookups.id = products.unit
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