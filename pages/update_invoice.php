<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if (!isset($_SESSION["edit_invoice_id"])) {

    die("Invoice not found.");

}

$invoice_id = $_SESSION["edit_invoice_id"];

$invoice   = $_SESSION["invoice"];
$customer  = $_SESSION["customer"];
$shipment  = $_SESSION["shipment"];
$banks     = $_SESSION["banks"];
$additional= $_SESSION["additional"];
$items     = $_SESSION["items"];

mysqli_begin_transaction($conn);

$sql = "UPDATE invoices SET

invoice_number=?,
invoice_date=?,
currency=?,
issued_by=?,
issued_by_en=?,
invoice_statement=?,
customer_id=?

WHERE id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

$stmt,

"ssssssii",

$invoice["invoice_number"],
$invoice["invoice_date"],
$invoice["currency"],
$invoice["issued_by"],
$invoice["issued_by_en"],
$invoice["invoice_statement"],
$customer["customer_id"],
$invoice_id
);

mysqli_stmt_execute($stmt);

$sql = "UPDATE shipments SET

master_bl=?,
house_bl=?,
customs_number=?,
customs_type=?,
customs_type_en=?,
shipper=?,
shipper_en=?,
consignee=?,
consignee_en=?,
cargo_description=?,
cargo_description_en=?

WHERE invoice_id=?";
$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

$stmt,

"sssssssssssi",

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
$shipment["cargo_description_en"],
$invoice_id

);

mysqli_stmt_execute($stmt);

$sql = "UPDATE banks SET

jod_bank_name=?,
jod_branch=?,
jod_iban=?,
jod_account=?,
jod_company=?,
usd_bank_name=?,
usd_branch=?,
usd_iban=?,
usd_account=?,
usd_company=?,
cliq_details=?,
cliq_id=?,
cliq_name=?,
cliq_bank=?

WHERE invoice_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

$stmt,

"ssssssssssssssi",

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
$banks["cliq_bank"],
$invoice_id

);

mysqli_stmt_execute($stmt);
$sql = "UPDATE additional_information SET

national_no=?,
tax_no=?,
website=?,
email=?,
mobile=?,
address=?,
address_en=?

WHERE invoice_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(

$stmt,

"sssssssi",

$additional["national_no"],
$additional["tax_no"],
$additional["website"],
$additional["email"],
$additional["mobile"],
$additional["address"],
$additional["address_en"],
$invoice_id

);

mysqli_stmt_execute($stmt);

// حذف البنود القديمة

$sql = "DELETE FROM invoice_items WHERE invoice_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

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

if(mysqli_errno($conn)){

    mysqli_rollback($conn);

    error_log(mysqli_error($conn));
die("حدث خطأ أثناء تنفيذ العملية.");

}

mysqli_commit($conn);

unset(
    $_SESSION["invoice"],
    $_SESSION["customer"],
    $_SESSION["shipment"],
    $_SESSION["banks"],
    $_SESSION["items"],
    $_SESSION["additional"],
    $_SESSION["edit_invoice_id"]
);

header("Location: savedInvoices.php?updated=1");
exit();