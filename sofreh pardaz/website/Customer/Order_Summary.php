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
  </style>
</head>
<body>

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
      <button class="btn btn-success">پرداخت نهایی</button>
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

</body>
</html>

<?php 

if($totalSum == true){
  $query = "UPDATE orders SET totalsum = $totalSum  WHERE table_number = {$id}";


$send_query = mysqli_query($connection, $query);
}

mysqli_close($connection);
?>
