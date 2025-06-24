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



#SQL ingshin

$id = (int) $_GET['id'];




#SQL ingshin

if(isset($_POST["submit"])) {

$query = "UPDATE customer SET payment_method ='' WHERE customer_code = {$id}";
$send_query = mysqli_query($connection, $query);

}



#Query

$query_table = "SELECT * FROM `customer` WHERE customer_code = {$id} ";
$send_query_table = mysqli_query($connection, $query_table);
$query = mysqli_fetch_assoc($send_query_table);



if(isset($_POST["submit_ok"])) {


  }

  
if (isset($_GET['submit1'])) {
    $id = (int) $_GET['id'];

    // مرحله ۱: گرفتن اطلاعات مشتری
    $result = mysqli_query($connection, "SELECT total_price, discount FROM customer WHERE customer_code = {$id}");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // مرحله ۲: محاسبه مبلغ نهایی با اعمال تخفیف
        $Q = (float)$row['total_price'] - (float)$row['discount'];
        $Q = round($Q); // اگر عدد صحیح می‌خوای


     
    
        // مرحله ۳: به‌روزرسانی اطلاعات پرداخت
        $query = "UPDATE customer SET payment_method ='پرداخت با دستگاه کارتخوان', total_price = $Q   WHERE customer_code = {$id}";
        $send_query = mysqli_query($connection, $query);

        // مرحله ۴: برگشت به پنل
        header('Location:Cashier_Panel.php');
        exit;
    } else {
        echo "اطلاعات مشتری پیدا نشد.";
    }
}


if (isset($_GET['submit2'])) {
    $id = (int) $_GET['id'];

    // مرحله ۱: گرفتن اطلاعات مشتری
    $result = mysqli_query($connection, "SELECT total_price, discount FROM customer WHERE customer_code = {$id}");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

  

        // مرحله ۲: محاسبه مبلغ نهایی با اعمال تخفیف
        $Q = (float)$row['total_price'] - (float)$row['discount'];
        $Q = round($Q); // اگر عدد صحیح می‌خوای

        // مرحله ۳: به‌روزرسانی اطلاعات پرداخت
        $query = "UPDATE customer SET payment_method ='پرداخت نقدی', total_price = $Q  WHERE customer_code = {$id}";
        $send_query = mysqli_query($connection, $query);

        // مرحله ۴: برگشت به پنل
        header('Location:Cashier_Panel.php');
        exit;
    } else {
        echo "اطلاعات مشتری پیدا نشد.";
    }
}

?>



<?php







// اتصال به پایگاه داده MySQL با نام database = restaurant_db
$conn = new mysqli("localhost", "root", "", "sofrehpardaz");

// بررسی اینکه آیا اتصال موفق بوده یا نه
if ($conn->connect_error) {
    // اگر اتصال مشکل داشت، اجرای اسکریپت متوقف می‌شود و پیام خطا نمایش داده می‌شود
    die("Connection failed: " . $conn->connect_error);
}

// مرحله اول: گرفتن تمام سفارشات ثبت‌شده از جدول invoice
$sql = "SELECT * FROM `invoice` WHERE customer_code = {$id}";
$result = $conn->query($sql);

// بررسی اینکه آیا سفارش وجود دارد یا نه
if ($result->num_rows > 0) {

    // پیمایش بین هر سفارش ثبت‌شده
    while ($invoiceRow = $result->fetch_assoc()) {
        // گرفتن نام غذا و تعداد سفارش آن
        $foodName = $invoiceRow['food_name'];
        $quantity = $invoiceRow['quantity'];

        // مرحله دوم: پیدا کردن مواد اولیه لازم برای این غذا از جدول menu
        $menuSql = "SELECT requirements FROM menu WHERE food_name = ?";
        $stmt = $conn->prepare($menuSql);                     // آماده‌سازی کوئری
        $stmt->bind_param("s", $foodName);                    // جایگذاری مقدار food_name در کوئری
        $stmt->execute();                                     // اجرای کوئری
        $stmt->bind_result($requirements);                    // گرفتن خروجی ستون requirements
        $stmt->fetch();                                       // واکشی داده
        $stmt->close();                                       // بستن statement برای آزاد کردن منابع

        // مرحله سوم: تجزیه مواد لازم که با | جدا شدن (مثلاً: "نان:2|نوشابه:1")
        $items = explode('|', $requirements);

        // مرحله چهارم: پردازش هر ماده به‌صورت جداگانه
        foreach ($items as $item) {
            // جدا کردن نام ماده و مقدار مصرفی آن از روی ":" (مثلاً: "نان:2")
            list($itemName, $amountPerFood) = explode(':', $item);

            // محاسبه مقدار کل مصرف‌شده با ضرب مقدار در تعداد سفارش
            $totalAmount = intval($amountPerFood) * intval($quantity);

            // مرحله پنجم: به‌روزرسانی مقدار item_exit در جدول goods
            $updateSql = "UPDATE goods SET item_exit = item_exit + ? WHERE item_name = ?";
            $updateStmt = $conn->prepare($updateSql);         // آماده‌سازی کوئری آپدیت
            $updateStmt->bind_param("is", $totalAmount, $itemName);  // جایگذاری مقادیر
            $updateStmt->execute();                           // اجرای کوئری آپدیت
            $updateStmt->close();                             // بستن statement
        }
    }
} else {
    // اگر هیچ سفارشی در invoice نبود
    echo "هیچ سفارشی پیدا نشد.";
}

// بستن اتصال به دیتابیس در پایان
$conn->close();


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
  <!--<a href="#" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>--> 

        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['name'];?> </span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: صندوق</span>
        </button>

       <!-- <a href="../logout_POS.php"> <button class="btn btn-outline-danger btn-sm">خروج</button></a>--> 
      </div>
    </div>
  </nav>

  <!-- محتوای اصلی -->
  <div class="container panel-actions text-center mt-5 pt-5">
    <br>
    <h2 class="mb-4">پرداخت هزینه سفارش</h2>
       <h5 class="mb-4">هزینه جهت پرداخت :</h5>
       <h5 class="mb-4">
<?php 
if (isset($query['total_price']) && isset($query['discount'])) {
    echo number_format((float)$query['total_price'] - (float)$query['discount'], 0);
} else {
    echo "مقدار نامعتبر";
}
?>
</h5>
    <br>

    <div class="btn-container">

    <a  class="btn btn-success btn-large" href="Make_Payment.php?id=<?php echo $id ?>&submit1=1">
پرداخت با دستگاه کارتخوان
  </a>

      <a  class="btn btn-primary btn-large" href="Make_Payment.php?id=<?php echo $id ?>&submit2=1">
        پرداخت نقدی
 </a>
  
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
  </script>

</body>
</html>


<?php  

mysqli_close($connection);

?>



<?php } else{

  header('Location:../POS_login.php');

  }?>


