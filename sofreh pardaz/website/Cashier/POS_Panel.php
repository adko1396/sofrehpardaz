<?php session_start(); if(isset($_SESSION['POS_login'] ) &&  $_SESSION['POS_login'] ){?>




<?php 

$host = "localhost";
$user = "root";
$password = "";
$dbname = "sofrehpardaz";
$connection = new mysqli($host, $user, $password , $dbname);

if (mysqli_connect_errno()) {
  die("MySQL 404". mysqli_connect_error()."[".mysqli_connect_errno()."]");
}


$query_restaurant_name = "SELECT * FROM `system` WHERE id = 1"; 
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$send_name = mysqli_fetch_assoc($send_restaurant_name);


?>




<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   <title>سفره‌پرداز</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f5f5f5;
      font-family: Vazir, sans-serif;
      margin: 0;
      padding: 0;
    }
    .navbar {
      background-color: #fff;
      border-bottom: 1px solid #ddd;
    }
    .panel-actions {
      margin-top: 4rem;
      padding: 0 1rem;
    }
    .restaurant-brand {
      font-weight: bold;
      font-size: 1.2rem;
    }
    .navbar-logo {
      height: 40px;
      margin-left: 10px;
    }
    .btn-orange-outline {
      background-color: transparent !important;
      color: #ff6600 !important;
      border: 2px solid #ff6600 !important;
      font-weight: bold;
    }
    .btn-orange-outline i {
      margin-left: 4px;
      color: #ff6600;
    }
    /* دکمه‌ها کنار هم با عرض بزرگ و ارتفاع بیشتر */
    .btn-large {
      flex: 1 1 45%;
      font-size: 1.5rem;
      padding: 5rem 0;  /* ارتفاع بیشتر */
      font-weight: bold;
    }
    .btn-container {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: nowrap;
    }
    @media (max-width: 576px) {
      .btn-container {
        flex-direction: column;
      }
      .btn-large {
        flex: 1 1 100%;
      }
    }
  </style>
</head>
<body>

  <!-- نوار بالایی -->
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">

      <div class="d-flex align-items-center gap-1">
        <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
        <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران <?php echo $send_name['restaurant_name']?> </h1>
      </div>

      <div class="d-flex align-items-center gap-3">
        <a href="#" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>

        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['name'];?> </span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: صندوق</span>
        </button>

        <a href="../logout_POS.php"> <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <!-- محتوای اصلی -->
  <div class="container panel-actions text-center mt-5 pt-5">
    <br>
    <h2 class="mb-4">پنل صندوق‌دار</h2>
    <br>

    <div class="btn-container">
     <a class="btn btn-success btn-large" href="Cashier_Panel.php"> فعال‌سازی صندوق</a>
      <a class="btn btn-primary btn-large" href="Invoices.php">نمایش فاکتورهای صادر شده</a>
    </div>

    
  </div>

  <!-- فوتر -->
 <footer class="text-center mt-5 py-4 bg-light border-top">
  <div class="container">
     <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img style="width: 10rem; height: 2rem;" src="../imag/sofreh pardaz english - 1.png" alt="لوگو" class="mb-3"></a>
   <h6 class="mb-2">سفره‌پرداز</h6>
    <ul class="list-inline">
      <li class="list-inline-item">
        <a href="https://github.com/adko1396" target="_blank" title="GitHub">
          <i style="color: #222;" class="bi bi-github fs-4"></i>
        </a>
      </li>
    </ul>
  </div>
</footer>

  <!-- اسکریپت ساعت -->
  <script>
    function updateClock() {
      const now = new Date();
      const date = now.toLocaleDateString('fa-IR');
      const time = now.toLocaleTimeString('fa-IR');
      document.getElementById("clock").textContent = `${date} - ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>

</body>
</html>




<?php  

mysqli_close($connection);

?>



<?php } else{

  header('Location:../POS_login.php');

  }?>