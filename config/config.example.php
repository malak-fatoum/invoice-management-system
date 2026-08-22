<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "invoice_system5";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed");
}

mysqli_set_charset($conn, "utf8mb4");

?>