<?php

require_once "../config/auth_check.php";

include "../config/config.php";

$username = $_SESSION["full_name"];
$role = $_SESSION["role"];

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
<meta name="language" content="Arabic">
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>بنود الفاتورة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/items.css">
     
</head>

<body>

<div class="app">

    <aside class="sidebar">

        <div>

            <div class="logo">

                <img src="../assets/logo.svg" alt="شعار العقرباوي">

                <div class="logo-text">

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

            <span>إنشاء فاتورة / بنود الفاتورة</span>

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

        <a href="banks.php" class="tab">
            <span>🏦</span>
            <p>بيانات البنوك</p>
        </a>

        <a href="items.php" class="tab active">
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

            5. بنود الفاتورة

        </h2>

        <div class="form-grid">

            <div class="form-group full">

                <label>البيان</label>

                <input type="text" id="description" name="description"
                    placeholder="أدخل البيان">

            </div>

            <div class="form-group">

                <label>الكمية</label>

                <input
                    type="number"
                    id="qty"
                    name="quantity"
                    value="1"
                    min="1">

            </div>

            <div class="form-group">

                <label>سعر الوحدة</label>

                <input
                    type="number"
                    id="price"
                    name="unit_price"
                    placeholder="0.00"
                    min="0"
                    step="0.01">

            </div>

        

        </div>


        <div class="form-group">

    <label>الإجمالي</label>

    <input
        type="number"
        id="total"
        name="total"
        readonly>

</div>

        <div class="item-actions">

            <button
                type="button"
                id="addItem"
                class="btn btn-next">

                + إضافة بند

            </button>

        </div>

        <div class="invoice-total">

            <span>الإجمالي الكلي</span>

            <input
                    type="number"
                    id="grandTotal"
                    readonly>

        </div>

        <table class="items-table">

            <thead>

                <tr>

                    <th>م</th>

                    <th>البيان</th>

                    <th>الكمية</th>

                    <th>سعر الوحدة</th>

                    <th>الإجمالي</th>

                    <th>حذف</th>

                </tr>

            </thead>

            <tbody id="itemsBody">

            </tbody>

        </table>

    <div class="page-buttons">
    <a href="banks.php" class="btn btn-cancel">
         السابق
    </a>

    <a href="additional.php"
        class="btn btn-next">
        التالي
    </a>
</div>
</section>

</main>

</div>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>

<script src="../js/items.js"></script>
</body>

</html>
