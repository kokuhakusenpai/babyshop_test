<?php
// Kết nối DB
include "db.php";

// Nhận JSON từ JS
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['name']) || !isset($data['phone']) || !isset($data['address']) || !isset($data['cart'])) {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu thông tin đặt hàng"]);
    exit;
}

$name = $conn->real_escape_string($data['name']);
$phone = $conn->real_escape_string($data['phone']);
$address = $conn->real_escape_string($data['address']);
$cart = $data['cart'];

// Validate cart
if (!is_array($cart) || empty($cart)) {
    http_response_code(400);
    echo json_encode(["error" => "Giỏ hàng không hợp lệ"]);
    exit;
}

// Tính tổng
$total = 0;
foreach ($cart as $item) {
    if (!isset($item['price']) || !isset($item['quantity'])) {
        continue;
    }
    $total += floatval($item['price']) * intval($item['quantity']);
}

// Use prepared statement for orders
$stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, total_price, status) 
                       VALUES (?, ?, ?, ?, 'Chờ xử lý')");
$stmt->bind_param("sssd", $name, $phone, $address, $total);
$stmt->execute();

$order_id = $conn->insert_id;

// Use prepared statement for order_items
$stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");

// Lưu vào order_items
foreach ($cart as $item) {
    if (!isset($item['name']) || !isset($item['price']) || !isset($item['quantity'])) {
        continue;
    }
    
    $pname = $conn->real_escape_string($item['name']);
    $qty = intval($item['quantity']);
    $price = floatval($item['price']);

    $stmt_item->bind_param("isid", $order_id, $pname, $qty, $price);
    $stmt_item->execute();
}

echo json_encode([
    "success" => true, 
    "message" => "🎉 Đặt hàng thành công! Mã đơn hàng: #$order_id",
    "order_id" => $order_id
]);