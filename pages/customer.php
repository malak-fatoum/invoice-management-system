<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";


$username = $_SESSION["full_name"];
$role = $_SESSION["role"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION["customer"] = [

        "customer_id" => $_POST["customer_id"]

    ];

    header("Location: shipment.php");
    exit();
}

$selectedCustomer = $_SESSION["customer"]["customer_id"] ?? "";

if (isset($_SESSION["edit_invoice_id"]) && !empty($_SESSION["customer"]["customer_id"])) {

    $selectedCustomer = $_SESSION["customer"]["customer_id"];

}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
<meta name="language" content="Arabic">
    
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>بيانات العميل</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

   <link rel="stylesheet" href="../css/invoice.css">
   <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="app">

<aside class="sidebar">

    <div>

        <div class="logo">

            <img src="../assets/logo.svg" alt="شعار العقرباوي">

            <div>

                <h2>العقرباوي</h2>

                <span>للنقل والشحن الدولي</span>

            </div>

        </div>

        <nav class="sidebar-menu">

    <a href="dashboard.php" class="menu-item">
        <i data-lucide="house"></i>
        <span>الرئيسية</span>
    </a>

    <a href="invoice.php" class="menu-item active">
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


        </nav>

    </div>

    <div class="logout">
    <a href="logout.php">
        <i data-lucide="log-out"></i>
        <span>تسجيل الخروج</span>
    </a>
</div>

</aside>

<main class="main">
    <header class="topbar">

    <div class="topbar-right">

        <div class="page-info">

            <h1>إنشاء فاتورة جديدة</h1>

            <span>إنشاء فاتورة / إنشاء فاتورة جديدة</span>

        </div>

    </div>

    <div class="topbar-left">
        <div class="user">

            <strong><?php echo $username; ?></strong>

            <small><?php echo $role; ?></small>

        </div>

        <div class="avatar">

            👤

        </div>

    </div>

</header>

<section class="content">

<div class="tabs">

  <a href="invoice.php" class="tab">
        <span>📄</span>
        <p>بيانات الفاتورة</p>
    </a>

  <a href="customer.php" class="tab active">
        <span>👤</span>
        <p>بيانات العميل</p>
    </a>

    <a href="shipment.php" class="tab">
        <span>🚚</span>
        <p>بيانات الشحنة</p>
    </a>

    <a href="banks.php" class="tab">
        <span>🏦</span>
        <p>بيانات البنوك</p>
    </a>
    <a href="items.php" class="step-tab">

    <span>📋</span>

    <p>بنود الفاتورة</p>

</a>

    <a href="additional.php" class="tab">
        <span>ℹ️</span>
        <p>معلومات إضافية</p>
    </a>

</div>
<div class="card">

    <h2 class="card-title">
        2. بيانات العميل
    </h2>

    <form method="POST">

        <?php
        $result = mysqli_query($conn, "SELECT id, customer_name FROM customers ORDER BY customer_name");
        ?>

        <div class="form-grid">

            <div class="form-group full">

                <label>اسم العميل</label>

                <select name="customer_id" required>

    <option value="">اختر العميل</option>

    <?php while($row = mysqli_fetch_assoc($result)){ ?>

        <option
            value="<?= $row['id']; ?>"
            <?= ($selectedCustomer == $row['id']) ? "selected" : "" ?>>

            <?= htmlspecialchars($row['customer_name']); ?>

        </option>

    <?php } ?>

</select>

            </div>

        </div>

        <div class="page-buttons">

            <a href="invoice.php" class="btn btn-cancel">
                السابق
            </a>

            <button type="submit" class="btn btn-next">
                حفظ والمتابعة
            </button>

        </div>

    </form>

</div>

    </select>

</div>

</form>

</section>

</main>

</div>
</body>

</html>