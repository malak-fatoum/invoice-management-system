<?php

require_once "../config/auth_check.php";

require_once "../config/permissions.php";

if (!canAddInvoice()) {

    header("Location: dashboard.php");
    exit();

}

require_once "../config/config.php";

$nextInvoiceNumber = 1;

$result = mysqli_query($conn, "SELECT MAX(invoice_number) AS last_number FROM invoices");

$row = mysqli_fetch_assoc($result);

if (!empty($row["last_number"])) {

    $nextInvoiceNumber = $row["last_number"] + 1;

}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['full_name'];
$role = $_SESSION['role'];

$date = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION["invoice"] = [

    "invoice_number" => $_POST["invoice_number"],
    "invoice_date" => $_POST["invoice_date"],
    "currency" => $_POST["currency"],

    "issued_by" => $_POST["issued_by"],
    "issued_by_en" => $_POST["issued_by_en"],

    "invoice_statement" => $_POST["invoice_statement"],
    "invoice_statement_en" => $_POST["invoice_statement_en"]

];

    header("Location: customer.php");
    exit();
}

$invoice = $_SESSION["invoice"] ?? [];

if(isset($_GET["customer_id"])){

    $_SESSION["customer"]["customer_id"] = (int)$_GET["customer_id"];

}

$editMode = false;
$invoiceData = [];

if (isset($_GET["edit"])) {

    $editMode = true;

    $invoice_id = (int)$_GET["edit"];

    $stmt = mysqli_prepare($conn, "SELECT * FROM invoices WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $invoice_id);
    mysqli_stmt_execute($stmt);

    $invoiceData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $_SESSION["edit_invoice_id"] = $invoice_id;

}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    
   <meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إنشاء فاتورة</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/invoice.css">
    <link rel="stylesheet" href="../css/dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="app">

    <!-- Sidebar -->

    <aside class="sidebar">

        <div class="logo">

            <img src="../assets/logo.svg" alt="شعارالعقرباوي">

            <div class="logo-text">

                <h2>العقرباوي</h2>

                <span>للنقل والشحن الدولي</span>

            </div>

        </div>

        <nav class="sidebar-menu">

    <a href="dashboard.php" class="menu-item ">
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

        <section class="content">

            

            <!-- Tabs -->

            <div class="tabs">

                <a href="invoice.php" class="tab active">
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

                <a href="items.php" class="tab">
                    <span>📋</span>
                    <p>بنود الفاتورة</p>
                </a>

                <a href="additional.php" class="tab">
                    <span>ℹ️</span>
                    <p>معلومات إضافية</p>
                </a>

            </div>

            <!-- Card -->

            <div class="card">

                <h2 class="card-title">

                    1. بيانات الفاتورة

                </h2>

                <form method="POST">

               <div class="form-grid">

                    <div class="form-group">

                        <label>رقم الفاتورة</label>
 
                        <input
                            type="text"
                            id="invoiceNumber"
                            name="invoice_number"
                            placeholder="INV-2024-"
                            value="<?= htmlspecialchars(
    $editMode
        ? ($invoiceData["invoice_number"] ?? "")
        : ($invoice["invoice_number"] ?? $nextInvoiceNumber)
) ?>">

                    </div>

                    <div class="form-group">

                        <label>تاريخ الفاتورة</label>

                        <input
                            type="date"
                            id="invoiceDate"
                            name="invoice_date"
                            value="<?= htmlspecialchars($editMode ? ($invoiceData["invoice_date"] ?? "") : ($invoice["invoice_date"] ?? $date)) ?>">
                    </div>
                    <div class="form-group">

    <label>العملة</label>

    <select id="currency" name="currency">

    <option value="JOD"
    <?= (($editMode ? ($invoiceData["currency"] ?? "") : ($invoice["currency"] ?? "")) == "JOD") ? "selected" : "" ?>>
        دينار أردني (JOD)
    </option>

    <option value="USD"
    <?= (($editMode ? ($invoiceData["currency"] ?? "") : ($invoice["currency"] ?? "")) == "USD") ? "selected" : "" ?>>
        دولار أمريكي (USD)
    </option>

</select>

</div>

                 

                    <div class="form-group">

    <label>اسم مصدر الفاتورة (عربي)</label>

    <input
        type="text"
        id="issuedBy"
        name="issued_by"
        placeholder="أدخل اسم مصدر الفاتورة بالعربي"
        value="<?= htmlspecialchars($editMode ? ($invoiceData["issued_by"] ?? "") : ($invoice["issued_by"] ?? "")) ?>">

</div>

<div class="form-group">

    <label>Invoice Source (English)</label>

    <input
        type="text"
        id="issuedByEn"
        name="issued_by_en"
        placeholder="Enter Invoice Source"
        value="<?= htmlspecialchars($editMode ? ($invoiceData["issued_by_en"] ?? "") : ($invoice["issued_by_en"] ?? "")) ?>">

</div>

                    <div class="form-group">

    <label>بيان الفاتورة (عربي)</label>

    <input
        type="text"
        id="invoiceStatement"
        name="invoice_statement"
        placeholder="أدخل بيان الفاتورة بالعربي"
        value="<?= htmlspecialchars($editMode ? ($invoiceData["issued_by_en"] ?? "") : ($invoice["issued_by_en"] ?? "")) ?>">

</div>


                </div>

                <div class="page-buttons">
    <a href="dashboard.php" class="btn btn-cancel">
         السابق
    </a>

    <button type="submit" class="btn btn-next">
    حفظ والمتابعة
</button>

 </form>

</div>

            </div>

            </div>

        </section>

    </main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>