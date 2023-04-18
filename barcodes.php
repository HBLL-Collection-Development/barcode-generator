<?php
require_once __DIR__.'/vendor/autoload.php';

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);

$type = htmlentities($_GET['type']);
if ($type == 'books') {
    $start_bay = htmlentities($_GET['start_bay']);
    $end_bay = htmlentities($_GET['end_bay']);
    $start_range = htmlentities($_GET['start_range']);
    $end_range = htmlentities($_GET['end_range']);
    $start_section = htmlentities($_GET['start_section']);
    $end_section = htmlentities($_GET['end_section']);
    $start_shelf = htmlentities($_GET['start_shelf']);
    $end_shelf = htmlentities($_GET['end_shelf']);

    $first_barcode = get_barcodes(array($start_bay), array($start_range), array($start_section), array($start_shelf));
    $last_barcode = get_barcodes(array($end_bay), array($end_range), array($end_section), array($end_shelf));
    $filename = $first_barcode[0] . '-' . $last_barcode[0];

    $bays = create_array($start_bay, $end_bay);
    $ranges = create_array($start_range, $end_range);
    $sections = create_array($start_section, $end_section);
    $shelves = create_array($start_shelf, $end_shelf);

    $barcodes = get_barcodes($bays, $ranges, $sections, $shelves);
    $html = show_barcodes($barcodes, 'books');
} elseif ($type == 'cassettes') {
    $start_cabinet = htmlentities($_GET['start_cabinet']);
    $end_cabinet = htmlentities($_GET['end_cabinet']);
    $start_drawer = htmlentities($_GET['start_drawer']);
    $end_drawer = htmlentities($_GET['end_drawer']);
    $start_column = htmlentities($_GET['start_column']);
    $end_column = htmlentities($_GET['end_column']);

    $first_barcode = get_cassettes(array($start_cabinet), array($start_drawer), array($start_column));
    $last_barcode = get_cassettes(array($end_cabinet), array($end_drawer), array($end_column));
    $filename = $first_barcode[0] . '-' . $last_barcode[0];

    $cabinets = create_array($start_cabinet, $end_cabinet);
    $drawers = create_array($start_drawer, $end_drawer);
    $columns = create_array($start_column, $end_column);

    $barcodes = get_cassettes($cabinets, $drawers, $columns);
    $html = show_barcodes($barcodes, 'cassettes');
}

$css = <<<HTML
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Barcode Generator</title>
	<style type="text/css">
        body{
            font-family: helvetica, arial, sans-serif;
            font-size: 12pt;
            text-align: center;
        }
        p.human_barcode{
            font-size: 14pt;
        }
        td {
            margin: 0.25in;
            width: 4in;
        }
        .byu{
            font-size: 6pt;
            line-height: 0;
        }
        img{
            height: 23px;
        }
        .break{
            page-break-before: always;
        }

    </style>
</head>
<body>
HTML;

$html = $css . $html . '</body></html>';

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Jared Howland');
$pdf->SetTitle('AuxStor Barcodes');
$pdf->SetSubject('AuxStor Barcodes');
$pdf->SetKeywords('barcodes, auxstor, hbll');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(0.25, 0.75, 0.25);

//set auto page breaks
$pdf->SetAutoPageBreak(true, 0.25);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
// $pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

// add a page
$pdf->AddPage();

// Clean output buffer
// ob_end_clean();

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

function show_barcodes($barcodes, $type)
{
    $html = '<table cellpadding="10">';
    $column = 'odd';
    $count = count($barcodes);
    $i = 1;
    foreach ($barcodes as $barcode) {
        // Create page break before the 12th row
        if ($i == 12) {
            $style = ' class="break"';
            $i = 1;
        } else {
            $style = '';
        }

        if ($column == 'odd') {
            $path = 'gif.php?type=' . $type . '&barcode=' . $barcode;
            $port = $_SERVER['SERVER_PORT'] === '' ? '' : ':' . $_SERVER['SERVER_PORT'];
            $server = 'https://' . $_SERVER['SERVER_NAME'] . $port . '/barcodes/' . $path;
            $file = file_get_contents($server);
            $base64 = base64_encode($file);
            $html .= '<tr' . $style . '><td><p class="byu">BRIGHAM YOUNG UNIVERSITY</p><img src="data:image/gif;base64,' . $base64 . '" alt="' . $barcode . '" width="341" height="30"/><p class="human_barcode">' . human_readable($barcode,
                    $type) . '</p></td>';
            $column = 'even';
        } else {
            $html .= '<td><p class="byu">BRIGHAM YOUNG UNIVERSITY</p><img src="data:image/gif;base64,' . $base64 . '" alt="' . $barcode . '" width="341" height="30"/><p class="human_barcode">' . human_readable($barcode,
                    $type) . '</p></td></tr>';
            $column = 'odd';
        }
        $i++;
    }
    // if there are an odd number of barcodes to be generated, add the final column and close out the row and table
    if ($count % 2) {
        $html .= '<td></td></tr></table>';
    } else {
        $html .= '</table>';
    }
    return $html;
}

