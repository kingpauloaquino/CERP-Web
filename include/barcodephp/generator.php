<?php
// Including all required classes
require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');

// Including the barcode technology
require_once('class/BCGcode128.barcode.php');

// Parameters
$pText = (isset($_GET['t']) || $_GET['t']=='') ? $_GET['t'] : 'BARCODE';
$pFileType = isset($_GET['typ']) ? $_GET['typ'] : 'PNG';
$pRotation = isset($_GET['rot']) ? (int) $_GET['rot'] : 0;
$pDpi = isset($_GET['dpi']) ? (int) $_GET['dpi'] : 72;
$pThick = isset($_GET['thk']) ? (int) $_GET['thk'] : 30;
$pScale = isset($_GET['scl']) ? (int) $_GET['scl'] : 1;
$pFontSize = isset($_GET['fsz']) ? (int) $_GET['fsz'] : 8;

// Loading Font
$font = new BCGFontFile('./font/Arial.ttf', $pFontSize);

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);

$drawException = null;
try {
	$code = new BCGcode128();
	$code->setScale($pScale); // Resolution
	$code->setThickness($pThick); // Thickness
	$code->setForegroundColor($color_black); // Color of bars
	$code->setBackgroundColor($color_white); // Color of spaces
	$code->setFont($font); // Font (or 0)
	$code->parse($pText); // Text
} catch(Exception $exception) {
	$drawException = $exception;
}

/* Here is the list of the arguments
1 - Filename (empty : display on screen)
2 - Background color */
$drawing = new BCGDrawing('', $color_white);
if($drawException) {
	$drawing->drawException($drawException);
} else {
	$drawing->setBarcode($code);
  $drawing->setRotationAngle($pRotation);
  $drawing->setDPI($pDpi);
  $drawing->draw();
}

switch ($pFileType) {
    case 'PNG':
        header('Content-Type: image/png');
        break;
    case 'JPEG':
        header('Content-Type: image/jpeg');
        break;
    case 'GIF':
        header('Content-Type: image/gif');
        break;
}

header('Content-Disposition: inline; filename="'.trim($pText).'.'.strtolower($pFileType).'"');

// Draw (or save) the image into PNG format.
$filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);
$drawing->finish($filetypes[$pFileType]);