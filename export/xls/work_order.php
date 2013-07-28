<?php
	require_once('templates/work_order.inc.php');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objName = $objDirectory .'/'. $work_number.'.xls';
  $objWriter->save($objName);