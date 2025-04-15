<?php
// include "check_admin.php";
include "db.php";

$sql = "SELECT id, fullname, phone, address, total_price, created_at FROM orders ORDER BY id DESC";
$result = $conn->query($sql);

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
