<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "purchase_id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = (isset($keyword) || $keyword != '') 
						? 
						'p.purchase_number LIKE "%'. $keyword .'%" OR '.
						'm.material_code LIKE "%'. $keyword .'%" OR '.
						'm.description LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$items	= "SELECT pi.id, pi.purchase_id, pi.item_id, pi.quantity, SUM(IFNULL(ri.quantity, 0)) AS received
		  				FROM purchases AS p
		  				LEFT OUTER JOIN purchase_items AS pi ON pi.purchase_id = p.id
		  				LEFT OUTER JOIN receive_items AS ri ON ri.receive_item = pi.id
		  				WHERE p.received = 0
		  				GROUP BY pi.id
		  				HAVING SUM(IFNULL(ri.quantity, 0)) < pi.quantity";
					
	$query = $DB->Fetch('purchases AS p', array(
							'columns'	=> 'pi.id, p.id AS purchase_id, p.purchase_number, ic.id AS material_id, m.material_code, 
  													m.description AS material_description, l.description AS unit, pi.quantity, pi.received',
					    'joins'		=> 'LEFT OUTER JOIN ('.$items.') AS pi ON pi.purchase_id = p.id
									  				INNER JOIN item_costs AS ic ON ic.id = pi.item_id AND ic.item_type = "MAT"
									  				INNER JOIN materials AS m ON m.id = ic.item_id
									  				INNER JOIN lookups AS l ON l.id = ic.unit
									  				AND p.received = 0 AND p.status = 137',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("receiving" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>