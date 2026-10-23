<?php
session_start();
include 'db.php';

header('Content-Type: application/json; charset=utf-8');

// Sadece giriş yapmış yetkililer kullanıcı bilgilerini güncelleyebilir
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Bu işlem için yetkiniz yok."]);
    exit();
}

// JSON verisini al ve işle: { "<kullanıcı id>": { "faculty": "...", "position": "..." } }
$data = json_decode(file_get_contents("php://input"), true);

// Güncellenebilecek alanlar. Sütun adı SQL'e yalnızca bu beyaz listeden geçerse yazılır.
$allowedFields = ['faculty', 'position'];

if (!empty($data) && is_array($data)) {
    foreach ($data as $userId => $fields) {
        if (!is_array($fields)) {
            continue;
        }
        $userId = intval($userId);
        foreach ($fields as $field => $value) {
            if (!in_array($field, $allowedFields, true)) {
                continue;
            }
            $stmt = $conn->prepare("UPDATE users SET $field = ? WHERE id = ?");
            $value = (string)$value;
            $stmt->bind_param("si", $value, $userId);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo json_encode(["status" => "success", "message" => "Değişiklikler kaydedildi."]);
} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz veri"]);
}

$conn->close();
