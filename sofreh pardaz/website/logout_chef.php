<?php

    session_start();
    $_SESSION['name'] = null;
    $_SESSION['role'] = null;
    $_SESSION['chef_login'] = false;
   


    unset($_SESSION['name']);
    unset($_SESSION['role']);
    unset($_SESSION['chef_login']);
   

    header('Location:chef_login.php');


?>