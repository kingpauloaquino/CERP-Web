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
	
	$query = $DB->Fetch('materials', array(
							'columns'	=> 'materials.id AS id, materials.material_code AS code, materials.description AS description, 
                    				brand_models.brand_model AS model, item_classifications.classification AS classification, 
                    				item_costs.cost AS price, lookups.description AS unit',
					    'joins'		=> 'INNER JOIN brand_models ON materials.brand_model = brand_models.id 
					                  INNER JOIN item_classifications ON materials.material_classification = item_classifications.id 
					                  INNER JOIN item_costs ON item_id = materials.id AND item_costs.item_type = "MAT" 
					                  INNER JOIN lookups ON lookups.id = item_costs.unit
					                  AND material_type=70',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("materials" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>