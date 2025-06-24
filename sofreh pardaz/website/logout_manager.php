<?php

    session_start();
    $_SESSION['manager_name'] = null;
    $_SESSION['restaurant_name'] = null;
    $_SESSION['manager_login'] = false;
  

    unset($_SESSION['manager_name']);
    unset($_SESSION['restaurant_name']);
    unset($_SESSION['manager_login']);

    header('Location:manager_login.php');


?>