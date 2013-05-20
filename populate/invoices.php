<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "receive_date");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "DESC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						?
						'invoice IS NOT NULL AND ('. 
						'delivery_items.invoice LIKE "%'. $keyword .'%" OR '.
						'suppliers.name LIKE "%'. $keyword .'%" OR '.
						'purchases.terms LIKE "%'. $keyword .'%" OR '.
						'delivery_items.receive_date LIKE "%'. $keyword .'%" )'
						: '';
	
	$query = $DB->Fetch('delivery_items', array(
							'columns'	=> 'DISTINCT invoice, suppliers.name AS supplier, purchases.terms, receive_date',
					    'joins'		=> 'INNER JOIN deliveries ON deliveries.id = delivery_id
														INNER JOIN purchases ON purchases.id = deliveries.purchase_id
														INNER JOIN suppliers ON suppliers.id = purchases.supplier_id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("invoices" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>