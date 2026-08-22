<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

date_default_timezone_set("Asia/Amman");

// عدد العملاء
$customersCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers")
)["total"];

// عدد الفواتير
$invoicesCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM invoices")
)["total"];

// عدد المستخدمين
$usersCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)["total"];

// الفواتير المدفوعة
$paidCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM invoices WHERE status='Paid'")
)["total"];

// الفواتير غير المدفوعة
$pendingCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM invoices WHERE status='Pending'")
)["total"];

// إجمالي الإيرادات
$income = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT SUM(total) AS income
        FROM invoice_items
    ")
);

$totalIncome = $income["income"] ?? 0;

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <title>لوحه التحكم</title>
    <meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

<div class="app">
    
            <!-- ================= Sidebar ================= -->

    <aside class="sidebar">

        <div class="logo">

            <img src="../assets/logo.svg" alt="شعارالعقرباوي">

            <div class="logo-text">

                <h2>العقرباوي</h2>

                <span>للنقل والشحن الدولي</span>

            </div>

        </div>

        <nav class="sidebar-menu">

    <a href="dashboard.php" class="menu-item active">
        <i data-lucide="house"></i>
        <span>الرئيسية</span>
    </a>

    <a href="invoice.php" class="menu-item">
        <i data-lucide="file-plus-2"></i>
        <span>إنشاء فاتورة</span>
    </a>

    <a href="savedInvoices.php" class="menu-item">
        <i data-lucide="files"></i>
        <span>الفواتير المحفوظة</span>
    </a>

    <a href="customers.php" class="menu-item">
        <i data-lucide="users"></i>
        <span>العملاء</span>
    </a>

    <a href="user.php" class="menu-item">
        <i data-lucide="user-cog"></i>
        <span>المستخدمون</span>
    </a>

</nav>

        <div class="logout">
    <a href="logout.php">
        <i data-lucide="log-out"></i>
        <span >تسجيل الخروج</span>
    </a>
</div>

    </aside>
    <!-- ================= Main ================= -->

<main class="main">

    <header class="topbar">

        <div class="topbar-right">

            <div class="date">

                <i data-lucide="calendar-days"></i>

                <span>
<?php

$days = [
    "Sunday" => "الأحد",
    "Monday" => "الاثنين",
    "Tuesday" => "الثلاثاء",
    "Wednesday" => "الأربعاء",
    "Thursday" => "الخميس",
    "Friday" => "الجمعة",
    "Saturday" => "السبت"
];

$months = [
    "January" => "يناير",
    "February" => "فبراير",
    "March" => "مارس",
    "April" => "أبريل",
    "May" => "مايو",
    "June" => "يونيو",
    "July" => "يوليو",
    "August" => "أغسطس",
    "September" => "سبتمبر",
    "October" => "أكتوبر",
    "November" => "نوفمبر",
    "December" => "ديسمبر"
];

echo $days[date("l")] . "، " . date("j") . " " . $months[date("F")] . " " . date("Y");

?>
</span>

            </div>

        </div>

        <div class="topbar-left">

           <div class="user">

    <strong><?php echo $_SESSION["full_name"]; ?></strong>

    <span><?php echo $_SESSION["role"]; ?></span>

</div>

            <div class="avatar">

                <i data-lucide="user"></i>

    </header>
    <section class="stats-grid">

<div class="stat-card">
    <h3>العملاء</h3>
    <span><?= $customersCount ?></span>
</div>

<div class="stat-card">
    <h3>الفواتير</h3>
    <span><?= $invoicesCount ?></span>
</div>

<div class="stat-card">
    <h3>المستخدمون</h3>
    <span><?= $usersCount ?></span>
</div>

<div class="stat-card">
    <h3>الفواتير المدفوعة</h3>
    <span><?= $paidCount ?></span>
</div>

<div class="stat-card">
    <h3>الفواتير غير المدفوعة</h3>
    <span><?= $pendingCount ?></span>
</div>

<div class="stat-card">
    <h3>الإيرادات</h3>
    <span><?= number_format($totalIncome,2) ?> JOD</span>
</div>

</section>

<section class="action-cards">

  <a href="invoice.php" class="action-card">

        <div class="card-icon red">

            <i data-lucide="file-plus-2"></i>

        </div>

        <h3>إنشاء فاتورة جديدة</h3>

        <p>
            إنشاء فاتورة جديدة وإدخال البيانات
        </p>

    </a>


  <a href="savedInvoices.php" class="action-card">

        <div class="card-icon red">

            <i data-lucide="files"></i>

        </div>

        <h3>الفواتير المحفوظة</h3>

        <p>
            عرض وإدارة جميع الفواتير المحفوظة
        </p>

    </a>
    <a href="customers.php" class="action-card">

    <div class="card-icon red">
        <i data-lucide="users"></i>
    </div>

    <h3>العملاء</h3>

    <p>إدارة بيانات العملاء</p>

</a>

</section>
<footer class="footer">

    جميع الحقوق محفوظة © العقرباوي للنقل والشحن الدولي

</footer>

</main>

</div>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>