<?php
require('../include/general.class.php');

$delivery_id = $_GET['delivery_id'];

if(empty($delivery_id)) return null;

function populate_records($id) {
  global $DB;
  
  $query = $DB->Fetch('receive_items', array(
                  'columns' => 'receive_items.id, material_code AS material_code, materials.description AS material_description, 
                                quantity, lookups.description AS status, passed, receive_items.remarks',
                  'joins' => 'INNER JOIN materials ON materials.id = receive_items.receive_item
                              INNER JOIN lookups ON lookups.id = receive_items.status',
                  'conditions' => 'delivery_id = '. $id)
           );
	
  return array("materials" => $query);
}

$JSON->build_pretty_json(populate_records($delivery_id));
?>