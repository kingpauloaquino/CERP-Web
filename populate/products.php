<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "product_code");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						? 
						'(products.product_code LIKE "%'. $keyword .'%" OR '.
						'products.description LIKE "%'. $keyword .'%" OR '.
						'brand_models.brand_model LIKE "%'. $keyword .'%" OR '.
						'product_series.series LIKE "%'. $keyword .'%") AND products.status = 16 ' 
						: 'products.status = 16';
	
	$query = $DB->Fetch('products', array(
							'columns'	=> 'products.id AS id, products.product_code AS code, products.description AS description, products.color,
														brand_models.brand_model AS brand, product_series.series,
                    				item_costs.cost AS price, lookups.description AS unit, pack_qty',
					    'joins'		=> 'INNER JOIN brand_models ON brand_models.id = products.brand_model 
					                  INNER JOIN item_costs ON item_id = products.id AND item_costs.item_type = "PRD" 
					                  INNER JOIN lookups ON lookups.id = item_costs.unit
					                  INNER JOIN product_series ON product_series.id = products.series',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
             )
           );
	return array("products" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>