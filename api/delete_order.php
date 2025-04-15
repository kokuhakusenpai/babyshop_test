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

// Use prepared statements
$stmt1 = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
$stmt1->bind_param("i", $order_id);
$stmt1->execute();

$stmt2 = $conn->prepare("DELETE FROM orders WHERE id = ?");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();

header("Location: ../admin/orders.php");