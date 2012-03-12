<?php
# A request for the boot code

$file = dirname(__FILE__) . '/firmware/bootcode.bin';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: inline; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}

?><h1>Error</h1>
<p>Could not find rabbit bootcode: <?php print $file ?></p>
