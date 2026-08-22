<?php

require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"] ?? "";
$password = $data["password"] ?? "";

$sql = "SELECT * FROM users WHERE username=?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){

    echo json_encode([
        "success"=>false,
        "message"=>"User not found"
    ]);

    exit();
}

$user = mysqli_fetch_assoc($result);

if(password_verify($password,$user["password"])){

    echo json_encode([

        "success"=>true,

        "user"=>[

            "id"=>$user["id"],
            "full_name"=>$user["full_name"],
            "role"=>$user["role"]

        ]

    ],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

}else{

    echo json_encode([

        "success"=>false,
        "message"=>"Wrong password"

    ]);

}