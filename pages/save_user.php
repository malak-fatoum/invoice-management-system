<?php

require_once "../config/auth_check.php";
require_once "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: user.php");
    exit();
}

$full_name = trim($_POST["full_name"]);
$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$password = trim($_POST["password"]);

$hashed_password = "";

if ($password != "") {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

$hashed_password = "";

if ($password != "") {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

$confirm_password = trim($_POST["confirm_password"]);
$role = trim($_POST["role"]);
$status = trim($_POST["status"]);
$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

if (
    $full_name == "" ||
    $username == "" ||
    $email == "" ||
    $role == "" ||
    $status == ""
){
    header("Location: user.php?error=1");
    exit();
}

// إذا كانت إضافة مستخدم جديد
if ($id == 0) {

    if ($password == "" || $confirm_password == "") {

        header("Location: user.php?password=1");
        exit();

    }

    if ($password != $confirm_password) {

        header("Location: user.php?password=0");
        exit();

    }

} else {

    // عند التعديل كلمة المرور اختيارية
    if ($password != "" || $confirm_password != "") {

        if ($password != $confirm_password) {

            header("Location: user.php?password=0");
            exit();

        }

    }

}

if ($id > 0) {

    // أثناء التعديل نتجاهل المستخدم الحالي
    $sql = "SELECT id FROM users
            WHERE (username=? OR email=?)
            AND id != ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $id);

} else {

    // أثناء الإضافة
    $sql = "SELECT id FROM users
            WHERE username=? OR email=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    header("Location: user.php?exists=1");
    exit();

}

if ($id > 0) {

    // تحديث مستخدم

    if ($password == "") {

        $sql = "UPDATE users
                SET full_name=?,
                    username=?,
                    email=?,
                    role=?,
                    status=?
                WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $full_name,
            $username,
            $email,
            $hashed_password,
            $role,
            $status,
            $id
        );

    } else {

        $sql = "UPDATE users
                SET full_name=?,
                    username=?,
                    email=?,
                    password=?,
                    role=?,
                    status=?
                WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $full_name,
            $username,
            $email,
            $hashed_password,
            $role,
            $status,
            $id
        );

    }

} else {

    // إضافة مستخدم جديد

    $sql = "INSERT INTO users
    (
        full_name,
        username,
        email,
        password,
        role,
        status
    )
    VALUES
    (
        ?,?,?,?,?,?
    )";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $full_name,
        $username,
        $email,
        $hashed_password,
        $role,
        $status
    );

}

mysqli_stmt_execute($stmt);

header("Location: user.php?saved=1");
exit();