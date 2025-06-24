<?php
session_start();
if (isset($_SESSION['POS_login']) && $_SESSION['POS_login']) {

$host = "localhost";
$user = "root";
$password = "";
$dbname = "sofrehpardaz";
$connection = new mysqli($host, $user, $password, $dbname);
if ($connection->connect_error) {
    die("MySQL Connection Error: " . $connection->connect_error);
}

// بررسی AJAX برای سفارش جدید
if (isset($_GET['check_orders'])) {
    $result = mysqli_query($connection, "SELECT COUNT(*) as total FROM orders WHERE approval = 1");
    $data = mysqli_fetch_assoc($result);
    echo $data['total'];
    exit;
}

$query_restaurant_name = "SELECT * FROM `system` WHERE id = 1";
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$send_name = mysqli_fetch_assoc($send_restaurant_name);

if (isset($_POST["submit"])) {
    $send1 = $_POST['form1'] ?? null;
    $send2 = $_POST['form2'] ?? null;
    $send3 = $_POST['form3'] ?? null;
    $send4 = $_POST['form4'] ?? null;

    $send1 = mysqli_real_escape_string($connection, $send1);
    $send2 = mysqli_real_escape_string($connection, $send2);
    $send3 = mysqli_real_escape_string($connection, $send3);
    $send4 = mysqli_real_escape_string($connection, $send4);

    $query_approval = "UPDATE orders SET approval = 0  WHERE table_number = $send1";
    mysqli_query($connection, $query_approval);

    $query_customer_code = "SELECT customer_code FROM customer ORDER BY customer_code DESC LIMIT 1";
    $send_customer_code = mysqli_query($connection, $query_customer_code);
    $orders_customer_code = mysqli_fetch_assoc($send_customer_code);
    $invoice_number = $orders_customer_code ? $orders_customer_code['customer_code'] + 1 : 1;

    $s_name = $_SESSION['name'];
    $date = date("H:i");

    function gregorian_to_jalali($gy, $gm, $gd) {
        $g_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];
        $j_days_in_month = [31,31,31,31,31,31,30,30,30,30,30,29];
        $gy2 = $gy - 1600;
        $gm2 = $gm - 1;
        $gd2 = $gd - 1;
        $g_day_no = 365*$gy2 + (int)(($gy2+3)/4) - (int)(($gy2+99)/100) + (int)(($gy2+399)/400);
        for ($i=0; $i < $gm2; ++$i) $g_day_no += $g_days_in_month[$i];
        if ($gm2>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))) $g_day_no++;
        $g_day_no += $gd2;
        $j_day_no = $g_day_no - 79;
        $j_np = (int)($j_day_no / 12053);
        $j_day_no %= 12053;
        $jy = 979 + 33 * $j_np + 4 * (int)($j_day_no / 1461);
        $j_day_no %= 1461;
        if ($j_day_no >= 366) {
            $jy += (int)(($j_day_no - 366) / 365);
            $j_day_no = ($j_day_no - 366) % 365;
        }
        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) $j_day_no -= $j_days_in_month[$i];
        $jm = $i + 1;
        $jd = $j_day_no + 1;
        return [$jy, $jm, $jd];
    }

    list($year, $month, $day) = gregorian_to_jalali(date('Y'), date('m'), date('d'));
    $echo = "$year/$month/$day";

    $query_customer = "INSERT INTO `customer` (invoice_number, cashier_name, customer_phone_number, table_number, time, date, total_price, discount)
                    VALUES ('$invoice_number', '$s_name', '$send3', '$send1', '$date', '$echo', '$send2', '$send4')";
    mysqli_query($connection, $query_customer);

    $query_orders = "SELECT * FROM `orders` WHERE table_number = '$send1'";
    $send_orders_name = mysqli_query($connection, $query_orders);

    if ($send_orders_name && mysqli_num_rows($send_orders_name) > 0) {
        $orders_name = mysqli_fetch_assoc($send_orders_name);
        $list_of_orders = $orders_name['list_of_orders'];
        $orders = explode("|", $list_of_orders);

        foreach ($orders as $order) {
            list($food_name, $quantity) = explode(":", $order);
            $food_name = mysqli_real_escape_string($connection, trim($food_name));
            $quantity = (int)trim($quantity);

            $query_insert_invoice = "INSERT INTO invoice (invoice_number, food_name, quantity , customer_code)
                                    VALUES ('$invoice_number', '$food_name', '$quantity' , '$invoice_number')";
            mysqli_query($connection, $query_insert_invoice);
        }

$query_customer_n = "SELECT customer_code FROM customer ORDER BY customer_code DESC LIMIT 1"; 
$send_customer_n = mysqli_query($connection, $query_customer_n);
$orders_customer_n = mysqli_fetch_assoc($send_customer_n);

if ($orders_customer_n && isset($orders_customer_n['customer_code'])) {
    $customer_code = $orders_customer_n['customer_code'];
    header("Location: Make_Payment.php?id=" . urlencode($customer_code));
    exit();
}
    } else {
        $message = "❌ سفارش یافت نشد برای این میز.";
    }
}

