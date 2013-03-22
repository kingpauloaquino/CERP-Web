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
	
	$query = $DB->Fetch('warehouse2_inventories', array(
							'columns'	=> 'warehouse2_inventories.id AS id, warehouse2_inventories.item_id AS pid, warehouse2_inventories.production_purchase_order_id, 
														warehouse2_inventories.tracking_no,warehouse2_inventories.prod_lot_no, products.product_code AS code, 
														SUM(warehouse2_inventories.qty) AS qty, products.color AS color, products.description AS description',
					    'joins'		=> 'INNER JOIN products ON warehouse2_inventories.item_id = products.id
					    							GROUP BY code',
              'order' 	=> $order .' '.$sort,
							'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("warehouse2_inventories" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $paged, $limit, $order, $sort));
?>