<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

$username = $_SESSION["full_name"];
$role = $_SESSION["role"];


if(!isset($_GET["customer_id"])){

    header("Location: savedInvoices.php");
exit();

}

$customer_id = (int)$_GET["customer_id"];

$sql = "SELECT * FROM customers WHERE id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$customer_id);

mysqli_stmt_execute($stmt);

$customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$search = trim($_GET["search"] ?? "");

$sql = "

SELECT
invoices.*,
users.full_name

FROM invoices

INNER JOIN users
ON invoices.created_by=users.id

WHERE customer_id=?";

if($search!=""){

    $sql .= " AND invoice_number LIKE ?";

}

$sql .= " ORDER BY invoice_date DESC";

$stmt = mysqli_prepare($conn,$sql);

if($search!=""){

    $like="%".$search."%";

    mysqli_stmt_bind_param($stmt,"is",$customer_id,$like);

}else{

    mysqli_stmt_bind_param($stmt,"i",$customer_id);

}

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
<meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>فواتير العميل | العقرباوي للنقل والشحن الدولي</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

 <link rel="stylesheet" href="../css/customerInvoices.css">
  <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="app">

    <!-- Sidebar -->

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

    <a href="invoice.php" class="menu-item">
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

                <div class="date">

                    <i data-lucide="calendar-days"></i>

                    <span><?= date("Y-m-d") ?></span>

                </div>

            </div>

            <div class="topbar-left">

                <div class="user">

                    <strong><?= htmlspecialchars($username) ?></strong>

                    <span><?= htmlspecialchars($role) ?></span>

                </div>

                <div class="avatar">

                    <i data-lucide="user"></i>

                </div>

            </div>

        </header>

        <section class="content">

            <div class="page-header">

                <div>

                    <h1>فواتير العميل</h1>

                    <p>عرض جميع فواتير العميل المحدد</p>

                </div>

                <a href="savedInvoices.php" class="btn btn-cancel">

                    ← العودة للفواتير المحفوظة

                </a>

            </div>

            <div class="card">

                <div class="page-header">

                    <div>

                        <h2><?= htmlspecialchars($customer["customer_name"]) ?></h2>

                        <p>
                           رقم الحساب : <?= htmlspecialchars($customer["account_number"]) ?>
                        </p>

                    </div>

                    <a href="invoice.php?customer_id=<?= $customer["id"] ?>" class="btn btn-next">

                        + إنشاء فاتورة

                    </a>

                </div>

                <div class="form-group">

                    <label>بحث</label>

                    <form method="GET">

<input
type="hidden"
name="customer_id"
value="<?= $customer_id ?>">

<input
type="search"
name="search"
placeholder="ابحث برقم الفاتورة"
value="<?= htmlspecialchars($_GET["search"] ?? "") ?>">

</form>

                </div>

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

    <td><?= htmlspecialchars($customer["customer_name"]) ?></td>

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

        <?php if($row["status"]=="Paid"){ ?>

            <span class="status paid">
                مدفوعة
            </span>

        <?php }else{ ?>

            <span class="status pending">
                غير مدفوعة
            </span>

        <?php } ?>

    </td>

    <td>

        <a href="preview.php?id=<?= $row["id"] ?>" class="table-btn">
            👁
        </a>

        <a href="load_invoice.php?id=<?= $row["id"] ?>" class="table-btn">
            ✏️
        </a>

        <a href="delete_invoice.php?id=<?= $row["id"] ?>"
           class="table-btn"
           onclick="return confirm('هل تريد حذف هذه الفاتورة؟');">
            🗑
        </a>

    </td>

</tr>

<?php } ?>

</tbody>

            

        </table>

    </div>

            </div>

        </section>

        <footer class="footer">

            جميع الحقوق محفوظة © العقرباوي للنقل والشحن الدولي

        </footer>

    </main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>

<script src="../js/customerInvoices.js"></script>

</body>

</html>