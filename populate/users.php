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
						'users.employee_id LIKE "%'. $keyword .'%" OR '.
						'users.first_name LIKE "%'. ucwords(strtolower($keyword)) .'%" OR '.
						'users.last_name LIKE "%'. ucwords(strtolower($keyword)) .'%" OR '.
						'roles.name LIKE "%'. ucwords(strtolower($keyword)) .'%" OR '.
						'lookups.description LIKE "%'. $keyword .'%" '
						: '';
	
	$query = $DB->Fetch('users', array(
							'columns'	=> 'users.id AS id, users.employee_id AS employee_id, 
														CONCAT(users.first_name," ",users.last_name) AS username, 
														roles.name AS role, lookups.description AS status',
					    'joins'		=> 'INNER JOIN roles on users.role = roles.id LEFT OUTER JOIN lookups on users.status = lookups.id',
					    'order' 	=> $order .' '.$sort,
    					'limit'		=> $startpoint .', '.$limit,
    					'conditions' => $search
             )
           );
	return array("users" => $query, "total" => $DB->totalRows());
}
echo json_encode(populate_records($keyword, $page, $limit, $order, $sort));
//$JSON->build_pretty_json(populate_records($keyword, $page, $limit, $order, $sort));
?>