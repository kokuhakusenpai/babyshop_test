<?php
$conn = new mysqli("localhost", "root", "", "babyshop");
$conn->set_charset("utf8");

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$res = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");

if ($res->num_rows === 0) {
  echo "<i>Không có sản phẩm nào trong đơn hàng này.</i>";
  return;
}

echo "<table border='1' cellpadding='5' cellspacing='0' style='width:100%'>";
echo "<tr><th>Tên sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>";

while ($item = $res->fetch_assoc()) {
  $lineTotal = $item['quantity'] * $item['price'];
  echo "<tr>
          <td>{$item['product_name']}</td>
          <td>{$item['quantity']}</td>
          <td>" . number_format($item['price']) . "₫</td>
          <td>" . number_format($lineTotal) . "₫</td>
        </tr>";
}

echo "</table>";
