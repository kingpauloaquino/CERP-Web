<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "requested_date");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	
	$query = $DB->Fetch('material_request_items', array(
							'columns'	=> 'material_request_items.id AS mrid, material_request_items.request_id AS rid, lookups.description AS type, 
														material_requests.batch_no, material_requests.requested_date, material_requests.expected_date,
														material_request_items.qty, terminals.terminal_name AS terminal, material_requests.completion_status AS completion_status_id, 
														lookup_status.description AS completion_status',
					    'joins'		=> 'INNER JOIN material_requests ON material_requests.id = material_request_items.request_id
														INNER JOIN materials ON materials.id = material_request_items.material_id
														INNER JOIN lookups ON lookups.id = material_requests.request_type
														INNER JOIN lookup_status ON lookup_status.id = material_requests.completion_status
														INNER JOIN terminals ON terminals.id = materials.production_entry_terminal_id',
							'conditions' => 'material_requests.status = 11 AND material_requests.completion_status !=25 AND material_request_items.material_id='.$_GET['mid'],
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("material_request_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>