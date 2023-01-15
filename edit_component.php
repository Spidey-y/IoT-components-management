<?php
ob_start();
session_start();
require __DIR__ . '/connection_db.php';
$conn = connect_to_db(); // function created in dbconnect, remember?


error_reporting(-1);
ini_set('display_errors', 'On');
$path = 'uploads/'; // upload directory

if (isset($_SESSION['username'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['quantity']) && isset($_POST['purchase_date']) && isset($_POST['name']) && isset($_POST['status']) && isset($_POST['id'])) {
            $data = [
                'quantity' => $_POST['quantity'],
                'purchase_date' => $_POST['purchase_date'],
                'name' => $_POST['name'],
                'status' => $_POST['status'],
                'id' => $_POST['id'],
            ];
            $sql = "UPDATE components SET quantity=:quantity, purchase_date=:purchase_date, name=:name, status=:status WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            if (isset($_FILES['image'])) {
                $img = $_FILES['image']['name'];
                $tmp = $_FILES['image']['tmp_name'];
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $final_image = rand(1000, 1000000) . $img;
                $path = $path . strtolower($final_image);
                move_uploaded_file($tmp, $path);
                $data = [
                    'image' => $path,
                    'id' => $_POST['id'],
                ];
                $sql = "UPDATE components SET image=:image WHERE id=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute($data);
            }
            echo '{"state": "success", "message": "Data updated successfully!"}';
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo '{"state": "error", "message": "' . $e->getMessage() . '"}';
        }
    }
}