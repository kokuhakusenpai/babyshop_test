<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$fullname = $data['fullname'];
$phone = $data['phone'];
$address = $data['address'];
$cart = $data['cart'];
$user_id = isset($data['user_id']) ? intval($data['user_id']) : null;

// Tính tổng tiền
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Tạm thời để user_id là NULL vì chưa đăng nhập
$sql = "INSERT INTO orders (user_id, fullname, phone, address, total_price) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssd", $user_id, $fullname, $phone, $address, $total);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;

    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

    foreach ($cart as $item) {
        $stmt_items->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $stmt_items->execute();
    }

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
