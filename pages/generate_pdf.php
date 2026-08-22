<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";
require_once "../fpdf/fpdf.php";




$invoice_id = (int)$_GET["id"];

$stmt = mysqli_prepare($conn,"
SELECT invoices.*,customers.customer_name
FROM invoices
INNER JOIN customers
ON invoices.customer_id=customers.id
WHERE invoices.id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$invoice=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$stmt = mysqli_prepare($conn,"
SELECT *
FROM shipments
WHERE invoice_id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$shipment=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$stmt=mysqli_prepare($conn,"
SELECT *
FROM invoice_items
WHERE invoice_id=?
");

mysqli_stmt_bind_param($stmt,"i",$invoice_id);
mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont("Arial","B",18);

$pdf->Cell(190,10,"INVOICE",0,1,"C");

$pdf->Ln(10);

$pdf->SetFont("Arial","",12);

$pdf->Cell(95,8,"Invoice Number: ".$invoice["invoice_number"],0,0);

$pdf->Cell(95,8,"Date: ".$invoice["invoice_date"],0,1);

$pdf->Cell(95,8,"Customer: ".$invoice["customer_name"],0,0);

$pdf->Cell(95,8,"Currency: ".$invoice["currency"],0,1);

$pdf->Cell(95,8,"Issued By: ".$invoice["issued_by"],0,1);

$pdf->Ln(8);

$pdf->SetFont("Arial","B",12);

$pdf->Cell(190,8,"Shipment Information",1,1,"C");

$pdf->SetFont("Arial","",11);

$pdf->Cell(95,8,"Master BL: ".$shipment["master_bl"],1,0);

$pdf->Cell(95,8,"House BL: ".$shipment["house_bl"],1,1);

$pdf->Cell(95,8,"Customs No: ".$shipment["customs_number"],1,0);

$pdf->Cell(95,8,"Customs Type: ".$shipment["customs_type"],1,1);

$pdf->Cell(95,8,"Shipper: ".$shipment["shipper"],1,0);

$pdf->Cell(95,8,"Consignee: ".$shipment["consignee"],1,1);

$pdf->Cell(190,8,"Cargo: ".$shipment["cargo_description"],1,1);

$pdf->Ln(10);

$pdf->SetFont("Arial","B",12);

$pdf->Cell(90,10,"Description",1,0,"C");
$pdf->Cell(30,10,"Qty",1,0,"C");
$pdf->Cell(35,10,"Price",1,0,"C");
$pdf->Cell(35,10,"Total",1,1,"C");

$pdf->SetFont("Arial","",11);

$grandTotal = 0;

while($item = mysqli_fetch_assoc($result)){

    $pdf->Cell(90,10,$item["description"],1);

    $pdf->Cell(30,10,$item["quantity"],1,0,"C");

    $pdf->Cell(35,10,number_format($item["unit_price"],2),1,0,"C");

    $pdf->Cell(35,10,number_format($item["total"],2),1,1,"C");

    $grandTotal += $item["total"];

}

$pdf->Ln(8);

$pdf->SetFont("Arial","B",14);

$pdf->Cell(155,10,"Grand Total",1);

$pdf->Cell(35,10,number_format($grandTotal,2),1,1,"C");

$pdf->Output("D","Invoice_".$invoice["invoice_number"].".pdf");