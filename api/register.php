<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data["fullname"]) || !isset($data["username"]) || !isset($data["email"]) || !isset($data["password"])) {
    echo json_encode(["success" => false, "message" => "Thiếu thông tin đăng ký"]);
    exit;
}

$fullname = $conn->real_escape_string($data["fullname"]);
$username = $conn->real_escape_string($data["username"]);
$email = $conn->real_escape_string($data["email"]);
$password = password_hash($data["password"], PASSWORD_BCRYPT);

// Validate username format
if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
    echo json_encode(["success" => false, "message" => "Tên đăng nhập không hợp lệ"]);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Email không hợp lệ"]);
    exit;
}

// Check if username exists
$check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_username->bind_param("s", $username);
$check_username->execute();
$check_username->store_result();

if ($check_username->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Tên đăng nhập đã được sử dụng"]);
    exit;
}

// Check if email exists
$check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email đã được sử dụng"]);
    exit;
}

// Insert new user
$sql = "INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, 'user')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $fullname, $username, $email, $password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Đăng ký thành công"]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi: " . $stmt->error]);
}