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
	
	$query = $DB->Fetch('purchases', array(
							'columns'	=> 'purchases.id, purchase_number, suppliers.id AS supplier_id, suppliers.name AS supplier_name, 
                            delivery_date, total_amount, lookups.description AS status, purchases.created_at',
					    'joins'		=> 'INNER JOIN suppliers ON suppliers.id = purchases.supplier_id 
                            INNER JOIN lookups ON lookups.id = purchases.status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("purchases" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>