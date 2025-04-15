<?php
// include "check_admin.php";
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data["id"]);
$name = $data["name"];
$price = floatval($data["price"]);
$image = $data["image"];
$description = $data["description"];

if ($id > 0) {
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=?, description=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $price, $image, $description, $id);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Cập nhật sản phẩm thành công!"]);
} else {
    $stmt = $conn->prepare("INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $image, $description);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Thêm sản phẩm thành công!"]);
}
