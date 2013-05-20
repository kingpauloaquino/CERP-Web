<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "material_code");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = 'delivery_items.invoice ="'.$_GET['inv'] .'"';
	
	$query = $DB->Fetch('delivery_items', array(
               'columns' => 'purchases.po_number, materials.material_code, materials.description, delivery_items.received, purchase_items.quantity AS po_qty,
															purchase_items.item_price, lookups.description AS unit, purchases.id AS purchase_id, materials.id AS item_id',
               'joins' => 'INNER JOIN deliveries ON deliveries.id = delivery_items.delivery_id
														INNER JOIN purchases ON purchases.id = deliveries.purchase_id
														INNER JOIN purchase_items ON purchase_items.id = delivery_items.purchase_item_id
														INNER JOIN materials ON materials.id = purchase_items.item_id
														INNER JOIN lookups ON lookups.id = purchase_items.currency',
						    'order' 	=> $order .' '.$sort,
	    					'limit'		=> $startpoint .', '.$limit,
                'conditions' => $search)
             );
	
	//$query = $Query->purchase_items_by_id($_GET['purchase_id']);
	return array("invoice_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>