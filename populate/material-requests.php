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
	
	$query = $DB->Fetch('material_requests', array(
							'columns'	=> 'material_requests.id AS id, orders.po_number AS po_number, material_requests.lot_no AS lot_no, 
														brand_models.brand_model AS brand, products.product_code AS product_code, 
														material_requests.request_date AS request_date',
					    'joins'		=> 'INNER JOIN products ON products.id = material_requests.product_id
													INNER JOIN brand_models ON brand_models.id = products.brand_model
													INNER JOIN production_purchase_orders ON production_purchase_orders.id = material_requests.production_purchase_order_id
													INNER JOIN orders ON orders.id = production_purchase_orders.order_id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit
             )
           );
	return array("material_requests" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>