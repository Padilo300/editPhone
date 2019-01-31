<pre>
<style>
body{

    background: black;
}
</style>
<?php
header('Content-Type: text/html; charset=utf-8');

ini_set('error_reporting',        E_ALL );
ini_set('display_errors',         1     );
ini_set('display_startup_errors', 1     );
memory_get_peak_usage();

function formatBytes($bytes, $precision = 2) {
    $units = array("b", "kb", "mb", "gb", "tb");

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . " " . $units[$pow];
}


function dd($var) {
    echo '<pre style="    background: black;
    padding: 15px;
    color: #0f0;
    font-size: 15px;
    font-weight: 500;
    font-family: monospace;
    width: max-content;">';
    var_dump($var);
    echo '</pre>';
}

require_once(__DIR__ . '/php/editPhone.php'     );
require_once(__DIR__ . '/php/csvGenerator.php'  );

$Filter     =   new CsvIterator(__DIR__ . '/1.csv' );
$editTel    =   new getLead();

$fp = fopen('result.csv', 'w');
foreach ($Filter->parse() as $row) {
    $row['phone']       =  $editTel->alignNumber($row['phone']);
    $row['cont_face']   =  preg_replace('/[^a-zA-Za-Яа-яЁё\s]/', '', $row['cont_face']);
    fputcsv($fp, $row);
    dd($row);
}

fclose($fp);



print formatBytes(memory_get_peak_usage());
?>

</pre>