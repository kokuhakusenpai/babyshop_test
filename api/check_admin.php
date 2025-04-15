<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(["error" => "Bạn không có quyền truy cập"]);
    exit;
}