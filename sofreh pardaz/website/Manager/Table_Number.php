

<?php session_start(); if(isset($_SESSION['manager_login'] ) &&  $_SESSION['manager_login'] ){?>



<!--SQL--> 
<?php 



#ُSQL

$host = "localhost";
$user = "root";
$password = "";
$dbname = "sofrehpardaz";
$connection = new mysqli($host, $user, $password , $dbname);

if (mysqli_connect_errno()) {
  die("MySQL 404". mysqli_connect_error()."[".mysqli_connect_errno()."]");
}
//else{
//  mysqli_query($connection,"SET NAMES utf8");
//}


#SQL ingshin

$id = (int) $_GET['id'];



#Query

$query_table = "SELECT * FROM `table` WHERE id = {$id} ";
$send_query_table = mysqli_query($connection, $query_table);
$query = mysqli_fetch_assoc($send_query_table);

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
    .section-box {
      background: white;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    h3 {
      text-align: center;
      margin-bottom: 1rem;
    }
    #qrcode {
      display: flex;
      justify-content: center;
      margin: 2rem 0;
    }
    .info-text {
      text-align: center;
      font-weight: bold;
      color: #444;
      margin-top: 1rem;
    }

    @media print {
      .navbar, footer, .btn, .no-print {
        display: none !important;
      }
      body {
        background: white;
        margin: 0;
      }
      .section-box {
        box-shadow: none;
        margin: 0;
        padding: 0;
      }
    }
  </style>
</head>
<body>
  <!-- ناوبری -->
 <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-1">
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
        <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران :  <?php echo $_SESSION['restaurant_name'];?> </h1>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="Admin_Panel.php" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
         <a href="Table_Management.php" class="btn btn-outline-primary btn-sm">داشبورد</a> 
          <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['manager_name'];?> </span> |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: مدیر</span>
        </button>
      <a href="../logout_manager.php">  <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <!-- محتوا -->
  <div class="container" style="margin-top: 7rem;">
    <div class="section-box text-center">
      <h3>شماره میز <?php echo $query['id']?></h3>
      <div id="qrcode"></div>
      <div class="info-text">به شبکه وای‌فای رستوران <?php echo $_SESSION['restaurant_name']?> وصل شوید و کد را جهت سفارش اسکن کنید</div>
      <button onclick="window.print()" class="btn btn-outline-primary mt-4 no-print"><i class="bi bi-printer"></i> چاپ QR</button>
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

  <!-- اسکریپت‌ها -->
  <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
  <script>
    // ساعت و تاریخ
    function updateClock() {
      const now = new Date();
      const time = now.toLocaleTimeString('fa-IR');
      const date = now.toLocaleDateString('fa-IR');
      document.getElementById("clock").textContent = `${date} - ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ساخت QR
    const qrText = "<?php echo "http://localhost/sofrehpardaz/Customer/Order_Menu.php?id={$id}"; ?>";
    new QRCode(document.getElementById("qrcode"), {
      text: qrText,
      width: 200,
      height: 200
    });
  </script>
</body>
</html>





<!--E SQL end --> 

<?php

mysqli_close($connection);

?>

<!--E SQL end -->







<?php } else{

  header('Location:../logout_manager.php');

  }?>