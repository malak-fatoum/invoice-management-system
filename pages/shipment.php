<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

$username = $_SESSION["full_name"];
$role = $_SESSION["role"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$_SESSION["shipment"] = [

    "master_bl" => $_POST["master_bl"],
    "house_bl" => $_POST["house_bl"],
    "customs_number" => $_POST["customs_number"],

    "customs_type" => $_POST["customs_type"],
    "customs_type_en" => $_POST["customs_type_en"],

    "shipper" => $_POST["shipper"],
    "shipper_en" => $_POST["shipper_en"],

    "consignee" => $_POST["consignee"],
    "consignee_en" => $_POST["consignee_en"],

    "cargo_description" => $_POST["cargo_description"],
    "cargo_description_en" => $_POST["cargo_description_en"]

];

    header("Location: banks.php");
    exit();
}

$shipment = $_SESSION["shipment"] ?? [];

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <title>الشحن</title>
    <meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/invoice.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

<div class="app">

    <!-- Sidebar -->
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

    <a href="../dashboard.php" class="menu-item">
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

        </div>

        <div class="logout">
    <a href="logout.php">
        <i data-lucide="log-out"></i>
        <span>تسجيل الخروج</span>
    </a>
</div>

    </aside>

    <!-- Main -->
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

        <!-- Content -->
        <section class="content">

            <!-- Tabs -->

            <div class="tabs">

                <a href="invoice.php" class="tab">
                    <span>📄</span>
                    <p>بيانات الفاتورة</p>
                </a>

                <a href="customer.php" class="tab">
                    <span>👤</span>
                    <p>بيانات العميل</p>
                </a>

                <a href="shipment.php" class="tab active">
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
                    3. بيانات الشحنة
                </h2>

                <form method="POST">

                <div class="form-grid">

<div class="form-group">
    <label>رقم البوليصة الماستر / البوكينج</label>
    <input
    type="text"
    id="masterBL"
    name="master_bl"
    value="<?= htmlspecialchars($shipment["master_bl"] ?? "") ?>">
</div>

<div class="form-group">
    <label>رقم البوليصة الفرعي</label>
    <input type="text" id="houseBL"  name="house_bl" value="<?= htmlspecialchars($shipment["house_bl"] ?? "") ?>">
</div>

<div class="form-group">
    <label>رقم البيان الجمركي</label>
    <input type="text" id="customsNumber" name="customs_number" value="<?= htmlspecialchars($shipment["customs_number"] ?? "") ?>">
</div>

<div class="form-group">

    <label>نوع البيان الجمركي</label>

    <select id="customsType" name="customs_type">

        <option value="استيراد"
            <?= (($shipment["customs_type"] ?? "") == "استيراد") ? "selected" : "" ?>>
            استيراد
        </option>

        <option value="تصدير"
            <?= (($shipment["customs_type"] ?? "") == "تصدير") ? "selected" : "" ?>>
            تصدير
        </option>

        <option value="ترانزيت"
            <?= (($shipment["customs_type"] ?? "") == "ترانزيت") ? "selected" : "" ?>>
            ترانزيت
        </option>

    </select>

</div>
<div class="form-group">

    <label>Customs Declaration Type</label>

    <select name="customs_type_en">

        <option value="Import"
            <?= (($shipment["customs_type_en"] ?? "") == "Import") ? "selected" : "" ?>>
            Import
        </option>

        <option value="Export"
            <?= (($shipment["customs_type_en"] ?? "") == "Export") ? "selected" : "" ?>>
            Export
        </option>

        <option value="Transit"
            <?= (($shipment["customs_type_en"] ?? "") == "Transit") ? "selected" : "" ?>>
            Transit
        </option>

    </select>

</div>

<div class="form-group">
    <label>اسم المرسل (عربي)</label>
    <input
        type="text"
        id="shipper"
        name="shipper"
        value="<?= htmlspecialchars($shipment["shipper"] ?? "") ?>">
</div>

<div class="form-group">
    <label>Shipper (English)</label>
    <input
        type="text"
        name="shipper_en"
        value="<?= htmlspecialchars($shipment["shipper_en"] ?? "") ?>">
</div>

<div class="form-group">
    <label>اسم المرسل إليه (عربي)</label>
    <input
        type="text"
        id="consignee"
        name="consignee"
        value="<?= htmlspecialchars($shipment["consignee"] ?? "") ?>">
</div>

<div class="form-group">
    <label>Consignee (English)</label>
    <input
        type="text"
        name="consignee_en"
        value="<?= htmlspecialchars($shipment["consignee_en"] ?? "") ?>">
</div>

<div class="form-group full">
    <label>وصف البضاعة (عربي)</label>
    <textarea
        id="cargoDescription"
        name="cargo_description"
        placeholder="اكتب وصف البضاعة"><?= htmlspecialchars($shipment["cargo_description"] ?? "") ?></textarea>
</div>

<div class="form-group full">
    <label>Goods Description (English)</label>
    <textarea
        name="cargo_description_en"
        placeholder="Enter goods description"><?= htmlspecialchars($shipment["cargo_description_en"] ?? "") ?></textarea>
</div>

</div>

            <div class="page-buttons">
    <a href="customer.php" class="btn btn-cancel">
         السابق
    </a>

   <button type="submit" class="btn btn-next">
        حفظ والمتابعة
    </button>
</div>

</form>
        </section>

    </main>

</div>

</body>
</html>