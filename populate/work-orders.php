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
						'work_orders.wo_number LIKE UCASE("%'. $keyword .'%") OR '.
						'work_orders.ship_date LIKE "%'. $keyword .'%" OR '.
						'lookup_status.description LIKE "%'. $keyword .'%" OR '.
						'lookup_status2.description LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('work_orders', array(
							'columns'	=> 'work_orders.id, work_orders.wo_number, work_orders.wo_date, work_orders.ship_date, work_orders.remarks, total_amount, 
														lookup_status.description AS status, lookup_status2.description AS completion_status',
					    'joins'		=> 'INNER JOIN lookup_status ON lookup_status.id = work_orders.status
					    							INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = work_orders.completion_status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("work_orders" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>