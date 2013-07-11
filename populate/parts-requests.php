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
	$search = 'production_purchase_order_product_parts.production_purchase_order_product_id ='.$keyword;
	
	$query = $DB->Fetch('production_purchase_order_product_parts', array(
							'columns'	=> 'production_purchase_order_product_parts.id AS id, production_purchase_order_product_parts.material_id AS mat_id, materials.material_code AS material_code, 
														production_purchase_order_product_parts.tracking_no AS tracking_no,	materials.description AS description, lookups.description AS unit, 
														production_purchase_order_product_parts.qty AS qty, production_purchase_order_product_parts.plan_qty AS plan_qty,
														production_purchase_order_product_parts.expected_datetime, lookups2.description AS status, production_purchase_order_product_parts.is_requested',
					    'joins'		=> 'INNER JOIN materials ON materials.id = production_purchase_order_product_parts.material_id
														LEFT OUTER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT" 
														LEFT OUTER JOIN lookups ON lookups.id = materials.unit
														LEFT OUTER JOIN lookups AS lookups2 ON lookups2.id = production_purchase_order_product_parts.status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("parts_requests" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>