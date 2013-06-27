<?php
require('../include/PHPExcel/IOFactory.php');
require('../include/general.class.php');


/** Error reporting */
  //error_reporting(E_ALL);
	//set_time_limit(30);
	
	ini_set ('memory_limit', '256M');
	ini_set('max_execution_time','600');
	
	//TEST
	// $callStartTime = microtime(true); 
	// $callEndTime = microtime(true); 
	// $callTime = $callEndTime - $callStartTime; 
	// echo 'PHPExcel Object Instantiated:- Current memory usage: ' . (memory_get_usage(true) / 1024 / 1024) . ' MB; Execution time: '.sprintf('%.4f',$callTime).' seconds<br />'; 
  //ini_set('display_errors', TRUE);
  //ini_set('display_startup_errors', TRUE);
	//set_time_limit(0);
	// $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
	// $cacheSettings = array( 
													// //'memcacheServer'  => 'localhost',
	                        // //'memcachePort'    => 11211,
	                        // //'cacheTime'       => 600,
	                        // 'memoryCacheSize' => '1024MB'
	                      // ); 
	// if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings))
	   // die('CACHEING ERROR');
	$objReader		= PHPExcel_IOFactory::createReader('Excel5');
	$objPHPExcel	= $objReader->load("excel_templates/template_month_end_products.xls");
	$objDirectory	= "../export_files";
  $inventory_items	= $Query->pinventory_end_by_month_year($mydate);

  // $objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

  $baseRow = 10;
  $r = 0;
	
	$sheet = $objPHPExcel->getActiveSheet();
  // foreach ($inventory_items as $item) {
  	// $row = $baseRow + $r;	
		// $r++;						
// 					
  	// $sheet->fromArray($item, null, 'A'.$row);
		// //TEST
		// error_log($r.' - Peak memory usage: ' . (memory_get_peak_usage(true) / 1024 / 1024) . ' MB<br />'); 	
  // }

	$sheet = $objPHPExcel->getActiveSheet();
	$sheet->setCellValue('A6', date('F Y', strtotime($mydate)).' MONTH-END INVENTORY REPORT');
	
  foreach ($inventory_items as $item) {
    $row = $baseRow + $r;
    $r++;
		//$sheet->insertNewRowBefore($row, 1);	
		
    $sheet->setCellValue('A'.$row, $r)
					->setCellValue('B'.$row, $item['code'])
					->setCellValue('C'.$row, $item['brand'])
					->setCellValue('D'.$row, $item['series'])
					->setCellValue('E'.$row, $item['pack_qty'])
					->setCellValue('F'.$row, $item['description'])
					->setCellValue('G'.$row, $item['uom'])
					->setCellValue('H'.$row, $item['qty'])
					->setCellValue('I'.$row, $item['physical_qty']);
					
		
		$sheet->getStyle('A'.$row.':I'.$row)->getFont()->setSize(9);
		// $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// $sheet->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		// $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		// $sheet->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// $sheet->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		// $sheet->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
// 		
		// $sheet->getStyle('A'.$row.':H'.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		// $sheet->getStyle('A'.$row.':H'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		// $sheet->getStyle('A'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// 		
		$sheet->getStyle('A'.$row.':I'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// 		
		// $sheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		// $sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
															
		//TEST
		//error_log($r.' - Peak memory usage: ' . (memory_get_peak_usage(true) / 1024 / 1024) . ' MB<br />'); 															
  }
  $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
	
            										
																

  // $objWriter->getSecurity()->setWorkbookPassword('cresc2012');
  

