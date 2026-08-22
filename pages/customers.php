<?php

require_once "../config/auth_check.php";
require_once "../config/permissions.php";

if (!(isAdmin() || isAccountant())) {

    header("Location: dashboard.php");
    exit();

}

require_once "../config/config.php";

if (isset($_GET["delete"])) {

    $id = (int) $_GET["delete"];

    $sql = "DELETE FROM customers WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    header("Location: customers.php?success=deleted");
    exit();
}

$editCustomer = null;

if (isset($_GET["edit"])) {

    $id = (int) $_GET["edit"];

    $sql = "SELECT * FROM customers WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $editCustomer = mysqli_fetch_assoc($result);

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"] ?? "";

    $customerName = trim($_POST["customerName"]);
    $customerNameEn = trim($_POST["customerNameEn"]);
    $customerAccount = trim($_POST["customerAccount"]);
    $customerPhone = trim($_POST["customerPhone"]);
    $customerCountry = trim($_POST["customerCountry"]);
    $customerCountryEn = trim($_POST["customerCountryEn"]);
    $customerAddress = trim($_POST["customerAddress"]);
    $customerAddressEn = trim($_POST["customerAddressEn"]);

    if (!empty($id)) {
       $sql = "UPDATE customers
        SET customer_name = ?,
            customer_name_en = ?,
            account_number = ?,
            phone = ?,
            country = ?,
            country_en = ?,
            address = ?,
            address_en = ?
        WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssi",
            $customerName,
            $customerNameEn,
            $customerAccount,
            $customerPhone,
            $customerCountry,
            $customerCountryEn,
            $customerAddress,
            $customerAddressEn,
            $id
        );

        mysqli_stmt_execute($stmt);

        header("Location: customers.php?success=updated");
        exit();

    } else {

        $sql = "INSERT INTO customers
                (
customer_name,
customer_name_en,
account_number,
phone,
country,
country_en,
address,
address_en
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssss",
            $customerName,
            $customerNameEn,
            $customerAccount,
            $customerPhone,
            $customerCountry,
            $customerCountryEn,
            $customerAddress,
            $customerAddressEn
        );

        mysqli_stmt_execute($stmt);

        header("Location: customers.php?success=added");
        exit();
    }

}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    
<meta name="language" content="Arabic">
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إدارة العملاء</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/customers.css">
    

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

        <a href="savedInvoices.php" class="menu-item">
            <i data-lucide="files"></i>
            <span>الفواتير المحفوظة</span>
        </a>

        <a href="customers.php" class="menu-item active">
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

                <div class="page-info">

                    <h1>إدارة العملاء</h1>

                    <span>العملاء / إضافة وإدارة العملاء</span>

                </div>

            </div>

            <div class="topbar-left">

                <div class="user">

                     <strong><?= $_SESSION["full_name"]; ?></strong>

                    <span><?= $_SESSION["role"]; ?></span>

                </div>

                <div class="avatar">

                    👤

                </div>

            </div>

        </header>

        <section class="content">

        <?php if (isset($_GET["success"])): ?>

<div class="success-message">

    <?php

    if ($_GET["success"] == "added") {
        echo "✅ تم إضافة العميل بنجاح";
    } elseif ($_GET["success"] == "updated") {
        echo "✅ تم تعديل العميل بنجاح";
    } elseif ($_GET["success"] == "deleted") {
        echo "✅ تم حذف العميل بنجاح";
    }

    ?>

</div>

<?php endif; ?>

<div class="card">

    <h2 class="card-title">
        إضافة عميل جديد
    </h2>

    <form method="POST">

    <input
    type="hidden"
    name="id"
    value="<?php echo $editCustomer['id'] ?? ''; ?>">

    <div class="form-grid">

        <div class="form-group">
            <label>اسم العميل</label>
            <input type="text" id="customerName"  name="customerName" value="<?php echo $editCustomer['customer_name'] ?? ''; ?>" placeholder="أدخل اسم العميل">
        </div>

        <div class="form-group">
    <label>Customer Name (English)</label>
    <input
        type="text"
        name="customerNameEn"
        value="<?php echo $editCustomer['customer_name_en'] ?? ''; ?>"
        placeholder="Enter Customer Name">
