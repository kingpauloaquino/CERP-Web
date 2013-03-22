<?php
require('../excel/purchase.php');

if (file_exists($objName)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($objName));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($objName));
    ob_clean();
    flush();
    readfile($objName);
    exit;
}
?>