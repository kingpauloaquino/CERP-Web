<?php
require('../include/general.class.php');

$keyword	= $_GET['params'];
$page			= ($_GET['page'] != "" ? $_GET['page'] : 1);
$limit		= ($_GET['limit'] != "" ? $_GET['limit'] : 15);
$order		= ($_GET['order'] != "" ? $_GET['order'] : "forecast_year");
$sort			= ($_GET['sort'] != "" ? $_GET['sort'] : "DESC");

function populate_records($keyword='', $page, $limit, $order, $sort) {
  global $DB;
  $startpoint = $limit * ($page - 1);
	$search = 'products.id ='.$_GET['pid'];
	
	$query = $DB->Fetch('forecast_calendar', array(
							'columns'	=> 'forecast_calendar.id, products.id AS product_id, products.product_code AS code, products.description, forecast_calendar.forecast_year, 
														jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dece',
					    'joins'		=> 'RIGHT OUTER JOIN products ON products.id = forecast_calendar.product_id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
             )
           );
	return array("forecast_months" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>