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
	$search = 'lookup_status.id != 6 AND delivery_id= '.$_GET['did'];
	$query = $DB->Fetch('receive_items', array(
                  'columns' => 'receive_items.id, materials.id AS mid, material_code AS material_code, materials.description AS material_description, 
																quantity, delivered, received, additional, lookup_status.description AS status, passed, receive_items.remarks, lookups.description AS unit',
                  'joins' => 'INNER JOIN materials ON materials.id = receive_items.item_id
															INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
															INNER JOIN lookup_status ON lookup_status.id = receive_items.status
															INNER JOIN lookups ON lookups.id = materials.unit',
							    'order' 	=> $order .' '.$sort,
		    					'limit'		=> $startpoint .', '.$limit,
                  'conditions' => $search)
           );
	//return array("materials" => $query, "total" => $DB->totalRows());
	return array("receive_items" => $query);
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>