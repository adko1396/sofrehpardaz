<?php

    session_start();
    $_SESSION['name'] = null;
    $_SESSION['role'] = null;
    $_SESSION['POS_login'] = false;
   


    unset($_SESSION['name']);
    unset($_SESSION['role']);
    unset($_SESSION['POS_login']);
   

    header('Location:POS_login.php');


?>