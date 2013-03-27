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
	$search = (isset($keyword) || $keyword != '') 
						? 
						'notifications.title LIKE "%'. $keyword .'%" OR '.
						'notifications.value LIKE "%'. $keyword .'%" OR '.
						'notifications.remarks LIKE "%'. $keyword .'%" OR '.
						'notifications.created_at LIKE "%'. $keyword .'%" OR '.
						'lookups.description LIKE "%'. $keyword .'%" '
						//'materials.tags LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('notifications', array(
							'columns'	=> 'notifications.id AS id, lookups.description AS type, notifications.title, notifications.value, 
                            notifications.url, notifications.remarks, notifications.created_at, lookups2.description AS status',
					    'joins'		=> 'INNER JOIN lookups ON notifications.type = lookups.id
					    							INNER JOIN lookups AS lookups2 ON notifications.status = lookups2.id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("notifications" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>