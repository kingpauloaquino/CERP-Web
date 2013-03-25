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
						'lookups.description LIKE "%'. $keyword .'%" OR '.
						'defects.defect LIKE "%'. $keyword .'%" OR '.
						'defects.model LIKE "%'. $keyword .'%" OR '.
						'defects.line LIKE "%'. $keyword .'%" OR '.
						'defects.category LIKE "%'. $keyword .'%" OR '.
						'defects.location LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('defects', array(
							'columns'	=> 'defects.id, lookups.description AS type, defects.defect, defects.model, defects.line, defects.category, defects.location',
					    'joins'		=> 'INNER JOIN lookups ON lookups.id = defects.type',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("defects" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>