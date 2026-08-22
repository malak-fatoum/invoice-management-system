<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if (!isset($_SESSION["invoice_id"])) {
    exit("No Invoice");
}

$invoice_id = $_SESSION["invoice_id"];

$description = trim($_POST["description"]);
$quantity = $_POST["quantity"];
$unit_price = $_POST["unit_price"];
$total = $_POST["total"];

$sql = "INSERT INTO invoice_items
(
    invoice_id,
    description,
    quantity,
    unit_price,
    total
)
VALUES (?,?,?,?,?)";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(
    $stmt,
    "isddd",
    $invoice_id,
    $description,
    $quantity,
    $unit_price,
    $total
);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {

    echo "success";

} else {

    echo mysqli_error($conn);

}

mysqli_stmt_close($stmt);
mysqli_close($conn);
