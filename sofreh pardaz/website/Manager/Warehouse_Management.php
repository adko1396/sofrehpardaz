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
//else{
//  mysqli_query($connection,"SET NAMES utf8");
//}


#SQL ingshin


#Query

$query = "SELECT * FROM `goods`";
$send_query= mysqli_query($connection, $query);




#Query

$query_table = "SELECT g.item_name, COALESCE(ap.total_value, 0) AS value, COALESCE(g.item_exit, 0) AS item_exit, COALESCE(ap.total_value, 0) - COALESCE(g.item_exit, 0) AS Request FROM goods g LEFT JOIN ( SELECT item_name, SUM(value) AS total_value FROM add_product GROUP BY item_name ) ap ON g.item_name = ap.item_name WHERE (COALESCE(ap.total_value, 0) - COALESCE(g.item_exit, 0)) <= g.minimum_stock_leve;  ";
$send_query_table = mysqli_query($connection, $query_table);

#Query
$query_if = "SELECT * FROM `add_product`";
$send_query_if = mysqli_query($connection, $query_if);

#Query

$id = $_GET['id'];
$query_GET_id = "SELECT SUM(ap.value) - g.item_exit AS sort FROM add_product ap JOIN goods g ON ap.item_name = g.item_name WHERE ap.item_name = '{$id}'";
$query_GET = "SELECT * FROM `add_product` WhERE item_name = '{$id}'"; 
$send_query_GET = mysqli_query($connection, $query_GET);
$send_query_GET_id = mysqli_query($connection, $query_GET_id);
$send_GET_id = mysqli_fetch_assoc($send_query_GET_id)

//**E SQL /
?>









<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   <title>سفره‌پرداز</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      margin-bottom: 4rem;
    }
    label {
      font-weight: bold;
    }
    .table th, .table td {
      text-align: center;
      vertical-align: middle;
    }
    footer {
      text-align: center;
      margin-top: 3rem;
      padding: 1.5rem 0;
      background-color: #f8f9fa;
      border-top: 1px solid #ddd;
    }
    canvas {
      max-height: 250px !important;
    }
  </style>

<script src="https://cdn.jsdelivr.net/npm/jalaali-js@1.1.0/dist/jalaali.min.js"></script>


</head>
<body>
  <!-- نوار بالا -->
 <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-1">
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
        <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران :  <?php echo $_SESSION['restaurant_name'];?> </h1>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="Admin_Panel.php" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
         
          <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['manager_name'];?> </span> |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: مدیر</span>
        </button>
      <a href="../logout_manager.php">  <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <div class="container content-box">
    <h4 class="text-center mb-4">گزارش‌گیری</h4>

    <h5 class="mt-4 mb-2">لیست کالاهای رو به اتمام</h5>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          
          <th>نام کالا</th>
          <th>جمع موجودی</th>

        </tr>
      </thead>
      <tbody>
        <?php while($query = mysqli_fetch_assoc($send_query_table)){
         
         ?>
         
        <tr>
        
          <td><?php echo $query['item_name'] ?></td>
          <td class="text-danger fw-bold" ><?php echo $query['Request'] ?></td>

        </tr>

        <?php
                }
         
    
      ?>
      </tbody>
    </table>
<hr>



    <h5 class="mt-5 mb-2">کالاهای بدون تاریخ انقضا</h5>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
         
          <th>نام کالا</th>
          <th>تاریخ و زمان ورود
            </th>
          <th>تاریخ انقضا
 </th>
          <th>تأمین‌کننده</th>
          <th> یادداشت</th>
          <th> مقدار</th>
        </tr>
      </thead>
      <tbody>


<?php

        // اگر تابع قبلا تعریف نشده بود، تعریفش کن
if (!function_exists('convertNumbersToPersian')) {
    function convertNumbersToPersian($string) {
        $englishNumbers = ['0','1','2','3','4','5','6','7','8','9'];
        $persianNumbers = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return str_replace($englishNumbers, $persianNumbers, $string);
    }
}

// تابع تبدیل تاریخ میلادی به شمسی (کد استاندارد و ساده)
if (!function_exists('gregorianToJalali')) {
    function gregorianToJalali($gy, $gm, $gd) {
        $g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
        if($gy > 1600){
            $jy=979;
            $gy-=1600;
        }else{
            $jy=0;
            $gy-=621;
        }
        $gy2 = ($gm > 2)? ($gy+1) : $gy;
        $days = (365*$gy) + ((int)(($gy2+3)/4)) - ((int)(($gy2+99)/100)) + ((int)(($gy2+399)/400)) - 80 + $gd + $g_d_m[$gm-1];
        $jy += 33 * ((int)($days / 12053));
        $days %= 12053;
        $jy += 4 * ((int)($days / 1461));
        $days %= 1461;
        if($days > 365){
            $jy += (int)(($days-1)/365);
            $days = ($days-1) % 365;
        }
        $jm = ($days < 186)? 1 + (int)($days / 31) : 7 + (int)(($days-186)/30);
        $jd = 1 + (($days < 186)? $days % 31 : ($days-186) % 30);
        return [$jy, $jm, $jd];
    }
}

