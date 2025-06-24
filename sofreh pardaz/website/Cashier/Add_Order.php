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


$get_foods = mysqli_query($connection, "SELECT * FROM menu");








$query_MAX_SELECT = "SELECT * FROM `orders`"; 
$send_MAX_SELECT = mysqli_query($connection, $query_MAX_SELECT);



if(isset($_POST["submit"])) {



$send1 = mysqli_real_escape_string($connection, $_POST['form']);


    header("Location: Order_Menu.php?id={$send1}");
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

    body {
      background-color: #f5f5f5;
      font-family: Vazir, sans-serif;
      margin: 0; padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
    main {
      flex-grow: 1;
      padding-bottom: 2rem;
    }
    footer {
      background-color: #fff;
      border-top: 1px solid #ddd;
      padding: 1.5rem 0;
      text-align: center;
    }
    table {
      background: #fff;
      text-align: center;
    }
    th, td {
      vertical-align: middle !important;
    }
    .form-section {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 1rem;
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
         <a href="Cashier_Panel.php" class="btn btn-outline-primary btn-sm">داشبورد</a>

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
  <main class="container mt-5 pt-5">
    <h4 class="text-center mb-4">افزودن سفارش جدید</h4>
<hr>
    <!-- فرم سفارش -->
<form class="row g-3 mb-4 align-items-end border rounded p-3 bg-white shadow-sm justify-content-center" method="post" action="Add_Order.php">
  
  <div class="col-md-2">
    <label for="quantity" class="form-label">شماره میز :</label>
    <input type="number" class="form-control" id="quantity" name="form" min="1">
  </div>
  <div class="col-md-4 d-flex gap-2">
    <button type="submit" class="btn btn-success w-100" name="submit">ثبت سفارش</button>
   <!--<button type="reset" class="btn btn-secondary w-100">لغو</button>--> 
  </div>
</form>
<hr>
<h4 class="text-center mb-4">میزهای رزرو شده</h4>
    <!-- جدول سفارشات -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
               <th>شماره میز</th>
      <th>وضعیت</th>

          </tr>
        </thead>
        <tbody>

         <?php while($query = mysqli_fetch_assoc($send_MAX_SELECT)){?>

          <tr>
            <td> <?php echo $query['table_number']?> </td>
            <td>

            <i style="color: forestgreen;" class="bi bi-check-circle-fill"></i>

            رزرو شده


            </td>
          </tr>
     <?php }?>
        </tbody>
      </table>
    </div>



  </main>

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

      function setPriceFromSelect() {
    const selectedOption = document.querySelector('#food option:checked');
    const price = selectedOption.getAttribute('data-price');
    document.getElementById('price').value = price || '';
  }
  </script>

</body>
</html>




<?php  

mysqli_close($connection);

?>



<?php } else{

  header('Location:../POS_login.php');

  }?>