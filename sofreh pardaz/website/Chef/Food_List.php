<?php session_start(); if(isset($_SESSION['chef_login'] ) &&  $_SESSION['chef_login'] ){?>


<?php 

$host = "localhost";
$user = "root";
$password = "";
$dbname = "sofrehpardaz";
$connection = new mysqli($host, $user, $password , $dbname);

if (mysqli_connect_errno()) {
  die("MySQL 404". mysqli_connect_error()."[".mysqli_connect_errno()."]");
}


$id = (int) $_GET['table_number'];

$query_restaurant_name = "SELECT * FROM `system` WHERE id = 1"; 
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$sen_name = mysqli_fetch_assoc($send_restaurant_name);

$SELECT = "SELECT * FROM `orders` WHERE approval = 0";
$SELECT_name = mysqli_query($connection, $SELECT);







function gregorian_to_jalali($gy, $gm, $gd)
{
    $g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
    $jy = ($gy <= 1600) ? 0 : 979;
    $gy -= ($gy <= 1600) ? 621 : 1600;
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + (int)(($gy2 + 3) / 4) - (int)(($gy2 + 99) / 100) + (int)(($gy2 + 399) / 400) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * (int)($days / 12053);
    $days %= 12053;
    $jy += 4 * (int)($days / 1461);
    $days %= 1461;
    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
    return [$jy, $jm, $jd];
}

// دریافت تاریخ امروز میلادی
$gy = date("Y");
$gm = date("n");
$gd = date("j");

// تبدیل به شمسی
list($jy, $jm, $jd) = gregorian_to_jalali($gy, $gm, $gd);




// گرفتن سفارش با توجه به شماره میز
$query_restaurant_name = "SELECT * FROM `orders` WHERE table_number = $id"; 
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$send_name = mysqli_fetch_assoc($send_restaurant_name);

// گرفتن رشته سفارش‌ها و جدا کردن آن‌ها
$orderString = $send_name['list_of_orders'];
$items = explode('|', $orderString);

$totalSum = 0;




?>








<!-- کد کامل با فوتر اضافه‌شده -->
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
    .order-box {
      background: #fff;
      border: 1px solid #ccc;
      padding: 2rem;
      border-radius: 8px;
      margin-top: 7rem;
    }
    th, td {
      text-align: center;
      vertical-align: middle !important;
    }
    @media print {
      body * {
        visibility: hidden;
      }
      #print-area, #print-area * {
        visibility: visible;
      }
      #print-area {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
      }
    }
  </style>
</head>
<body>

  
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-1">
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
 <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران <?php echo $sen_name['restaurant_name']?> </h1>
      </div>
      <div class="d-flex align-items-center gap-3">
       
  <a href="Registered_Orders_List.php" class="btn btn-outline-primary btn-sm">داشبورد</a>
        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span><?php echo $_SESSION['name'];?></span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: آشپز</span>
        </button>
        <a href="../logout_chef.php">
        <button class="btn btn-outline-danger btn-sm">خروج</button>
        </a>
      </div>
    </div>
  </nav>


  <!-- محتوای اصلی -->
  <div class="container" id="print-area">
    <div class="order-box">
      <h4 class="text-center mb-4">لیست غذاهای سفارش داده شده</h4>

      <div class="row mb-3">
        
        <div class="col-md-3"><strong>شماره میز:</strong> <?php echo $id  ?></div>
        <div class="col-md-3"><strong>ساعت:</strong> <?php echo date("H:i"); ?></div>
        <div class="col-md-3"><strong>تاریخ:</strong> <?php echo " $jy/$jm/$jd";?></div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>نام غذا</th>
              <th>تعداد</th>
            </tr>
          </thead>
          <tbody>
            <?php
          foreach ($items as $item) {
              list($name, $count) = explode(':', $item);
              $name = trim($name);
              $count = (int) trim($count);

              // جستجو در جدول menu با استفاده از food_name
              $safe_name = mysqli_real_escape_string($connection, $name);
              $query_menu = "SELECT * FROM `menu` WHERE `food_name` = '$safe_name' LIMIT 1";
              $result_menu = mysqli_query($connection, $query_menu);
              $menu_item = mysqli_fetch_assoc($result_menu);

              if ($menu_item) {
                  $unitPrice = (int) $menu_item['price'];
                  $totalPrice = $unitPrice * $count;
                  $totalSum += $totalPrice;

                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($name) . "</td>";
                  echo "<td>" . $count . "</td>";
                 
                  echo "</tr>";
              } else {
                  echo "<tr class='table-danger'>";
                  echo "<td colspan='4'>غذا با نام <strong>" . htmlspecialchars($name) . "</strong> در منو یافت نشد.</td>";
                  echo "</tr>";
              }
          }
          ?> 
          </tbody>
        </table>
      </div>

      <div class="text-center mt-4 d-flex justify-content-center gap-3">
        <button onclick="window.print()" class="btn btn-success">
          <i class="bi bi-printer"></i> چاپ 
        </button>
        <a href="Registered_Orders_List.php" class="btn btn-secondary">
          <i class="bi bi-arrow-right-circle"></i> بازگشت به پنل
        </a>
      </div>
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

  <!-- Clock Script -->
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


<?php  

mysqli_close($connection);

?>



<?php } else{

  header('Location:../chef_login.php');

  }?>