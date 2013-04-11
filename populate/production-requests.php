<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "production_purchase_order_product_parts.created_at");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "DESC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						? 
						''
						: '';
	
	$query = $DB->Fetch('production_purchase_order_product_parts', array(
							'columns'	=> 'production_purchase_order_product_parts.id, orders.po_number, production_purchase_order_products.lot_no AS prod_lot_no, production_purchase_orders.id AS ppoid,
														production_purchase_order_product_parts.tracking_no, production_purchase_order_product_parts.material_id,
														production_purchase_order_product_parts.plan_qty, production_purchase_order_product_parts.pending_qty,
														production_purchase_order_product_parts.updated_at, lookups.description AS status, production_purchase_orders.order_id AS oid,
														production_purchase_order_product_parts.expected_datetime, production_purchase_order_product_parts.is_requested',
					    'joins'		=> 'INNER JOIN production_purchase_order_products ON production_purchase_order_products.id = production_purchase_order_product_parts.production_purchase_order_product_id
														INNER JOIN production_purchase_orders ON production_purchase_orders.id =  production_purchase_order_products.production_purchase_order_id
														INNER JOIN orders ON orders.id = production_purchase_orders.order_id
														INNER JOIN lookups ON lookups.id = production_purchase_order_product_parts.status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => 'production_purchase_order_product_parts.material_id = '.$_GET['mid'],
             )
           );
	return array("production-requests" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>