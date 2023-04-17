<?php
use Barcode\Template;

require_once __DIR__.'/vendor/autoload.php';

$template = new Template();
$template->tm('header', 'Barcode Generator');
?>

<div class="span-6">
    <h1>AuxStor Books</h1>
    <form action="generate.php" id="barcodes" method="get">
        <p>Bays
            <input type="number" min="1" name="start_bay" required placeholder="Start bay"/>
            <input type="number" min="1" name="end_bay" required placeholder="End bay"/>
        </p>
        <p>Ranges
            <input type="number" min="1" name="start_range" required placeholder="Start range"/>
            <input type="number" min="1" name="end_range" required placeholder="End range"/>
        </p>
        <p>Sections
            <input type="number" min="1" name="start_section" required placeholder="Start section"/>
            <input type="number" min="1" name="end_section" required placeholder="End section"/>
        </p>
        <p>Shelves
            <input type="number" min="1" name="start_shelf" required placeholder="Start shelf"/>
            <input type="number" min="1" name="end_shelf" required placeholder="End shelf"/>
        </p>
        <input type="hidden" name="type" value="books">
        <input type="submit" value="Submit"/>
    </form>
</div>
<div class="span-6">
    <h1>AuxStor Cassettes</h1>
    <form action="generate.php" id="barcodes" method="get">
        <p>Cabinets
            <input type="number" min="1" name="start_cabinet" required placeholder="Start cabinet"/>
            <input type="number" min="1" name="end_cabinet" required placeholder="End cabinet"/>
        </p>
        <p>Drawers
            <input type="number" min="1" name="start_drawer" required placeholder="Start drawer"/>
            <input type="number" min="1" name="end_drawer" required placeholder="End drawer"/>
        </p>
        <p>Columns
            <input type="number" min="1" name="start_column" required placeholder="Start column"/>
            <input type="number" min="1" name="end_column" required placeholder="End column"/>
        </p>
        <input type="hidden" name="type" value="cassettes">
        <input type="submit" value="Submit"/>
    </form>
</div>

<?php $template->tm('footer'); ?>
