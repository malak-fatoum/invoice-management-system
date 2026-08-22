<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$_SESSION["items"] = $data;

echo "success";