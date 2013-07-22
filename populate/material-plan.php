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
	$search = 'materials.material_type = 70  AND suppliers.id ='.$_GET['sid'];
	
	$query = $DB->Fetch('materials', array(
							'columns'	=> 'materials.id, materials.material_code, brand_models.brand_model AS model, item_classifications.classification, 
												    suppliers.name AS supplier, materials.defect_rate, lookups.code AS unit, item_costs.currency,
												    materials.sorting_percentage, item_costs.moq, item_costs.cost AS price,
														(
												        SELECT COALESCE(SUM(warehouse_inventories.qty), 0) AS qty FROM warehouse_inventories 
												        WHERE 
												        (
											        			warehouse_inventories.item_id = purchase_order_item_parts.material_id
											        			OR
												        		warehouse_inventories.item_id = work_order_item_parts.material_id
										        		) AND warehouse_inventories.status = 16
												    ) AS inventory, 
														(
																SELECT SUM((purchase_order_items.quantity * purchase_order_item_parts.parts_tree_qty)) AS qty
																FROM purchase_order_item_parts
																INNER JOIN purchase_order_items ON purchase_order_items.id = purchase_order_item_parts.purchase_order_item_id
																INNER JOIN purchase_orders ON purchase_orders.id = purchase_order_items.purchase_order_id AND purchase_orders.status = 11
																WHERE material_id = materials.id
																GROUP BY purchase_order_item_parts.material_id
														) AS po_plan,
														(
																SELECT SUM((work_order_items.quantity * work_order_item_parts.parts_tree_qty)) AS qty
																FROM work_order_item_parts
																INNER JOIN work_order_items ON work_order_items.id = work_order_item_parts.work_order_item_id
																INNER JOIN work_orders ON work_orders.id = work_order_items.work_order_id AND work_orders.status = 11
																WHERE material_id = materials.id
																GROUP BY work_order_item_parts.material_id
														) AS wo_plan,
														(
																SELECT COALESCE(SUM(purchase_items.quantity), 0) AS qty FROM purchase_items 
																INNER JOIN purchases ON purchases.id = purchase_items.purchase_id AND purchases.status = 11
																WHERE purchase_items.item_id = materials.id
														) AS open_po
												    ',
					    'joins'		=> 'INNER JOIN brand_models ON brand_models.id = materials.brand_model
														INNER JOIN item_classifications ON item_classifications.id = materials.material_classification
														INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT"
														INNER JOIN lookups ON lookups.id = materials.unit
														INNER JOIN suppliers ON suppliers.id = item_costs.supplier
														LEFT OUTER JOIN purchase_order_item_parts ON purchase_order_item_parts.material_id = materials.id 
														LEFT OUTER JOIN work_order_item_parts ON work_order_item_parts.material_id = materials.id 
														',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group'	=> 'materials.id HAVING po_plan > 0 OR wo_plan > 0 OR open_po > 0'
             )
           );
	return array("material_plan" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>