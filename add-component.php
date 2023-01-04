<?php


error_reporting(-1);
ini_set('display_errors', 'On');

// connect to database
$servername = "localhost";
$username = "root";
$password = "abcd1234";
$conn = new mysqli($servername, $username);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['quantity']) && isset($_POST['image']) && isset($_POST['purchase_date']) && isset($_POST['name']) && isset($_POST['status'])) {
    //get form data
    $name = $_POST['name'];
    $image = $_POST['image'];
    $purchaseDate = $_POST['purchase-date'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];
    
    // insert new component into database
    $stmt = $conn->prepare("INSERT INTO projet_web.components (name, image, purchase_date, quantity, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $image, $purchaseDate, $quantity, $status]);
    $conn->close();
    $stmt->close();
}
?>