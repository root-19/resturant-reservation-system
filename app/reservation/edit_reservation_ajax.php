<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Debug: Log the incoming request data
error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

$id = $_POST['id'] ?? null;
$day = $_POST['day'] ?? null;
$time = $_POST['time'] ?? null;
$table_size = $_POST['table_size'] ?? null;

if (!$id || !$day || !$time || !$table_size) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

$image_path = null;
if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp = $_FILES['receipt']['tmp_name'];
    $fileName = uniqid('receipt_') . '_' . basename($_FILES['receipt']['name']);
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($fileTmp, $targetFile)) {
        $image_path = 'uploads/' . $fileName;
    } else {
        error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
        echo json_encode(['success' => false, 'message' => 'Failed to upload receipt. Please try again.']);
        exit;
    }
}

try {
    $pdo = Database::connect();
    if ($image_path) {
        $stmt = $pdo->prepare('UPDATE reservations SET day = ?, time = ?, table_size = ?, image_path = ? WHERE id = ?');
        $result = $stmt->execute([$day, $time, $table_size, $image_path, $id]);
        // error_log("Update query with image_path executed. Result: " . ($result ? 'true' : 'false'));
    } else {
        $stmt = $pdo->prepare('UPDATE reservations SET day = ?, time = ?, table_size = ? WHERE id = ?');
        $result = $stmt->execute([$day, $time, $table_size, $id]);
        // error_log("Update query without image_path executed. Result: " . ($result ? 'true' : 'false'));
    }
    
    if ($result) {
        if ($image_path) {
            echo json_encode(['success' => true, 'message' => 'Receipt sent!']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Reservation updated successfully']);
        }
    } else {
        error_log("Database update failed. Error: " . print_r($stmt->errorInfo(), true));
        // echo json_encode(['success' => false, 'message' => 'Failed to update reservation. Please try again.']);
    }
} catch (Exception $e) {
    error_log("Exception occurred: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);


} 







