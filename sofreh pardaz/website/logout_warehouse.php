<?php

    session_start();
    $_SESSION['name'] = null;
    $_SESSION['name_login'] = null;
    $_SESSION['warehouse_login'] = false;
    $_SESSION['role'] = false;


    unset($_SESSION['name']);
    unset($_SESSION['name_login']);
    unset($_SESSION['role']);
    unset($_SESSION['warehouse_login']);

    header('Location:warehouse_login.php');


?>