// گرفتن تاریخ امروز میلادی
list($gy, $gm, $gd) = explode('-', date('Y-m-d'));

// تبدیل میلادی به شمسی
list($jy, $jm, $jd) = gregorianToJalali((int)$gy, (int)$gm, (int)$gd);

// ساخت رشته تاریخ شمسی
$jalaliDate = sprintf('%04d/%02d/%02d', $jy, $jm, $jd);

// تبدیل اعداد به فارسی
$jalaliDateFa = convertNumbersToPersian($jalaliDate);

// نمایش تاریخ شمسی امروز با اعداد فارسی
//echo   $jalaliDateFa;

?>


      <?php while($if = mysqli_fetch_assoc($send_query_if)){
        
        if ($jalaliDateFa  == $if['expiry_date']) {
          
       
        ?>
              </tr>
            <td><?php echo htmlspecialchars($if['item_name']); ?></td>
            <td><?php echo htmlspecialchars($if['entry_date_and_time']); ?></td>
            <td><?php echo htmlspecialchars($if['expiry_date']); ?></td>
            <td><?php echo htmlspecialchars($if['supplier_name']); ?></td>
            <td><?php echo htmlspecialchars($if['note']); ?></td>
            <td><?php echo htmlspecialchars($if['value']); ?></td>
        </tr>
<?php } }?>

      </tbody>
    </table>
<hr>
    <h5 class="mt-5 mb-3">نمایش موجودی کل کالا</h5>
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="product-name">نام کالا</label>
  <form method="get" action="Warehouse_Management.php" name="get">
  <select name="id" class="form-select" onchange="this.form.submit()">
    <option selected disabled>انتخاب کنید</option>
    <?php while($query = mysqli_fetch_assoc($send_query)) { ?>
      <option value="<?php echo $query['item_name']; ?>">
        <?php echo $query['item_name']; ?>
      </option>
    <?php } ?>
  </select>
</form>
      </div>
      <div class="col-md-6 d-flex align-items-end">
        
    <!--<button class="btn btn-primary w-100">نمایش</button>-->
        
      </div>
    </div>

    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>شماره ثبت</th>
          <th>نام کالا</th>
          <th>تاریخ و ساعت ورود</th>
          <th>مقدار</th>
          <th>قیمت</th>
          <th>تأمین‌کننده</th>
          <th>یادداشت‌ها</th>
          <th>تاریخ انقضا</th>
        </tr>
      </thead>
      <tbody>
        
          <?php while($send_GET = mysqli_fetch_assoc($send_query_GET)){?>
            <tr>
          <td><?php echo $send_GET['id']?></td>
          <td><?php echo $send_GET['item_name']?></td>
          <td><?php echo $send_GET['entry_date_and_time']?></td>
          <td><?php echo $send_GET['value']?></td>
          <td><?php echo number_format($send_GET['price'])?></td>
          <td><?php echo $send_GET['supplier_name']?></td>
          <td><?php echo $send_GET['note']?></td>
          <td><?php echo $send_GET['expiry_date']?></td>
          </tr>
          <?php } ?>
        
        
      </tbody>
    </table>

    <div class="mb-4">
      <label for="total-amount">مقدار کل:</label>
      <input type="text" id="total-amount" class="form-control" value="<?php echo $send_GET_id['sort'] ?>" readonly />
    </div>
<hr>
    <h5 class="mt-5 mb-3">نمودار موجودی کالاها</h5>
    <canvas id="inventoryChart"></canvas>
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


<?php
// اتصال به پایگاه داده
$conn = mysqli_connect("localhost", "root", "", "sofrehpardaz");

$labels = [];
$data = [];

$query = "SELECT ap.item_name, SUM(ap.value) - g.item_exit AS sort FROM add_product ap JOIN goods g ON ap.item_name = g.item_name GROUP BY ap.item_name, g.item_exit;
";
$send_query_GET_id = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($send_query_GET_id)) {
    $labels[] = $row['item_name'];
    $data[] = (int)$row['sort'];
}
?>
<canvas id="inventoryChart" width="400" height="400"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('inventoryChart').getContext('2d');

const labels = <?php echo json_encode($labels); ?>;
const data = <?php echo json_encode($data); ?>;

new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: labels,
    datasets: [{
      label: 'موجودی',
      data: data,
      backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom'
      }
    }
  }
});
</script>
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



<!--E SQL end --> 

<?php

mysqli_close($connection);

?>

<!--E SQL end -->




<?php } else{

  header('Location:../logout_manager.php');

  }?>