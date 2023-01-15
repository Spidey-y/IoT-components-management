<?php
// Load the database configuration file 
include_once 'connection_db.php';

error_reporting(-1);
ini_set('display_errors', 'On');
// Filter the excel data 
$conn = connect_to_db(); // function created in dbconnect, remember?

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// Excel file name for download 
$fileName = "export.xlsx";

// Column names 
$fields = array('ID', 'NAME', 'PURCHASE DATE', 'STATUS', 'QUANTITY', 'IMAGE LINK');

// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n";

// Fetch records from database 
$isFiltered = false;
$currentFilter = '';
if (isset($_GET['status']) && $_GET['status'] != 'all' && $_GET['status'] != '') {
    $currentFilter = 'WHERE `status` = "' . $_GET['status'] . '"';
    $isFiltered = true;
} else if (isset($_GET['query']) && $_GET['query'] != '') {
    $currentFilter = 'where `name` LIKE ' . '"' . '%' . $_GET['query'] . '%' . '"';
    $isFiltered = true;
} else if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
    $currentFilter = 'where `purchase_date` BETWEEN ' . '"' . $_GET['start_date'] . '"' . ' AND ' . '"' .  $_GET['end_date'] . '"';
    $isFiltered = true;
}
// echo 'shit';
// exit();
$stm = 'SELECT * FROM components ';
if ($isFiltered == true) {
    $stm .= $currentFilter;
}
$query = $conn->query($stm . " ORDER BY id ASC");
$r = $query->rowCount();
if ($r > 0) {
    while ($row = $query->fetch()) {
        $image = 'localhost/IoT-components-management/' . $row->image;
        $lineData = array($row->id, $row->name, $row->purchase_date, $row->status, $row->quantity, $image);
        array_walk($lineData, 'filterData');
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
} else {
    $excelData .= 'No records found...' . "\n";
}

// Headers for download 
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data 
echo $excelData;

exit;
