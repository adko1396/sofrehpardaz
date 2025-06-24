<?php

    session_start();
    $_SESSION['name'] = null;
    $_SESSION['role'] = null;
    $_SESSION['waiter_login'] = false;
   


    unset($_SESSION['name']);
    unset($_SESSION['role']);
    unset($_SESSION['waiter_login']);
   

    header('Location:waiter_login.php');


?>