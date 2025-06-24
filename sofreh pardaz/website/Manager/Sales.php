

<?php 
session_start(); 

if (isset($_SESSION['manager_login']) && $_SESSION['manager_login']) { 

    // تنظیمات اتصال به دیتابیس
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "sofrehpardaz";
    $connection = new mysqli($host, $user, $password, $dbname);

    if ($connection->connect_errno) {
        die("MySQL connection failed: " . $connection->connect_error);
    }

    // تنظیم charset برای پشتیبانی UTF-8
    $connection->set_charset("utf8");

    // تابع تبدیل تاریخ میلادی به شمسی
    function gregorianToJalali($gy, $gm, $gd)
    {
        $g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
        $jy = ($gy <= 1600) ? 0 : 979;
        $gy -= ($gy <= 1600) ? 621 : 1600;
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = (365 * $gy) + (int)(($gy2 + 3) / 4) - (int)(($gy2 + 99) / 100) + (int)(($gy2 + 399) / 400) - 80 + $gd + $g_d_m[$gm -1];
        $jy += 33 * (int)($days / 12053);
        $days %= 12053;
        $jy += 4 * (int)($days / 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
        $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
        return [$jy, $jm, $jd];
    }

    // تابع تبدیل اعداد لاتین به فارسی
    function convertToPersianNumbers($string)
    {
        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return str_replace($en, $fa, $string);
    }

    // تابع تبدیل ساعت به فرمت 12 ساعته با قالب زیبا
    function formatTime($time) {
        $time = trim($time);
        if (empty($time)) return '';
        
        // اگر ساعت به فرمت 24 ساعته است (مثلاً 14:04)
        if (strpos($time, ':') !== false) {
            $parts = explode(':', $time);
            $hour = (int)$parts[0];
            $minute = $parts[1];
            
            $period = ($hour < 12) ? 'ق.ظ' : 'ب.ظ';
            $hour12 = ($hour > 12) ? $hour - 12 : $hour;
            $hour12 = ($hour12 == 0) ? 12 : $hour12;
            
            return convertToPersianNumbers(sprintf('%02d:%02d %s', $hour12, $minute, $period));
        }
        
        return convertToPersianNumbers($time);
    }

    // گرفتن تاریخ امروز میلادی
    $gDate = getdate();
    list($jy, $jm, $jd) = gregorianToJalali($gDate['year'], $gDate['mon'], $gDate['mday']);

    // ایجاد تاریخ امروز به دو فرمت مختلف
    $today_formatted = "$jy/".str_pad($jm, 2, '0', STR_PAD_LEFT)."/".str_pad($jd, 2, '0', STR_PAD_LEFT);
    $today_unformatted = "$jy/$jm/$jd";
    $today_display = convertToPersianNumbers($today_formatted);

    // کوئری دریافت گزارش فروش روزانه - استفاده از تاریخ میلادی برای دقت بیشتر
    $query_sales_today = "
        SELECT invoice_number, total_price, date, time, payment_method 
        FROM customer 
        WHERE DATE(date_SQL) = CURDATE()
        ORDER BY time DESC, invoice_number DESC;
    ";

    $result_sales_today = $connection->query($query_sales_today);
    $total_today = 0;
    $today_orders = [];
    
    if ($result_sales_today && $result_sales_today->num_rows > 0) {
        while($row = $result_sales_today->fetch_assoc()) {
            $total_today += (int)$row['total_price'];
            $today_orders[] = $row;
        }
    }

    // کوئری برای گزارش ماهانه
    $current_month = "$jy/".str_pad($jm, 2, '0', STR_PAD_LEFT);
    $query_sales_month = "
        SELECT 
            DATE_FORMAT(date_SQL, '%Y/%m/%d') as date_sql,
            date,
            SUM(total_price) as daily_total,
            COUNT(invoice_number) as order_count
        FROM customer
        WHERE DATE_FORMAT(date_SQL, '%Y/%m') = '{$gDate['year']}/".str_pad($gDate['mon'], 2, '0', STR_PAD_LEFT)."'
        GROUP BY date_SQL, date
        ORDER BY date_SQL;
    ";

    $result_sales_month = $connection->query($query_sales_month);
    $monthly_data = [];
    $monthly_labels = [];
    $monthly_total = 0;
    
    if ($result_sales_month && $result_sales_month->num_rows > 0) {
        while($row = $result_sales_month->fetch_assoc()) {
            $monthly_data[] = $row['daily_total'];
            $monthly_labels[] = $row['date'];
            $monthly_total += $row['daily_total'];
        }
    }

    // کوئری برای گزارش سالانه
    $current_year = $jy;
    $query_sales_year = "
        SELECT 
            DATE_FORMAT(date_SQL, '%Y/%m') as month_sql,
            SUBSTRING_INDEX(date, '/', 2) as month_jalali,
            SUM(total_price) as monthly_total,
            COUNT(invoice_number) as order_count
        FROM customer
        WHERE DATE_FORMAT(date_SQL, '%Y') = '{$gDate['year']}'
        GROUP BY DATE_FORMAT(date_SQL, '%Y/%m'), SUBSTRING_INDEX(date, '/', 2)
        ORDER BY month_sql;
    ";

    $result_sales_year = $connection->query($query_sales_year);
    $yearly_data = [];
    $yearly_labels = [];
    $yearly_total = 0;
    
    if ($result_sales_year && $result_sales_year->num_rows > 0) {
        while($row = $result_sales_year->fetch_assoc()) {
            $yearly_data[] = $row['monthly_total'];
            $yearly_labels[] = $row['month_jalali'];
            $yearly_total += $row['monthly_total'];
        }
    }

    // کوئری برای ساعات پرفروش
    $query_sales_hours = "
        SELECT 
            HOUR(time) as hour,
            SUM(total_price) as hourly_total,
            COUNT(invoice_number) as order_count
        FROM customer
        WHERE DATE(date_SQL) = CURDATE()
        GROUP BY HOUR(time)
        ORDER BY hour;
    ";

    $result_sales_hours = $connection->query($query_sales_hours);
    $hourly_data = array_fill(0, 24, 0);
    $hourly_order_count = array_fill(0, 24, 0);
    
    if ($result_sales_hours && $result_sales_hours->num_rows > 0) {
        while($row = $result_sales_hours->fetch_assoc()) {
            $hour = (int)$row['hour'];
            $hourly_data[$hour] = $row['hourly_total'];
            $hourly_order_count[$hour] = $row['order_count'];
        }
    }
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>سفره‌پرداز</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <style>
    body {
      background-color: #f5f5f5;
      font-family: Vazir, sans-serif;
      margin: 0; padding: 0;
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
    .section-box {
      background: white;
      border-radius: 8px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    h5 {
      margin-bottom: 1rem;
      border-bottom: 2px solid #ddd;
      padding-bottom: .5rem;
    }
    table {
      font-size: 0.9rem;
    }
    table thead {
      background-color: #f8f9fa;
      font-weight: 600;
    }
    table tbody tr:hover {
      background-color: #ffe;
    }
    .chart-container {
      width: 100%;
      max-width: 500px;
      margin: 1rem auto;
    }
    .payment-cash { color: #28a745; }
    .payment-card { color: #007bff; }
    .payment-online { color: #6f42c1; }
    .datetime-col {
      white-space: nowrap;
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

  <div class="container" style="margin-top: 7rem;">
    <div class="section-box">
      <h5>گزارش‌ها</h5>

      <div class="mb-4">
        <h6>فروش امروز (<?php echo $today_display; ?>): <span class="text-success"><?php 
          echo convertToPersianNumbers(number_format($total_today)) . " ریال";
        ?></span></h6>
        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th>شماره فاکتور</th>
              <th>مبلغ</th>
              <th>تاریخ و زمان</th>
              <th>روش پرداخت</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if (!empty($today_orders)) {
                foreach ($today_orders as $row) { 
                    $payment_class = '';
                    if ($row['payment_method'] === 'نقدی') $payment_class = 'payment-cash';
                    elseif ($row['payment_method'] === 'کارت') $payment_class = 'payment-card';
                    elseif ($row['payment_method'] === 'آنلاین') $payment_class = 'payment-online';
                    ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                    <td><?php echo convertToPersianNumbers(number_format($row['total_price'])); ?> ریال</td>
                    <td class="datetime-col">
                      <?php echo convertToPersianNumbers($row['date']); ?> - 
                      <?php echo formatTime($row['time']); ?>
                    </td>
                    <td class="<?php echo $payment_class; ?>"><?php echo htmlspecialchars($row['payment_method']); ?></td>
                  </tr>
            <?php }
            } else { ?>
              <tr><td colspan="4">هیچ داده‌ای برای امروز موجود نیست</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <hr>

      <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6>فروش ماهانه: <span class="text-primary"><?php echo convertToPersianNumbers(number_format($monthly_total)); ?> ریال</span></h6>
            <!-- <input type="text" id="monthPicker" class="form-control" style="max-width: 180px;" value="<?php //echo $current_month; ?>">-->
        </div>
        <div class="chart-box">
          <canvas id="monthlyChart"></canvas>
        </div>
        <table class="table table-bordered text-center mt-3">
          <thead>
            <tr><th>تاریخ</th><th>تعداد سفارش</th><th>مبلغ کل</th></tr>
          </thead>
          <tbody>
            <?php 
            if ($result_sales_month && $result_sales_month->num_rows > 0) {
                $result_sales_month->data_seek(0);
                while ($row = $result_sales_month->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo convertToPersianNumbers($row['date']); ?></td>
                    <td><?php echo convertToPersianNumbers(number_format($row['order_count'])); ?></td>
                    <td><?php echo convertToPersianNumbers(number_format($row['daily_total'])); ?> ریال</td>
                  </tr>
            <?php }
            } else { ?>
              <tr><td colspan="3">هیچ داده‌ای برای این ماه موجود نیست</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <hr>

      <div class="mb-4">
        <h6>فروش سالانه (<?php echo convertToPersianNumbers($current_year); ?>): <span class="text-success"><?php echo convertToPersianNumbers(number_format($yearly_total)); ?> ریال</span></h6>
        <div class="chart-box">
          <canvas id="yearlyChart"></canvas>
        </div>
        <table class="table table-bordered text-center mt-3">
          <thead>
            <tr><th>ماه</th><th>تعداد سفارش</th><th>مبلغ کل</th></tr>
          </thead>
          <tbody>
            <?php 
            if ($result_sales_year && $result_sales_year->num_rows > 0) {
                $result_sales_year->data_seek(0);
                while ($row = $result_sales_year->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo convertToPersianNumbers($row['month_jalali']); ?></td>
                    <td><?php echo convertToPersianNumbers(number_format($row['order_count'])); ?></td>
                    <td><?php echo convertToPersianNumbers(number_format($row['monthly_total'])); ?> ریال</td>
                  </tr>
            <?php }
            } else { ?>
              <tr><td colspan="3">هیچ داده‌ای برای این سال موجود نیست</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <hr>

      <div>
        <h6>ساعات پرفروش امروز</h6>
        <div class="chart-box">
          <canvas id="hourChart"></canvas>
        </div>
        <table class="table table-bordered text-center mt-3">
          <thead>
            <tr><th>ساعت</th><th>تعداد سفارش</th><th>مبلغ کل</th></tr>
          </thead>
          <tbody>
            <?php 
            for ($hour = 0; $hour < 24; $hour++) {
                if ($hourly_data[$hour] > 0) { ?>
                  <tr>
                    <td><?php echo convertToPersianNumbers($hour); ?>:00 - <?php echo convertToPersianNumbers($hour+1); ?>:00</td>
                    <td><?php echo convertToPersianNumbers($hourly_order_count[$hour]); ?></td>
                    <td><?php echo convertToPersianNumbers(number_format($hourly_data[$hour])); ?> ریال</td>
                  </tr>
            <?php }
            } 
            if (array_sum($hourly_data) === 0) { ?>
              <tr><td colspan="3">هیچ داده‌ای برای ساعات امروز موجود نیست</td></tr>
            <?php } ?>
          </tbody>
        </table>
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

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

  <script>
   
  </script>


  <script>
    // نمایش ساعت به صورت زنده
     function updateClock() {
      const now = new Date();
      const time = now.toLocaleTimeString('fa-IR');
      const date = now.toLocaleDateString('fa-IR');
      document.getElementById("clock").textContent = `${date} - ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // داده‌های نمودار ماهانه از PHP
    const monthlyChartLabels = <?php echo json_encode($monthly_labels); ?>;
    const monthlyChartData = <?php echo json_encode($monthly_data); ?>;

    const ctxMonth = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(ctxMonth, {
      type: 'bar',
      data: {
        labels: monthlyChartLabels,
        datasets: [{
          label: 'مبلغ فروش (ریال)',
          data: monthlyChartData,
          backgroundColor: '#ff6600'
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) { return value.toLocaleString() + ' ریال'; }
            }
          }
        }
      }
    });

    // داده‌های نمودار سالانه از PHP
    const yearlyChartLabels = <?php echo json_encode($yearly_labels); ?>;
    const yearlyChartData = <?php echo json_encode($yearly_data); ?>;

    const ctxYear = document.getElementById('yearlyChart').getContext('2d');
    const yearlyChart = new Chart(ctxYear, {
      type: 'line',
      data: {
        labels: yearlyChartLabels,
        datasets: [{
          label: 'مبلغ فروش (ریال)',
          data: yearlyChartData,
          borderColor: '#ff6600',
          fill: false,
          tension: 0.3
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: false,
            ticks: {
              callback: function(value) { return value.toLocaleString() + ' ریال'; }
            }
          }
        }
      }
    });

    // داده‌های ساعات پرفروش از PHP
    const hourChartLabels = [];
    for (let i = 0; i < 24; i++) {
        hourChartLabels.push(`${i}:00 - ${i+1}:00`);
    }
    const hourChartData = <?php echo json_encode($hourly_data); ?>;

    const ctxHour = document.getElementById('hourChart').getContext('2d');
    const hourChart = new Chart(ctxHour, {
      type: 'bar',
      data: {
        labels: hourChartLabels,
        datasets: [{
          label: 'مبلغ فروش (ریال)',
          data: hourChartData,
          backgroundColor: '#ff6600'
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) { return value.toLocaleString() + ' ریال'; }
            }
          }
        }
      }
    });

    // تنظیم تاریخ‌گیر ماهانه
    $("#monthPicker").persianDatepicker({
      format: 'YYYY/MM',
      viewMode: 'months',
      autoClose: true,
      initialValue: false,
      onSelect: function(unix) {
        const selectedDate = new persianDate(unix);
        const year = selectedDate.year();
        const month = selectedDate.month() + 1; // ماه در persianDate از 0 شروع می‌شود
        
        // ارسال درخواست AJAX برای دریافت داده‌های ماه جدید
        $.ajax({
          url: 'get_monthly_data.php',
          method: 'POST',
          data: {
            year: year,
            month: month
          },
          success: function(response) {
            const data = JSON.parse(response);
            
            // به‌روزرسانی نمودار
            monthlyChart.data.labels = data.labels;
            monthlyChart.data.datasets[0].data = data.data;
            monthlyChart.update();
            
            // به‌روزرسانی جدول (می‌توانید این بخش را نیز با AJAX کامل کنید)
            $('.monthly-total').text(data.total.toLocaleString() + ' ریال');
          }
        });
      }
    });
  </script>
</body>
</html>

<?php 
    $connection->close();
} else {
    header("Location: ../logout_manager.php");
    exit();
}
?>