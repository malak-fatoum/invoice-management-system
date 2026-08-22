<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if(!isset($_GET["id"])){

    die("Invoice not found");

}

$invoice_id = (int)$_GET["id"];

// invoice

$stmt = mysqli_prepare($conn,"
SELECT *
FROM invoices
WHERE id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$_SESSION["invoice"] =
mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// customer

$_SESSION["customer"] = [

    "customer_id" => $_SESSION["invoice"]["customer_id"]

];

// shipment

$stmt = mysqli_prepare($conn,"
SELECT *
FROM shipments
WHERE invoice_id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$_SESSION["shipment"] =
mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// banks

$stmt = mysqli_prepare($conn,"
SELECT *
FROM banks
WHERE invoice_id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$_SESSION["banks"] =
mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// additional information

$stmt = mysqli_prepare($conn,"
SELECT *
FROM additional_information
WHERE invoice_id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$_SESSION["additional"] =
mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// invoice items

$stmt = mysqli_prepare($conn,"
SELECT *
FROM invoice_items
WHERE invoice_id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$_SESSION["items"] = [];

while($row = mysqli_fetch_assoc($result)){

    $_SESSION["items"][] = $row;

}

$_SESSION["edit_invoice_id"] = $invoice_id;

header("Location: invoice.php?edit=".$invoice_id);
exit();