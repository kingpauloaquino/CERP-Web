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
	
	$query = $DB->Fetch('location_addresses', array(
							'columns'	=> 'location_addresses.id AS id, location_addresses.address AS address, materials.material_code AS item, 
														locations1.location_code AS bldg, location_addresses.description AS description',
					    'joins'		=> 'LEFT OUTER JOIN location_address_items ON location_address_items.address = location_addresses.id
													LEFT OUTER JOIN materials ON materials.id = location_address_items.item_id
													LEFT OUTER JOIN locations AS locations1 ON locations1.id = location_addresses.bldg',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("location_addresses" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>