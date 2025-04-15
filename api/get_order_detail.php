<?php
include "db.php";

$order_id = intval($_GET['id']);

$sql = "SELECT p.name AS product_name, oi.quantity, oi.price
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$details = [];

while ($row = $result->fetch_assoc()) {
    $details[] = $row;
}

echo json_encode($details);
