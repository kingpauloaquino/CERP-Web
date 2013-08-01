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
	$search = (isset($keyword) || $keyword != '') 
						? 
						'(materials.material_code LIKE "%'. $keyword .'%" OR '.
						'materials.description LIKE "%'. $keyword .'%" OR '.
						'brand_models.brand_model LIKE "%'. $keyword .'%" OR '.
						'item_classifications.classification LIKE "%'. $keyword .'%" OR '.
						'materials.tags LIKE "%'. $keyword .'%") AND materials.status = 16'
						: 'materials.status = 16';
	
	$query = $DB->Fetch('materials', array(
							'columns'	=> 'materials.id AS id, materials.material_code AS code, materials.description AS description, 
                    				brand_models.brand_model AS model, item_classifications.classification AS classification,
                    				location_addresses.address',
					    'joins'		=> 'INNER JOIN brand_models ON materials.brand_model = brand_models.id 
					                  INNER JOIN item_classifications ON materials.material_classification = item_classifications.id
					                  LEFT OUTER JOIN location_addresses ON location_addresses.id = materials.address',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("location_materials" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>