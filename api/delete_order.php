<?php
$conn = new mysqli("localhost", "root", "", "babyshop");
$conn->set_charset("utf8");

$order_id = intval($_POST['order_id']);

// Xóa order_items trước
$conn->query("DELETE FROM order_items WHERE order_id = $order_id");

// Xóa đơn hàng
$conn->query("DELETE FROM orders WHERE id = $order_id");

header("Location: ../admin/orders.php");