function human_readable($barcode, $type)
{
    if ($type == 'books') {
        $search = array('Ba', 'Ra', 'Se', 'Sh');
        $replace = array('Bay ', ' Range ', ' Section ', ' Shelf ');
    } elseif ($type == 'cassettes') {
        $search = array('Ca', 'Cabinet ssette', 'Dr', 'Co');
        $replace = array(' Cabinet ', 'Cassette ', ' Drawer ', ' Column ');
    }
    return str_replace($search, $replace, $barcode);
}

function create_array($start, $end)
{
    $array = null;
    while ($start <= $end) {
        $array[] = $start;
        $start++;
    }
    return array_values($array);
}

function get_barcodes($bays, $ranges, $sections, $shelves)
{
    $shelving = array('1' => $bays, '2' => $ranges, '3' => $sections, '4' => $shelves);
    $barcodes = permutations($shelving);
    $barcode = create_barcodes($barcodes);
    return $barcode;
}

function get_cassettes($cabinets, $drawers, $columns)
{
    $shelving = array('1' => $cabinets, '2' => $drawers, '3' => $columns);
    $barcodes = permutations($shelving);
    $barcode = create_cassettes($barcodes);
    return $barcode;
}

// Find all possible permutations of a group of arrays
// http://www.dannyherran.com/2011/06/finding-unique-array-combinations-with-php-permutations/
function permutations(array $array, $inb = false)
{
    switch (count($array)) {
        case 1:
            // Return the array as-is; returning the first item
            // of the array was confusing and unnecessary
            return $array[0];
            break;
        case 0:
            throw new InvalidArgumentException('Requires at least one array');
            break;
    }

    // We'll need these, as array_shift destroys them
    $keys = array_keys($array);

    $a = array_shift($array);
    $k = array_shift($keys); // Get the key that $a had
    $b = permutations($array, 'recursing');

    $return = array();
    foreach ($a as $v) {
        if ($v) {
            foreach ($b as $v2) {
                // array($k => $v) re-associates $v (each item in $a)
                // with the key that $a originally had
                // array_combine re-associates each item in $v2 with
                // the corresponding key it had in the original array
                // Also, using operator+ instead of array_merge
                // allows us to not lose the keys once more
                if ($inb == 'recursing') {
                    $return[] = array_merge(array($v), (array)$v2);
                } else {
                    $return[] = array($k => $v) + array_combine($keys, $v2);
                }
            }
        }
    }

    return $return;
}

function create_barcodes($barcodes)
{
    foreach ($barcodes as $barcode_array) {
        $bay = 'Ba' . str_pad($barcode_array[1], 2, '0', STR_PAD_LEFT);
        $range = 'Ra' . str_pad($barcode_array[2], 2, '0', STR_PAD_LEFT);
        $section = 'Se' . str_pad($barcode_array[3], 2, '0', STR_PAD_LEFT);
        $shelf = 'Sh' . str_pad($barcode_array[4], 2, '0', STR_PAD_LEFT);
        $barcode[] = $bay . $range . $section . $shelf;
    }
    return $barcode;
}

function create_cassettes($barcodes)
{
    foreach ($barcodes as $barcode_array) {
        $cabinet = 'CassetteCa' . str_pad($barcode_array[1], 2, '0', STR_PAD_LEFT);
        $drawer = 'Dr' . str_pad($barcode_array[2], 2, '0', STR_PAD_LEFT);
        $column = 'Co' . str_pad($barcode_array[3], 2, '0', STR_PAD_LEFT);
        $barcode[] = $cabinet . $drawer . $column;
    }
    return $barcode;
}

//Close and output PDF document
$pdf->Output($filename, 'I');

?>
