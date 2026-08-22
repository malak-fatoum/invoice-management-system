<?php

require_once "config.php";

if (!isset($_GET["id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Invoice ID is required"
    ]);

    exit();
}

$invoice_id = (int)$_GET["id"];
$sql = "SELECT
            invoices.*,
            customers.customer_name,
            customers.phone,
            customers.country,
            customers.address
        FROM invoices
        LEFT JOIN customers
        ON invoices.customer_id = customers.id
        WHERE invoices.id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$invoice = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM shipments WHERE invoice_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

mysqli_stmt_execute($stmt);

$shipment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$sql = "SELECT * FROM banks WHERE invoice_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

mysqli_stmt_execute($stmt);

$banks = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$sql = "SELECT * FROM invoice_items WHERE invoice_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$items = [];

while($row = mysqli_fetch_assoc($result)){

    $items[] = $row;

}

$sql = "SELECT * FROM additional_information WHERE invoice_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

mysqli_stmt_execute($stmt);

$additional = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

echo json_encode([

    "invoice"=>$invoice,

    "shipment"=>$shipment,

    "banks"=>$banks,

    "items"=>$items,

    "additional_information"=>$additional

], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);