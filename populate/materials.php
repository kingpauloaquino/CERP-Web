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
						'materials.material_code LIKE "%'. $keyword .'%" OR '.
						'materials.description LIKE "%'. $keyword .'%" OR '.
						'brand_models.brand_model LIKE "%'. $keyword .'%" OR '.
						'item_classifications.classification LIKE "%'. $keyword .'%" OR '.
						'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('materials', array(
							'columns'	=> 'materials.id AS id, materials.material_code AS code, materials.description AS description, 
                    				brand_models.brand_model AS model, item_classifications.classification AS classification',
					    'joins'		=> 'INNER JOIN brand_models ON materials.brand_model = brand_models.id 
					                  INNER JOIN item_classifications ON materials.material_classification = item_classifications.id 
					                  AND material_type=70 AND materials.status = 73',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("materials" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>