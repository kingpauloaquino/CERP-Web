<?php
// ========================================================
// General Functions
// ========================================================

function host($uri='') {
  global $HostAccount;
  return $HostAccount."/".$uri;
}

function redirect_to($url) {
  //header('Location: '.$url);
  //exit();
	echo "<script> window.location.replace('".$url."') </script>";
}

function excel_file($params='') {
  global $HostExcel;
  return $HostExcel."/download.php".$params;
}

function strdate($str, $format='') {
  return date($format, strtotime($str));
}

function current_page_name() {
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

function extract_querystring($querystring, $items) {
	$querystring = explode('&',$querystring);
	$items = explode( ',', $items);   
	$newqs = array();
	foreach ($items as $itm) {
		foreach ($querystring as $qs) {
		 	if(strpos($qs, $itm)!==FALSE) {
				$newqs[] = $qs;
		 	}				
		}
	}  
	return implode("&", $newqs);
}

function select_tag($items=array(), $selected='', $id='', $name='', $option='', $style='', $value_is_text=FALSE) {
  $tag = '<select id="'.$id.'" name="'.$name.'" style="'.$style.'">';
  if (!empty($items)) {
  	$tag .= ($option == '' ? '' : '<option value="">'.$option.'</option>');
    foreach ($items as $key => $value) {
		  $tag .= ($value_is_text) ? '<option value="'.$value.'" ' : '<option value="'.$key.'" ';
		  $tag .= ($selected == $value ? ' selected="selected">' : '>');
		  $tag .= $value;
		  $tag .= '</option>';
		}
  }
  $tag .= '</select>';
  echo $tag;
}

function select_tag2($items=array(), $selected='', $id='', $name='', $option='', $class='text', $value_is_text=FALSE) {
  $tag = '<select id="'.$id.'" name="'.$name.'" class="'.$class.'">';
  if (!empty($items)) {
  	$tag .= ($option == '' ? '' : '<option value="">'.$option.'</option>');
    foreach ($items as $key => $value) {
		  $tag .= ($value_is_text) ? '<option value="'.$value.'" ' : '<option value="'.$key.'" ';
		  $tag .= ($selected == $value ? ' selected="selected">' : '>');
		  $tag .= $value;
		  $tag .= '</option>';
		}
  }
  $tag .= '</select>';
  echo $tag;
}
  
function select_query_tag2($rows=array(), $key='', $value='', $selected='', $id='', $name='', $option='', $class='text', $readonly = FALSE) {
  $tag = '<select id="'.$id.'" name="'.$name.'" class="'.$class.'" '.$ro=($readonly)?'readonly="readonly"':''.'>';
  $tag .= ($option == '' ? '' : '<option value="">'.$option.'</option>');
  if (!empty($rows)) {
    foreach($rows as $row) {
	  $tag .= '<option value="'.trim($row[$key]).'" ';
	  $tag .= ($selected == trim($row[$key]) ? 'selected="selected">' : '>');
	  $tag .= $row[$value];
	  $tag .= '</option>';
	}
  }
  $tag .= '</select>';
  echo $tag;
}

function select_query_tag($rows=array(), $key='', $value='', $selected='', $id='', $name='', $option='', $style='', $readonly = FALSE) {
  $tag = '<select id="'.$id.'" name="'.$name.'" style="'.$style.'" '.$ro=($readonly)?'readonly="readonly"':''.'>';
  $tag .= ($option == '' ? '' : '<option value="">'.$option.'</option>');
  if (!empty($rows)) {
    foreach($rows as $row) {
	  $tag .= '<option value="'.trim($row[$key]).'" ';
	  $tag .= ($selected == trim($row[$key]) ? 'selected="selected">' : '>');
	  $tag .= $row[$value];
	  $tag .= '</option>';
	}
  }
  $tag .= '</select>';
  echo $tag;
}

function get_role_capabilities($roles, $id) {
  if(isset($id)) {
    foreach ($roles as $role) {
      if($role['id'] == $id) { return explode(',', $role['capabilities']); }
    }
  }
  return explode(',', $roles[0]['capabilities']);
}

function array_flatten($array) { 
  if (!is_array($array)) return false; 
    
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } 
    else { 
      $result[$key] = $value; 
    }
  } 
  return $result; 
}

