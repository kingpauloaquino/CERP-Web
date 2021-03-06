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
	$sql = "SELECT users.* FROM users WHERE users.employee_id = '".$username."' AND users.password = '".md5($password)."'";
	return $this->Query($sql);
  }
	
	function Find($table, $args='') {
  	if (isset($args)) {
  	  $args['columns'] = (isset($args['columns']) ? $args['columns'] : '*');
  	  $args['joins'] = (isset($args['joins']) ? $args['joins'] : '');
	 	 	$args['conditions'] = ($args['conditions'] == '' ? '' : 'where '.$args['conditions']);
		}
	
		$sql = "SELECT ".$args['columns']." FROM ".$table." ".$args['joins']." ".$args['conditions'];
		$row = $this->IsEmpty($this->Query($sql));
// echo '<br/><br/>';
// var_dump($sql); die();
		if($row == null) { return null; }
		return mysql_fetch_array($row);
  }

  function Get($table, $args='') {
  	if (isset($args)) {
  	  $args['columns'] = (isset($args['columns']) ? $args['columns'] : '*');
  	  $args['joins'] = (isset($args['joins']) ? $args['joins'] : '');
		  $args['conditions'] = ($args['conditions'] == '' ? '' : 'WHERE '.$args['conditions']);
		  //$args['sort_column'] = ($args['sort_column'] == '' ? '' : 'ORDER BY '.$args['sort_column']);
		  //$args['sort_order'] = ($args['sort_order'] == '' ? '' : $args['sort_order']);
  	  $args['order'] = (isset($args['order']) ? ' ORDER BY '.$args['order'] : '');
  	  $args['limit'] = (isset($args['limit']) ? ' LIMIT '.$args['limit'] : '');
  	  $args['group'] = (isset($args['group']) ? ' GROUP BY '.$args['group'] : '');
		}
	
		$data = array();
		$sql = "SELECT ".$args['columns']." FROM ".$table." ".$args['joins']." ".$args['conditions']." ".$args['group']." ".$args['order']." ".$args['limit'];
// echo '<br/><br/>';
// var_dump($sql);	
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

			$args['conditions'] = (isset($args['conditions']) && $args['conditions'] != '') ? ' WHERE '.$args['conditions'] : '';
			$args['order'] = (isset($args['order']) ? ' ORDER BY '.$args['order'] : '');
			$args['limit'] = (isset($args['limit']) ? ' LIMIT '.$args['limit'] : '');
			$args['group'] = (isset($args['group']) ? ' GROUP BY '.$args['group'] : '');
      // $args['sort_column'] = ($args['sort_column'] == '' ? '' : 'ORDER BY '.$args['sort_column']);
    }
	
	$data = array();
	// $sql = "SELECT ".$args['columns']." FROM ".$table." ".$args['joins']." ".$args['conditions']." ".$args['sort_column']." ".$args['sort_order']." ".$args['startpoint']." ".$args['limit'];
    $sql = "SELECT ".$args['columns'];
    $sql .= " FROM ".$table." ".$args['joins'];
    $sql .= $args['conditions'];
    $sql .= $args['group'];
    $sql .= $args['order'];
	
	// echo '<br/><br/>';
	// var_dump($sql); die();
	$this->statement = $sql;
    $sql .= $args['limit'];
	
    //var_dump($sql);
	$result = $this->IsEmpty($this->Query($sql));
	
	if(!empty($result)) {
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
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
  
  private function IsEmpty($result) {
    if(mysql_num_rows($result) > 0) return $result;
    return null;
  }
  
  public function InsertRecord($table, $arguments) {
  	if(!is_array($arguments)) return 'Error::InvalidArguments';	
	
  	$columns	= implode(array_keys($arguments),",");
		$values		= implode($arguments,"','");
    $sql			= "INSERT INTO ".$table." (".$columns.", created_at) VALUES ('".$values."', '".date('Y-m-d H:i:s')."')";
// echo '<br/><br/>';
// var_dump($sql); die(); 
		$this->Query($sql);
		return mysql_insert_id();
  }
  
  public function UpdateRecord($table, $arguments) {
  	if(!is_array($arguments)) return 'Error::InvalidArguments';	
  	$values = '';
	
	foreach ($arguments['variables'] as $key => $value) {			
		//if(strpos($value, "'")!==FALSE) $value = str_replace("'", "''", $value);
		$value = mysql_real_escape_string($value);
		if(strpos($value, "qty")!==FALSE) {
			$values .= $key."=".$value.",";
		}	else {
			$values .= $key."='".$value."',";
		}
		
	} 
	$sql = "UPDATE ".$table." SET ".rtrim($values, ",").", updated_at='".date('Y-m-d H:i:s')."' WHERE ".$arguments['conditions']; 
// echo '<br/><br/>';
// var_dump($sql); die();	
	$this->Query($sql);
	return mysql_affected_rows();
  }
	
	public function DeleteRecord($table, $arguments) {
  	if(!is_array($arguments)) return 'Error::InvalidArguments';	
	
    $sql = "DELETE FROM ".$table." WHERE ".$arguments['conditions']; 
// echo '<br/><br/>';
// var_dump($sql); die();
		$this->Query($sql);
		return mysql_affected_rows();
  }
	
	public function ExecuteQuery($arguments) {
		if(!is_array($arguments)) return 'Error::InvalidArguments';		
// echo '<br/><br/>';	
// var_dump($arguments); die();
		//loop query, mysql_query doesnt support multiple query in one call
		$conn = $this->Connect();		
		foreach ($arguments as $key => $value) {
    	$result = mysql_query($value, $conn);
		}
		return $result;
	}
}