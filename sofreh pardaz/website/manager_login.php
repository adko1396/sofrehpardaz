
<!--SQL--> 
<?php 
session_start();


if(isset($_SESSION['manager_login'] ) &&  $_SESSION['manager_login'] ){
     header('Location:Manager/Admin_Panel.php');
}

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


$send1 = mysqli_real_escape_string($connection, $_POST['form1']);
$send2 = mysqli_real_escape_string($connection, $_POST['form2']);


#Query


$query = "SELECT * FROM `system` WHERE login_username = '{$send1}' AND password = '{$send2}' OR pin_code = '{$send2}' LIMIT 1";

$send_query = mysqli_query($connection, $query);




if ($send_query) {
  $mysqli_rows = mysqli_num_rows($send_query);

  if ($mysqli_rows) {

    $SQL = mysqli_fetch_assoc($send_query);
    $_SESSION['manager_name'] = $SQL['manager_name'];
    $_SESSION['restaurant_name'] = $SQL['restaurant_name'];
    $_SESSION['manager_login'] = true;
    header('Location:Manager/Admin_Panel.php');
}else{
    $message = "❌ نام کاربری یا رمز عبور اشتباه است. ";
  }

}else{
  die("MySQL 404".mysqli_error($connection));
}

}


#Query


?>


<!--E SQL--> 




<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>سفره‌پرداز</title>

  <!-- Bootstrap RTL -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

  <!-- فونت فارسی -->
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">

<style>
body {
 overflow: hidden;
      font-family: 'Vazirmatn', sans-serif;
      background: url('imag/Administrative.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      margin: 0;
    }
</style>

</head>
<body >

  <div class="overlay"></div>

  <div class="login-box mt-5">
    <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img src="imag/sofreh pardaz english - 1.png" alt="لوگو" class="logo"></a>
    <img src="imag/2503732.png" alt="لوگو" class="logoIcon">
    <div class="title">ورود به سیستم مدیریت </div>

    <form action="manager_login.php" method="post">
      <div class="mb-3">
        <label for="username" class="form-label">نام کاربری</label>
        <input type="text" class="form-control" id="username" placeholder="نام کاربری خود را وارد کنید" name="form1">
      </div>

      <div class="mb-3 password-wrapper">
        <label for="password" class="form-label">رمز عبور</label>
        <input type="password" class="form-control" id="password" placeholder="رمز عبور خود را وارد کنید" name="form2">
        <button type="button" class="show-hide-btn" onclick="togglePassword()">نمایش</button>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-login" name="submit">ورود</button>
      </div>
    </form>
  </div>

<script src="js/script.js" defer></script>


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