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
	$search = 'purchase_id='.$_GET['pid'];
	
	$query = $DB->Fetch('purchase_items', array(
               'columns' => 'purchase_items.id, purchase_items.item_id, material_code AS code, materials.description, quantity,
														lookups.description AS unit, item_price, item_costs.currency, item_costs.moq',
               'joins' => 'INNER JOIN purchases ON purchases.id = purchase_items.purchase_id
														INNER JOIN materials ON materials.id = purchase_items.item_id
														INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type="MAT" AND item_costs.supplier = purchases.supplier_id
														INNER JOIN lookups ON lookups.id = item_costs.unit',
						    'order' 	=> $order .' '.$sort,
	    					'limit'		=> $startpoint .', '.$limit,
                'conditions' => $search)
             );
	
	//$query = $Query->purchase_items_by_id($_GET['purchase_id']);
	return array("purchase_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>