function now($format="m/d/Y") {
  return date($format);
}
  
function dformat($datetime, $format='Y-m-d H:i:s') {
  if(isset($datetime)) return date($format, strtotime($datetime));
  return '--';
}

function get_lookup_code($key) {
	$codes = array('supplier_type' => 'SUPTYP', 'term_of_payment' => 'TRMPAY', 'unit_of_measure' => 'UNITS', 'currency' => 'CURNCY', 'country' => 'CTRY', 
					'product_service' => 'PRDSVC', 'material_type' => 'MATTYP', 'item_status' => 'ITMSTA', 'user_status' => 'USRSTA',	'loc_bldg' => 'BLDG', 
					'loc_bldg_no' => 'BLDGNUM', 'loc_deck' => 'DECK', 'loc_area' => 'AREA', 'wh1_terminal' => 'WH1TRML','wh2_terminal' => 'WH2TRML', 
					'wip_terminal' => 'WIPTRML', 'inventory_status' => 'INVSTA', 'packing_type' => 'PACTYP', 'mat_req_type' => 'REQTYP', 'inventory_type' => 'INVTYP');
	return $codes[$key];
}

function build_select_suppliers($value="", $key="") {
  global $DB;
  
  $query = array('columns' => 'id, name', 'order' => 'name');
  $data = $DB->Fetch('suppliers', $query);
  $options = "";
  
  foreach ($data as $item) {
    $options .= "<option value=\"". $item['id'] ."\" ";
	if($value != "") $options .= ($value == $item['id'] ? "selected" : "");
	if($key != "") $options .= ($key == $item['name'] ? "selected" : "");
    $options .= ">". $item['name'] ."</option>";
  }
  return $options;
}

function build_select_delivery_receipts($value="", $key="") {
  global $DB;
  
  $query = array('columns' => 'id, delivery_receipt', 'conditions' => 'status = 135');
  $data = $DB->Fetch('deliveries', $query);
  $options = "";
  
  foreach ($data as $item) {
    $options .= "<option value=\"". $item['id'] ."\" ";
	if($value != "") $options .= ($value == $item['id'] ? "selected" : "");
	if($key != "") $options .= ($key == $item['delivery_receipt'] ? "selected" : "");
    $options .= ">". $item['delivery_receipt'] ."</option>";
  }
  return $options;
}


function build_options($query, $columns=array(), $value="", $key="") {
  $options = "";
  
  foreach ($query as $item) {
    $options .= "<option value=\"". $item[$columns[0]] ."\" ";
	if($value != "") $options .= ($value == $item[$columns[0]] ? "selected" : "");
	if($key != "") $options .= ($key == $item[$columns[1]] ? "selected" : "");
    $options .= ">". $item[$columns[1]] ."</option>";
  }
  return $options;
}

function build_select_post_status($key="", $keyname="") {
  global $DB;
  
  $query = array('columns' => 'id, description', 'conditions' => 'parent = "APRVL"');
  $data = $DB->Fetch('lookup_status', $query);
  $options = "";
  
  foreach ($data as $value) {
    $options .= "<option value=\"". $value['id'] ."\" ";
	if($key != "") $options .= ($key == $value['id'] ? "selected" : "");
	if($keyname != "") $options .= ($keyname == $value['description'] ? "selected" : "");
    $options .= ">". $value['description'] ."</option>";
  }

  return $options;
}

//TODO: make generic
function build_select_post_status1($key="", $keyname="") {
  global $DB;
  
  $query = array('columns' => 'id, description', 'conditions' => 'parent = "RCVNG"');
  $data = $DB->Fetch('lookup_status', $query);
  $options = "";
  
  foreach ($data as $value) {
    $options .= "<option value=\"". $value['id'] ."\" ";
	if($key != "") $options .= ($key == $value['id'] ? "selected" : "");
	if($keyname != "") $options .= ($keyname == $value['description'] ? "selected" : "");
    $options .= ">". $value['description'] ."</option>";
  }

  return $options;
}

