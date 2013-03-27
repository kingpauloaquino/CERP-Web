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
	$search = 'item_costs.item_type = "MAT" AND products_parts_tree.product_id = '.$keyword;
	
	$query = $DB->Fetch('products_parts_tree', array(
							'columns'	=> 'products_parts_tree.id, materials.id AS material_id, materials.material_code AS code, materials.description,
				  												lookups.code AS unit, item_costs.cost AS item_price, products_parts_tree.material_qty AS quantity, 
				  												suppliers.name, products_parts_tree.remarks, item_costs.transportation_rate',
					    'joins'		=> 'LEFT OUTER JOIN materials ON products_parts_tree.material_id = materials.id
				  	    									LEFT OUTER JOIN item_costs ON materials.id = item_costs.item_id
				  	    									LEFT OUTER JOIN suppliers ON item_costs.supplier = suppliers.id
				  	    									LEFT OUTER JOIN lookups ON item_costs.unit = lookups.id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
							'conditions'=> $search
             )
           );
	return array("parts_tree" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>