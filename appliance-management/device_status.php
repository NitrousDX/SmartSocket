<?php
require_once('./src/db/connection.php');
date_default_timezone_set('Asia/Manila');

$action = $_GET['action'] ?? '';
$device_id = $_GET['device_id'] ?? '';

if ($action === 'get_device_ids') {
    try {
        $stmt = $pdo->query("SELECT device_id FROM device_status");
        $deviceIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($deviceIds);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} elseif ($action === 'check_status' && $device_id !== '') {
    try {
        $stmt = $pdo->prepare("SELECT last_seen FROM device_status WHERE device_id = ?");
        $stmt->execute([$device_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $last_seen = strtotime($row['last_seen']);
            $online = (time() - $last_seen) < 3;
            echo json_encode(['online' => $online]);
        } else {
            echo json_encode(['online' => false]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
