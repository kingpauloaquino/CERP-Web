<?php
require('../include/PHPExcel/IOFactory.php');
require('../include/general.class.php');


/** Error reporting */
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);

	$objReader		= PHPExcel_IOFactory::createReader('Excel5');
	$objPHPExcel	= $objReader->load("excel_templates/template_work_order.xls");
	$objDirectory	= "../export_files";

  $work			= $Query->work_order_by_id($id);
  $work_number	= $work['order_no'];
  $work_items	= $Query->work_order_items_by_id($id);

  $objPHPExcel->getActiveSheet()->setCellValue('S1', $work['order_no']);
  $objPHPExcel->getActiveSheet()->setCellValue('S2', date("F d, Y", strtotime($work['order_date'])));
  $objPHPExcel->getActiveSheet()->setCellValue('C11', date("F d, Y", strtotime($work['ship_date'])));

  // $objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

  $baseRow = 15;
  $r = 0;
  $unit = '';
  foreach ($work_items as $item) {
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

  $objPHPExcel->getActiveSheet()->setCellValue('A'. ($baseRow + count($work_items) + 1), $work['remarks']);

	$objPHPExcel->getActiveSheet()->setCellValue('P'. ($baseRow + count($work_items) -1), $unit);
	$objPHPExcel->getActiveSheet()->setCellValue('N'. ($baseRow + count($work_items) -1), '=SUM(N16:O'.($baseRow + count($work_items) -2).')');
	
	$objPHPExcel->getActiveSheet()->setCellValue('U'. ($baseRow + count($work_items) -1), $work['total_amount']);
	$objPHPExcel->getActiveSheet()->getStyle('U'. ($baseRow + count($work_items) -1))->getNumberFormat()->setFormatCode('"₱"#,##0.00_-');
	
	
  $objPHPExcel->getActiveSheet()->setCellValue('A'. ($baseRow + count($work_items) + 4), $work['creator']);
  $objPHPExcel->getActiveSheet()->setCellValue('J'. ($baseRow + count($work_items) + 4), $work['checker']);
  $objPHPExcel->getActiveSheet()->setCellValue('R'. ($baseRow + count($work_items) + 4), $work['approver']);
	
	
	// $objPHPExcel->getActiveSheet()->getStyle('R'. ($baseRow + count($work_items) -1))->getNumberFormat()
            										// ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
            										
																

  // $objWriter->getSecurity()->setWorkbookPassword('cresc2012');
  

