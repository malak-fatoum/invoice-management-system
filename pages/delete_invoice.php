<?php

require_once "../config/auth_check.php";
require_once "../config/permissions.php";
require_once "../config/config.php";
if (!canDeleteInvoice()) {

    header("Location: dashboard.php");
    exit();

}

if (!isset($_GET["id"])) {
  header("Location: savedInvoices.php");
exit();
}

$invoice_id = (int)$_GET["id"];

mysqli_begin_transaction($conn);

try {

    // حذف البنود
    $stmt = mysqli_prepare($conn, "DELETE FROM invoice_items WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt, "i", $invoice_id);
    mysqli_stmt_execute($stmt);

    // حذف الشحنة
    $stmt = mysqli_prepare($conn, "DELETE FROM shipments WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt, "i", $invoice_id);
    mysqli_stmt_execute($stmt);

    // حذف البنوك
    $stmt = mysqli_prepare($conn, "DELETE FROM banks WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt, "i", $invoice_id);
    mysqli_stmt_execute($stmt);

    // حذف المعلومات الإضافية
    $stmt = mysqli_prepare($conn, "DELETE FROM additional_information WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt, "i", $invoice_id);
    mysqli_stmt_execute($stmt);

    // حذف الفاتورة
    $stmt = mysqli_prepare($conn, "DELETE FROM invoices WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $invoice_id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);

} catch (Exception $e) {
mysqli_rollback($conn);

error_log(mysqli_error($conn));

header("Location: savedInvoices.php?error=1");
exit();
}

header("Location: savedInvoices.php?deleted=1");
exit();