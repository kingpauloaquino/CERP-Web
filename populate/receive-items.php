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
	$search = 'delivery_id= '.$keyword;
	$query = $DB->Fetch('receive_items', array(
                  'columns' => 'receive_items.id, material_code AS material_code, materials.description AS material_description, 
                                quantity, lookups.description AS status, passed, receive_items.remarks',
                  'joins' => 'INNER JOIN materials ON materials.id = receive_items.receive_item
                              INNER JOIN lookups ON lookups.id = receive_items.status',
							    'order' 	=> $order .' '.$sort,
		    					'limit'		=> $startpoint .', '.$limit,
                  'conditions' => $search)
           );
	//return array("materials" => $query, "total" => $DB->totalRows());
	return array("materials" => $query);
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>