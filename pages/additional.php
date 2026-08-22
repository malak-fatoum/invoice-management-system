<?php
require_once "../config/auth_check.php";
require_once "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$_SESSION["additional"] = [

    "national_no" => $_POST["national_no"],
    "tax_no" => $_POST["tax_no"],
    "website" => $_POST["website"],
    "email" => $_POST["email"],
    "mobile" => $_POST["mobile"],
    "address" => $_POST["address"],

    "address_en" => $_POST["address_en"]

];
    header("Location: preview.php");
    exit();
}

$additional = $_SESSION["additional"] ?? [];

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>معلومات إضافية</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/invoice.css">

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

            <nav>

                <a class="menu" href="dashboard.php">الرئيسية</a>

                <a class="menu menu-red" href="invoice.php">إنشاء فاتورة</a>

               <a class="menu" href="savedInvoices.php">الفواتير المحفوظة</a>

              <a class="menu" href="customers.php">العملاء</a>

               <a class="menu" href="user.php">المستخدمون</a>

            

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
    <strong><?= $_SESSION["full_name"]; ?></strong>
    <small><?= $_SESSION["role"]; ?></small>
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

                <a href="customer.php" class="tab">
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

                <a href="additional.php" class="tab active">
                    <span>ℹ️</span>
                    <p>معلومات إضافية</p>
                </a>

            </div>
            
            <div class="card">

    <h2 class="card-title">
        6. معلومات الشركة
    </h2>

    <form method="POST">

 <div class="form-group">
    <label>الرقم الوطني</label>
<input
    type="text"
    id="nationalNo"
    name="national_no"
    placeholder="أدخل الرقم الوطني"
    value="<?= htmlspecialchars($additional["national_no"] ?? "") ?>"></div>

<div class="form-group">
    <label>الرقم الضريبي</label>
    <input type="text" id="taxNo"name="tax_no" value="<?= htmlspecialchars($additional["tax_no"] ?? "") ?>" placeholder="أدخل الرقم الضريبي">
</div>

<div class="form-group">
    <label>الموقع الإلكتروني</label>
    <input type="text" id="website"name="website" value="<?= htmlspecialchars($additional["website"] ?? "") ?>" placeholder="www.example.com">
</div>

<div class="form-group">
    <label>البريد الإلكتروني</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($additional["email"] ?? "") ?>" placeholder="info@example.com">
</div>

<div class="form-group">
    <label>رقم الهاتف</label>
    <input type="text" id="mobile"name="mobile" value="<?= htmlspecialchars($additional["mobile"] ?? "") ?>" placeholder="+962 7XXXXXXXX">
</div>

<div class="form-group full">
    <label>العنوان</label>
    <input type="text" id="address"name="address" value="<?= htmlspecialchars($additional["address"] ?? "") ?>" placeholder="أدخل عنوان الشركة">
</div>
<div class="form-group">
    <label>Address (English)</label>
    <input
        type="text"
        name="address_en"
        placeholder="Enter company address"
        value="<?= htmlspecialchars($additional["address_en"] ?? "") ?>">
</div>

  <div class="page-buttons">

    <a href="items.php" class="btn btn-cancel">
        السابق
    </a>

    <button type="submit" class="btn btn-next">
       معاينة الفاتورة
    </button>

</div>

</form>

</div>
        </section>

    </main>

</div>
</body>
</html>