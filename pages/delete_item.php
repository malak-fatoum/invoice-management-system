<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

$id=(int)$_POST["id"];

$sql="DELETE FROM invoice_items WHERE id=?";

$stmt=mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$id);

mysqli_stmt_execute($stmt);

echo "success";