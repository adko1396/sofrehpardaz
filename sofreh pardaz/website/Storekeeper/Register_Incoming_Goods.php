<?php session_start(); if(isset($_SESSION['warehouse_login'] ) &&  $_SESSION['warehouse_login'] ){?>










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

if(isset($_POST["submit"])) {


//$send1 = $_POST['form1'];

$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);
$send4 = mysqli_real_escape_string($connection, $_POST['form4']);
$send5 = mysqli_real_escape_string($connection, $_POST['form5']);
$send6 = mysqli_real_escape_string($connection, $_POST['form6']);
$send7 = mysqli_real_escape_string($connection, $_POST['form7']);


#Query

$query = "INSERT INTO add_product(item_name, entry_date_and_time, value, price, supplier_name, expiry_date,note) VALUES('{$send1}', '{$send2}', {$send3}, {$send4}, '{$send5}', '{$send6}', '{$send7}')";

$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}

}

#Query

$query_table = "SELECT * FROM `goods`";
$send_query_table = mysqli_query($connection, $query_table);


$query_restaurant_name = "SELECT * FROM `system` WHERE id = 1"; 
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$sen_name = mysqli_fetch_assoc($send_restaurant_name);

?>


//**E SQL /



<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   <title>سفره‌پرداز</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

  <!-- Persian Datepicker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css"/>
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
    .content-box {
      background: #fff;
      border: 1px solid #ccc;
      padding: 2rem;
      border-radius: 8px;
      margin-top: 7rem;
    }
    label {
      font-weight: bold;
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
   <!--<a href="#" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>-->
      <a href="Warehouse_Management_Panel.php" class="btn btn-outline-primary btn-sm">داشبورد</a>
        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span><?php echo $_SESSION['name'];?> </span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: انباردار</span>
        </button>
        <a href="../logout_warehouse.php"> <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <div class="container content-box">
    <h4 class="text-center mb-4">ثبت ورود کالا</h4>

    <form action="Register_Incoming_Goods.php" method="post">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="entry-id">شماره ثبت</label>
          <input type="text" id="entry-id" class="form-control" placeholder="مثلاً WRD-1234" required disabled value="شماره ثبت">
        </div>
        <div class="col-md-4">
          <label for="product-name">نام کالا</label>
          <select id="product-name" class="form-select" required name="form1">
            <option value="">انتخاب کنید</option>

            <?php while($query = mysqli_fetch_assoc($send_query_table)){?>
            <option><?php echo $query['item_name']?></option>
            <?php }?>

          </select>
        </div>
        <div class="col-md-4">
          <label>تاریخ و زمان ورود</label>
          <input type="text" class="form-control" id="entry-time" readonly name="form2">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-3">
          <label for="quantity">مقدار</label>
          <input type="text" id="quantity" class="form-control" required name="form3">
        </div>
        <div class="col-md-3">
          <label for="price">قیمت (ریال)</label>
          <input type="text" id="price" class="form-control" required name="form4">
        </div>
        <div class="col-md-3">
          <label for="supplier">تأمین‌کننده</label>
          <input type="text" id="supplier" class="form-control" required name="form5">
        </div>
        <div class="col-md-3">
          <label for="expiry">تاریخ انقضا</label>
          <input type="text" id="expiry" class="form-control date-picker" name="form6">
        </div>
      </div>

      <div class="mb-3">
        <label for="note">یادداشت</label>
        <textarea id="note" class="form-control" rows="3" name="form7"></textarea >
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-success px-5" name="submit">ثبت </button>
      </div>
    </form>
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

  <!-- Clock & Persian Datepicker -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

  <script>
    function updateClock() {
      const now = new Date();
      const time = now.toLocaleTimeString('fa-IR');
      const date = now.toLocaleDateString('fa-IR');
      document.getElementById("clock").textContent = `${date} - ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    $(document).ready(function () {
      // Set current date-time for entry
      const now = new Date();
      const time = now.toLocaleTimeString('fa-IR');
      const date = now.toLocaleDateString('fa-IR');
      $('#entry-time').val(date + ' ' + time);

      // Activate Persian Datepicker
      $(".date-picker").persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false,
        autoClose: true,
        toolbox: {
          calendarSwitch: {
            enabled: false
          }
        }
      });
    });
  </script>

<!--message-->  
<?php if (isset($message)) { ?>
<script>
    alert("<?php echo $message ?>");
</script>
<?php } ?>
<!--E message--> 




</body>
</html>


<!--E SQL end --> 

<?php

mysqli_close($connection);

?>

<!--E SQL end -->










<?php } else{

  header('Location:../warehouse_login.php');

  }?>