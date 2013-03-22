<?php
require('../include/general.class.php');

$keyword	= $_GET['keyword'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	
	$query = $DB->Fetch('products', array(
							'columns'	=> 'products.id AS id, products.product_code AS code, products.description AS description, products.color,
														brand_models.brand_model AS brand, item_classifications.classification AS classification, 
                    				item_costs.cost AS price, lookups.description AS unit',
					    'joins'		=> 'INNER JOIN brand_models ON brand_models.id = products.brand_model 
					                  INNER JOIN item_classifications ON products.product_classification = item_classifications.id 
					                  INNER JOIN item_costs ON item_id = products.id AND item_costs.item_type = "PRD" 
					                  INNER JOIN lookups ON lookups.id = item_costs.unit',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("products" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>