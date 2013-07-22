<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "expected_date");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						? 
						'lookups.description LIKE "%'. $keyword .'%" OR '.
						'remarks LIKE "%'. $keyword .'%"'
						: '';
	
	$query = $DB->Fetch('material_requests', array(
							'columns'	=> 'material_requests.id, material_requests.request_no, lookups.description AS request_type, batch_no, remarks, expected_date, requested_date, lookup_status.description AS status',
					    'joins'		=> 'INNER JOIN lookups ON lookups.id = material_requests.request_type
					    							INNER JOIN lookup_status ON lookup_status.id = material_requests.completion_status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
             )
           );
	return array("requests" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>