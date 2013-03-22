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
	
	$query = $DB->Fetch('production_inventories', array(
							'columns'	=> 'production_inventories.id AS id, 
													production_inventories.prod_lot_no AS prod_lot_no, production_inventories.item_id AS item_id, production_inventories.mat_lot_no AS mat_lot_no, 
													production_inventories.tracking_no AS tracking_no, materials.material_code AS material_code, materials.description AS description, 
													lookups.description AS status, production_inventories.qty AS qty',
					    'joins'		=> 'INNER JOIN materials ON materials.id = production_inventories.item_id
					    							INNER JOIN lookups ON lookups.id = production_inventories.status',
					    'conditions' => ' production_inventories.terminal_id = '.$_GET['tid'],
              // 'order' 	=> $order .' '.$sort,
							// 'limit'		=> $startpoint .', '.$limit							//TODO: if with order/limit => error
             )
           );
	return array("terminal_prod_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>   