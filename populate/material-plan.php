<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "material_code");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = 'purchases.supplier_id ='.$_GET['sid'];
	
	$query = $DB->Fetch('purchase_items', array(
							'columns'	=> 'purchase_items.id, purchase_items.item_id, materials.material_code, brand_models.brand_model AS model, 
														item_classifications.classification, suppliers.name AS supplier, materials.defect_rate, 
														(
															SELECT COALESCE(SUM(warehouse_inventories.qty), 0) AS qty FROM warehouse_inventories WHERE warehouse_inventories.item_id = purchase_items.item_id
															GROUP BY warehouse_inventories.item_id
														) AS inventory,
														(
															SELECT COALESCE(SUM(pi.quantity), 0) AS qty FROM purchase_items AS pi WHERE pi.item_id = purchase_items.item_id
															GROUP BY pi.item_id
														) AS prod_plan,
														purchase_items.item_price AS price, item_costs.moq, lookups.code AS unit',
					    'joins'		=> 'INNER JOIN purchases ON purchases.id = purchase_items.purchase_id
														INNER JOIN suppliers ON suppliers.id = purchases.supplier_id
														INNER JOIN materials ON materials.id = purchase_items.item_id
														INNER JOIN brand_models ON brand_models.id = materials.brand_model
														INNER JOIN item_classifications ON item_classifications.id = materials.material_classification
														LEFT OUTER JOIN warehouse_inventories ON warehouse_inventories.item_id = purchase_items.item_id
														INNER JOIN item_costs ON item_costs.item_id = purchase_items.item_id AND item_costs.item_type = "MAT"
														INNER JOIN lookups ON lookups.id = item_costs.unit
														',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group'	=> 'purchase_items.item_id'
             )
           );
	return array("material_plan" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>