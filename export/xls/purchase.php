<?php
	require_once('templates/purchase.inc.php');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objName = $objDirectory .'/'. $purchase_number.'.xls';
  $objWriter->save($objName);
