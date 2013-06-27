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
	$objPHPExcel	= $objReader->load("excel_templates/template_received_by_supplier.xls");
	$objDirectory	= "../export_files";
  $received_items	= $Query->received_by_supplier_by_month_year($mydate, $sid);
	$supplier_name = $Query->get_lookups_value('suppliers', $sid);

  // $objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

  $baseRow = 10;
  $r = 0;

	$sheet = $objPHPExcel->getActiveSheet();
	$sheet->setCellValue('A5',$supplier_name['supplier_name']);
	$sheet->setCellValue('A6',strtoupper(date('F Y', strtotime($mydate))).' RECEIVING REPORT SUMMARY');
	
  foreach ($received_items as $item) {
    $row = $baseRow + $r;
    $r++;
		$sheet->insertNewRowBefore($row, 1);	
		
    $sheet->setCellValue('A'.$row, $r)
					->setCellValue('B'.$row, $item['receive_date'])
					->setCellValue('C'.$row, $item['invoice'])
					->setCellValue('D'.$row, $item['receipt']);
															
		//TEST
		//error_log($r.' - Peak memory usage: ' . (memory_get_peak_usage(true) / 1024 / 1024) . ' MB<br />'); 															
  }
  $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
	
            										
																

  // $objWriter->getSecurity()->setWorkbookPassword('cresc2012');
  

