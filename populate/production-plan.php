<?php
require('../include/general.class.php');

$keyword	= $_GET['keyword'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	
	$query = $DB->Fetch('production_purchase_orders', array(
							'columns'	=> 'production_purchase_orders.id AS id, production_purchase_orders.order_id AS oid, orders.po_number AS po_number, 
														orders.po_date AS po_date, production_purchase_orders.target_date AS target_date, lookups.description AS status',
					    'joins'		=> 'INNER JOIN orders ON orders.id = production_purchase_orders.order_id
														INNER JOIN lookups ON lookups.id = production_purchase_orders.status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("production_purchase_orders" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>