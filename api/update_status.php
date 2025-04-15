<?php
session_start();
include "db.php";

// Check admin permission
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid request");
}

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$status = $_POST['status'] ?? '';
$allowed_statuses = ["Chờ xử lý", "Đang giao", "Hoàn tất", "Đã hủy"];

// Validate status
if (!in_array($status, $allowed_statuses)) {
    die("Invalid status");
}

// Use prepared statement
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

header("Location: ../admin/orders.php");