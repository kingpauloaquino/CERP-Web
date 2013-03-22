<?php
session_start();

require('database.class.php');
require('capabilities.class.php');
require('posts.class.php');
require('query.class.php');
require('prettyjson.class.php');
require('menu.class.php');
  
$Host			= ""; //gethostname();
$HostAccount	= "account";
$HostExcel		= "excel";
$DB				= new MySQL;

// Database::Development
// if(gethostname() == "localhost") {
  $Host			= "cerp";
  $HostAccount	= "/".$Host."/".$HostAccount; 
  $HostExcel	= "/".$Host."/".$HostExcel; 
  $DB->database	= "cerpdb";
  $DB->username	= "cerpuser";  
  $DB->password	= "cerpuser";
  $DB->hostname	= "localhost";
// }

$Capabilities	= new Capabilities;
$Posts			= new Posts($DB);
$Query			= new Query($DB);
$JSON			= new PrettyJson;
$Menu			= new Menu;

$Signed			= $_SESSION['user'];  
$Title			= $Capabilities->GetName();

$default_page_limit = 15; 
date_default_timezone_set('Asia/Manila');
  
require('functions.php');
require('tabling.php');
?>