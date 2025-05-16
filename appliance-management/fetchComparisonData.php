<?php
require_once('./src/db/connection.php');

header('Content-Type: application/json');

try {
    $stmtRated = $pdo->query("SELECT nameApp, serialNumber, minVoltage, minWatts, minCurrent FROM appliances");
    $ratedValues = $stmtRated->fetchAll(PDO::FETCH_ASSOC);

    $liveData = [];
    foreach ($ratedValues as $appliance) {
        $tableName = $appliance['serialNumber'];

        if (preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
            $liveStmt = $pdo->prepare("SELECT powerReceived, voltageReceived, currentReceived FROM `$tableName` ORDER BY id DESC LIMIT 1");
            $liveStmt->execute();
            $liveData[$tableName] = $liveStmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $liveData[$tableName] = null;
        }
    }

    $comparisonData = [];
    foreach ($ratedValues as $appliance) {
        $serialNumber = $appliance['serialNumber'];
        $live = $liveData[$serialNumber] ?? null;

        $condition = "Good Condition";
        if ($live) {
            if ($live['voltageReceived'] > $appliance['minVoltage']) {
                $condition = "is in Good Operation";
            } elseif ($live['voltageReceived'] < ($appliance['minVoltage'] * 0.5)) {
                $condition = "Needs Checking";
            }

            if ($live['powerReceived'] > $appliance['minWatts']) {
                $condition = "is Energy Inefficient";
            } elseif ($live['powerReceived'] <= $appliance['minWatts']) {
                $condition = "is Energy Efficient";
            }
        } else {
            $condition = "No Live Data";
        }

        $comparisonData[] = [
            'nameApp' => $appliance['nameApp'],
            'serialNumber' => $serialNumber,
            'ratedVoltage' => $appliance['minVoltage'],
            'liveVoltage' => $live['voltageReceived'] ?? 'N/A',
            'ratedWattage' => $appliance['minWatts'],
            'liveWattage' => $live['powerReceived'] ?? 'N/A',
            'ratedCurrent' => $appliance['minCurrent'],
            'liveCurrent' => $live['currentReceived'] ?? 'N/A',
            'condition' => $condition
        ];
    }

    $totalWattage = 0;
    foreach ($liveData as $serialNumber => $live) {
        if ($live && isset($live['powerReceived'])) {
            $totalWattage += $live['powerReceived'];
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $comparisonData,
        'totalWattage' => $totalWattage
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
