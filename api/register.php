<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$fullname = $data["fullname"];
$email = $data["email"];
$password = password_hash($data["password"], PASSWORD_BCRYPT);

// Kiểm tra tồn tại
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email đã được sử dụng"]);
    exit;
}

$sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fullname, $email, $password);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Không thể đăng ký"]);
}
