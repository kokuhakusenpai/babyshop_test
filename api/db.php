<?php
$host = "localhost";
$user = "root";
$pass = ""; // sửa nếu có mật khẩu
$dbname = "babyshop";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
