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

  # حذف مشتری (اگر لازم است)
  if (isset($_GET['del'])) {
      $id = (int) $_GET['del'];
      $query = "DELETE FROM `customer` WHERE id = {$id}";
      mysqli_query($connection, $query);
      header('Location: search_invoice.php'); // نام فایل خود را اصلاح کنید
      exit;
  }

  # دریافت شماره تلفن جستجو شده
  $search_phone = "";
  if (isset($_GET['phone']) && !empty(trim($_GET['phone']))) {
      $search_phone = $connection->real_escape_string(trim($_GET['phone']));
      $query_table = "SELECT * FROM `customer` WHERE customer_phone_number LIKE '%$search_phone%'";
  } else {
      $query_table = "SELECT * FROM `customer`";
  }

  $send_query_table = mysqli_query($connection, $query_table);

  $query_restaurant_name = "SELECT * FROM `system` WHERE id = 1"; 
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$send_name = mysqli_fetch_assoc($send_restaurant_name);

?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>سفره‌پرداز</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <style>
    body { background-color: #f5f5f5; font-family: Vazir, sans-serif; margin: 0; padding: 0; min-height: 100vh; display: flex; flex-direction: column; }
    .navbar { background-color: #fff; border-bottom: 1px solid #ddd; }
    .restaurant-brand { font-weight: bold; font-size: 1.2rem; }
    .navbar-logo { height: 40px; margin-left: 10px; }
    main { flex-grow: 1; padding-bottom: 2rem; padding-top: 3rem; }
    footer { background-color: #fff; border-top: 1px solid #ddd; padding: 1.5rem 0; text-align: center; }
    .btn-orange-outline { background-color: transparent !important; color: #ff6600 !important; border: 2px solid #ff6600 !important; font-weight: bold; }
    .btn-radius { border-radius: 50px; min-width: 110px; }
    table { background: #fff; text-align: center; }
    th, td { vertical-align: middle !important; }
    form.search-form { background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; }
    .form-check-label { cursor: pointer; }
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
         
<a href="POS_Panel.php" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
        <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['name'];?> </span>  |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: صندوق</span>
        </button>

        <a href="../logout_POS.php"> <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>


  <main class="container mt-5 pt-5">
    <h4 class="text-center mb-4">فاکتور های صادر شده</h4>

    <!-- فرم جستجو -->
    <form class="search-form row g-3 align-items-center" method="GET" action="">
      <div class="col-md-7 d-flex gap-2">
        <label class="form-check-label" for="phone">شماره تماس :</label>
        <input type="text" name="phone" id="phone" class="form-control" placeholder="شماره تلفن..." value="<?php echo htmlspecialchars($search_phone); ?>" />
        <button type="submit" class="btn btn-primary btn-radius">جستجو</button>
      </div>
    </form>

    <!-- جدول فاکتورها -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>شماره مشتری</th>
            <th>کد مشتری</th>
            <th>تاریخ و ساعت</th>
            <th>قیمت کل (ریال)</th>
            <th>عملیات</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($send_query_table) > 0) {
            while($query = mysqli_fetch_assoc($send_query_table)) { ?>
              <tr>
                <td><?php echo htmlspecialchars($query['customer_phone_number']); ?></td>
                <td><?php echo htmlspecialchars($query['customer_code']); ?></td>
                <td><?php echo htmlspecialchars($query['date'] . " - " . $query['time']); ?></td>
                <td><?php echo number_format($query['total_price']); ?></td>
                <td>
                <a href="Invoice_Generation.php?customer_code=<?php echo $query['customer_code'] ?>">
                <button class="btn btn-sm btn-info btn-radius" >نمایش</button>
              </a>
              </td>
              </tr>
          <?php }
          } else { ?>
            <tr><td colspan="5" class="text-center">موردی یافت نشد</td></tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>

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

</body>
</html>

<?php 
mysqli_close($connection);


?>


<?php } else{

  header('Location:../POS_login.php');

  }?>