<?php
require '../include/general.class.php';
$DB = new MySQL; 
if($_POST)
{
	$table			= $_POST['table'];
	$columns		= $_POST['columns'];
	$joins			= $_POST['joins'];
	$conditions	= $_POST['conditions'];
	
	$query_result = $DB->Find($table, array(
			        				'columns' 		=> $columns,
			        				'joins'				=> $joins,
			        				'conditions' 	=> $conditions,
		  							 ));	
	if(isset($query_result['id'])) {
		echo 'true';
	}  
}

?> 