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



#Query

$id = (int) $_GET['id'];
$query_edit = "SELECT * FROM `user` WhERE id = {$id} LIMIT 1"; 
$send_query_edit = mysqli_query($connection, $query_edit);
$send_edit = mysqli_fetch_assoc($send_query_edit);




#SQL ingshin

if(isset($_POST["submit"])) {




$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);



#Query

$query = "UPDATE user SET name ='{$send1}' , name_login = '{$send2}' , password = '{$send3}' WHERE id = {$id}";



$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
  header('Location:Staff_Management.php');
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}

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
    .section-box {
      background: white;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    h5 {
      margin-bottom: 1rem;
      border-bottom: 2px solid #ddd;
      padding-bottom: .5rem;
    }
  </style>
</head>
<body>
  <!-- منوی ناوبری (کپی شده از کد فاکتور) -->
<nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-1">
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
        <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران :  <?php echo $_SESSION['restaurant_name'];?> </h1>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="Admin_Panel.php" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
        <a href="Staff_Management.php" class="btn btn-outline-primary btn-sm">داشبورد</a>
          <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['manager_name'];?> </span> |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: مدیر</span>
        </button>
      <a href="../logout_manager.php">  <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <!-- محتوای مدیریت کارکنان -->
  <div class="container" style="margin-top: 7rem;">
    <div class="section-box">
      <h5>ویرایش کارکنان</h5>

      <!-- ثبت انباردار -->
         
      <form class="row g-3 mb-3 " action="Staff_Management_edit.php?id=<?php echo $id?>" method="post">
        <label class="text-center">نام و نام خانوادگی :</label>
        <br><br>
        <div class="col-md-3 container"><input type="text" class="form-control" placeholder="نام و نام خانوادگی" name="form1" value="<?php echo $send_edit['name']?>"> </div>
        <label class="text-center">نام کاربری :</label>
        <br><br>
        <div class="col-md-3 container"><input type="text" class="form-control" placeholder="نام کاربری" name="form2" value="<?php echo $send_edit['name_login']?>"></div>
        <label class="text-center">رمز عبور :</label>
        <br><br>
        <div class="col-md-3 container"><input type="text" class="form-control" placeholder="رمز عبور" name="form3" value="<?php echo $send_edit['password']?>"></div>
 <label></label>
        <div class="col-md-3 container"><button class="btn btn-outline-success w-100" name="submit" type="submit">ثبت </button></div>
      </form>
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

  header('Location:../logout_manager.php');

  }?>