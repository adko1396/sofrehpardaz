
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

$id = (int) $_GET['id'];


if (isset($_GET['del'])) {
    $id = (int) $_GET['del'];

    $query = "DELETE FROM `orders` WHERE table_number = {$id}"; // بدون *
    mysqli_query($connection, $query);

    header('Location:Order_Menu.php?' . 'id='. $id);
    exit;
}


// گرفتن سفارش با توجه به شماره میز
$query_restaurant_name = "SELECT * FROM `orders` WHERE table_number = $id"; 
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$send_name = mysqli_fetch_assoc($send_restaurant_name);

// گرفتن رشته سفارش‌ها و جدا کردن آن‌ها
$orderString = $send_name['list_of_orders'];
$items = explode('|', $orderString);

$totalSum = 0;




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
      background-color: #f9f9f9;
      font-family: Vazir, sans-serif;
    }
    .section-title {
      font-size: 1.8rem;
      font-weight: bold;
      color: #333;
      margin-top: 2rem;
      text-align: center;
    }

    /* ===== تنظیمات کلی صفحه ===== */
body {
  background-color: #f5f5f5;
  font-family: 'Vazir', sans-serif;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  direction: rtl; /* برای نمایش صحیح فارسی */
}

/* ===== ناوبری (Navbar) ===== */
.navbar {
  background-color: #fff;
  border-bottom: 1px solid #ddd;
}

.navbar-logo {
  height: 40px;
  margin-left: 10px;
}

.restaurant-brand {
  font-weight: bold;
  font-size: 1.2rem;
}

/* ===== دکمه‌ها ===== */
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

.btn-large {
  flex: 1 1 45%;
  font-size: 1.5rem;
  padding: 5rem 0;
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

/* ===== باکس هدر ===== */
.header-box {
  background-color: #ffffff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  margin-bottom: 30px;
  text-align: center;
}

.header-box h1 {
  font-size: 1.8rem;
  color: #333;
}

/* ===== عنوان بخش‌ها ===== */
.section-title {
  font-size: 1.4rem;
  font-weight: 600;
  margin-top: 40px;
  margin-bottom: 20px;
  color: #444;
  border-bottom: 2px solid #ddd;
  padding-bottom: 6px;
}

/* ===== کارت غذا ===== */
.food-card {
  background: #fff;
  border: none;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  overflow: hidden;
  transition: transform 0.2s;
}

.food-card:hover {
  transform: translateY(-5px);
}

.food-img {
  height: 180px;
  object-fit: cover;
  width: 100%;
}

.card-body h5 {
  font-size: 1.1rem;
  color: #222;
  margin-bottom: 5px;
}

.card-body .price {
  font-size: 0.9rem;
  color: #777;
  margin-bottom: 10px;
}

/* ===== بخش انتخاب تعداد ===== */
.quantity-box {
  display: none;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.btn-qty {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: none;
  background-color: #007bff;
  color: #fff;
  font-size: 18px;
}

.btn-qty:hover {
  background-color: #0056b3;
}

.qty-num {
  min-width: 24px;
  text-align: center;
  font-weight: bold;
}

/* ===== جدول ===== */
table {
  background: #fff;
  text-align: center;
}

th, td {
  text-align: center;
  vertical-align: middle !important;
}

/* ===== فرم‌ها و پنل‌ها ===== */
.form-section {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1rem;
}

.panel-actions {
  margin-top: 4rem;
  padding: 0 1rem;
}

/* ===== محتوای اصلی و فوتر ===== */
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
         <a href="Add_Order.php" class="btn btn-outline-primary btn-sm">داشبورد</a>

        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['name'];?> </span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: صندوق</span>
        </button>

        <a href="../logout_POS.php"> <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <h2 class="section-title">خلاصه سفارش شما</h2>
    <div class="table-responsive mt-4">
      <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
          <tr>
            <th>نام غذا</th>
            <th>تعداد</th>
            <th>قیمت واحد</th>
            <th>قیمت کل</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($items as $item) {
              list($name, $count) = explode(':', $item);
              $name = trim($name);
              $count = (int) trim($count);

              // جستجو در جدول menu با استفاده از food_name
              $safe_name = mysqli_real_escape_string($connection, $name);
              $query_menu = "SELECT * FROM `menu` WHERE `food_name` = '$safe_name' LIMIT 1";
              $result_menu = mysqli_query($connection, $query_menu);
              $menu_item = mysqli_fetch_assoc($result_menu);

              if ($menu_item) {
                  $unitPrice = (int) $menu_item['price'];
                  $totalPrice = $unitPrice * $count;
                  $totalSum += $totalPrice;

                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($name) . "</td>";
                  echo "<td>" . $count . "</td>";
                  echo "<td>" . number_format($unitPrice) . " ریال</td>";
                  echo "<td>" . number_format($totalPrice) . " ریال</td>";
                  echo "</tr>";
              } else {
                  echo "<tr class='table-danger'>";
                  echo "<td colspan='4'>غذا با نام <strong>" . htmlspecialchars($name) . "</strong> در منو یافت نشد.</td>";
                  echo "</tr>";
              }
          }
          ?>  
        </tbody>
      </table>
    </div>

    <div class="text-center mt-4">
      <samp style="color: green; font-size: 20px;">جمع کل :</samp>
      <samp style="color: green;font-size: 20px;"><?php echo number_format($totalSum); ?> ریال</samp>
    </div>

    <div class="text-center mt-4">
      <a href="Submit.php?id=<?php echo $id ?>">
      <button class="btn btn-success">ثبت نهایی</button>
      </a>
    </div>

    <div class="text-center mt-4">
       <a href="Order_Summary.php?del=<?php echo $id ?>" onclick="return confirm('⚠️ آیا مطمئنی می‌خواهی لغو سفارش کنی؟')">
      <button class="btn btn-danger">لغو سفارش</button>
      </a>
    </div>
  </div>

  <footer class="text-center mt-5 py-4 bg-light border-top">
    <div class="container">
      <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub">
        <img style="width: 10rem; height: 2rem;" src="../imag/sofreh pardaz english - 1.png" alt="لوگو" class="mb-3">
      </a>
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

if($totalSum == true){
  $query = "UPDATE orders SET totalsum = $totalSum  WHERE table_number = {$id}";


$send_query = mysqli_query($connection, $query);
}

mysqli_close($connection);
?>



<?php } else{

  header('Location:../POS_login.php');

  }?>