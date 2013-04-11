<?php
require('../include/general.class.php');

function populate_records() {
  global $DB;
  
  $query = array(
    'columns'		=> 'id, code, description',
    'conditions'	=> 'parent = "UNITS"'
  );
  
  $data = $DB->Fetch('lookups', $query);
  return array("units" => $data, "total" => $DB->totalRows());
}

$data = populate_records($keyword, $paged, $limit, $sort, $order);
$JSON->build_pretty_json($data);
?>