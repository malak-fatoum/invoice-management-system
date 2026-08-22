<?php

require_once "../config/auth_check.php";

header("Content-Type: application/json");

echo json_encode($_SESSION["items"] ?? []);