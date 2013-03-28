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
	$search = 'purchase_id='.$keyword;
	
	$query = $DB->Fetch('purchase_items', array(
               'columns' => 'purchase_items.id, purchase_items.item_id, material_code AS code, materials.description, 
                             lookups.description AS unit, brand_model, material_type, quantity, item_price',
               'joins' => 'INNER JOIN materials ON materials.id = purchase_items.item_id
                           LEFT OUTER JOIN item_costs ON item_type = "MAT" AND item_costs.item_id = purchase_items.item_id
                           LEFT OUTER JOIN lookups ON lookups.id = item_costs.unit',
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