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
						'suppliers.supplier_code LIKE "%'. $keyword .'%" OR '.
						'suppliers.name LIKE "%'. $keyword .'%" OR '.
						'lookups.description LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('suppliers', array(
							'columns'	=> 'suppliers.id AS id, suppliers.supplier_code AS code, suppliers.name AS name, 
                            lookups.description AS prodserv, lookups2.description AS type',
					    'joins'		=> 'INNER JOIN lookups ON suppliers.product_service = lookups.id
					    							INNER JOIN lookups AS lookups2 ON lookups2.id = suppliers.supplier_type',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("suppliers" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>