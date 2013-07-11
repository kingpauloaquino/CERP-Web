


<?php
require('../include/PHPExcel/IOFactory.php');
require('../include/general.class.php');


/** Error reporting */
  //error_reporting(E_ALL);
	//set_time_limit(30);
	
	ini_set ('memory_limit', '2500M');
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
	$objPHPExcel	= $objReader->load("excel_templates/template_month_end_raw_materials.xls");
	$objDirectory	= "../export_files";
  $month_year	= 6;
  //$inventory_items	= $Query->minventory_end_by_month_year($month_year);

  // $objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

  $baseRow = 10;
  $r = 0;

	$sheet = $objPHPExcel->getActiveSheet();
	
			$mysqli = new mysqli("localhost", "cerpuser", "cerpuser", "cerpdb");

			/* check connection */
			if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}
			
			$query = 'SELECT
									m.id, m.material_code AS code, item_classifications.classification AS classification, 
									m.description AS description, lookups.description AS uom, msq,
									COALESCE(wh1.qty,0) AS qty, COALESCE(wh2.qty,0) AS physical_qty
								FROM materials AS m
								LEFT OUTER JOIN 
									(
									SELECT warehouse_inventories.item_id,sum(warehouse_inventories.qty) as qty
									FROM warehouse_inventories
									GROUP BY warehouse_inventories.item_id
									) AS wh1 ON wh1.item_id = m.id
								LEFT OUTER JOIN
									(
									SELECT warehouse_inventory_actual.item_id, sum(warehouse_inventory_actual.qty) as qty
									FROM warehouse_inventory_actual
									GROUP BY warehouse_inventory_actual.item_id
									) AS wh2 ON wh2.item_id = m.id
								INNER JOIN item_classifications ON m.material_classification = item_classifications.id
								INNER JOIN item_costs ON item_costs.item_id = m.id AND item_costs.item_type = "MAT"
								INNER JOIN lookups ON lookups.id = m.unit
								WHERE m.id < 10
								GROUP BY m.id';
			$result = $mysqli->query($query);
			
			//while ($item = $result->fetch_array(MYSQLI_ASSOC)) {
				$objPHPExcel->getActiveSheet()->fromArray(array($result), null, 'A1');
				
        // $row = $baseRow + $r;
		    // $r++;
				// $sheet->insertNewRowBefore($row, 1);
		    // $sheet->setCellValue('A'.$row, $r)
																			// ->setCellValue('B'.$row, $item['code'])
		                                  // ->mergeCells('C'.$row.':D'.$row)->setCellValue('C'.$row, $item['description'])
																			// ->setCellValue('E'.$row, $item['classification'])
																			// ->setCellValue('F'.$row, $item['uom'])
																			// ->setCellValue('G'.$row, $item['qty'])
																			// ->setCellValue('H'.$row, $item['physical_qty']);
      //}
			
			/* free result set */
			$result->free();
			
			/* close connection */
			$mysqli->close();
	
	
  $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
	
            										
																

  // $objWriter->getSecurity()->setWorkbookPassword('cresc2012');
  

