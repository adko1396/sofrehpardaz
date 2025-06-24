<?php
session_start();
if (!isset($_SESSION['manager_login']) || !$_SESSION['manager_login']) {
    header('Location: ../logout_manager.php');
    exit;
}

// اتصال به دیتابیس
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sofrehpardaz";

$connection = new mysqli($host, $user, $password, $dbname);
if ($connection->connect_errno) {
    die("خطا در اتصال به دیتابیس: " . $connection->connect_error);
}

$message = "";

// دریافت دسته‌بندی‌ها
$query_table = "SELECT * FROM category";
$send_query_table = $connection->query($query_table);

// دریافت مواد اولیه (goods)
$query_table_category = "SELECT * FROM goods";
$send_query_table_category = $connection->query($query_table_category);

// پردازش فرم ثبت غذا
if (isset($_POST['submit'])) {
    $category = $connection->real_escape_string($_POST['category']);
    $food_name = $connection->real_escape_string($_POST['food_name']);
    $price = (int)$_POST['price'];

    // آپلود تصویر
    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../Manager/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $image_name = basename($_FILES["image"]["name"]);
        // نام امن برای فایل
        $image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $image_name);
        $image_path = $target_dir . $image_name;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            $message = "❌ خطا در آپلود تصویر.";
        }
    }

    if ($message == "") {
        // ساخت رشته مواد اولیه به شکل "گوشت:200|پیاز:1|..."
        $ingredients_string = "";
        if (!empty($_POST['items'])) {
            $parts = [];
            foreach ($_POST['items'] as $item_id => $item) {
                if (isset($item['used']) && $item['used'] == "1" && !empty(trim($item['amount']))) {
                    $name = $connection->real_escape_string($item['name']);
                    $amount = $connection->real_escape_string($item['amount']);
                    $parts[] = "{$name}:{$amount}";
                }
            }
            $ingredients_string = implode("|", $parts);
        }

        // درج در جدول foods (جدول و ستون‌ها باید مطابق دیتابیس شما باشد)
        $query_food = "INSERT INTO menu (category, food_name, price , food_photo, requirements , active) VALUES ('$category', '$food_name', '$price', '$image_path', '$ingredients_string' , '1')";
        if ($connection->query($query_food)) {
            $message = "✅ غذا با موفقیت ثبت شد.";
        } else {
            $message = "❌ خطا در ثبت غذا: " . $connection->error;
        }
    }
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
            border-bottom: 2px solid #ddd;
            padding-bottom: .5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<!-- ناوبری -->
<nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-1">
            <a href="https://github.com/sofrehpardaz" target="_blank" title="GitHub">
                <img src="../imag/sofreh pardaz farsi - 2.png" alt="لوگو" class="navbar-logo">
            </a>
            <h1 class="restaurant-brand">
                سیستم مدیریت رستوران ( سفره‌پرداز ) | رستوران : <?php echo $_SESSION['restaurant_name']; ?>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="Admin_Panel.php" class="btn btn-outline-secondary btn-sm">صفحه اصلی</a>
            <a href="Menu_Management_Panel.php" class="btn btn-outline-primary btn-sm">داشبورد</a>
            <button class="btn btn-orange-outline btn-sm d-flex align-items-center gap-2" disabled>
                <i class="bi bi-person"></i><span> <?php echo $_SESSION['manager_name']; ?> </span> |
                <i class="bi bi-clock"></i> <span id="clock"></span> |
                <i class="bi bi-briefcase-fill"></i> <span>نقش: مدیر</span>
            </button>
            <a href="../logout_manager.php"><button class="btn btn-outline-danger btn-sm">خروج</button></a>
        </div>
    </div>
</nav>

<!-- محتوا -->
<div class="container" style="margin-top: 7rem;">
    <div class="section-box">
        <h5>مدیریت منو</h5>

        <!-- فرم اطلاعات غذا -->
        <form class="row g-3 mb-4" enctype="multipart/form-data" method="post" action="Menu_Management.php">
            <div class="col-md-3">
                <label class="form-label">دسته‌بندی</label>
                <select class="form-select" name="category" required>
                    <option selected disabled>انتخاب کنید</option>
                    <?php while ($row = $send_query_table->fetch_assoc()) { ?>
                        <option value="<?php echo $row['category_name']; ?>"><?php echo $row['category_name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">نام غذا</label>
                <input type="text" class="form-control" placeholder="مثلاً چلوکباب" name="food_name" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">قیمت (ریال)</label>
                <input type="number" class="form-control" placeholder="مثلاً 500000" name="price" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">عکس غذا</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>

            <!-- نیازمندی‌ها -->
            <h6 class="mt-4 mb-3">نیازمندی‌ها</h6>
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                <tr>
                    <th>نام ماده اولیه</th>
                    <th>استفاده</th>
                    <th>مقدار مورد نیاز</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // بازخوانی مجدد مواد اولیه چون حلقه دسته‌بندی مصرف شده بود
                $send_query_table_category = $connection->query($query_table_category);
                while ($row = $send_query_table_category->fetch_assoc()) {
                    $item_id = $row['id'];
                    $item_name = $row['item_name'];
                    ?>
                    <tr>
                        <td>
                            <input type="text" class="form-control" value="<?php echo $item_name ?>" disabled>
                            <input type="hidden" name="items[<?php echo $item_id ?>][name]" value="<?php echo $item_name ?>">
                        </td>
                        <td>
                            <div class="form-check d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" name="items[<?php echo $item_id ?>][used]" value="1">
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="items[<?php echo $item_id ?>][amount]" placeholder="مثلاً 3 کیلوگرم - ليتر - عدد - بسته">
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="text-center">
                <button class="btn btn-primary px-5" name="submit" type="submit">ثبت</button>
            </div>
        </form>
    </div>
</div>

<!-- فوتر -->
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

<!-- ساعت -->
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

<!-- پیام موفقیت یا خطا -->
<?php if ($message != "") { ?>
<script>
    alert("<?php echo $message; ?>");
</script>
<?php } ?>

</body>
</html>

<?php
$connection->close();
?>
