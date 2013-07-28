<?php
require('../include/PHPExcel/IOFactory.php');
require('../include/general.class.php');


/** Error reporting */
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);

	$objReader		= PHPExcel_IOFactory::createReader('Excel5');
	$objPHPExcel	= $objReader->load("excel_templates/template_purchase.xls");
	$objDirectory	= "../export_files";

  $purchase			= $Query->purchase_by_id($id);
  $purchase_number	= $purchase['po_number'];
  $purchase_items	= $Query->purchase_items_by_id($id);

  $objPHPExcel->getActiveSheet()->setCellValue('R1', $purchase['po_number']);
  $objPHPExcel->getActiveSheet()->setCellValue('R2', date("F d, Y", strtotime($purchase['po_date'])));
  $objPHPExcel->getActiveSheet()->setCellValue('B4', $purchase['supplier_name'] ." ". $purchase['supplier_address']);
  $objPHPExcel->getActiveSheet()->setCellValue('C8', $purchase['supplier_phone']);
  $objPHPExcel->getActiveSheet()->setCellValue('C9', $purchase['supplier_fax']);
  $objPHPExcel->getActiveSheet()->setCellValue('D10', $purchase['supplier_person']);
  $objPHPExcel->getActiveSheet()->setCellValue('A13', $purchase['terms']);
  $objPHPExcel->getActiveSheet()->setCellValue('G13', date("F d, Y", strtotime($purchase['delivery_date'])));
  $objPHPExcel->getActiveSheet()->setCellValue('J13', $purchase['delivery_via']);
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
																	
		$objPHPExcel->getActiveSheet()->getStyle('P'.$row)->getNumberFormat()->setFormatCode('"₱"#,##0.00_-');
		$objPHPExcel->getActiveSheet()->getStyle('S'.$row)->getNumberFormat()->setFormatCode('"₱"#,##0.00_-');
  }
  $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);

  $objPHPExcel->getActiveSheet()->setCellValue('A'. ($baseRow + count($purchase_items)), $purchase['remarks']);
	
	$objPHPExcel->getActiveSheet()->setCellValue('R'. ($baseRow + count($purchase_items) -1), $purchase['total_amount']);
	$objPHPExcel->getActiveSheet()->getStyle('R'. ($baseRow + count($purchase_items) -1))->getNumberFormat()->setFormatCode('"₱"#,##0.00_-');
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'. ($baseRow + count($purchase_items) + 3), $purchase['creator']);
  $objPHPExcel->getActiveSheet()->setCellValue('I'. ($baseRow + count($purchase_items) + 3), $purchase['checker']);
  $objPHPExcel->getActiveSheet()->setCellValue('Q'. ($baseRow + count($purchase_items) + 3), $purchase['approver']);
	
	// $objPHPExcel->getActiveSheet()->getStyle('R'. ($baseRow + count($purchase_items) -1))->getNumberFormat()
            										// ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
            										
																

  // $objWriter->getSecurity()->setWorkbookPassword('cresc2012');
  

