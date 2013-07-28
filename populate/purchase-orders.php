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
						'purchase_orders.po_number LIKE UCASE("%'. $keyword .'%") OR '.
						'lookups.description LIKE "%'. $keyword .'%" OR '.
						'lookup_status.description LIKE "%'. $keyword .'%" OR '.
						'lookup_status2.description LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('purchase_orders', array(
							'columns'	=> 'purchase_orders.id AS id, purchase_orders.po_number, purchase_orders.po_date, lookups.description AS payment_terms, purchase_orders.remarks,
														purchase_orders.ship_date, lookup_status.description AS status, lookup_status2.description AS completion_status, total_amount ',
					    'joins'		=> 'INNER JOIN lookups ON purchase_orders.payment_terms = lookups.id
					    							INNER JOIN lookup_status ON lookup_status.id = purchase_orders.status
					    							INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = purchase_orders.completion_status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("purchase_orders" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>