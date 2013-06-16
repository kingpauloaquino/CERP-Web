<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "t.prod_date");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	
	$search = (isset($keyword) || $keyword != '') 
						? 
						't.code LIKE "%'. $keyword .'%" OR '.
						't.description LIKE "%'. $keyword .'%" OR '.
						't.series LIKE "%'. $keyword .'%" OR '.
						't.unit LIKE "%'. $keyword .'%"'
						: '';
						
	$sub_query_table = '
									(
										SELECT forecasts.prod_date, forecasts.product_id AS pid, products.product_code AS code, products.description, lookups.code AS unit, product_series.series, products.pack_qty, SUM(forecasts.qty) AS total_qty
										FROM forecasts
										INNER JOIN products ON products.id = forecasts.product_id
										INNER JOIN item_costs ON item_costs.item_id = products.id AND item_costs.item_type = "PRD"
										INNER JOIN lookups ON lookups.id = item_costs.unit
										INNER JOIN product_series ON product_series.id = products.series
										WHERE MONTH(forecasts.prod_date) = '.$_GET['pmonth'].' 
										GROUP BY forecasts.product_id
							          
										UNION ALL
							          
										SELECT shipment_plans.prod_date, shipment_plans.item_id AS pid, products.product_code AS code, products.description, lookups.code AS unit, product_series.series, products.pack_qty, SUM(shipment_plans.qty) AS total_qty
										FROM shipment_plans
										INNER JOIN products ON products.id = shipment_plans.item_id
										INNER JOIN item_costs ON item_costs.item_id = products.id AND item_costs.item_type = "PRD"
										INNER JOIN lookups ON lookups.id = item_costs.unit
										INNER JOIN product_series ON product_series.id = products.series
										WHERE MONTH(shipment_plans.prod_date) = '.$_GET['pmonth'].' 
										GROUP BY shipment_plans.item_id
									) t ';
	
	$query = $DB->Fetch($sub_query_table, array(
							'columns'	=> 't.prod_date, t.pid, t.code, t.description, t.unit, t.series, t.pack_qty, SUM(t.total_qty) AS total_qty',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'group' => 't.pid',
    					'conditions' => $search,
             )
           );
	return array("production_plans" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>