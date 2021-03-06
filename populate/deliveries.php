<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "DESC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						? 
						'purchases.po_number LIKE "%'. $keyword .'%" OR '.
						'suppliers.name LIKE "%'. $keyword .'%" OR '.
						'lookup_status.description LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('deliveries', array(
							'columns'	=> 'deliveries.id, purchases.po_number, deliveries.delivery_date, supplier_id, name AS supplier_name, 
                           lookup_status.description AS status, lookup_status2.description AS completion_status',
					    'joins'		=> 'INNER JOIN purchases ON purchases.id = deliveries.purchase_id
					    						INNER JOIN suppliers ON suppliers.id = supplier_id
                         	INNER JOIN lookup_status ON lookup_status.id = deliveries.status
                         	INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = deliveries.completion_status',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("deliveries" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));

// SELECT t.id, t.purchase_id, p.po_number, t.delivery_date, p.supplier_id, s.name AS supplier_name, l.description AS status, ll.description AS completion_status
// FROM deliveries AS t
// INNER JOIN
	// (
	// SELECT MAX(deliveries.id) AS id, deliveries.purchase_id, purchases.po_number, deliveries.delivery_date, supplier_id, name AS supplier_name, 
		// lookup_status.description AS status, lookup_status2.description AS completion_status
	// FROM deliveries
	// INNER JOIN purchases ON purchases.id = deliveries.purchase_id
	// INNER JOIN suppliers ON suppliers.id = supplier_id
	// INNER JOIN lookup_status ON lookup_status.id = deliveries.status
	// INNER JOIN lookup_status AS lookup_status2 ON lookup_status2.id = deliveries.completion_status
	// GROUP BY purchase_id
	// ) AS t2 ON t2.id = t.id
// INNER JOIN purchases AS p ON p.id = t.purchase_id
// INNER JOIN suppliers AS s ON s.id = p.supplier_id
// INNER JOIN lookup_status AS l ON l.id = t.status
// INNER JOIN lookup_status AS ll ON ll.id = t.completion_status

?>