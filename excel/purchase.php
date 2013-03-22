<?php
require('../include/PHPExcel/IOFactory.php');
require('../include/general.class.php');

$objReader		= PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel	= $objReader->load("templates/template_purchase.xls");
$objDirectory	= "../excel_files/purchases";

if(isset($_GET['id']) != "") {
  $id				= $_GET['id'];
  $purchase			= $Query->purchase_by_id($id);
  $purchase_number	= $purchase['purchase_number'];
  $purchase_items	= $Query->purchase_items_by_id($id);

  // echo date('H:i:s') ." Purchase No: ". $purchase['purchase_number'];

  // Purchase Number
  $objPHPExcel->getActiveSheet()->setCellValue('R2', $purchase['purchase_number']);
  // Purchase Detail
  $objPHPExcel->getActiveSheet()->setCellValue('B4', $purchase['supplier_name'] ." ". $purchase['supplier_address']);
  $objPHPExcel->getActiveSheet()->setCellValue('C8', $purchase['supplier_phone']);
  $objPHPExcel->getActiveSheet()->setCellValue('C9', $purchase['supplier_fax']);
  $objPHPExcel->getActiveSheet()->setCellValue('D10', $purchase['supplier_person']);
  $objPHPExcel->getActiveSheet()->setCellValue('A13', $purchase['trade_terms']);
  $objPHPExcel->getActiveSheet()->setCellValue('D13', $purchase['invoice_number']);
  $objPHPExcel->getActiveSheet()->setCellValue('G13', $purchase['delivery_via']);
  $objPHPExcel->getActiveSheet()->setCellValue('J13', dformat($purchase['delivery_date'], 'm/d/Y'));
  $objPHPExcel->getActiveSheet()->setCellValue('N13', $purchase['payment_terms']);
  $objPHPExcel->getActiveSheet()->setCellValue('R13', dformat($purchase['created_at'], 'm/d/Y'));

  // $objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

  $baseRow = 16;
  $r = 0;
  
  foreach ($purchase_items as $item) {
    $row = $baseRow + $r;
    $r++;
  
    $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r)
                                  ->mergeCells('B'.$row.':D'.$row)->setCellValue('B'.$row, $item['code'])
                                  ->mergeCells('E'.$row.':K'.$row)->setCellValue('E'.$row, $item['description'])
                                  ->mergeCells('L'.$row.':M'.$row)->setCellValue('L'.$row, $item['quantity'])
                                  ->mergeCells('N'.$row.':O'.$row)->setCellValue('N'.$row, $item['unit'])
                                  ->mergeCells('P'.$row.':R'.$row)->setCellValue('P'.$row, $item['item_price'])
                                  ->mergeCells('S'.$row.':U'.$row)->setCellValue('S'.$row, '=L'.$row.'*P'.$row);
  }
  $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
  // Add Remarks
  $objPHPExcel->getActiveSheet()->setCellValue('A'. ($baseRow + count($purchase_items)), $purchase['remarks']);

  // $objWriter->getSecurity()->setWorkbookPassword('cresc2012');
  
  // echo "<br/>". date('H:i:s') ." Write to Excel5 format";

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objName = $objDirectory .'/'. $purchase_number.'.xls';
  // $objWriter->save(str_replace('.php', '.xls', __FILE__));
  $objWriter->save($objName);

  // echo "<br/>". date('H:i:s') ." File written to ". $objName;
  // Echo memory peak usage
  // echo "<br/>". date('H:i:s') ." Peak memory usage: ". (memory_get_peak_usage(true) / 1024 / 1024) ." MB";
  // Echo done
  // echo "<br/>". date('H:i:s') ." Done writing file";
  // echo "File has been created in ". getcwd();
  
  // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
  // $objWriter->writeAllSheets();
  // $objWriter->save($objDirectory .'/'. $purchase_number.'.pdf');
}

