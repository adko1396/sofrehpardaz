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

$query_check = "SELECT * FROM `user`";
$send_query_check = mysqli_query($connection, $query_check);
$send_SQL_check = mysqli_fetch_assoc($send_query_check);
$send_SQL_check2 = mysqli_fetch_assoc($send_query_check);
$send_SQL_check3 = mysqli_fetch_assoc($send_query_check);



#SQL ingshin

if(isset($_POST["submit1"])) {

if ($send_SQL_check['role'] == "warehouse") {

   $message = "⚠️ این کاربر قبلاً در سیستم اضافه شده است.";

}else{



$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);


#Query

$query = "INSERT INTO user(name , name_login , password , role ) VALUES ('{$send1}' , '{$send2}','{$send3}','warehouse')";

$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}

}
}



if(isset($_POST["submit2"])) {

if ($send_SQL_check2['role'] == "chef") {

   $message = "⚠️ این کاربر قبلاً در سیستم اضافه شده است.";

}else{



$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);


#Query

$query = "INSERT INTO user(name , name_login , password , role ) VALUES ('{$send1}' , '{$send2}','{$send3}','chef')";

$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}

}
}


if(isset($_POST["submit3"])) {

if ($send_SQL_check3['role'] == "waiter") {

   $message = "⚠️ این کاربر قبلاً در سیستم اضافه شده است.";

}else{



$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);


#Query

$query = "INSERT INTO user(name , name_login , password , role ) VALUES ('{$send1}' , '{$send2}','{$send3}','waiter')";

$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}

}
}


if(isset($_POST["submit4"])) {



$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);


#Query

$query = "INSERT INTO user(name , name_login , password , role ) VALUES ('{$send1}' , '{$send2}','{$send3}','cashier')";

$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}


}
#Query

$query_table = "SELECT * FROM `user`";
$send_query_table = mysqli_query($connection, $query_table);





if(isset($_POST["submitpass"])) {



$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);
$send3 = mysqli_real_escape_string($connection, $_POST['form3']);


$query_check_pass = "SELECT * FROM `system` WHERE id = '1' ";
$send_query_pass = mysqli_query($connection, $query_check_pass);
$send_SQL_pass = mysqli_fetch_assoc($send_query_pass);


if ($send_SQL_pass['password'] == $send1) {
  #Query
if ($send2 == $send3 ){

$query = "UPDATE system SET password ='{$send2}'  WHERE id = '1' ";

$send_query = mysqli_query($connection, $query);


if ($send_query) {
  $message = "✅ اطلاعات با موفقیت ذخیره شد.";
}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}


}else{
  $message = "❌ خطا در ذخیره اطلاعات: ";
}

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
        <a href="Admin_Panel.php" class="btn btn-outline-primary btn-sm">داشبورد</a>
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
      <h5>مدیریت کارکنان</h5>
<p> ثبت انباردار (  فقط یک نفر می‌توان ثبت شود)</p>
      <!-- ثبت انباردار -->
      <form class="row g-3 mb-3" method="post" action="Staff_Management.php">
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام و نام خانوادگی" name="form1"></div>
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام کاربری" name="form2"></div>
        <div class="col-md-3"><input type="password" class="form-control" placeholder="رمز عبور" name="form3"></div>
        <div class="col-md-3"><button class="btn btn-outline-success w-100" name="submit1" type="submit">ثبت انباردار</button></div>
      </form>
<hr>
<p>ثبت آشپز  (  فقط یک نفر می‌توان ثبت شود)</p>
      <!-- ثبت آشپز -->
      <form class="row g-3 mb-3" method="post" action="Staff_Management.php">
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام و نام خانوادگی" name="form1"></div>
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام کاربری" name="form2"></div>
        <div class="col-md-3"><input type="password" class="form-control" placeholder="رمز عبور" name="form3"></div>
        <div class="col-md-3"><button class="btn btn-outline-success w-100" type="submit" name="submit2">ثبت آشپز</button></div>
      </form>
<hr>
<p>ثبت گارسون  (  فقط یک نفر می‌توان ثبت شود)</p>
      <!-- ثبت گارسون -->
      <form class="row g-3 mb-3" method="post" action="Staff_Management.php">
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام و نام خانوادگی" name="form1"></div>
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام کاربری" name="form2"></div>
        <div class="col-md-3"><input type="password" class="form-control" placeholder="رمز عبور" name="form3"></div>
        <div class="col-md-3"><button class="btn btn-outline-success w-100" type="submit" name="submit3">ثبت گارسون</button></div>
      </form>
<hr>
<p>ثبت صندوق‌دار</p>
      <!-- ثبت صندوق‌دار -->
      <form class="row g-3 mb-3" method="post" action="Staff_Management.php">
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام و نام خانوادگی" name="form1"></div>
        <div class="col-md-3"><input type="text" class="form-control" placeholder="نام کاربری" name="form2"></div>
        <div class="col-md-3"><input type="password" class="form-control" placeholder="رمز عبور" name="form3"></div>
        <div class="col-md-3"><button class="btn btn-outline-success w-100" type="submit" name="submit4">ثبت صندوق‌دار</button></div>
      </form>
    </div>

    <!-- لیست کارکنان -->
    <div class="section-box">
      <h5>لیست کارکنان</h5>
      <table class="table table-bordered text-center">
        <thead class="table-light">
          <tr>
            <th>نام و نام خانوادگی</th>
            <th>نوع کار</th>
            <th>نام کاربری</th>
            <th>رمز عبور</th>
            <th>عملیات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php while($query = mysqli_fetch_assoc($send_query_table)){?>
            <td><?php echo $query['name'] ?></td>
            <td><?php switch($query['role']){
              case 'cashier':
                echo "صندوق‌دار";
                break;
                  case 'chef':
                echo "آشپز  ";
                break;
                  case 'waiter':
                echo "گارسون ";
                break;
                  case 'warehouse':
                echo "انباردار ";
                break;
                default : 
                echo "404";
               

            } ?></td>
            <td><?php echo $query['name_login'] ?></td>
            <td><?php echo $query['password'] ?></td>
            <td>
              <a href="Staff_Management_edit.php?id=<?php echo $query['id'] ?>">
              <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> ویرایش</button>
              </a>
            </td>
          </tr>
           <?php }?>
          <!-- سایر کارمندان -->
        </tbody>
      </table>
    </div>

    <!-- تغییر رمز مدیر -->
    <div class="section-box">
      <h5>تغییر رمز عبور مدیر</h5>
      <form class="row g-3" method="post" action="Staff_Management.php">
        <div class="col-md-4"><input type="password" class="form-control" placeholder="رمز فعلی" name="form1"></div>
        <div class="col-md-4"><input type="password" class="form-control" placeholder="رمز جدید" name="form2"></div>
        <div class="col-md-4"><input type="password" class="form-control" placeholder="تکرار رمز جدید" name="form3"></div>
<div class="col-12 text-center">
  <button class="btn btn-success mt-2" type="submit" name="submitpass">تغییر رمز</button>
</div>
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