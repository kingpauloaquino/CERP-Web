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
						'suppliers.id = '.$_GET['sid']. ' AND ('.
						'materials.material_code LIKE "%'. $keyword .'%" OR '.
						'materials.description LIKE "%'. $keyword .'%" OR '.
						'suppliers.name LIKE "%'. $keyword .'%" OR '.
						'lookups.code LIKE "%'. $keyword .'%" OR '.
						'materials.tags LIKE "%'. $keyword .'%") '
						: '';
	
	$query = $DB->Fetch('materials', array(
							'columns'	=> 'materials.id AS id, materials.material_code AS code, materials.description AS description, 
														item_costs.cost AS price, item_costs.moq, suppliers.name AS supplier, lookups.code AS unit, item_costs.currency,
														SUM(warehouse_inventories.qty) AS stock',
					    'joins'		=> 'LEFT OUTER JOIN warehouse_inventories ON warehouse_inventories.item_id = materials.id AND warehouse_inventories.item_type = "MAT"
												    INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
												    INNER JOIN suppliers ON suppliers.id = item_costs.supplier
												    INNER JOIN lookups ON lookups.id = item_costs.unit
												    AND materials.status = 16',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group' => 'materials.id'
             )
           );
	return array("material-supplier-costs" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>