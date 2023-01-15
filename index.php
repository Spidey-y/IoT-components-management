<?php
//    session_start();
//    unset($_SESSION["username"]);   
//    unset($_SESSION['valid']);
//    unset($_SESSION['timeout']);
//    session_destroy();
    if(!(isset($_SESSION['username']))) {
        echo 'You have cleaned session';
        header('Refresh: 0; URL = login.php');
    } else {
        echo 'You have cleaned session';
        header('Refresh: 0; URL = dashboard.php');
    }
?>