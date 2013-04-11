<?php
require '../include/general.class.php';
$DB = new MySQL; 
if($_POST) {
	$type = $_POST['filterType'];
	// switch ($type) {
		// case 'devices':
			$table			= $_POST['table'];
			$columns		= $_POST['columns'];
			$joins			= $_POST['joins'];
			$conditions	= $_POST['conditions'];
			$query_result = $DB->Find($table, array('columns' => $columns, 'joins' => $joins, 'conditions' => $conditions));
			echo json_encode($query_result);	
			exit(); break;			
	// }	
}
?> 