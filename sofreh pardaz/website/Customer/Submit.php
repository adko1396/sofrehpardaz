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


if ($id == true) {
$query = "UPDATE orders SET approval = 1  WHERE table_number = {$id}";


$send_query = mysqli_query($connection, $query);

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
      background-color: #f9f9f9;
      font-family: Vazir, sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 2rem;
    }
    .message-box {
      background: white;
      padding: 3rem 2rem;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      max-width: 600px;
    }
    .message-box h2 {
      color: #28a745;
      margin-bottom: 1rem;
    }
    .message-box p {
      font-size: 1.2rem;
      color: #555;
    }
  </style>
</head>
<body>

  <div class="content">
    <div class="message-box">
      <h2><i class="bi bi-check-circle-fill"></i> سفارش شما ثبت شد!</h2>
      <p>جهت تکمیل و پرداخت سفارش خود لطفاً به صندوق مراجعه کنید.</p>
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

</body>
</html>


<!--E SQL end --> 

<?php

mysqli_close($connection);

?>

<!--E SQL end -->