$query_table = "SELECT * FROM `orders`";
$send_query_table = mysqli_query($connection, $query_table);
$count_orders_query = mysqli_query($connection, "SELECT COUNT(*) as total FROM orders WHERE approval = 1");
$count_orders = mysqli_fetch_assoc($count_orders_query)['total'];

if (isset($_GET['off'])) {
    $id = (int) $_GET['off'];
    $query = "DELETE FROM `orders` WHERE id = {$id}";
    mysqli_query($connection, $query);
    header('Location:Cashier_Panel.php');
    exit;
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

    body {
            background-color: #f5f5f5;
            font-family: Vazir, sans-serif;
            margin: 0; padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
        .btn-order {
            font-size: 1.8rem;
            padding: 1rem 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            width: 100%;
            max-width: 300px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        table {
            background: #fff;
            text-align: center;
        }
        th, td {
            vertical-align: middle !important;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 0.25rem 0.5rem;
            font-size: 1rem;
            box-sizing: border-box;
            resize: vertical;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        textarea {
            min-height: 50px;
        }
        .btn-sm-custom {
            padding: 0.25rem 0.6rem;
            font-size: 0.85rem;
        }
        .btn-group {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: nowrap;
        }
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
        <!--<a href="" class="btn btn-outline-primary btn-sm">داشبورد</a>-->
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
  <a class="btn btn-success btn-order" href="Add_Order.php">
    + ثبت سفارش
    </a>
    <div class="table-responsive">
        <?php if (isset($message)) { ?>
            <div class="alert alert-info text-center" role="alert">
                <?php echo $message; ?>
            </div>
        <?php } ?>
        <form action="Cashier_Panel.php" method="post">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>شماره میز</th>
                        <th>هزینه نهایی (ریال)</th>
                        <th>شماره تماس</th>
                        <th>تخفیف‌ها (ریال)</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($send_query_table && mysqli_num_rows($send_query_table) > 0) {
                        while ($query = mysqli_fetch_assoc($send_query_table)) {
                            if ($query['approval'] == "1") {
                                ?>
                                <tr>
                                    <td><input type="text" class="form-control text-center" name="form1" value="<?php echo htmlspecialchars($query['table_number']); ?>" readonly ></td>
                                    <td><input type="text" class="form-control text-center" name="form2" value="<?php echo  htmlspecialchars($query['totalsum']); ?>" readonly></td>
                                    <td><input type="text" class="form-control text-center" name="form3" ></td>
                                    <td><input type="text" class="form-control text-center" name="form4"></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-success btn-sm btn-sm-custom" type="submit" name="submit">تایید</button>
                                            <a class="btn btn-primary btn-sm btn-sm-custom" href="Order.php?id=<?php echo htmlspecialchars($query['id']); ?>">مشاهده سفارش</a>
                                            <a class="btn btn-danger btn-sm btn-sm-custom" href="Cashier_Panel.php?off=<?php echo htmlspecialchars($query['id']); ?>" onclick="return confirm('⚠️ آیا مطمئنی لغو کنی؟')">لغو</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    } else {
                        echo '<tr><td colspan="5">هیچ سفارشی برای نمایش وجود ندارد.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </form>
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
    // ساعت و تاریخ
    function updateClock() {
        const now = new Date();
        const date = now.toLocaleDateString('fa-IR');
        const time = now.toLocaleTimeString('fa-IR');
        document.getElementById("clock").textContent = `${date} - ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // بررسی سفارش جدید هر 5 ثانیه
    let lastOrderCount = <?php echo $count_orders; ?>;

    function checkForNewOrders() {
        fetch(window.location.href + '?check_orders=1')
            .then(response => response.text())
            .then(count => {
                if (parseInt(count) > lastOrderCount) {
                    location.reload();
                }
            });
    }

    setInterval(checkForNewOrders, 5000);
</script>

</body>
</html>

<?php

mysqli_close($connection);
} else {
    header('Location:../POS_login.php');
    exit();
}
?>


