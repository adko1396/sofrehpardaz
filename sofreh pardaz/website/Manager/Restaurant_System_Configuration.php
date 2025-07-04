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





#Query

$query_scene = "SELECT * FROM `system` WHERE id = 1"; 
$send_query_scene = mysqli_query($connection, $query_scene);
$send_scene = mysqli_fetch_assoc($send_query_scene);




#SQL ingshin

if(isset($_POST["submit"])) {




$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);


#Query

$query = "UPDATE system SET restaurant_name ='{$send1}' , restaurant_address = '{$send2}' , restaurant_phone_number = '{$send3}' WHERE id = '1'";



$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
  header('Location:System_Configuration.php');
}else{
  $message = "❌ خطا در ذخیره اطلاعات: "; 
}

}


//**E SQL /
?>




<?php 

if ("1" == $send_scene['scene']) {
  header('Location:../manager_login.php');
 
}else{


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
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="https://raw.githubusercontent.com/adko1396/sofrehpardaz/refs/heads/main/sofreh%20pardaz/logo/sofreh%20pardaz%20farsi%20-%202.png.png" alt="لوگو" class="navbar-logo"></a>
        <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) </h1>
      </div>
<!--
      <div class="d-flex align-items-center gap-3">
        <a href="#" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
        <a href="#" class="btn btn-outline-primary btn-sm">داشبورد</a>
        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span>علی محمدی</span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: انباردار</span>
        </button>
        <button class="btn btn-outline-danger btn-sm">خروج</button>
      </div>
    </div>
-->
  </nav>

  <div class="container content-box">
    <h4 class="text-center mb-4">کانفیگ سیستم رستوران</h4>

    <form method="post" action="Restaurant_System_Configuration.php">
      <div class="mb-3">
        <label for="restaurant-name">نام رستوران</label>
        <input type="text" id="restaurant-name" class="form-control" placeholder="مثلاً رستوران سفره‌پرداز" name="form1">
      </div>
      <div class="mb-3">
        <label for="restaurant-address">آدرس رستوران</label>
        <input type="text" id="restaurant-address" class="form-control" placeholder="مثلاً تهران، خیابان انقلاب، پلاک 12" name="form2">
      </div>
      <div class="mb-3">
        <label for="restaurant-phone">شماره تماس رستوران</label>
        <input type="text" id="restaurant-phone" class="form-control" placeholder="مثلاً 021-12345678" name="form3">
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-success px-5" name="submit">ثبت</button>
      </div>
    </form>
  </div>

 <footer class="text-center mt-5 py-4 bg-light border-top">
  <div class="container">
     <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img style="width: 10rem; height: 2rem;" src="https://raw.githubusercontent.com/adko1396/sofrehpardaz/refs/heads/main/sofreh%20pardaz/logo/sofreh%20pardaz%20english%20-%201.png" alt="لوگو" class="mb-3"></a>
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

  <!-- ساعت -->
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

<?php } ?>

<!--E SQL end --> 

<?php

mysqli_close($connection);

?>

<!--E SQL end -->