<?php
class MySQL {
  // Database::Production
  public $database	= "cerpdb";
  public $username	= "cerpuser";  
  public $password	= "cerpuser";
  public $hostname	= "localhost";  

  public $table		= "";
  public $sql		= "";
  public $statement	= "";
  
  public function Connect() {
    $conn = mysql_connect($this->hostname, $this->username, $this->password) or die("<br/><span class='notice'><p class='error'><strong>ERROR!</strong> Unable to connect to MySQL! </p></span>");
    mysql_select_db($this->database, $conn) or die("<br/><span class='notice'><p class='error'><strong>ERROR!</strong> MySQL database not found </p></span>");
    
    return $conn;
	// $conn->close()
	
  } 
  
  public function Query($sql) {
  	$conn = $this->Connect();
    $result = mysql_query($sql, $conn);
	
    // How to parse result using different MySQL fetch function
    // $user = mysql_fetch_object($result)
    // $user->last_name

    // $user = mysql_fetch_array($result)
    // $user['last_name']
	return $result;
  }
  
  // Authenticate Username and Password
  // Get User Information
  function authenticate($username, $password) {
	$sql = "SELECT * FROM users WHERE username = '".$username."' AND password = '".md5($password)."'";
	return $this->Query($sql);
  }

  function Get($table, $args='') {
  	if (isset($args)) {
  	  $args['columns'] = (isset($args['columns']) ? $args['columns'] : '*');
  	  $args['joins'] = (isset($args['joins']) ? $args['joins'] : '');
		  $args['conditions'] = ($args['conditions'] == '' ? '' : 'WHERE '.$args['conditions']);
		  $args['sort_column'] = ($args['sort_column'] == '' ? '' : 'ORDER BY '.$args['sort_column']);
		  $args['sort_order'] = ($args['sort_order'] == '' ? '' : $args['sort_order']);
		  $args['startpoint'] = ($args['startpoint'] == '' ? '' : 'LIMIT '.$args['startpoint']);
		  $args['limit'] = ($args['limit'] == '' ? '' : ','.$args['limit']);
		}
	
	$data = array();
	$sql = "SELECT ".$args['columns']." FROM ".$table." ".$args['joins']." ".$args['conditions']." ".$args['sort_column']." ".$args['sort_order']." ".$args['startpoint']." ".$args['limit'];
//var_dump($sql);
	$result = $this->IsEmpty($this->Query($sql));
	
	if(!empty($result)) {
      while ($row = mysql_fetch_array($result)) {
        $data[] = $row;
      }
	}
	return $data;
  }

	function Fetch($table, $args='') {
  	if (isset($args)) {
  	  $args['columns'] = (isset($args['columns']) ? $args['columns'] : '*');
  	  $args['joins'] = (isset($args['joins']) ? $args['joins'] : '');
	  $args['conditions'] = ($args['conditions'] == '' ? '' : ' WHERE '.$args['conditions']);
      $args['order'] = ($args['order'] == '' ? '' : ' ORDER BY '.$args['order']);
      $args['limit'] = ($args['limit'] == '' ? '' : ' LIMIT '.$args['limit']);
      // $args['sort_column'] = ($args['sort_column'] == '' ? '' : 'ORDER BY '.$args['sort_column']);
    }
	
	$data = array();
	// $sql = "SELECT ".$args['columns']." FROM ".$table." ".$args['joins']." ".$args['conditions']." ".$args['sort_column']." ".$args['sort_order']." ".$args['startpoint']." ".$args['limit'];
    $sql = "SELECT ".$args['columns'];
    $sql .= " FROM ".$table." ".$args['joins'];
    $sql .= $args['conditions'];
    $sql .= $args['order'];
	
	// echo $sql; exit();
	
	$this->statement = $sql;
    $sql .= $args['limit'];
	
    //var_dump($sql);
	$result = $this->IsEmpty($this->Query($sql));
	
	if(!empty($result)) {
      while ($row = mysql_fetch_array($result)) {
        $data[] = $row;
      }
	}
	return $data;
  }
  
  function totalRows() {
  	$sql	= 'SELECT COUNT(*) AS num FROM ('.$this->statement.') AS rowTable';
    $result	= $this->IsEmpty($this->Query($sql));
	
		if(empty($result)) return 0;
		$row = mysql_fetch_array($result);
		return $row[0];
  }
  
  function Find($table, $args='') {
  	if (isset($args)) {
  	  $args['columns'] = (isset($args['columns']) ? $args['columns'] : '*');
  	  $args['joins'] = (isset($args['joins']) ? $args['joins'] : '');
	  $args['conditions'] = ($args['conditions'] == '' ? '' : 'where '.$args['conditions']);
	}
	
	$sql = "SELECT ".$args['columns']." FROM ".$table." ".$args['joins']." ".$args['conditions'];
	$row = $this->IsEmpty($this->Query($sql));
//var_dump($sql);
	if($row == null) { return null; }
	return mysql_fetch_array($row);
  }
  
  private function IsEmpty($result) {
    if(mysql_num_rows($result) > 0) return $result;
    return null;
  }
  
  public function InsertRecord($table, $arguments) {
  	if(!is_array($arguments)) return 'Error::InvalidArguments';	
	
  	$columns	= implode(array_keys($arguments),",");
		$values		= implode($arguments,"','");
    $sql			= "INSERT INTO ".$table." (".$columns.", created_at) VALUES ('".$values."', '".date('Y-m-d H:i:s')."')"; 
//var_dump($sql); 
		$this->Query($sql);
		return mysql_insert_id();
  }
  
  public function UpdateRecord($table, $arguments) {
  	if(!is_array($arguments)) return 'Error::InvalidArguments';	
  	$values = '';
	
	foreach ($arguments['variables'] as $key => $value) {			
		if(strpos($value, "'")!==FALSE) $value = str_replace("'", "''", $value);
		if(strpos($value, "qty")!==FALSE) {
			$values .= $key."=".$value.",";
		}	else {
			$values .= $key."='".$value."',";
		}
	} 
	$sql = "UPDATE ".$table." SET ".rtrim($values, ",").", updated_at='".date('Y-m-d H:i:s')."' WHERE ".$arguments['conditions']; 
//var_dump($sql);	
	$this->Query($sql);
	return mysql_affected_rows();
  }
	
	public function DeleteRecord($table, $arguments) {
  	if(!is_array($arguments)) return 'Error::InvalidArguments';	
	
    $sql = "DELETE FROM ".$table." WHERE ".$arguments['conditions']; 
//var_dump($sql);
		$this->Query($sql);
		return mysql_affected_rows();
  }
	
	public function ExecuteQuery($arguments) {
		if(!is_array($arguments)) return 'Error::InvalidArguments';		
//var_dump($arguments);
		//loop query, mysql_query doesnt support multiple query in one call
		$conn = $this->Connect();		
		foreach ($arguments as $key => $value) {
    	$result = mysql_query($value, $conn);
		}
		return $result;
	}
}