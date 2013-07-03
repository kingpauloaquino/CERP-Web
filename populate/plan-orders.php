<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "ship_date, id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						? 
						'order_no LIKE UCASE("%'. $keyword .'%") OR '.
						'remarks LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('plan_orders_view', array(
							'columns'	=> '*',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("plan_orders" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>