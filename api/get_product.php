<?php
include "db.php";

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Thiếu ID"]);
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Không tìm thấy sản phẩm"]);
}
