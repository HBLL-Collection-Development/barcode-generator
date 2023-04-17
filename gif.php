<?php
use Barcode\Barcode;

require_once __DIR__.'/vendor/autoload.php';

$barcode = $_GET['barcode'];
$type = $_GET['type'];

// set Barcode39 object
$bc = new Barcode($barcode);

if ($type == 'cassettes') {
    $bc->barcode_bar_thick = 3;
    $bc->barcode_bar_thin = 1;
} else {
    $bc->barcode_bar_thick = 4;
    $barcode_bar_thick = $bc->barcode_bar_thick;
    $bc->barcode_bar_thin = $barcode_bar_thick / 3;
}
$bc->barcode_height = 30;
$bc->barcode_padding = 0;
$bc->barcode_text = false;
// display new barcode
$bc->draw();
?>
