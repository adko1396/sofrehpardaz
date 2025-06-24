<meta http-equiv="refresh" content="10">


<?php 

$host = "localhost";
$user = "root";
$password = "";
$dbname = "sofrehpardaz";
$connection = new mysqli($host, $user, $password , $dbname);

if (mysqli_connect_errno()) {
  die("MySQL 404". mysqli_connect_error()."[".mysqli_connect_errno()."]");
}



$query_table = "SELECT * FROM menu WHERE active = 1";
$send_query_table = mysqli_query($connection, $query_table);
$query 

?>









<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>سفره‌پرداز</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f9f9f9;
      font-family: Vazir, sans-serif;
    }
    .clock {
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      margin-top: 20px;
    }
    .menu-item img {
      width: 100%;
      max-height: 200px;
      object-fit: cover;
      border-radius: 8px;
    }
    .unavailable {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="container py-4">
    <div id="clock" class="clock"></div>

    <h2 class="text-center my-4">منوی امروز</h2>
<hr>

    <div class="row g-4">
     <?php while($query = mysqli_fetch_assoc($send_query_table)){?>

      <div class="col-md-4">
        <div class="card menu-item shadow-sm">
           <img src="<?php echo $query['food_photo']?>" class="food-img" alt="تصویر غذا">
          <div class="card-body text-center">
             <h5 class="card-title"> <?php echo $query['food_name']?></h5>
<hr>

            <p class="card-text text-success fw-bold"><?php echo $query['price']?></p>
          </div>
        </div>
      </div>
      <?php }?>
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

  <!-- ساعت داینامیک -->
  <script>
    function updateClock() {
      const now = new Date();
      const options = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' };
      const dateStr = now.toLocaleDateString('fa-IR', options);
      const timeStr = now.toLocaleTimeString('fa-IR');
      document.getElementById("clock").textContent = `امروز ${dateStr} - ساعت ${timeStr}`;
    }

    updateClock();
    setInterval(updateClock, 1000);
  </script>

</body>
</html>


<?php

mysqli_close($connection);
 
 ?>