</div>

        <div class="form-group">
            <label>رقم حساب العميل</label>
            <input type="text" id="customerAccount" name="customerAccount"value="<?php echo $editCustomer['account_number'] ?? ''; ?>" placeholder="أدخل رقم الحساب">
        </div>

        <div class="form-group">
            <label>رقم الهاتف</label>
            <input type="text" id="customerPhone" name="customerPhone"value="<?php echo $editCustomer['phone'] ?? ''; ?>" placeholder="+9627XXXXXXXX">
        </div>

     <div class="form-group">

    <label>الدولة</label>

    <input
        type="text"
        id="customerCountry"
        name="customerCountry"
        value="<?php echo $editCustomer['country'] ?? ''; ?>"
        placeholder="أدخل اسم الدولة">

    </div>

    <div class="form-group">
    <label>Country (English)</label>
    <input
        type="text"
        name="customerCountryEn"
        value="<?php echo $editCustomer['country_en'] ?? ''; ?>"
        placeholder="Enter Country">
</div>

        <div class="form-group full">
            <label>العنوان</label>
            <input type="text" id="customerAddress" name="customerAddress"value="<?php echo $editCustomer['address'] ?? ''; ?>" placeholder="أدخل عنوان العميل">
        </div>


        <div class="form-group full">
    <label>Address (English)</label>
    <input
        type="text"
        name="customerAddressEn"
        value="<?php echo $editCustomer['address_en'] ?? ''; ?>"
        placeholder="Enter Address">
</div>

    </div>

    <div class="page-buttons">
        <button type="submit" class="btn btn-next">
          <?php echo $editCustomer ? "تحديث العميل" : "حفظ العميل"; ?>
        </button>

    </div>

</form>

    </div>
    <div class="card">

    <h2 class="card-title">
        العملاء المسجلون
    </h2>


    <form method="GET" class="search-form">

    <input
        type="text"
        name="search"
        placeholder="ابحث باسم العميل أو رقم الحساب أو الهاتف..."
        value="<?php echo htmlspecialchars($search ?? ''); ?>">

    <button type="submit" class="btn btn-next">
        بحث
    </button>

</form>

    <table class="items-table">

        <thead>

            <tr>

                <th>#</th>

                <th>اسم العميل</th>

                <th>رقم الحساب</th>

                <th>الهاتف</th>

                <th>الدولة</th>

                <th>العنوان</th>
                <th>إجراءات</th> 
                

            </tr>

        </thead>

       <tbody>

<?php

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

if ($search != "") {

    $sql = "SELECT * FROM customers
            WHERE customer_name LIKE ?
               OR account_number LIKE ?
               OR phone LIKE ?
            ORDER BY id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    $keyword = "%$search%";

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $keyword,
        $keyword,
        $keyword
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

} else {

    $sql = "SELECT * FROM customers ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);

}

$number = 1;

while ($row = mysqli_fetch_assoc($result)) {

?>

<tr>

    <td><?php echo $number++; ?></td>

    <td><?php echo htmlspecialchars($row["customer_name"]); ?></td>

    <td><?php echo htmlspecialchars($row["account_number"]); ?></td>

    <td><?php echo htmlspecialchars($row["phone"]); ?></td>

    <td><?php echo htmlspecialchars($row["country"]); ?></td>

    <td><?php echo htmlspecialchars($row["address"]); ?></td>

    <td class="actions">

    <?php if (canEditCustomer()) { ?>

<a
href="customers.php?edit=<?php echo $row['id']; ?>"
class="table-btn editCustomer">
✏️
</a>

<?php } ?>

<?php if (canDeleteCustomer()) { ?>

<a
href="customers.php?delete=<?php echo $row['id']; ?>"
class="table-btn deleteCustomer"
onclick="return confirm('هل أنت متأكد من حذف هذا العميل؟');">
🗑️
</a>

<?php } ?>

</td>

</tr>

<?php

}

?>

</tbody>

    </table>


</section>

</main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>

<script src="../js/customers.js"></script>
</body>

</html>
