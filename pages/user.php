<?php

require_once "../config/auth_check.php";
require_once "../config/permissions.php";
require_once "../config/config.php";
$editUser = null;

if (isset($_GET["edit"])) {

    $id = (int)$_GET["edit"];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultEdit = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultEdit) > 0) {
        $editUser = mysqli_fetch_assoc($resultEdit);
    }
}

$editUser = null;

if (isset($_GET["edit"])) {

    $id = (int)$_GET["edit"];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultEdit = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultEdit) > 0) {
        $editUser = mysqli_fetch_assoc($resultEdit);
    }
}

if (!canManageUsers()) {

    header("Location: dashboard.php");
    exit();

}

require_once "../config/config.php";


$sql = "SELECT * FROM users ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<?php
if (isset($_GET["cannot_delete_self"])) {
    echo "<script>alert('لا يمكنك حذف حسابك أثناء تسجيل الدخول.');</script>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <title>المستخدمون</title>
<meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إدارة المستخدمين | العقرباوي للنقل والشحن الدولي</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/user.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="app">

    <!-- Sidebar -->

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

        <a href="savedInvoices.php" class="menu-item">
            <i data-lucide="files"></i>
            <span>الفواتير المحفوظة</span>
        </a>

        <a href="customers.php" class="menu-item">
            <i data-lucide="users"></i>
            <span>العملاء</span>
        </a>

        <a href="user.php" class="menu-item active">
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

                <div class="date">

                    <i data-lucide="calendar-days"></i>

<span>

<?php

date_default_timezone_set("Asia/Amman");

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

                   <strong><?= htmlspecialchars($_SESSION["full_name"]) ?></strong>

<span><?= htmlspecialchars($_SESSION["role"]) ?></span>
                </div>

                <div class="avatar">

                    <i data-lucide="user"></i>

                </div>

            </div>

        </header>

        <section class="content">

            <div class="page-header">

                <div>

                    <h1>إدارة المستخدمين</h1>

                    <p>إضافة وإدارة مستخدمي النظام وصلاحياتهم</p>

                </div>

            </div>

            <!-- إضافة مستخدم -->

            <div class="card">

                <h2 class="card-title">

                    إضافة مستخدم جديد

                </h2>

                <form action="save_user.php" method="POST">

<input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">

                <div class="form-group">

<label>الاسم الكامل</label>

<input
type="text"
id="fullName"
name="full_name"
value="<?= htmlspecialchars($editUser['full_name'] ?? '') ?>"
placeholder="أدخل الاسم الكامل"
required>

</div>

<div class="form-group">

    <label>البريد الإلكتروني</label>

    <input
type="email"
name="email"
value="<?= htmlspecialchars($editUser['email'] ?? '') ?>"
placeholder="أدخل البريد الإلكتروني"
required>

</div>

                    <div class="form-group">

                        <label>اسم المستخدم</label>

<input
type="text"
id="username"
name="username"
value="<?= htmlspecialchars($editUser['username'] ?? '') ?>"
placeholder="أدخل اسم المستخدم"
required>
                    </div>

                    <div class="form-group">

    <label>كلمة المرور</label>

    <input
    type="password"
    id="password"
    name="password"
    placeholder="********"
    <?= $editUser ? "" : "required" ?>>

</div>

<div class="form-group">

    <label>تأكيد كلمة المرور</label>

    <input
    type="password"
    name="confirm_password"
    placeholder="أعد إدخال كلمة المرور"
    <?= $editUser ? "" : "required" ?>>

</div>

<div class="form-group">

    <label>الدور</label>

    <select id="role" name="role">

    <option value="admin" <?= (($editUser['role'] ?? '') == 'admin') ? 'selected' : '' ?>>
        مدير النظام
    </option>

    <option value="accountant" <?= (($editUser['role'] ?? '') == 'accountant') ? 'selected' : '' ?>>
        محاسب
    </option>

</select>

</div>

                    <div class="form-group">

    <label>الحالة</label>

    <select name="status">

    <option value="active" <?= (($editUser['status'] ?? '') == 'active') ? 'selected' : '' ?>>
        فعال
    </option>

    <option value="inactive" <?= (($editUser['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>
        غير فعال
    </option>

</select>

</div>

                <div class="page-buttons">

<button type="submit" class="btn btn-next">
                       <?= $editUser ? "تحديث المستخدم" : "حفظ المستخدم" ?>

                    </button>

                </div>

                </form>

            </div>

            <!-- جدول المستخدمين -->

            <div class="card">

                <h2 class="card-title">

                    المستخدمون المسجلون

                </h2>

                <table class="items-table">

                    <thead>

                        <tr>

                            <th>#</th>
<th>الاسم</th>
<th>اسم المستخدم</th>
<th>البريد الإلكتروني</th>
<th>الدور</th>
<th>الحالة</th>
<th>الإجراءات</th>

                        </tr>

                    </thead>

                    <tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row["id"] ?></td>

<td><?= htmlspecialchars($row["full_name"]) ?></td>

<td><?= htmlspecialchars($row["username"]) ?></td>

<td><?= htmlspecialchars($row["email"]) ?></td>

<td>
    <?= $row["status"] == "active" ? "فعال" : "غير فعال"; ?>
</td>

<td><?= htmlspecialchars($row["role"]) ?></td>

<td class="actions">

    <a href="user.php?edit=<?= $row['id'] ?>" class="btn-edit">
    
        <i class="fa-solid fa-pen"></i>
    </a>

    <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn-delete"
   onclick="return confirm('هل أنت متأكد من حذف المستخدم؟')">
        <i class="fa-solid fa-trash"></i>
    </a>

</td>

</tr>

<?php } ?>

</tbody>

                </table>

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

<script src="../js/user.js"></script>

</body>

</html>