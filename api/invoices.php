<?php

require_once "config.php";

$sql = "SELECT
            invoices.*,
            customers.customer_name
        FROM invoices
        LEFT JOIN customers
        ON invoices.customer_id = customers.id
        ORDER BY invoices.id DESC";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);