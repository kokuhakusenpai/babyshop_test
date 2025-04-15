<?php
// include "check_admin.php";
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data["id"]);

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["success" => true, "message" => "Đã xoá sản phẩm"]);
