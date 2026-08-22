<?php

require_once "../config/auth_check.php";
require_once "../config/permissions.php";
require_once "../config/config.php";

$username = $_SESSION["full_name"];
$role = $_SESSION["role"];

$search = "";

if(isset($_GET["search"])){

    $search = trim($_GET["search"]);

}

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

if ($search != "") {

    $sql = "SELECT
                invoices.*,
                customers.customer_name,
                users.full_name
            FROM invoices
            INNER JOIN customers
                ON invoices.customer_id = customers.id
            INNER JOIN users
                ON invoices.created_by = users.id
            WHERE
                invoices.invoice_number LIKE ?
                OR customers.customer_name LIKE ?
            ORDER BY invoices.id DESC";

    $stmt = mysqli_prepare($conn, $sql);
    $like = "%".$search."%";
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {

    $sql = "SELECT
                invoices.*,
                customers.customer_name,
                users.full_name
            FROM invoices
            INNER JOIN customers
                ON invoices.customer_id = customers.id
            INNER JOIN users
                ON invoices.created_by = users.id
            ORDER BY invoices.id DESC";

    $result = mysqli_query($conn, $sql);
}
$customersSql = "

SELECT

customers.id,
customers.customer_name,
customers.account_number,
COUNT(invoices.id) AS invoices_count,
MAX(invoices.invoice_date) AS last_invoice

FROM customers

LEFT JOIN invoices
ON customers.id = invoices.customer_id

GROUP BY customers.id

ORDER BY customers.customer_name ASC

";

$customersResult = mysqli_query($conn, $customersSql);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    
    <meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>الفواتير المحفوظة | العقرباوي للنقل والشحن الدولي</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">


    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/savedInvoices.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../js/alerts.js"></script>
</head>

<body>

<div class="app">
    

    <aside class="sidebar">

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

        <a href="invoice.php" class="menu-item ">
            <i data-lucide="file-plus-2"></i>
            <span>إنشاء فاتورة</span>
        </a>

        <a href="savedInvoices.php" class="menu-item active">
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

<main class="main">

    <header class="topbar">

        <div class="topbar-right">

            <div class="date">

                <i data-lucide="calendar-days"></i>

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

            </div>

        </div>

        <div class="topbar-left">

            <i data-lucide="log-out"></i>

            <div class="user">

                <strong><?= $username ?></strong>

                <span><?= $role ?></span>

            </div>

            <div class="avatar">

                <i data-lucide="user"></i>

            </div>

            <i data-lucide="chevron-down"></i>

        </div>

    </header>
   <section class="content">

    <div class="page-header">

        <div>
            <h1>الفواتير المحفوظة</h1>
            <p>عرض جميع الفواتير وإدارة فواتير العملاء</p>
        </div>

        <a href="invoice.php" class="btn btn-next">
            + إنشاء فاتورة جديدة
        </a>

    </div>

    <div class="card">

        <div class="form-group">

            <label>بحث</label>

            <form method="GET">

<input
    type="search"
    name="search"
    placeholder="ابحث برقم الفاتورة أو اسم العميل"
    value="<?= htmlspecialchars($_GET["search"] ?? "") ?>">

</form>

        </div>

    </div>

    <div class="card">

        <h2 class="card-title">
            العملاء
        </h2>

        <table class="items-table">

            <thead>

                <tr>
                    <th>اسم العميل</th>
                    <th>رقم الحساب</th>
                    <th>عدد الفواتير</th>
                    <th>آخر فاتورة</th>
                    <th>عرض الفواتير</th>
                </tr>

            </thead>

                    <tbody>

<?php while($customer = mysqli_fetch_assoc($customersResult)){ ?>

<tr>

    <td><?= htmlspecialchars($customer["customer_name"]) ?></td>

    <td><?= htmlspecialchars($customer["account_number"]) ?></td>

    <td><?= $customer["invoices_count"] ?></td>

    <td><?= $customer["last_invoice"] ?? "-" ?></td>

    <td>

        <a href="customerInvoices.php?customer_id=<?= $customer["id"] ?>"
           class="table-btn"
           title="عرض الفواتير">

            📂

        </a>

    </td>

</tr>

<?php } ?>

</tbody>

        </table>

    </div>

    <!-- جدول جميع الفواتير -->

    <div class="card">

        <h2 class="card-title">
            جميع الفواتير
        </h2>

        <table class="items-table">

            <thead>

                <tr>
                    <th>رقم الفاتورة</th>
                    <th>العميل</th>
                    <th>التاريخ</th>
                    <th>الإجمالي</th>
                    <th>العملة</th>
                    <th>أنشأها</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>

            </thead>
<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td><?= htmlspecialchars($row["invoice_number"]) ?></td>

    <td><?= htmlspecialchars($row["customer_name"]) ?></td>

    <td><?= htmlspecialchars($row["invoice_date"]) ?></td>

    <td>

<?php

$totalSql = "SELECT SUM(total) AS total
             FROM invoice_items
             WHERE invoice_id=?";

$totalStmt = mysqli_prepare($conn, $totalSql);

mysqli_stmt_bind_param($totalStmt, "i", $row["id"]);

mysqli_stmt_execute($totalStmt);

$totalResult = mysqli_stmt_get_result($totalStmt);

$totalRow = mysqli_fetch_assoc($totalResult);



echo number_format($totalRow["total"] ?? 0, 2);

?>

</td>

    <td><?= htmlspecialchars($row["currency"]) ?></td>

    <td><?= htmlspecialchars($row["full_name"]) ?></td>
    <td>

<?php if($row["status"] == "Paid"){ ?>

    <span class="status paid">
        مدفوعة
    </span>

<?php }else{ ?>

    <span class="status pending">
        غير مدفوعة
    </span>

<?php } ?>

</td>

    <td class="actions">

        <a href="preview.php?id=<?= $row["id"] ?>" class="table-btn">
            👁
        </a>


        
        <?php if (canEditInvoice()) { ?>

<a href="load_invoice.php?id=<?= $row["id"] ?>" class="table-btn">
    ✏️
</a>

<?php } ?>

<?php if(canDeleteInvoice()){ ?>

        <a href="#"
class="table-btn"
onclick="confirmDelete('delete_invoice.php?id=<?= $row["id"] ?>')">
🗑
</a>
<?php } ?>

<?php if(canMarkPaid()){ ?>

<a href="mark_paid.php?id=<?= $row["id"] ?>"
   class="table-btn"
   title="تعيين كمدفوعة">
    💰
</a>
<?php } ?>

    </td>

</tr>

<?php } ?>

</tbody>

            

        </table>

    </div>

</section>
    <!-- ================= Action Cards ================= -->


<footer class="footer">

    جميع الحقوق محفوظة © العقرباوي للنقل والشحن الدولي

</footer>

</main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

function markAsPaid(button){

    const row = button.closest("tr");

    const status = row.querySelector(".status");

    status.classList.remove("pending");

    status.classList.add("paid");

    status.textContent="مدفوعة";

    button.style.display="none";

}

</script>

<script>
function openCustomerInvoices(customerName){

    localStorage.setItem("selectedCustomer", customerName);

    window.location.href = "customerInvoices.php";

}
</script>

<?php if(isset($_GET["deleted"])){ ?>

<script>

successMessage("تم حذف الفاتورة بنجاح");

</script>

<?php } ?>

<?php if(isset($_GET["saved"])){ ?>

<script>

successMessage("تم حفظ الفاتورة بنجاح");

</script>

<?php } ?>

<?php if(isset($_GET["updated"])){ ?>

<script>

successMessage("تم تعديل الفاتورة بنجاح");

</script>

<?php } ?>

</body>
</html>
