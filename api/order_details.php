<?php
session_start();
include "db.php";

// Check permissions (either admin or the user who placed the order)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Could add code here to check if this is the user's own order
    header("HTTP/1.1 403 Forbidden");
    echo "Không có quyền truy cập";
    exit;
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Use prepared statement
$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  echo "<i>Không có sản phẩm nào trong đơn hàng này.</i>";
  exit;
}

echo "<table border='1' cellpadding='5' cellspacing='0' style='width:100%'>";
echo "<tr><th>Tên sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>";

while ($item = $res->fetch_assoc()) {
  $lineTotal = $item['quantity'] * $item['price'];
  echo "<tr>
          <td>" . htmlspecialchars($item['product_name']) . "</td>
          <td>" . htmlspecialchars($item['quantity']) . "</td>
          <td>" . number_format($item['price']) . "₫</td>
          <td>" . number_format($lineTotal) . "₫</td>
        </tr>";
}

echo "</table>";