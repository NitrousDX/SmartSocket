<?php
require_once('../appliance-management/src/db/connection.php');

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT serialNumber FROM appliances");
    $appliances = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $liveData = [];
    foreach ($appliances as $appliance) {
        $tableName = $appliance['serialNumber'];

        if (preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
            $live_stmt = $pdo->prepare("SELECT powerReceived, voltageReceived, currentReceived FROM `$tableName` ORDER BY id DESC LIMIT 1");
            $live_stmt->execute();
            $liveData[$tableName] = $live_stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $liveData[$tableName] = null;
        }
    }

    echo json_encode(['success' => true, 'liveData' => $liveData]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
