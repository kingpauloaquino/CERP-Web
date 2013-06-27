<?php
$id = $_GET['id'];

switch($_GET['cat']) {
	case 'purchases':
		require($_GET['type'].'/purchase.php');		
		break;
	case 'minventory_report':
		$mydate = $_GET['mydate'];
		$name = 'materials_month_end_report.xls';
		require($_GET['type'].'/minventory-report.php');
		break;
	case 'pinventory_report':
		$mydate = $_GET['mydate'];
		$name = 'products_month_end_report.xls';
		require($_GET['type'].'/pinventory-report.php');
		break;
	case 'receive_date_report':
		$mydate = $_GET['mydate'];
		$name = 'receive_date_report.xls';
		require($_GET['type'].'/receive-date-report.php');
		break;
	case 'receive_supplier_report':
		$mydate = $_GET['mydate'];
		$sid = $_GET['sid'];
		$name = 'receive_supplier_report.xls';
		require($_GET['type'].'/receive-supplier-report.php');
		break;
	case 'receive_all_supplier_report':
		$mydate = $_GET['mydate'];
		$name = 'receive_all_supplier_report.xls';
		require($_GET['type'].'/receive-all-supplier-report.php');
		break;
}

if($_GET['type'] == 'xls') {
	header('Content-Description: File Transfer');
	header('Content-type: application/vnd.ms-excel');
  //header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename='.basename($objName));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($objName));
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