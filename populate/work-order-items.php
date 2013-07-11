<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	
	
	$query = $DB->Fetch('work_order_items', array(
               'columns' => 'work_order_items.id, work_order_items.product_id, products.product_code, work_order_items.remarks, work_order_items.quantity,
               							lookups.description AS unit, item_costs.cost AS item_price, work_order_items.work_order_id',
               'joins' => 'INNER JOIN products ON products.id = work_order_items.product_id
														INNER JOIN item_costs ON item_costs.item_id = products.id AND item_costs.item_type = "PRD"
														INNER JOIN lookups ON lookups.id = products.unit',
               'conditions' => 'work_order_items.work_order_id='.$_GET['wid'],
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
             ));
	
	return array("work_order_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>