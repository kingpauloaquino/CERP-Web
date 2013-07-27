<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "code");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	
	$query = $DB->Fetch('material_request_items', array(
							'columns'	=> 'material_request_items.id, material_request_items.qty, materials.id AS mid, 
														materials.material_code AS code, lookups.description AS type, lookups2.description AS unit, material_request_items.status',
					    'joins'		=> 'INNER JOIN materials ON materials.id = material_request_items.material_id
					    							INNER JOIN lookups ON lookups.id = materials.material_type
					    							INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type="MAT"
					    							INNER JOIN lookups AS lookups2 ON lookups2.id = materials.unit',
							'conditions' => 'request_id='.$_GET['rid'],
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("material_request_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>