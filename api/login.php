<?php
include "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$password = $data['password'];

$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // 🆕 Thêm dòng này
    echo json_encode(["success" => true, "role" => $user['role']]);
} else {
    echo json_encode(["success" => false, "message" => "Sai tài khoản hoặc mật khẩu"]);
}
