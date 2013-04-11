<?php
require('../include/general.class.php');

function populate_records() {
  global $DB;
  
  $data		= array();
  $items	= "SELECT pi.id, pi.purchase_id, pi.item_id, pi.quantity, SUM(IFNULL(ri.quantity, 0)) AS received
  				FROM purchases AS p
  				LEFT OUTER JOIN purchase_items AS pi ON pi.purchase_id = p.id
  				LEFT OUTER JOIN receive_items AS ri ON ri.receive_item = pi.id
  				WHERE p.received = 0
  				GROUP BY pi.id
  				HAVING SUM(IFNULL(ri.quantity, 0)) < pi.quantity";
				
  $sql		= "SELECT pi.id, p.id AS purchase_id, p.purchase_number, ic.id AS material_id, m.material_code, 
  				m.description AS material_description, l.description AS unit, pi.quantity, pi.received
  				FROM purchases AS p
  				LEFT OUTER JOIN (".$items.") AS pi ON pi.purchase_id = p.id
  				INNER JOIN item_costs AS ic ON ic.id = pi.item_id AND ic.item_type = 'MAT'
  				INNER JOIN materials AS m ON m.id = ic.item_id
  				INNER JOIN lookups AS l ON l.id = ic.unit
  				WHERE p.received = 0 AND p.status = 137";
  $result	= $DB->query($sql);
  
  if(!empty($result)) {
  	while ($row = mysql_fetch_array($result)) {
      $data[] = $row;
    }
  }
  
  return array("materials" => $data);
}

$JSON->build_pretty_json(populate_records());
?>