<?php


error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__ . '/connection_db.php';
$conn = connect_to_db();

try {
    $statement = $conn->prepare("SELECT * FROM components");
    $statement->execute();
    $row = $statement->rowCount();
    $results = $statement->fetchAll();
    if ($row > 0) {
        echo json_encode($results);
    } else {
        echo '{}';
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
