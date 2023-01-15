<?php


error_reporting(-1);
ini_set('display_errors', 'On');

// connect to database
require __DIR__ . '/connection_db.php';
$conn = connect_to_db();

if (isset($_GET['status'])) {
    if ($_GET['status'] != 'all') {
        try {
            $statement = $conn->prepare("SELECT * FROM components where `status` = ?");
            $statement->execute([$_GET['status']]);
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
            $results = $statement->fetchAll();
            if ($row > 0) {
                echo json_encode($results);
            } else {
                echo '{}';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
} else if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    if ($_GET['start_date'] != '' && $_GET['end_date'] != '') {
        try {
            $statement = $conn->prepare("SELECT * FROM components where `purchase_date` BETWEEN ? AND ?");
            $statement->execute([$_GET['start_date'], $_GET['end_date']]);
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
            $results = $statement->fetchAll();
            if ($row > 0) {
                echo json_encode($results);
            } else {
                echo '{}';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
} else {
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
}
