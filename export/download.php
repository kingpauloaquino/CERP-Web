<?php
$id = $_GET['id'];
switch($_GET['cat']) {
	case 'purchases':
		require($_GET['type'].'/purchase.php');		
		break;
}

if($_GET['type'] == 'xls') {
	header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename='.basename($objName));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($objName));
  ob_clean();
  flush();
  readfile($objName);
  exit;
}
if($_GET['type'] == 'pdf') {
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="'. basename($objName) .'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($objName));
	header('Accept-Ranges: bytes');
  @readfile($objName);
	exit;
}
?>