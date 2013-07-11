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
	
	
	$query = $DB->Fetch('purchase_order_items', array(
               'columns' => 'purchase_order_items.id, purchase_order_items.purchase_order_id, products.id AS item_id, products.product_code AS code, products.description, item_costs.cost AS item_price, lookups.code AS unit, 
               							purchase_order_items.item_type AS item_type, purchase_order_items.quantity AS quantity, purchase_order_items.remarks AS remarks',
               'joins' => 'INNER JOIN products ON products.id=purchase_order_items.item_id
														INNER JOIN item_costs ON item_costs.item_id = products.id
														INNER JOIN lookups ON lookups.id = products.unit',
               'conditions' => 'item_costs.item_type="PRD" AND purchase_order_items.item_type="PRD" AND purchase_order_items.purchase_order_id='.$_GET['pid'].'
               							UNION SELECT
			  	  								purchase_order_items.id, purchase_order_items.purchase_order_id, materials.id AS item_id, materials.material_code AS code, materials.description, item_costs.cost AS item_price, lookups.code AS unit, 
														purchase_order_items.item_type AS item_type, purchase_order_items.quantity AS quantity, purchase_order_items.remarks AS remarks
			  	  								FROM purchase_order_items
			  	  								INNER JOIN materials ON materials.id=purchase_order_items.item_id
														INNER JOIN item_costs ON item_costs.item_id = materials.id
														INNER JOIN lookups ON lookups.id = materials.unit
														WHERE item_costs.item_type="MAT" AND purchase_order_items.item_type="MAT" AND purchase_order_items.purchase_order_id='.$_GET['pid'],
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
             ));
	
	//$query = $Query->purchase_items_by_id($_GET['purchase_id']);
	return array("purchase_order_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>