<?php
include "../api/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $desc, $price, $image);
    $stmt->execute();

    echo "Thêm sản phẩm thành công!";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input name="name" placeholder="Tên sản phẩm" required><br>
    <textarea name="description" placeholder="Mô tả" required></textarea><br>
    <input name="price" type="number" step="0.01" placeholder="Giá" required><br>
    <input name="image" type="file"><br>
    <button type="submit">Thêm sản phẩm</button>
</form>
