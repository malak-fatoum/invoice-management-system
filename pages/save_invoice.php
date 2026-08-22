<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

mysqli_begin_transaction($conn);

try {

    $invoice   = $_SESSION["invoice"] ?? [];
    $shipment  = $_SESSION["shipment"] ?? [];
    $banks     = $_SESSION["banks"] ?? [];
    $additional= $_SESSION["additional"] ?? [];
    $items     = $_SESSION["items"] ?? [];

    $customer_id = $_SESSION["customer"]["customer_id"] ?? 0;

    $created_by = $_SESSION["user_id"];

}
catch(Exception $e){

    die($e->getMessage());

}

$sql = "INSERT INTO invoices
(
    invoice_number,
    invoice_date,
    currency,
    issued_by,
    issued_by_en,
    invoice_statement,
    customer_id,
    created_by
)
VALUES
(
    ?,?,?,?,?,?,?,?
)";

$invoice_number    = $invoice["invoice_number"];
$invoice_date      = $invoice["invoice_date"];
$currency          = $invoice["currency"];
$issued_by         = $invoice["issued_by"];
$invoice_statement = $invoice["invoice_statement"];
$issued_by_en = $invoice["issued_by_en"];

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssssii",
    $invoice_number,
    $invoice_date,
    $currency,
    $issued_by,
    $issued_by_en,
    $invoice_statement,
    $customer_id,
    $created_by
);

mysqli_stmt_execute($stmt);

$invoice_id = mysqli_insert_id($conn);

$sql = "INSERT INTO shipments
(
    invoice_id,
    master_bl,
    house_bl,
    customs_number,
    customs_type,
    customs_type_en,
    shipper,
    shipper_en,
    consignee,
    consignee_en,
    cargo_description,
    cargo_description_en
)
VALUES
(
    ?,?,?,?,?,?,?,?,?,?,?,?
)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "isssssssssss",

    $invoice_id,
    $shipment["master_bl"],
    $shipment["house_bl"],
    $shipment["customs_number"],
    $shipment["customs_type"],
    $shipment["customs_type_en"],
    $shipment["shipper"],
    $shipment["shipper_en"],
    $shipment["consignee"],
    $shipment["consignee_en"],
    $shipment["cargo_description"],
    $shipment["cargo_description_en"]

);

mysqli_stmt_execute($stmt);
$sql = "INSERT INTO banks
(
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
)
VALUES
(
    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(

    $stmt,

    "issssssssssssss",

    $invoice_id,
    $banks["jod_bank_name"],
    $banks["jod_branch"],
    $banks["jod_iban"],
    $banks["jod_account"],
    $banks["jod_company"],

    $banks["usd_bank_name"],
    $banks["usd_branch"],
    $banks["usd_iban"],
    $banks["usd_account"],
    $banks["usd_company"],

    $banks["cliq_details"],
    $banks["cliq_id"],
    $banks["cliq_name"],
    $banks["cliq_bank"]

);

mysqli_stmt_execute($stmt);

$sql = "INSERT INTO additional_information
(
    invoice_id,
    national_no,
    tax_no,
    website,
    email,
    mobile,
    address,
    address_en
)
VALUES
(
    ?,?,?,?,?,?,?,?
)";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

    $stmt,

  "isssssss",  

$invoice_id,
$additional["national_no"],
$additional["tax_no"],
$additional["website"],
$additional["email"],
$additional["mobile"],
$additional["address"],
$additional["address_en"]

);

mysqli_stmt_execute($stmt);

foreach($items as $item){

    $sql = "INSERT INTO invoice_items
    (
        invoice_id,
        description,
        quantity,
        unit_price,
        total
    )
    VALUES
    (
        ?,?,?,?,?
    )";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param(

        $stmt,

        "isidd",

        $invoice_id,
        $item["description"],
        $item["quantity"],
        $item["unit_price"],
        $item["total"]

    );

    mysqli_stmt_execute($stmt);

}

mysqli_commit($conn);

unset(
    $_SESSION["invoice"],
    $_SESSION["customer"],
    $_SESSION["shipment"],
    $_SESSION["banks"],
    $_SESSION["items"],
    $_SESSION["additional"]
);

$_SESSION["last_invoice_id"] = $invoice_id;

header("Location: savedInvoices.php?saved=1");
exit();
if (empty($_SESSION["invoice"]["invoice_number"]) ||
    empty($_SESSION["invoice"]["invoice_date"])) {

    header("Location: invoice.php?error=missing_invoice");
    exit();
}