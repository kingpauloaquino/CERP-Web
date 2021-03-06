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
	$search = 'forecast_weeks.forecast_cal_id='.$_GET['fwid'].' AND forecast_weeks.month_id='.$_GET['mo'].' AND forecast_weeks.week_id='.$_GET['wk'];
	
	$query = $DB->Fetch('forecast_week_days', array(
							'columns'	=> 'forecast_week_days.*',
					    //'joins'		=> 'INNER JOIN forecast_weeks ON forecast_weeks.id = forecast_week_days.forecast_week_id',
					    'joins'		=> 'INNER JOIN forecast_weeks ON forecast_weeks.id = forecast_week_days.forecast_week_id
					    							INNER JOIN forecast_calendar ON forecast_calendar.id = forecast_weeks.forecast_cal_id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search,
             )
           );
	return array("forecast_week_days" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>