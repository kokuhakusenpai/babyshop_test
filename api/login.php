<?php
include "db.php";
session_start();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$data = json_decode(file_get_contents("php://input"), true);
$username = $conn->real_escape_string($data['username'] ?? '');
$password = $data['password'] ?? '';

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Use password_verify to check hashed password
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Send back minimal information (no password)
        echo json_encode([
            "success" => true, 
            "role" => $user['role'],
            "username" => $user['username']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Sai tài khoản hoặc mật khẩu"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Sai tài khoản hoặc mật khẩu"]);
}