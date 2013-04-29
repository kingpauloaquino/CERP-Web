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
						'purchases.purchase_number LIKE "%'. $keyword .'%" OR '.
						'suppliers.name LIKE "%'. $keyword .'%" OR '.
						'lookup_status.description LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('purchases', array(
							'columns'	=> 'purchases.id, purchase_number, suppliers.id AS supplier_id, suppliers.name AS supplier_name, 
                            delivery_date, total_amount, lookup_status.description AS status,lookup_status2.description AS completion_status, purchases.created_at',
					    'joins'		=> 'INNER JOIN suppliers ON suppliers.id = purchases.supplier_id 
                            INNER JOIN lookup_status ON lookup_status.id = purchases.status
                            INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = purchases.completion_status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("purchase-orders" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>