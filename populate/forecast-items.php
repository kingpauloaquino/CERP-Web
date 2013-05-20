<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "forecasts.product_id, forecasts.id");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "ASC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = 'forecasts.product_id='.$_GET['pid'];
	
	$query = $DB->Fetch('forecasts', array(
               'columns' => 'forecasts.id, forecast_calendar.id AS forecast_calendar_id,forecast_calendar.week, forecasts.ship_date, forecasts.qty, forecasts.remarks',
               'joins' => 'RIGHT OUTER JOIN forecast_calendar ON forecast_calendar.id = forecasts.forecast_cal_id
														LEFT OUTER JOIN products ON products.id = forecasts.product_id',
						    'order' 	=> $order .' '.$sort,
	    					'limit'		=> $startpoint .', '.$limit,
                'conditions' => $search)
             );
	
	return array("forecast_items" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>