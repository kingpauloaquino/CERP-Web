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
	$search = 'warehouse_inventories.item_type = "MAT" AND warehouse_inventories.item_id ='.$_GET['id'];

	$query = $DB->Fetch('warehouse_inventories', array(
							'columns'	=> 'warehouse_inventories.id, warehouse_inventories.item_id, warehouse_inventories.invoice_no, warehouse_inventories.lot_no,
			  										warehouse_inventories.qty, warehouse_inventories.remarks, lookups.description AS unit',
					    'joins'		=> 'INNER JOIN item_costs ON item_costs.item_id = warehouse_inventories.item_id AND item_costs.item_type = "MAT"
														INNER JOIN lookups ON lookups.id = item_costs.unit',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
             )
           );
	return array("minventory_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>   