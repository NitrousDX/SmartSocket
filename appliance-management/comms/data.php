<?php
require_once('../src/db/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);

    if (isset($dataArray['applianceId'], $dataArray['power'], $dataArray['voltage'], $dataArray['current'])) {
        $deviceSerial = preg_replace('/[^a-zA-Z0-9_]/', '_', $dataArray['applianceId']);
        $power = $dataArray['power'];
        $voltage = $dataArray['voltage'];
        $current = $dataArray['current'];

        try {
            //reference
            $stmtRated = $pdo->prepare("SELECT minVoltage, minWatts, minCurrent FROM appliances WHERE serialNumber = :serialNumber LIMIT 1");
            $stmtRated->execute([':serialNumber' => $deviceSerial]);
            $appliance = $stmtRated->fetch(PDO::FETCH_ASSOC);

            $condition = "Good Condition";
            if ($appliance) {
                if ($voltage > $appliance['minVoltage']) {
                    $condition = "is in Good Operation";
                } elseif ($voltage < ($appliance['minVoltage'] * 0.5)) {
                    $condition = "Needs Checking";
                }
                if ($power > $appliance['minWatts']) {
                    $condition = "is Energy Inefficient";
                } elseif ($power <= $appliance['minWatts']) {
                    $condition = "is Energy Efficient";
                }
            } else {
                $condition = "No Rated Data";
            }

            if ($current < 0.5) {
                $current = 0;
            }

            if ($power < 0.5) {
                $power = 0;
            }

            $sql = "INSERT INTO `$deviceSerial` (powerReceived, voltageReceived, currentReceived, deviceCondition, timeReceived)
                    VALUES (:powRec, :voltRec, :currRec, :devCond, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':powRec' => $power,
                ':voltRec' => $voltage,
                ':currRec' => $current,
                ':devCond' => $condition
            ]);

            $stmt = $pdo->prepare("INSERT INTO device_status (device_Id, last_seen) VALUES (:deviceId, NOW())
                ON DUPLICATE KEY UPDATE last_seen = NOW()");
            $stmt->execute([
                ':deviceId' => $deviceSerial,
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Data saved successfully.', 'deviceCondition' => $condition]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid data received.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
