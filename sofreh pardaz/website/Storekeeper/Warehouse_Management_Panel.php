


<?php session_start(); if(isset($_SESSION['warehouse_login'] ) &&  $_SESSION['warehouse_login'] ){?>

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
$sen_name = mysqli_fetch_assoc($send_restaurant_name);
  mysqli_close($connection);
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
    .panel-box {
      background: #fff;
      border: 1px solid #ccc;
      padding: 2rem;
      border-radius: 8px;
      margin-top: 7rem;
      text-align: center;
    }
    .panel-box .btn {
      font-size: 2rem;
      padding: 2rem 4rem;
      color: #fff !important;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-1">
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
 <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران <?php echo $sen_name['restaurant_name']?> </h1>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="#" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
  <!--<a href="#" class="btn btn-outline-primary btn-sm">داشبورد</a>-->
        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['name'];?></span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: انباردار</span>
        </button>
       <a href="../logout_warehouse.php"> <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="panel-box">
      <h4 class="mb-5">پنل مدیریت انبار</h4>

      <div class="d-flex flex-column flex-md-row justify-content-center gap-4">
        <a href="Product_Management.php" class="btn btn-primary"><i class="bi bi-box-seam"></i> مدیریت کالاها</a>
        <a href="Register_Incoming_Goods.php" class="btn btn-success"><i class="bi bi-box-arrow-in-down"></i> ثبت ورود کالا</a>
        <a href="Reporting.php" class="btn btn-warning"><i class="bi bi-file-earmark-text"></i> گزارش‌گیری</a>
      </div>
    </div>
  </div>

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

  <script>
    function updateClock() {
      const now = new Date();
      const time = now.toLocaleTimeString('fa-IR');
      const date = now.toLocaleDateString('fa-IR');
      document.getElementById("clock").textContent = `${date} - ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>











<?php } else{

  header('Location:../warehouse_login.php');

  }?>