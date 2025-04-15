<?php
$host = "localhost";
$user = "root";
$pass = ""; // sửa nếu có mật khẩu
$dbname = "babyshop";

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>