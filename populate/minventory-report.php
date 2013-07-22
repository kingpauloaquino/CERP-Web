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
	$search = (isset($keyword) || $keyword != '') 
						? 
						'(m.material_code LIKE "%'. $keyword .'%" OR '.
						'm.description LIKE "%'. $keyword .'%" OR '.
						'item_classifications.classification LIKE "%'. $keyword .'%") AND m.material_type=70 AND m.status = 16' 
						: 'm.material_type=70 AND m.status = 16';

	$query = $DB->Fetch('materials AS m', array(
							'columns'	=> 'm.id, m.material_code AS code, item_classifications.classification AS classification, 
														m.description AS description, lookups.description AS uom, msq,
														COALESCE(wh1.qty,0) AS qty, COALESCE(wh2.qty,0) AS physical_qty',
					    'joins'		=> 'LEFT OUTER JOIN 
															(
															SELECT warehouse_inventories.item_id,sum(warehouse_inventories.qty) as qty
															FROM warehouse_inventories
															WHERE (EXTRACT(YEAR_MONTH FROM warehouse_inventories.created_at) <= EXTRACT(YEAR_MONTH FROM "'.$_GET['mydate'].'")) AND warehouse_inventories.status=16
															GROUP BY warehouse_inventories.item_id
															) AS wh1 ON wh1.item_id = m.id
														LEFT OUTER JOIN
															(
															SELECT warehouse_inventory_actual.item_id, sum(warehouse_inventory_actual.qty) as qty
															FROM warehouse_inventory_actual
															WHERE EXTRACT(YEAR_MONTH FROM warehouse_inventory_actual.entry_date) = EXTRACT(YEAR_MONTH FROM "'.$_GET['mydate'].'")
															GROUP BY warehouse_inventory_actual.item_id
															) AS wh2 ON wh2.item_id = m.id
														INNER JOIN item_classifications ON m.material_classification = item_classifications.id
														INNER JOIN item_costs ON item_costs.item_id = m.id AND item_costs.item_type = "MAT"
														INNER JOIN lookups ON lookups.id = m.unit',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group' => 'm.id'
             )
           );
	return array("material_inventory_report" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>   