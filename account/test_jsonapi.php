<?php
	$name = $_REQUEST['name'];
	$arr = array("name" => $name, "age" => "29", "gender" => "male");
	echo json_encode($arr);
?>