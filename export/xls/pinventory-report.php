<?php
	require_once('templates/pinventory-report.inc.php');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->setPreCalculateFormulas(FALSE);
  $objName = $objDirectory .'/'.strtoupper(date('F_Y', strtotime($mydate))).'_'.$name;
	//$objWriter->save('php://output');
  $objWriter->save($objName);
	$objPHPExcel->disconnectWorksheets(); 
  unset($objPHPExcel);
	if (ob_get_length() > 0) { ob_end_clean(); }