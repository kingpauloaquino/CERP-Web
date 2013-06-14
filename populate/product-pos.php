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
	$search = 'purchase_order_items.item_id='.$_GET['pid'];
	
	$query = $DB->Fetch('purchase_orders', array(
							'columns'	=> 'purchase_orders.id, purchase_order_items.item_id AS pid, purchase_orders.po_number, purchase_orders.po_date, 
														purchase_orders.ship_date, purchase_order_items.quantity, purchase_order_items.remarks',
					    'joins'		=> 'INNER JOIN purchase_order_items ON purchase_order_items.purchase_order_id = purchase_orders.id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
             )
           );
	return array("product_pos" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>