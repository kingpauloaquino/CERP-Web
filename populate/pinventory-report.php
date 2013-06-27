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
						'p.product_code LIKE "%'. $keyword .'%" OR '.
						'brand_models.brand_model LIKE "%'. $keyword .'%" OR '.
						'product_series.series LIKE "%'. $keyword .'%" OR '.
						'p.description LIKE "%'. $keyword .'%" ' 
						: '';

	$query = $DB->Fetch('products as p', array(
							'columns'	=> 'p.id, p.product_code AS code, brand_models.brand_model AS brand, product_series.series, p.pack_qty,
														p.description AS description, lookups.description AS uom, 
														COALESCE(wh1.qty,0) AS qty, COALESCE(wh2.qty,0) AS physical_qty',
					    'joins'		=> 'LEFT OUTER JOIN 
															(
															SELECT warehouse2_inventories.item_id,sum(warehouse2_inventories.qty) as qty
															FROM warehouse2_inventories
															WHERE EXTRACT(YEAR_MONTH FROM warehouse2_inventories.endorse_date) <= EXTRACT(YEAR_MONTH FROM "'.$_GET['mydate'].'")
															GROUP BY warehouse2_inventories.item_id
															) AS wh1 ON wh1.item_id = p.id
														LEFT OUTER JOIN
															(
															SELECT warehouse2_inventory_actual.item_id, sum(warehouse2_inventory_actual.qty) as qty
															FROM warehouse2_inventory_actual
															WHERE EXTRACT(YEAR_MONTH FROM warehouse2_inventory_actual.entry_date) = EXTRACT(YEAR_MONTH FROM "'.$_GET['mydate'].'")
															GROUP BY warehouse2_inventory_actual.item_id
															) AS wh2 ON wh2.item_id = p.id
														INNER JOIN brand_models ON brand_models.id = p.brand_model
														INNER JOIN product_series ON product_series.id = p.series
														INNER JOIN item_costs ON item_costs.item_id = p.id AND item_costs.item_type = "PRD"
														INNER JOIN lookups ON lookups.id = item_costs.unit',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group' => 'p.id'
             )
           );
	return array("product_inventory_report" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>   