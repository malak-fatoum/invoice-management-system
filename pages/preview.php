<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

$invoice = $_SESSION["invoice"] ?? [];
$shipment = $_SESSION["shipment"] ?? [];
$banks = $_SESSION["banks"] ?? [];
$additional = $_SESSION["additional"] ?? [];
$items = $_SESSION["items"] ?? [];

if(isset($_GET["id"])){

    $invoice_id = (int)$_GET["id"];

    // الفاتورة
    $stmt = mysqli_prepare($conn,"SELECT * FROM invoices WHERE id=?");
    mysqli_stmt_bind_param($stmt,"i",$invoice_id);
    mysqli_stmt_execute($stmt);
    $invoice = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $_SESSION["invoice"] = [
    "invoice_number"    => $invoice["invoice_number"],
    "invoice_date"      => $invoice["invoice_date"],
    "currency"          => $invoice["currency"],
    "issued_by"         => $invoice["issued_by"],
    "invoice_statement" => $invoice["invoice_statement"]
];

    // الشحنة
    $stmt = mysqli_prepare($conn,"SELECT * FROM shipments WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt,"i",$invoice_id);
    mysqli_stmt_execute($stmt);
    $shipment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // البنوك
    $stmt = mysqli_prepare($conn,"SELECT * FROM banks WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt,"i",$invoice_id);
    mysqli_stmt_execute($stmt);
    $banks = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // المعلومات الإضافية
    $stmt = mysqli_prepare($conn,"SELECT * FROM additional_information WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt,"i",$invoice_id);
    mysqli_stmt_execute($stmt);
    $additional = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // البنود
    $stmt = mysqli_prepare($conn,"SELECT * FROM invoice_items WHERE invoice_id=?");
    mysqli_stmt_bind_param($stmt,"i",$invoice_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $items = [];

    while($row=mysqli_fetch_assoc($result)){

        $items[]=$row;

    }

    $_SESSION["customer"]["customer_id"] = $invoice["customer_id"];

}

$customer = [];

if (!empty($_SESSION["customer"]["customer_id"])) {

    $customer_id = $_SESSION["customer"]["customer_id"];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM customers WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt, "i", $customer_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $customer = mysqli_fetch_assoc($result);

}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head> 
<meta name="language" content="Arabic">
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>معاينة الفاتورة</title>

      <link rel="stylesheet" href="../css/preview.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="invoice-page">

    <div class="invoice-header">

    <div class="invoice-title">
        <h1>فاتورة</h1>
        <h3>INVOICE</h3>
    </div>

    <div class="company">
        <div>
            <h2>العقرباوي</h2>
            <p>للنقل والشحن الدولي</p>
            <span>AL-AQRABAWI TRANSPORT & SHIPPING</span>
        </div>

        <img src="../assets/logo.svg" alt="Logo">
    </div>

</div>

<div class="invoice-info">

    <div class="info-column">

        <table>
            <tr>
                <td>Invoice No.</td>
                <td>:</td>
                <td><?= htmlspecialchars($invoice["invoice_number"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Invoice Date</td>
                <td>:</td>
                <td><?= htmlspecialchars($invoice["invoice_date"] ?? "") ?></td>            
            </tr>

            <tr>
                <td>Currency</td>
                <td>:</td>
                <td><?= htmlspecialchars($invoice["currency"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Customer Name</td>
                  <td>:</td>
                 <td><?= htmlspecialchars($customer["customer_name_en"] ?? "") ?></td>  
            </tr>

            <tr>
                <td>Customer Account No.</td>
                <td>:</td>
                <td><?= htmlspecialchars($customer["account_number"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Master B/L / Booking No.</td>
                <td>:</td>
                <td><?= htmlspecialchars($shipment["master_bl"] ?? "") ?></td>
            </tr>

            <tr>
                <td>House B/L</td>
                <td>:</td>
                <td><?= htmlspecialchars($shipment["house_bl"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Custom Declaration No. & Type</td>
                <td>:</td>
                <td>
                <?= htmlspecialchars(($shipment["customs_number"] ?? "") . " - " . ($shipment["customs_type_en"] ?? "")) ?>
                </td>
            </tr>
<tr>
    <td>Shipper</td>
    <td>:</td>
    <td> <?= htmlspecialchars($shipment["shipper_en"] ?? "") ?></td>
</tr>

<tr>
    <td>Consignee</td>
    <td>:</td>
    <td><?= htmlspecialchars($shipment["consignee_en"] ?? "") ?></td>
</tr>

<tr>
    <td>Cargo Description</td>
    <td>:</td>
    <td><?= htmlspecialchars($shipment["cargo_description_en"] ?? "") ?></td>
</tr>
        </table>

    </div>

    <div class="info-column">

        <table>

            <tr>
                <td>رقم الفاتورة</td>
                <td>:</td>
                <td id="invoiceNoAr"><?= htmlspecialchars($invoice["invoice_number"] ?? "") ?></td>
            </tr>

            <tr>
                <td>تاريخ إصدار الفاتورة</td>
                <td>:</td>
                <td><?= htmlspecialchars($invoice["invoice_date"] ?? "") ?></td>
            </tr>

            <tr>
                <td>العملة</td>
                <td>:</td>
                <td><?= htmlspecialchars($invoice["currency"] ?? "") ?></td>
            </tr>
            <tr>
                <td>اسم العميل</td>
                <td>:</td>
                <td><?= htmlspecialchars($customer["customer_name"] ?? "") ?></td>
            </tr>

            <tr>
                <td>رقم حساب العميل</td>
                <td>:</td>
                <td><?= htmlspecialchars($customer["account_number"] ?? "") ?></td>
            </tr>

            <tr>
                <td>رقم البوليصة الماستر / البوكينج</td>
                <td>:</td>
                <td><?= htmlspecialchars($shipment["master_bl"] ?? "") ?></td>
            </tr>

            <tr>
                <td>رقم البوليصة الفرعي</td>
                <td>:</td>
                <td><?= htmlspecialchars($shipment["house_bl"] ?? "") ?></td>
            </tr>

            <tr>
                <td>رقم البيان الجمركي ونوعه</td>
                <td>:</td>
                <td>
                    <?= htmlspecialchars(($shipment["customs_number"] ?? "") . " - " . ($shipment["customs_type"] ?? "")) ?>
                </td>
            </tr>
<tr>
    <td>اسم المرسل</td>
    <td>:</td>
    <td><?= htmlspecialchars($shipment["shipper"] ?? "") ?></td>
</tr>

<tr>
    <td>اسم المرسل إليه</td>
    <td>:</td>
    <td><?= htmlspecialchars($shipment["consignee"] ?? "") ?></td>
</tr>

<tr>
    <td>وصف البضاعة</td>
    <td>:</td>
    <td><?= htmlspecialchars($shipment["cargo_description"] ?? "") ?></td>
</tr>
        </table>

    </div>

</div>
<div class="invoice-note">

    <span class="note-title">بيان الفاتورة :</span>

    <span class="note-line">
    <?= htmlspecialchars($invoice["invoice_statement"] ?? "") ?>
</span>

</div>

<table class="invoice-table">

    <thead>

        <tr>

            <th style="width:8%">No.</th>

            <th style="width:48%">Description / البيان</th>

            <th style="width:12%">Qty</th>

            <th style="width:16%">Unit Price</th>

            <th style="width:16%">Amount</th>

        </tr>

    </thead>
<tbody>

<?php

$total = 0;

foreach($items as $index => $item){

    $total += $item["total"];

?>

<tr>

    <td><?= $index + 1 ?></td>

    <td><?= htmlspecialchars($item["description"]) ?></td>

    <td><?= htmlspecialchars($item["quantity"]) ?></td>

    <td><?= number_format($item["unit_price"],2) ?></td>

    <td><?= number_format($item["total"],2) ?></td>

</tr>

<?php } ?>

</tbody>

</table><div class="invoice-summary">

    <span>الإجمالي الكلي / Total</span>

<strong><?= number_format($total,2) ?></strong>
</div>

<div class="invoice-warning">

    <p class="warning-ar">
        في حال وجود أي اعتراض على هذه الفاتورة أو على أي بند من بنودها يجب تقديم الاعتراض خطياً خلال مدة أقصاها عشرة (10) أيام من تاريخ الاستلام، وبعد انقضاء هذه المدة تعتبر الفاتورة نهائية ومقبولة وغير قابلة للاعتراض.
    </p>

    <p class="warning-en">
        Any objection to this invoice or any of its items must be submitted in writing within a maximum period of ten (10) days from the date of receipt; otherwise, the invoice shall be deemed final, accepted, and non-objectionable.
    </p>

</div>

<table class="bank-table">

    <tr class="bank-title">

        <th colspan="2">Bank Details (USD)</th>

        <th colspan="2">Bank Details (JOD)</th>

    </tr>

    <tr>

        <td><strong>BANK NAME</strong></td>
        <td><?= htmlspecialchars($banks["usd_bank_name"] ?? "") ?></td>

        <td><?= htmlspecialchars($banks["jod_bank_name"] ?? "") ?></td>
        <td><strong>BANK NAME</strong></td>

    </tr>

    <tr>

        <td><strong>BRANCH</strong></td>
        <td><?= htmlspecialchars($banks["usd_branch"] ?? "") ?></td>

        <td><?= htmlspecialchars($banks["jod_branch"] ?? "") ?></td>
        <td><strong>BRANCH</strong></td>

    </tr>

    <tr>

        <td><strong>IBAN</strong></td>
        <td><?= htmlspecialchars($banks["usd_iban"] ?? "") ?></td>

        <td><?= htmlspecialchars($banks["jod_iban"] ?? "") ?></td>
        <td><strong>IBAN</strong></td>

    </tr>

    <tr>

        <td><strong>ACCOUNT NO.</strong></td>
        <td><?= htmlspecialchars($banks["usd_account"] ?? "") ?></td>

        <td><?= htmlspecialchars($banks["jod_account"] ?? "") ?></td>
        <td><strong>ACCOUNT NO.</strong></td>

    </tr>

    <tr>

        <td><strong>COMPANY</strong></td>
        <td><?= htmlspecialchars($banks["usd_company"] ?? "") ?></td>

        <td><?= htmlspecialchars($banks["jod_company"] ?? "") ?></td>
        <td><strong>COMPANY</strong></td>

    </tr>

<tr class="cliq-row">

    <td colspan="4">

<div class="cliq-info">

    <span><strong>CliQ Details:</strong> <span><?= htmlspecialchars($banks["cliq_details"] ?? "") ?></span></span>

    <span><strong>CliQ ID:</strong> <span><?= htmlspecialchars($banks["cliq_id"] ?? "") ?></span></span>

    <span><strong>CliQ Name:</strong> <span><?= htmlspecialchars($banks["cliq_name"] ?? "") ?></span></span>

    <span><strong>Bank:</strong> <span><?= htmlspecialchars($banks["cliq_bank"] ?? "") ?></span></span>

</div>

</td>

</tr>
</table>
  


<div class="company-footer">
<div class="footer-left">

    <p><strong>National Number:</strong> <span><?= htmlspecialchars($additional["national_no"] ?? "") ?></span></p>
    <p><strong>Tax ID Number:</strong> <span><?= htmlspecialchars($additional["tax_no"] ?? "") ?></span></p>
    <p><strong>Website:</strong> <span><?= htmlspecialchars($additional["website"] ?? "") ?></span></p>
    <p><strong>Email:</strong> <span><?= htmlspecialchars($additional["email"] ?? "") ?></span></p>
    <p><strong>Mobile:</strong> <span><?= htmlspecialchars($additional["mobile"] ?? "") ?></span></p>
    <p><strong>Address:</strong> <span><?= htmlspecialchars($additional["address_en"] ?? "") ?></span></p>

</div>

<div class="footer-right">

    <p><strong>الرقم الوطني:</strong> <span><?= htmlspecialchars($additional["national_no"] ?? "") ?></span></p>
    <p><strong>الرقم الضريبي:</strong> <span><?= htmlspecialchars($additional["tax_no"] ?? "") ?></span></p>
    <p><strong>الموقع الإلكتروني:</strong> <span><?= htmlspecialchars($additional["website"] ?? "") ?></span></p>
    <p><strong>البريد الإلكتروني:</strong> <span><?= htmlspecialchars($additional["email"] ?? "") ?></span></p>
    <p><strong>الهاتف المتحرك:</strong> <span><?= htmlspecialchars($additional["mobile"] ?? "") ?></span></p>
    <p><strong>العنوان:</strong> <span><?= htmlspecialchars($additional["address"] ?? "") ?></span></p>

</div>
</div>

<div class="issued-by">

    <div>
        <strong>Issued By:</strong>
        <?= htmlspecialchars($invoice["issued_by_en"] ?? "") ?>
    </div>

    <div>
        <strong>اسم مصدر الفاتورة:</strong>
        <?= htmlspecialchars($invoice["issued_by"] ?? "") ?>
    </div>

</div>

<div class="invoice-actions">

 <form action="<?= isset($_SESSION['edit_invoice_id']) ? 'update_invoice.php' : 'save_invoice.php' ?>" method="POST" id="saveInvoiceForm">

    <button type="submit" class="btn-save">
        💾 حفظ الفاتورة
    </button>

</form>

    <button class="btn-print" onclick="window.print()">
        🖨️ طباعة / حفظ PDF
    </button>

</div>

<script>

document.getElementById("saveInvoiceForm").addEventListener("submit", function(e){

    if(!confirm("هل أنت متأكد من حفظ الفاتورة؟")){

        e.preventDefault();

    }

});

</script>

</body>
</html>