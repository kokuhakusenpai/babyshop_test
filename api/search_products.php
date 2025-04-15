<?php
include "db.php";

$keyword = $_GET['keyword'] ?? '';
$keyword = '%' . $conn->real_escape_string($keyword) . '%';

$sql = "SELECT * FROM products WHERE name LIKE ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
