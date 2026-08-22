<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

$username = $_SESSION["full_name"];
$role = $_SESSION["role"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION["banks"] = [

        "jod_bank_name" => $_POST["jod_bank_name"],
        "jod_branch"    => $_POST["jod_branch"],
        "jod_iban"      => $_POST["jod_iban"],
        "jod_account"   => $_POST["jod_account"],
        "jod_company"   => $_POST["jod_company"],

        "usd_bank_name" => $_POST["usd_bank_name"],
        "usd_branch"    => $_POST["usd_branch"],
        "usd_iban"      => $_POST["usd_iban"],
        "usd_account"   => $_POST["usd_account"],
        "usd_company"   => $_POST["usd_company"],

        "cliq_details"  => $_POST["cliq_details"],
        "cliq_id"       => $_POST["cliq_id"],
        "cliq_name"     => $_POST["cliq_name"],
        "cliq_bank"     => $_POST["cliq_bank"]

    ];

    header("Location: items.php");
    exit();
}

$banks = $_SESSION["banks"] ?? [];


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <title>البنوك</title>
    <meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

   <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/invoice.css">
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

            <span>إنشاء فاتورة / بيانات البنوك</span>

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

                <a href="customer.php" class="tab">
                    <span>👤</span>
                    <p>بيانات العميل</p>
                </a>

                <a href="shipment.php" class="tab">
                    <span>🚚</span>
                    <p>بيانات الشحنة</p>
                </a>

               <a href="banks.php" class="tab active">
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

            <h2 class="card-title">بيانات الحساب المحلي (JOD)</h2>

            <form  method="POST">
                <div class="form-grid">

    <div class="form-group">
        <label>اسم البنك</label>
        <input
    type="text"
    id="jodBankName"
    name="jod_bank_name"
    value="<?= htmlspecialchars($banks["jod_bank_name"] ?? "") ?>">
    </div>

    <div class="form-group">
        <label>الفرع</label>
        <input type="text" id="jodBranch" name="jod_branch" value="<?= htmlspecialchars($banks["jod_branch"] ?? "") ?>">
    </div>

    <div class="form-group">
        <label>IBAN</label>
        <input type="text" id="jodIban" name="jod_iban" value="<?= htmlspecialchars($banks["jod_iban"] ?? "") ?>">
    </div>

    <div class="form-group">
        <label>رقم الحساب</label>
        <input type="text" id="jodAccount" name="jod_account" value="<?= htmlspecialchars($banks["jod_account"] ?? "") ?>">
    </div>

    <div class="form-group full">
        <label>اسم الشركة</label>
        <input type="text" id="jodCompany" name="jod_company" value="<?= htmlspecialchars($banks["jod_company"] ?? "") ?>">
    </div>

</div>

<h2 class="card-title">
    بيانات الحساب الدولي (USD)
</h2>

<div class="form-grid">

    <div class="form-group">
        <label>اسم البنك</label>
        <input
    type="text"
    id="usdBankName"
    name="usd_bank_name"
    value="<?= htmlspecialchars($banks["usd_bank_name"] ?? "") ?>">
    </div>

    <div class="form-group">
        <label>الفرع</label>
        <input
    type="text"
    id="usdBranch"
    name="usd_branch"
    value="<?= htmlspecialchars($banks["usd_branch"] ?? "") ?>">
    </div>

    <div class="form-group">
        <label>رقم الآيبان (IBAN)</label>
        <input
    type="text"
    id="usdIban"
    name="usd_iban"
    value="<?= htmlspecialchars($banks["usd_iban"] ?? "") ?>">
    </div>

    <div class="form-group">
        <label>رقم الحساب</label>
        <input
    type="text"
    id="usdAccount"
    name="usd_account"
    value="<?= htmlspecialchars($banks["usd_account"] ?? "") ?>">
    </div>

    <div class="form-group full">
        <label>اسم الشركة</label>
        <input
    type="text"
    id="usdCompany"
    name="usd_company"
    value="<?= htmlspecialchars($banks["usd_company"] ?? "") ?>">
    </div>
   
</div>


    <hr class="section-divider">

    <h2 class="card-title">بيانات كليك</h2>


    <div class="form-grid">

        <div class="form-group">
            <label>تفاصيل كليك (CliQ Details)</label>
            <input
    type="text"
    id="cliqDetails"
    name="cliq_details"
    value="<?= htmlspecialchars($banks["cliq_details"] ?? "") ?>">
        </div>

        <div class="form-group">
            <label>معرف كليك (CliQ ID)</label>
            <input
    type="text"
    id="cliqId"
    name="cliq_id"
    value="<?= htmlspecialchars($banks["cliq_id"] ?? "") ?>">
        </div>

        <div class="form-group">
            <label>اسم كليك (CliQ Name)</label>
            <input
    type="text"
    id="cliqName"
    name="cliq_name"
    value="<?= htmlspecialchars($banks["cliq_name"] ?? "") ?>">
        </div>

        <div class="form-group">
            <label>البنك</label>
           <input
    type="text"
    id="cliqBank"
    name="cliq_bank"
    value="<?= htmlspecialchars($banks["cliq_bank"] ?? "") ?>">
        </div>

    </div>

     <div class="page-buttons">
    <a href="shipment.php" class="btn btn-cancel">
         السابق
    </a>

    <button type="submit" class="btn btn-next">
         حفظ والمتابعة
    </button>
     </div> 

     </form>

</div>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>
</body>
</html>