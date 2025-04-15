<?php
// Kết nối DB
$conn = new mysqli("localhost", "root", "", "babyshop"); // Thay thông tin DB của bạn
$conn->set_charset("utf8");

// Nhận JSON từ JS
$data = json_decode(file_get_contents("php://input"), true);

$name = $conn->real_escape_string($data['name']);
$phone = $conn->real_escape_string($data['phone']);
$address = $conn->real_escape_string($data['address']);
$cart = $data['cart'];

// Tính tổng
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Lưu vào orders
$conn->query("INSERT INTO orders (customer_name, customer_phone, customer_address, total_price)
              VALUES ('$name', '$phone', '$address', $total)");

$order_id = $conn->insert_id;

// Lưu vào order_items
foreach ($cart as $item) {
    $pname = $conn->real_escape_string($item['name']);
    $qty = (int)$item['quantity'];
    $price = (int)$item['price'];

    $conn->query("INSERT INTO order_items (order_id, product_name, quantity, price)
                  VALUES ($order_id, '$pname', $qty, $price)");
}

echo "🎉 Đặt hàng thành công! Mã đơn hàng: #$order_id";
