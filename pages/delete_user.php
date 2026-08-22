<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";
require_once "../config/permissions.php";



if (!canManageUsers()) {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: user.php");
    exit();
}

$id = (int)$_GET["id"];


// منع المستخدم من حذف حسابه الحالي
if ($id == $_SESSION["user_id"]) {

    header("Location: user.php?cannot_delete_self=1");
    exit();

}

$stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
if (mysqli_stmt_affected_rows($stmt) == 0) {

    header("Location: user.php?error=1");
    exit();

}

header("Location: user.php?deleted=1");
exit();