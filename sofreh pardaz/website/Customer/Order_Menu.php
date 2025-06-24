

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
if ($id == null) {
  echo '<script>alert("سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید");</script>';
  echo '<h1 style="text-align: center; font-size: 300px; color: red; background-color:yellow ;">404</h1>';
  exit;
}


$query_a = "SELECT * FROM `orders`  WHERE table_number = $id"; 
$send_a = mysqli_query($connection, $query_a);
$send_aa = mysqli_fetch_assoc($send_a );


ini_set('display_errors', 0);   // خطاها نمایش داده نشوند
error_reporting(E_ALL);         // تمام خطاها گزارش شوند ولی نمایش داده نشوند

$value = null;

// شبیه‌سازی خطا: دسترسی به offset از null
if (is_null($value)) {
    $errorMsg = "Warning: Trying to access array offset on value of type null in Order_Menu.php on line 33";
    echo "<script>console.log(" . json_encode($errorMsg) . ");</script>";
}


if ($send_aa['table_number'] != $id ) {
  


if (isset($_POST['submit_order'])) {
  $table_number = (int) $_POST['table_number'];
  $list_of_orders = mysqli_real_escape_string($connection, $_POST['list_of_orders']);
  $approval = 0; // سفارش تأیید نشده است

  $insert_query = "INSERT INTO orders (table_number, list_of_orders, approval) VALUES ('$table_number', '$list_of_orders', '$approval')";

  if (mysqli_query($connection, $insert_query)) {
    echo "<script>alert('✅ سفارش با موفقیت ثبت شد.'); window.location.href = window.location.href;</script>";
    header('Location: Order_Summary.php?id=' . $id);
    exit;
  } else {
    echo "<script>alert('❌ خطا در ثبت سفارش: ".mysqli_error($connection)."');</script>";
  }
}

$query_restaurant_name = "SELECT * FROM `system` WHERE id = 1"; 
$send_restaurant_name = mysqli_query($connection, $query_restaurant_name);
$send_name = mysqli_fetch_assoc($send_restaurant_name);

$query_table = "SELECT category, food_name, price, food_photo, requirements FROM menu WHERE active = 1 ORDER BY category";
$send_query_table = mysqli_query($connection, $query_table);

$categories = [];
while ($row = mysqli_fetch_assoc($send_query_table)) {
    $categories[$row['category']][] = $row;
} 
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>سفره‌پرداز</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    body {
      background-color: #f9f9f9;
      font-family: 'Vazir', sans-serif;
    }
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
    .section-title {
      font-size: 1.4rem;
      font-weight: 600;
      margin-top: 40px;
      margin-bottom: 20px;
      color: #444;
      border-bottom: 2px solid #ddd;
      padding-bottom: 6px;
    }
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
  </style>
</head>
<body>
<div class="container py-4">
  <div class="header-box">
    <h1>خوش آمدید به رستوران <?php echo $send_name['restaurant_name']?> | میز شماره <?php echo $_GET['id']?></h1>
  </div>

  <?php foreach ($categories as $category => $items): ?>
    <div class="section-title"><?php echo htmlspecialchars($category); ?></div>
    <div class="row g-4">
      <?php foreach ($items as $item): ?>
        <div class="col-md-4">
          <div class="card food-card">
            <img src="<?php echo htmlspecialchars($item['food_photo']); ?>" class="food-img" alt="تصویر غذا">
            <div class="card-body text-center">
              <h5><?php echo htmlspecialchars($item['food_name']); ?></h5>
              <hr>
              <div class="price"><?php echo number_format( htmlspecialchars($item['price'])); ?> ریال</div>
              <hr>
              <div class="form-check form-switch d-flex align-items-center justify-content-center gap-2 mb-2">
                <input class="form-check-input food-switch" type="checkbox" role="switch">
                <label class="form-check-label">افزودن به سفارش</label>
              </div>
              <br>
              <div class="quantity-box" style="display:none; justify-content:center; align-items:center; gap:10px;">
                <button class="btn-qty minus">-</button>
                <div class="qty-num">1</div>
                <button class="btn-qty plus">+</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>

  <form method="POST" id="orderForm">
    <input type="hidden" name="list_of_orders" id="list_of_orders">
    <input type="hidden" name="table_number" value="<?php echo $_GET['id']; ?>">
    <div class="text-center mt-4">
      <button type="submit" name="submit_order" class="btn btn-success btn-lg">ثبت سفارش</button>
    </div>
  </form>
</div>


  <!-- فوتر سفارشی -->
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
  document.querySelectorAll('.food-card').forEach(card => {
    const switchInput = card.querySelector('.food-switch');
    const qtyBox = card.querySelector('.quantity-box');
    const plus = card.querySelector('.plus');
    const minus = card.querySelector('.minus');
    const qtyNum = card.querySelector('.qty-num');
    let count = 1;

    switchInput.addEventListener('change', () => {
      if (switchInput.checked) {
        qtyBox.style.display = 'flex';
      } else {
        qtyBox.style.display = 'none';
        count = 1;
        qtyNum.textContent = count;
      }
    });

    plus.addEventListener('click', () => {
      count++;
      qtyNum.textContent = count;
    });

    minus.addEventListener('click', () => {
      if (count > 1) {
        count--;
        qtyNum.textContent = count;
      }
    });
  });

  document.getElementById("orderForm").addEventListener("submit", function(e) {
    const orders = [];
    document.querySelectorAll('.food-card').forEach(card => {
      const switchInput = card.querySelector('.food-switch');
      if (switchInput.checked) {
        const foodName = card.querySelector("h5").innerText.trim();
        const qty = card.querySelector(".qty-num").innerText.trim();
        orders.push(`${foodName}:${qty}`);
      }
    });

    if (orders.length === 0) {
      e.preventDefault();
      alert("لطفاً حداقل یک غذا انتخاب کنید.");
      return;
    }

    const result = orders.join('|');
    document.getElementById("list_of_orders").value = result;
  });
</script>
</body>
</script>
</html>
<?php }else{
   echo '<script>alert("❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌");</script>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
 echo '<h1 style="text-align: center; font-size: 50px; color:#ddd; background-color:cadetblue ;">❌ سفارش شما قبلاً ثبت شده است، لطفاً به صندوق مراجعه کنید ❌</h1>';
}

 mysqli_close($connection);
 
 ?>
