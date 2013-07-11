<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "status, id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = 'delivery_items.status = 22 AND purchases.id='.$_GET['id'];
	
	$query = $DB->Fetch('delivery_items', array(
               'columns' => 'delivery_items.id, purchase_items.item_id, delivery_items.purchase_item_id, materials.material_code AS code, materials.description, purchase_items.quantity, 
													    delivery_items.invoice, delivery_items.received, delivery_items.remarks, lookups.description AS unit, lookup_status.description AS status, 
													    (
													        SELECT SUM(received) 
													        FROM delivery_items AS del 
													        INNER JOIN purchase_items AS pi ON pi.id = del.purchase_item_id
													        INNER JOIN purchases AS p ON p.id = pi.purchase_id
													        INNER JOIN materials AS m ON m.id = pi.item_id
													        WHERE del.purchase_item_id = delivery_items.purchase_item_id
													    ) AS delivered',
               'joins' => 'INNER JOIN purchase_items ON purchase_items.id = delivery_items.purchase_item_id
												    INNER JOIN purchases ON purchases.id = purchase_items.purchase_id
												    INNER JOIN materials ON materials.id = purchase_items.item_id
												    INNER JOIN item_costs ON item_costs.item_id = materials.id AND item_costs.item_type = "MAT" AND item_costs.supplier = purchases.supplier_id
												    INNER JOIN lookups ON lookups.id = materials.unit
												    INNER JOIN lookup_status ON lookup_status.id = delivery_items.status',
						    'order' 	=> $order .' '.$sort,
	    					'limit'		=> $startpoint .', '.$limit,
                'conditions' => $search)
             );
	
	//$query = $Query->purchase_items_by_id($_GET['purchase_id']);
	return array("delivery_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>