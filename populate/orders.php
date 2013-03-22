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
	
	$query = $DB->Fetch('orders', array(
							'columns'	=> 'orders.id AS id, orders.po_number AS po_number, orders.po_date AS po_date, orders.description AS description, 
														lookups.description AS payment_terms, orders.delivery_date AS delivery_date, lookups2.description AS status',
					    'joins'		=> 'INNER JOIN lookups ON orders.payment_terms = lookups.id
					    							INNER JOIN lookups AS lookups2 ON lookups2.id = orders.status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("orders" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>