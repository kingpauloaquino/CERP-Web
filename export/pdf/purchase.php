<?php
	ini_set('memory_limit', '-1'); // address memory size allocation fatal error
	require_once('templates/purchase.inc.php');

	$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
	$rendererLibrary = 'MPDF56';
	// $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
	// $rendererLibrary = 'tcpdf';
	
	$rendererLibraryPath = '/Users/jedsilvestre/Projects/GitHub/CERP-Web/include/' . $rendererLibrary;

	$objPHPExcel->getActiveSheet()->setShowGridLines(false);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);
	
	if (!PHPExcel_Settings::setPdfRenderer(
			$rendererName,
			$rendererLibraryPath
		)) {
		die(
			'NOTICE: Please set the $rendererName and $rendererLibraryPath values' . 
			EOL .
			'at the top of this script as appropriate for your directory structure'
		);
	}
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
	$objWriter->setSheetIndex(0);
	$objName = $objDirectory .'/'. $purchase_number.'.pdf';
  $objWriter->save($objName);
  
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
	// $objWriter->setSheetIndex(0);
	// $objWriter->setUseInlineCSS(TRUE);
	// $objName = $objDirectory .'/'. $purchase_number.'.html';
  // $objWriter->save($objName);
