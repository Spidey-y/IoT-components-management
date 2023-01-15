<?php


error_reporting(-1);
ini_set('display_errors', 'On');

// connect to database
$path = 'uploads/'; // upload directory

require __DIR__ . '/connection_db.php';
$conn = connect_to_db();

if (isset($_POST['quantity']) && isset($_FILES['image']) && isset($_POST['purchase_date']) && isset($_POST['name']) && isset($_POST['status'])) {
    try {
        // echo 'shit';
        // die();
        $img = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $final_image = rand(1000, 1000000) . $img;
        $path = $path . strtolower($final_image);
        move_uploaded_file($tmp, $path);

        //get form data
        $name = $_POST['name'];
        $purchaseDate = $_POST['purchase_date'];
        $quantity = $_POST['quantity'];
        $status = $_POST['status'];

        // insert new component into database
        $stmt = $conn->prepare("INSERT INTO components (name, image, purchase_date, quantity, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $path, $purchaseDate, $quantity, $status]);
        echo '{"state": "success", "message": "Data inserted successfully!"}';    
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo '{"state": "error", "message": "' . $e->getMessage() . '"}';    
    }
}