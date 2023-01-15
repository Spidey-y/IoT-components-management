<?php
   session_start();
   unset($_SESSION["username"]);   
   unset($_SESSION['valid']);
   unset($_SESSION['timeout']);
   session_destroy();

   echo 'You have cleaned session';
   header('Refresh: 2; URL = index.php');
?>