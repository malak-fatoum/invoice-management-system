<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = (int)$_POST["customer_id"];
    $invoice_id = (int)$_SESSION["invoice_id"];

    $sql = "UPDATE invoices
            SET customer_id = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $customer_id, $invoice_id);

    if (mysqli_stmt_execute($stmt)) {

        header("Location: shipment.php");
        exit();

    } else {

        error_log(mysqli_error($conn));

die("حدث خطأ أثناء حذف الفاتورة.");

    }

}