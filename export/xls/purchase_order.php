<?php
	require_once('templates/purchase_order.inc.php');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objName = $objDirectory .'/'. $purchase_number.'.xls';
  $objWriter->save($objName);
