<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $invoice_id = $_SESSION["invoice_id"];

    // بيانات الحساب المحلي
    $jod_bank_name = trim($_POST["jod_bank_name"]);
    $jod_branch = trim($_POST["jod_branch"]);
    $jod_iban = trim($_POST["jod_iban"]);
    $jod_account = trim($_POST["jod_account"]);
    $jod_company = trim($_POST["jod_company"]);

    // بيانات الحساب الدولي
    $usd_bank_name = trim($_POST["usd_bank_name"]);
    $usd_branch = trim($_POST["usd_branch"]);
    $usd_iban = trim($_POST["usd_iban"]);
    $usd_account = trim($_POST["usd_account"]);
    $usd_company = trim($_POST["usd_company"]);

    // بيانات CliQ
    $cliq_details = trim($_POST["cliq_details"]);
    $cliq_id = trim($_POST["cliq_id"]);
    $cliq_name = trim($_POST["cliq_name"]);
    $cliq_bank = trim($_POST["cliq_bank"]);

    $sql = "INSERT INTO banks (
        invoice_id,
        jod_bank_name,
        jod_branch,
        jod_iban,
        jod_account,
        jod_company,
        usd_bank_name,
        usd_branch,
        usd_iban,
        usd_account,
        usd_company,
        cliq_details,
        cliq_id,
        cliq_name,
        cliq_bank
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "issssssssssssss",
        $invoice_id,
        $jod_bank_name,
        $jod_branch,
        $jod_iban,
        $jod_account,
        $jod_company,
        $usd_bank_name,
        $usd_branch,
        $usd_iban,
        $usd_account,
        $usd_company,
        $cliq_details,
        $cliq_id,
        $cliq_name,
        $cliq_bank
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: items.php");
        exit();

    } else {

        die("Error: " . mysqli_error($conn));

    }

}