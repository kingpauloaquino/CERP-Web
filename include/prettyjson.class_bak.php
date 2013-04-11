<?php
class PrettyJson {
  
  public function build_pretty_json($arrays) {
  	$jsontext = '';
	
  	foreach ($arrays as $array) {
  	  $jsontext .= $this->flatten_json($array).',';
    }
	
	header('Content-Type: application/json');
    echo $this->pretty_json('['.substr_replace($jsontext, '', -1).']');
	exit();
  }
  
  public function flatten_json($arrays) {
  	$n = 0;
	$jsontext = '';
	foreach ($arrays as $key => $value) {
	  if($n%2 == 1) {
	    $jsontext .= '"'.addslashes($key).'":"'.addslashes($value).'",';
	  }
	  $n++;
    }
	return '{'.substr_replace($jsontext, '', -1).'}';
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