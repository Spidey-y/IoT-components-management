<?php

use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/connection_db.php';
$conn = connect_to_db();
require_once './vendor/autoload.php';
$filename = "uploads/decharge.docx";

$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filename);
$devices = [];
foreach ($_GET['devices'] as $key => $value) {
    $query = "SELECT * from components where id= ?";
    try {
        $stmt = $conn->prepare("SELECT * FROM components where `id` = ?");
        $stmt->execute([$value]);
    } catch (PDOException $e) {
        echo $query . "<br>" . $e->getMessage();
        die();
    };
    $row = $stmt->fetch();
    array_push($devices, $row);
}
$dateNow =  date("d/m/Y");
$content = "";
$table = new Table(['borderSize' => 12, 'borderColor' => 'green', 'width' => 6000, 'unit' => TblWidth::TWIP]);
$table->addRow();
$table->addCell(150)->addText('Device ID');
$table->addCell(150)->addText('Device Name');
$table->addCell(150)->addText('Device Purchase Date');
foreach ($devices as $key => $value) {
    $table->addRow();
    $table->addCell(150)->addText($value->id);
    $table->addCell(150)->addText($value->name);
    $table->addCell(150)->addText($value->purchase_date);
}
$templateProcessor->setValue('dateNow', $dateNow);
$templateProcessor->setValue('student_name', $_GET['student_name']);
$templateProcessor->setComplexBlock('content', $table);
ob_clean();
$templateProcessor->saveAs('uploads/word.docx');


$filePath = 'uploads/word.docx';
if (file_exists($filePath)) {
    $fileName = basename($filePath);
    $fileSize = filesize($filePath);
    header("Cache-Control: private");
    header("Content-Type: application/stream");
    header("Content-Length: " . $fileSize);
    header("Content-Disposition: attachment; filename=" . $fileName);
    // Output file.
    readfile($filePath);
    exit();
} else {
    die('The provided file path is not valid.');
}
