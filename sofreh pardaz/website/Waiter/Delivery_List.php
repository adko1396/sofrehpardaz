<meta http-equiv="refresh" content="5">



<?php session_start(); if(isset($_SESSION['waiter_login'] ) &&  $_SESSION['waiter_login'] ){?>


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

$SELECT = "SELECT * FROM `orders` WHERE approval = 0";
$SELECT_name = mysqli_query($connection, $SELECT);


if (isset($_GET['table_number'])) {
    $id = (int) $_GET['table_number'];

    $query = "UPDATE `orders` SET approval = 3 WHERE table_number = {$id}"; // بدون *
    mysqli_query($connection, $query);

    header('Location:Delivery_List.php');
    exit;
}



$SELECT = "SELECT * FROM `orders` WHERE approval = 2 OR approval = 3";
$SELECT_name = mysqli_query($connection, $SELECT);


$SELECT_orders = "SELECT * FROM `orders` WHERE  approval = 3";
$SELECT_name_orders = mysqli_query($connection, $SELECT_orders);



if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $query = "DELETE FROM `orders` WHERE id = {$id}";
    mysqli_query($connection, $query);
    header('Location:Delivery_List.php');
    exit;
}
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
    .invoice-box {
      background: #fff;
      border: 1px solid #ccc;
      padding: 2rem;
      border-radius: 8px;
      margin-top: 7rem;
      margin-bottom: 2rem;
    }
    th, td {
      text-align: center;
      vertical-align: middle !important;
    }
  </style>
</head>
<body>

  <!-- Navbar شما -->
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-1">
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
 <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران <?php echo $send_name['restaurant_name']?> </h1>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="#" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
  <!-- <a href="#" class="btn btn-outline-primary btn-sm">داشبورد</a>-->
        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span><?php echo $_SESSION['name'];?></span> |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: گارسون</span>
        </button>
        <a href="../logout_waiter.php">
        <button class="btn btn-outline-danger btn-sm">خروج</button>
        </a>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <div class="container" id="printable-invoice">
    <div class="invoice-box">
      <h4 class="text-center mb-4">لیست تحویل و سرویس سفارش‌ها</h4>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              
              <th>شماره میز</th>
              
             
              <th>لیست سفارش</th>
              <th>عملیات</th>
            </tr>
          </thead>
          <tbody>

           <?php while ($Q  = mysqli_fetch_assoc( $SELECT_name)) {?>
            <tr>
              
              <td><?php echo $Q['table_number']?></td>
              
            
              <td>
                <a href="Ordered_Food_List.php?table_number=<?php echo $Q['table_number']?> ">
                <button style="color: white; " class="btn btn-primary">
                  <i class="bi bi-list-ul"></i> نمایش لیست
                </button>
                </a>
              </td>
              <td class="d-flex justify-content-center gap-2 flex-wrap">
                
              

              
  <?php 
                  
                  if ($Q ['approval'] == 3) {
                    
                  
                 
                  ?>
<a href="Delivery_List.php?delete=<?php echo $Q['id']?> "onclick="return confirm('⚠️ آیا میز آماده ارائه سرویس مجدد است؟')">
                <button class="btn btn-danger btn-sm">
                  <i class="bi bi-check2-square"></i> آماده سرویس
                </button>
</a>
                <?php  } else{?>

<a href="Delivery_List.php?table_number=<?php echo $Q['table_number']?> "onclick="return confirm('⚠️ آیا سفارش مشتری تحویل داده شده ؟')"
>
                <button class="btn btn-success btn-sm">
                  <i class="bi bi-check-circle"></i> تحویل داده شد
                </button>
</a>
              </td>
            </tr>

            <?php } }?>
            <!-- ردیف‌های دیگر -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

 
  <!-- Footer -->
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

  header('Location:../waiter_login.php');

  }?>