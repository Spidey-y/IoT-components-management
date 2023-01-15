<?php


error_reporting(-1);
ini_set('display_errors', 'On');

// connect to database
require __DIR__ . '/connection_db.php';
$conn = connect_to_db();

if (isset($_GET['query'])) {
    try {
        $statement = $conn->prepare("SELECT * FROM components where `name` LIKE ?");

        $statement->execute(['%'.$_GET['query'].'%']);
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
} else {
    try {
        $statement = $conn->prepare("SELECT * FROM components");
        $statement->execute();
        $row = $statement->rowCount();
        $results = $statement->fetch();
        if ($row > 0) {
            echo json_encode($results);
        } else {
            echo '{}';
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}