<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $invoice_id = $_SESSION["invoice_id"];

    $master_bl = trim($_POST["master_bl"]);
    $house_bl = trim($_POST["house_bl"]);
    $customs_number = trim($_POST["customs_number"]);
    $customs_type = $_POST["customs_type"];
    $shipper = trim($_POST["shipper"]);
    $consignee = trim($_POST["consignee"]);
    $cargo_description = trim($_POST["cargo_description"]);

    $sql = "INSERT INTO shipments
    (
        invoice_id,
        master_bl,
        house_bl,
        customs_number,
        customs_type,
        shipper,
        consignee,
        cargo_description
    )
    VALUES (?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(
        $stmt,
        "isssssss",
        $invoice_id,
        $master_bl,
        $house_bl,
        $customs_number,
        $customs_type,
        $shipper,
        $consignee,
        $cargo_description
    );

    if(mysqli_stmt_execute($stmt)){

        header("Location: banks.php");
        exit();

    }else{

        error_log(mysqli_error($conn));

die("حدث خطأ أثناء حذف الفاتورة.");

    }

}