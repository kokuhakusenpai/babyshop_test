<?php
session_start();
include "../api/db.php";

// Check admin permission
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $desc = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']);

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Validate file type
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        
        if (in_array(strtolower($ext), $allowed)) {
            // Create safe filename
            $newFilename = uniqid() . '.' . $ext;
            $uploadDir = "../uploads/";
            
            // Create directory if not exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $image = 'uploads/' . $newFilename;
            move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $desc, $price, $image);
    $stmt->execute();

    echo "Thêm sản phẩm thành công!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Thêm sản phẩm mới</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <input name="name" placeholder="Tên sản phẩm" required><br>
        <textarea name="description" placeholder="Mô tả" required></textarea><br>
        <input name="price" type="number" step="0.01" min="0" placeholder="Giá" required><br>
        <input name="image" type="file" accept="image/*"><br>
        <button type="submit">Thêm sản phẩm</button>
    </form>
    
    <p><a href="products.php">Quay lại danh sách sản phẩm</a></p>
</body>
</html>