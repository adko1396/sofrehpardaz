<?php session_start(); if(isset($_SESSION['manager_login'] ) &&  $_SESSION['manager_login'] ){?>


<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   <title>سفره‌پرداز</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
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
    .management-btn {
      font-size: 1.2rem;
      padding: 1.5rem 1rem;
      width: 100%;
      height: 100%;
      color: #fff !important; /* رنگ متن سفید */
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-1">
 <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub"><img  src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo"></a>
        <h1 class="restaurant-brand"> سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران :  <?php echo $_SESSION['restaurant_name'];?> </h1>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="#" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
          <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
          <i class="bi bi-person"></i><span> <?php echo $_SESSION['manager_name'];?> </span> |
          <i class="bi bi-clock"></i> <span id="clock"></span> |
          <i class="bi bi-briefcase-fill"></i> <span>نقش: مدیر</span>
        </button>
      <a href="../logout_manager.php">  <button class="btn btn-outline-danger btn-sm">خروج</button></a>
      </div>
    </div>
  </nav>

  <div class="container content-box text-center">
    <h3 class="mb-4">پنل مدیریتی</h3>
    <div class="row g-4">
      <div class="col-md-4">
        <a href="Staff_Management.php">
        <button class="btn management-btn text-white" style="background-color:#28a745;">
          <i class="bi bi-people-fill"></i> مدیریت کارکنان
        </button>
        </a>
      </div>
      <div class="col-md-4">
        <a href="Menu_Management_Panel.php">
        <button class="btn management-btn text-white" style="background-color:#ff6600;">
          <i class="bi bi-list-ul"></i> مدیریت منو
        </button>
        </a>
      </div>
      <div class="col-md-4">
        <a href="Table_Management.php">
        <button class="btn management-btn text-white" style="background-color:#6f42c1;">
          <i class="bi bi-grid-1x2-fill"></i> مدیریت میزها
        </button>
        </a>
      </div>
      <div class="col-md-4">
        <a href="Warehouse_Management.php">
        <button class="btn management-btn text-white" style="background-color:#20c997;">
          <i class="bi bi-box-seam"></i> مدیریت انبار
        </button>
        </a>
      </div>
      <div class="col-md-4">
        <a href="invoices.php">
        <button class="btn management-btn text-white" style="background-color:#17a2b8;">
          <i class="bi bi-receipt"></i> نمایش فاکتورهای صادرشده
        </button>
        </a>
      </div>
      <div class="col-md-4">
        <a href="Sales.php">
        <button class="btn management-btn text-white" style="background-color:#fd7e14;">
          <i class="bi bi-bar-chart-line-fill"></i> گزارش‌گیری
        </button>
        </a>
      </div>
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


<?php } else{

  header('Location:../logout_manager.php');

  }?>