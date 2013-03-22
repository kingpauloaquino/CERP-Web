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
	
	$query = $DB->Fetch('warehouse_inventories', array(
							'columns'	=> 'warehouse_inventories.item_id AS id, materials.material_code AS code, SUM(warehouse_inventories.qty) AS qty,
														item_classifications.classification AS classification, materials.description AS description',
					    'joins'		=> 'INNER JOIN materials ON warehouse_inventories.item_id = materials.id
														INNER JOIN item_classifications ON materials.material_classification = item_classifications.id
														AND warehouse_inventories.item_type="MAT" GROUP BY code',
              'order' 	=> $order .' '.$sort,
							'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("material_inventory" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>   