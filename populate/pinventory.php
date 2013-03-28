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
						'products.product_code LIKE "%'. $keyword .'%" OR '.
						'products.description LIKE "%'. $keyword .'%" OR '.
						'brand_models.brand_model LIKE "%'. $keyword .'%" OR '.
						'item_classifications.classification LIKE "%'. $keyword .'%" ' 
						: '';
	
	$query = $DB->Fetch('warehouse2_inventories', array(
							'columns'	=> 'warehouse2_inventories.id AS id, warehouse2_inventories.item_id AS pid, warehouse2_inventories.production_purchase_order_id, 
														warehouse2_inventories.tracking_no,warehouse2_inventories.prod_lot_no, products.product_code AS code, 
														SUM(warehouse2_inventories.qty) AS qty, products.color AS color, products.description AS description,
														item_classifications.classification AS pack, brand_models.brand_model AS brand',
					    'joins'		=> 'INNER JOIN products ON warehouse2_inventories.item_id = products.id
					    							INNER JOIN item_classifications ON item_classifications.id = products.product_classification
					    							INNER JOIN brand_models ON brand_models.id = products.brand_model',
              'order' 	=> $order .' '.$sort,
							'limit'		=> $startpoint .', '.$limit,
							'conditions' => $search,
							'group'		=> 'warehouse2_inventories.id'
             )
           );
	return array("warehouse2_inventories" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $paged, $limit, $order, $sort));
?>