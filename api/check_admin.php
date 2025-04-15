<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Cấm truy cập
    echo json_encode(["error" => "Bạn không có quyền truy cập"]);
    exit;
}
