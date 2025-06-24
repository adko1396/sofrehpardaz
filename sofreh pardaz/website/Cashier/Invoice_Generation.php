<?php session_start(); if(isset($_SESSION['POS_login'] ) &&  $_SESSION['POS_login'] ){?>





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

$id = (int) $_GET['customer_code'];




#SQL ingshin

if(isset($_POST["submit"])) {




$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);


#Query

$query = "UPDATE menu SET food_name ='{$send1}' , price ='{$send2}' WHERE id = {$id}";



$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
  header('Location:List_of_Menus.php');
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}

}


#Query

$query_system = "SELECT * FROM `system` WHERE id = '1' ";
$send_query_system = mysqli_query($connection, $query_system);
$query_system = mysqli_fetch_assoc($send_query_system);



#Query

$query_customer = "SELECT * FROM `customer` WHERE customer_code = {$id} ";
$send_query_customer = mysqli_query($connection, $query_customer);
$query_customer = mysqli_fetch_assoc($send_query_customer);

#Query

//$query_invoice = "SELECT * FROM `invoice` WHERE customer_code = {$id} ";

$query_invoice = "SELECT i.*, m.price AS menu_price FROM invoice i LEFT JOIN menu m ON i.food_name = m.food_name WHERE i.customer_code =  {$id}";
$send_query_invoice = mysqli_query($connection, $query_invoice);




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
    }
    th, td {
      text-align: center;
      vertical-align: middle !important;
    }
    @media print {
      body * {
        visibility: hidden;
      }
      #printable-invoice, #printable-invoice * {
        visibility: visible;
      }
      #printable-invoice {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
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
         <a href="Invoices.php" class="btn btn-outline-primary btn-sm">داشبورد</a>

        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['name'];?> </span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: صندوق</span>
        </button>

        <a href="../logout_POS.php"> <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>


  <div class="container" id="printable-invoice">
    <div class="invoice-box">
      <h4 class="text-center mb-4">صدور فاکتور</h4>
      <p class="text-center mb-4"> سیستم سفره‌پرداز</p>
      <div class="row mb-3">
        <div class="col-md-6"><strong>نام رستوران:</strong> <?php echo $query_system['restaurant_name']?></div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6"><strong>شماره تماس:</strong> <?php echo $query_system['restaurant_phone_number']?></div>
        <div class="col-md-6"><strong>آدرس:</strong> <?php echo $query_system['restaurant_address']?> </div>
      </div>
      <div class="mb-3">
        <strong>یادداشت رستوران:</strong>
        <p class="border rounded p-2">از خرید شما سپاسگزاریم. در صورت رضایت، ما را به دوستان خود معرفی کنید.</p>
      </div>
      <div class="row mb-3">
        <div class="col-md-6"><strong>کد مشتری:</strong> <?php echo $query_customer['customer_code']?></div>
        <div class="col-md-3"><strong>شماره فاکتور:</strong> <?php echo $query_customer['invoice_number']?></div>
        <div class="col-md-3"><strong>نام صندوق‌دار:</strong> <?php echo $query_customer['cashier_name']?></div>
<br>
<br>
<hr>

        <div class="col-md-3"><strong>شماره تماس مشتری:</strong> <?php echo $query_customer['customer_phone_number']?></div>
<div class="col-md-3"><strong>شماره میز:</strong> <?php echo $query_customer['table_number']?></div>
        <div class="col-md-3"><strong>ساعت:</strong> <?php echo $query_customer['time']?></div>
        <div class="col-md-3"><strong>تاریخ:</strong> <?php echo $query_customer['date']?></div>
      </div>
<br>
      <div class="mb-3"><strong>نوع پرداخت:</strong> <?php echo $query_customer['payment_method']?></div>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>نام غذا</th>
              <th>تعداد</th>
              <th>قیمت واحد</th>
              <th>قیمت کل</th>
            </tr>
          </thead>
          <tbody>
            <?php while($query = mysqli_fetch_assoc($send_query_invoice)){?>
            <tr>
              <td><?php echo $query['food_name']?></td>
              <td><?php echo $query['quantity']?></td>
              <td><?php echo number_format( $query['menu_price'])?></td>
              <td><?php echo number_format( $query['menu_price'] * $query['quantity'] )?></td>
            </tr>
<?php }?>
          </tbody>
        </table>
      </div>

      <div class="row mt-4">
        <div class="col-md-4 offset-md-8">
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              <span>مبلغ تخفیف (ریال)</span><strong><?php echo number_format ($query_customer['discount'])?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>جمع کل قابل پرداخت (ریال) </span><strong><?php echo number_format( $query_customer['total_price'])?></strong>
            </li>
          </ul>
        </div>
      </div>

      <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-success btn-lg">
          <i class="bi bi-printer"></i> چاپ فاکتور
        </button>
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
      document.getElementById("invoice-clock").textContent = time;
      document.getElementById("invoice-date").textContent = date;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>



<!--E SQL end --> 

<?php

mysqli_close($connection);

?>

<!--E SQL end -->



<?php } else{

  header('Location:../POS_login.php');

  }?>