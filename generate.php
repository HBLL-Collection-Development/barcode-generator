<?php
use Barcode\Template;

require_once __DIR__.'/vendor/autoload.php';

$template = new Template();

$template->tm('header', 'Barcode Generator');

echo '<p><a href="barcodes-txt.php?'.$_SERVER['QUERY_STRING'].'">Download plain text file to send to LIT for creation of pseudo-patrons</a></p>';
echo '<p><a href="barcodes.php?'.$_SERVER['QUERY_STRING'].'">Download PDF file of barcodes</a></p>';

$template->tm('footer');
?>
