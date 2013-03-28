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
	$search = (isset($keyword) || $keyword != '') 
						? 
						'materials.material_code LIKE "%'. $keyword .'%" OR '.
						'materials.description LIKE "%'. $keyword .'%" OR '.
						'brand_models.brand_model LIKE "%'. $keyword .'%" OR '.
						'item_classifications.classification LIKE "%'. $keyword .'%" ' 
						: '';

	$query = $DB->Fetch('warehouse_inventories', array(
							'columns'	=> 'warehouse_inventories.item_id AS id, materials.material_code AS code, SUM(warehouse_inventories.qty) AS qty,
														item_classifications.classification AS classification, materials.description AS description, 
														brand_models.brand_model AS model, lookups.description AS uom',
					    'joins'		=> 'INNER JOIN materials ON warehouse_inventories.item_id = materials.id
														INNER JOIN item_classifications ON materials.material_classification = item_classifications.id
														INNER JOIN brand_models ON brand_models.id = materials.brand_model
														INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
														INNER JOIN lookups ON lookups.id = item_costs.unit
														AND warehouse_inventories.item_type="MAT"',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group' => 'warehouse_inventories.id'
             )
           );
	return array("material_inventory" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>   