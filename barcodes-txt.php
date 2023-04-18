<?php
header('Content-Type: text/plain');

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
    header('Content-Disposition: attachment; filename=' . $filename);
    $bays = create_array($start_bay, $end_bay);
    $ranges = create_array($start_range, $end_range);
    $sections = create_array($start_section, $end_section);
    $shelves = create_array($start_shelf, $end_shelf);

    $barcodes = get_barcodes($bays, $ranges, $sections, $shelves);
    show_barcodes($barcodes, 'books');
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
    header('Content-Disposition: attachment; filename=' . $filename);

    $cabinets = create_array($start_cabinet, $end_cabinet);
    $drawers = create_array($start_drawer, $end_drawer);
    $columns = create_array($start_column, $end_column);

    $barcodes = get_cassettes($cabinets, $drawers, $columns);
    show_barcodes($barcodes, 'cassettes');
}

function show_barcodes($barcodes, $type)
{
    foreach ($barcodes as $barcode) {
        echo $barcode . "\r\n";
    }
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

    // We 'll need these, as array_shift destroys them
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

?>