function to_double($value) {
  return preg_replace('/[^0-9_\.\-]/s', '', $value);
}

function page_not_found() {
  require('../account/404-not-found.php');
  exit();
}
 
function format_empty($value, $non_empty_value, $empty_value = '-') {
	return $res = (isset($value)) ? $non_empty_value : $empty_value;
}

function flatten_json($arrays) {
  $rows = '';
  
  foreach ($arrays as $array) {
    $n = 0; $jsontext = '';
    foreach ($array as $key => $value) {
	  if($n%2 == 1) $jsontext .= '"'.addslashes($key).'":"'.addslashes($value).'",';
      $n++;
    }
    $rows .= '{'.substr_replace($jsontext, '', -1).'},';
  }
  return '['.substr_replace($rows, '', -1).']';
} 

function set_color($color) {
	switch($color) {
		case 'C' :
			return 'background-color: cyan; border:none';
		case 'M' :
			return 'background-color: magenta; color:white; border:none';
		case 'Y' :
			return 'background-color: yellow; border:none';
		case 'B' :
			return 'background-color: black; color:white; border:none';
	}
}

function get_ink_color($color) {	
	switch($color) {
		case 'C' :
			return '<table><tr><td style="background: cyan"></td></tr></table>';
		case 'M' :
			return '<table><tr><td style="background: magenta"></td></tr></table>';
		case 'Y' :
			return '<table><tr><td style="background: yellow"></td></tr></table>';
		case 'B' :
			return '<table><tr><td style="background: black"></td></tr></table>';
		case 'LC' :
			return '<table><tr><td style="background: #E0FFFF"></td></tr></table>';
		case 'LM' :
			return '<table><tr><td style="background: #FF42F9"></td></tr></table>';
		//case 'CMYK' :
			// return '<table><tr><td style="background: cyan"></td><td style="background: magenta"></td><td style="background: yellow"></td><td style="background: black"></td></tr></table>';
		// case 'CMYKLCLM' :
			// return '<table style="margin: -5px"><tr><td style="background: cyan"></td><td style="background: magenta"></td><td style="background: yellow"></td><td style="background: black"></td>
												 // <td style="background: #E0FFFF"></td><td style="background: #FFE0FF"></td></tr></table>';
	}
}

function trim_decimal($value) {
	return rtrim(rtrim($value, "0"),".");
}

function link_to($url) {
	return '<a alt="details" target="_blank" href="'.$url.'"><img style="margin-top: -4px" src="../images/details.png" /></a>';
}

function if_contains($stack, $needle) {
	return strpos($stack,$needle) !== false;
}

function subtract_days($date1, $days) {
	return $newdate = date('Y-m-d', strtotime('-'.$days.' days', strtotime($date1)));
}

function pad_number($number,$n) {
	return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}

function generate_new_code($type) {
	switch($type) {
		case "order_number":	
			$prefix = "STJ-";	
			$flag = '-';			
			$table = 'orders';
			$column = 'po_number';
			$pad = TRUE; 
			$pad_cnt = 3;			
			break;
			
		case "purchase_number":	
			$prefix = "CRS-13VM";	
			$flag = 'M';			
			$table = 'purchases';
			$column = 'purchase_number';
			break;
			
		case "supplier_code":	
			$prefix = "SUP";
			$flag = 'P';
			$table = 'suppliers';
			$column = 'supplier_code';	
			$pad = TRUE; 
			$pad_cnt = 3;			
			break;
			
		case "employee_id":	
			$prefix = "13-";
			$flag = '-';
			$table = 'users';
			$column = 'employee_id';	
			$pad = TRUE; 
			$pad_cnt = 3;			
			break;
	}
	
	global $DB;
	$result = $DB->Find($table, array('columns' => 'MAX(id) AS current'));
	//$res = substr($result['current'], strpos($result['current'], $flag)+1)+1;
	$res = $result['current'] + 1;
	if($pad) { $res = pad_number($res, $pad_cnt); }
	return $prefix . $res;
}
