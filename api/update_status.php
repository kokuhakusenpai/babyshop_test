<?php
$conn = new mysqli("localhost", "root", "", "babyshop");
$conn->set_charset("utf8");

$order_id = intval($_POST['order_id']);
$status = $conn->real_escape_string($_POST['status']);

$conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
header("Location: ../admin/orders.php");
