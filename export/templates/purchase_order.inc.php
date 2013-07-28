<?php
require('../include/PHPExcel/IOFactory.php');
require('../include/general.class.php');


/** Error reporting */
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);

	$objReader		= PHPExcel_IOFactory::createReader('Excel5');
	$objPHPExcel	= $objReader->load("excel_templates/template_purchase_order.xls");
	$objDirectory	= "../export_files";

  $purchase			= $Query->purchase_order_by_id($id);
  $purchase_number	= $purchase['order_no'];
  $purchase_items	= $Query->purchase_order_items_by_id($id);

  $objPHPExcel->getActiveSheet()->setCellValue('S1', $purchase['order_no']);
  $objPHPExcel->getActiveSheet()->setCellValue('S2', date("F d, Y", strtotime($purchase['order_date'])));
  $objPHPExcel->getActiveSheet()->setCellValue('E11', $purchase['payment_terms']);
  $objPHPExcel->getActiveSheet()->setCellValue('E12', $purchase['terms']);
  $objPHPExcel->getActiveSheet()->setCellValue('E13', date("F d, Y", strtotime($purchase['ship_date'])));

  // $objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

  $baseRow = 17;
  $r = 0;
  $unit = '';
  foreach ($purchase_items as $item) {
    $row = $baseRow + $r;
    $r++;
  
    $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r)
		                                  ->mergeCells('B'.$row.':E'.$row)->setCellValue('B'.$row, $item['code'])
		                                  ->mergeCells('F'.$row.':M'.$row)->setCellValue('F'.$row, $item['remarks'])
		                                  ->mergeCells('N'.$row.':O'.$row)->setCellValue('N'.$row, $item['quantity'])
		                                  ->mergeCells('P'.$row.':Q'.$row)->setCellValue('P'.$row, $item['unit'])
		                                  ->mergeCells('R'.$row.':T'.$row)->setCellValue('R'.$row, $item['item_price'])
		                                  ->mergeCells('U'.$row.':W'.$row)->setCellValue('U'.$row, '=N'.$row.'*R'.$row);
																	
		$objPHPExcel->getActiveSheet()->getStyle('R'.$row)->getNumberFormat()->setFormatCode('"₱"#,##0.000_-');
		$objPHPExcel->getActiveSheet()->getStyle('U'.$row)->getNumberFormat()->setFormatCode('"₱"#,##0.00_-');
		$unit = $item['unit'];
  }
  $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);

  $objPHPExcel->getActiveSheet()->setCellValue('A'. ($baseRow + count($purchase_items) + 1), $purchase['remarks']);

	$objPHPExcel->getActiveSheet()->setCellValue('P'. ($baseRow + count($purchase_items) -1), $unit);
	$objPHPExcel->getActiveSheet()->setCellValue('N'. ($baseRow + count($purchase_items) -1), '=SUM(N16:O'.($baseRow + count($purchase_items) -2).')');
	
	$objPHPExcel->getActiveSheet()->setCellValue('U'. ($baseRow + count($purchase_items) -1), $purchase['total_amount']);
	$objPHPExcel->getActiveSheet()->getStyle('U'. ($baseRow + count($purchase_items) -1))->getNumberFormat()->setFormatCode('"₱"#,##0.00_-');
	
	
  $objPHPExcel->getActiveSheet()->setCellValue('A'. ($baseRow + count($purchase_items) + 4), $purchase['creator']);
  $objPHPExcel->getActiveSheet()->setCellValue('J'. ($baseRow + count($purchase_items) + 4), $purchase['checker']);
  $objPHPExcel->getActiveSheet()->setCellValue('R'. ($baseRow + count($purchase_items) + 4), $purchase['approver']);
	
	
	// $objPHPExcel->getActiveSheet()->getStyle('R'. ($baseRow + count($purchase_items) -1))->getNumberFormat()
            										// ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
            										
																

  // $objWriter->getSecurity()->setWorkbookPassword('cresc2012');
  

