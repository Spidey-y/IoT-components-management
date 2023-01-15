<?php
ob_start();
session_start();
require __DIR__ . '/connection_db.php';
$conn = connect_to_db(); // function created in dbconnect, remember?

$msg = '';

if (
    isset($_POST['login']) && !empty($_POST['username'])
    && !empty($_POST['password'])
) {
    try {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM `users` WHERE `username`=? AND `password`=? ";
        $query = $conn->prepare($sql);
        $query->execute(array($username, $password));
        $row = $query->rowCount();
        $fetch = $query->fetch();
        if ($row > 0) {
            // var_dump($fetch->username);
            // die();
            $_SESSION['username'] = $fetch->id;
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            header('Refresh: 0; URL = dashboard.php');
        } else {
            echo "
            <script>alert('Invalid username or password')</script>
            <script>window.location = 'login.php'</script>
            ";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<html>

<html lang="en">

<head>
    <title>Tutorialspoint.com</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }

        .form-signin .checkbox {
            font-weight: normal;
        }

        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color: #017572;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color: #017572;
        }

        h2 {
            text-align: center;
            color: #017572;
        }
    </style>

</head>

<body>

    <h2>Enter Username and Password</h2>
    <div class="container form-signin">

    </div>

    <div class="container">

        <form class="form-signin" role="form" action="login.php" method="post">
            <input type="text" class="form-control" name="username" placeholder="username" required autofocus></br>
            <input type="password" class="form-control" name="password" placeholder="password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Login</button>
        </form>

    </div>

</body>

</html>