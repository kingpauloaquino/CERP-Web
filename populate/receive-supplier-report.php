<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "receive_date");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						? 
						'(delivery_items.invoice LIKE "%'. $keyword .'%" OR '.
						'delivery_items.receipt LIKE "%'. $keyword .'%") AND '.
						'EXTRACT(YEAR_MONTH FROM delivery_items.receive_date) = EXTRACT(YEAR_MONTH FROM "'.$_GET['mydate'].'")
							AND purchases.supplier_id='.$_GET['sid'] 
						: 'EXTRACT(YEAR_MONTH FROM delivery_items.receive_date) = EXTRACT(YEAR_MONTH FROM "'.$_GET['mydate'].'")
							AND purchases.supplier_id='.$_GET['sid'];

	$query = $DB->Fetch('delivery_items', array(
							'columns'	=> 'delivery_items.id, delivery_items.delivery_id, delivery_items.invoice, delivery_items.receipt, 
														delivery_items.receive_date',
					    'joins'		=> 'INNER JOIN deliveries ON deliveries.id = delivery_items.delivery_id
														INNER JOIN purchases ON purchases.id = deliveries.purchase_id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
    					'group' => 'delivery_items.delivery_id'
             )
           );
	return array("receive_supplier_report" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>   