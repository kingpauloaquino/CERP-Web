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
	
	
	$query = $DB->Fetch('work_order_item_parts', array(
               'columns' => 'work_order_item_parts.id, work_order_item_parts.material_id, materials.material_code, materials.description, work_order_item_parts.parts_tree_qty, 
               							work_order_items.quantity AS wo_qty, lookups.description AS unit, item_costs.moq, item_costs.cost AS item_price',
               'joins' => 'LEFT OUTER JOIN work_order_items ON work_order_items.id = work_order_item_parts.work_order_item_id
               							INNER JOIN materials ON materials.id = work_order_item_parts.material_id
														INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
														INNER JOIN lookups ON lookups.id = materials.unit',
               'conditions' => 'work_order_item_parts.work_order_item_id='.$_GET['woid'],
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
             ));
	
	return array("work_order_item_parts" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>