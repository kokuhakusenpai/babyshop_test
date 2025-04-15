<?php
$conn = new mysqli("localhost", "root", "", "shop_db");
$conn->set_charset("utf8");

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý đơn hàng</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      max-width: 1000px;
      margin: auto;
      padding: 20px;
      font-family: Arial, sans-serif;
    }
    h2 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    .order-details {
      background-color: #f9f9f9;
      padding: 10px;
      display: none;
    }
  </style>
</head>
<body>

<h2>📦 Quản lý đơn hàng</h2>

<table>
  <thead>
  <tr>
  <th>ID</th>
  <th>Khách hàng</th>
  <th>Điện thoại</th>
  <th>Địa chỉ</th>
  <th>Tổng tiền</th>
  <th>Ngày tạo</th>
  <th>Trạng thái</th>
  <th>Chi tiết</th>
  <th>Hành động</th>
</tr>

  </thead>
  <tbody>
    <?php while ($row = $orders->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['customer_name'] ?></td>
        <td><?= $row['customer_phone'] ?></td>
        <td><?= $row['customer_address'] ?></td>
        <td><?= number_format($row['total_price']) ?>₫</td>
        <td><?= $row['created_at'] ?></td>
        <td><button onclick="toggleDetails(<?= $row['id'] ?>)">Xem</button></td>
      </tr>
      <tr id="details-<?= $row['id'] ?>" class="order-details">
        <td colspan="7">Đang tải...</td>
      </tr>
      <td>
  <form method="post" action="../api/update_status.php">
    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
    <select name="status" onchange="this.form.submit()">
      <?php
        $statuses = ["Chờ xử lý", "Đang giao", "Hoàn tất", "Đã hủy"];
        foreach ($statuses as $status) {
          $selected = $status === $row['status'] ? 'selected' : '';
          echo "<option value='$status' $selected>$status</option>";
        }
      ?>
    </select>
  </form>
</td>
<td><button onclick="toggleDetails(<?= $row['id'] ?>)">Xem</button></td>
<td>
  <form method="post" action="../api/delete_order.php" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?');">
    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
    <button style="color: red;">Xóa</button>
  </form>
</td>
    <?php endwhile; ?>
  </tbody>
</table>

<script>
  function toggleDetails(orderId) {
    const detailsRow = document.getElementById("details-" + orderId);
    const isVisible = detailsRow.style.display === "table-row";

    if (isVisible) {
      detailsRow.style.display = "none";
    } else {
      fetch("../api/order_details.php?id=" + orderId)
        .then(res => res.text())
        .then(html => {
          detailsRow.innerHTML = `<td colspan="7">${html}</td>`;
          detailsRow.style.display = "table-row";
        });
    }
  }
</script>

</body>
</html>
