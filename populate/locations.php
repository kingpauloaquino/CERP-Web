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
						'location_addresses.address LIKE "%'. $keyword .'%" OR '.
						'materials.material_code LIKE "%'. $keyword .'%" OR '.
						'locations1.location_code LIKE "%'. $keyword .'%" OR '.
						'location_addresses.description LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('location_addresses', array(
							'columns'	=> 'location_addresses.id AS id, location_addresses.address AS address, materials.material_code AS item, 
														locations1.location_code AS bldg, location_addresses.description AS description',
					    'joins'		=> 'LEFT OUTER JOIN materials ON materials.id = location_addresses.item_id
													LEFT OUTER JOIN locations AS locations1 ON locations1.id = location_addresses.bldg',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("location_addresses" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>