<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if(!isset($_GET["id"])){

    die("Invoice not found.");

}

$invoice_id = (int)$_GET["id"];

$stmt = mysqli_prepare($conn,"
UPDATE invoices
SET status='Paid'
WHERE id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

mysqli_stmt_execute($stmt);

header("Location: savedInvoices.php");

exit();