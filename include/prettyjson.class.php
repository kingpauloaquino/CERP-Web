<?php
class PrettyJson {
  
  public function build_pretty_json($data) {
  	$jsontext = '';
	
    foreach ($data as $key => $value) {
      $jsontext .= '"'.addslashes($key).'":'.$this->flatten_array($value).', ';
    }
	$jsontext = '{'.substr_replace($jsontext, '', -2).'}';
	
	header('Content-Type: application/json');
	echo $this->pretty_json($jsontext);
	exit();
  }
  
  public function flatten_array($arrays) {
  	$jsontext = '';
	
	if(is_array($arrays) != 1) {
	  // if(is_int($arrays) == 1) return $arrays;
	  return (!is_numeric($arrays) ? '"'.$arrays.'"' : $arrays);
	}
	
    foreach ($arrays as $array) {
  	  $n = 0; $item = '';
	  foreach ($array as $key => $value) {
	    if($n%2 == 1) $item .= '"'.addslashes($key).'":"'.addslashes($value).'", ';
	    $n++;
      }
	  $jsontext .= '{'.substr_replace($item, '', -2).'}, ';
	}
	
	$jsontext = substr_replace($jsontext, '', -2);
	return '['.$jsontext.']';
  } 
  
  public function pretty_json($json) {
    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

      // Grab the next character in the string.
      $char = substr($json, $i, 1);

      // Are we inside a quoted string?
      if ($char == '"' && $prevChar != '\\') {
        $outOfQuotes = !$outOfQuotes;
        
      // If this character is the end of an element, 
      // output a new line and indent the next line.
      } else if(($char == '}' || $char == ']') && $outOfQuotes) {
        $result .= $newLine;
        $pos --;
        for ($j=0; $j<$pos; $j++) {
          $result .= $indentStr;
        }
      }
        
      // Add the character to the result string.
      $result .= $char;
      // If the last character was the beginning of an element, 
      // output a new line and indent the next line.
      if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
        $result .= $newLine;
        if ($char == '{' || $char == '[') { $pos ++; }
            
        for ($j = 0; $j < $pos; $j++) {
          $result .= $indentStr;
        }
      }
      $prevChar = $char;
    }
    return $result;
  }
}