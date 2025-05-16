<?php
require_once 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['nameApp']);
    $min_voltage = $_POST['min-voltage'] !== '' ? (float)$_POST['min-voltage'] : null;
    $min_watts = $_POST['min-watts'] !== '' ? (float)$_POST['min-watts'] : null;
    $min_current = $_POST['min-current'] !== '' ? (float)$_POST['min-current'] : null;
    $serialNumber = trim($_POST['serialNumber']);

    if (empty($name) || empty($serialNumber)) {
        die("Error: Name and Serial Number are required.");
    }

    $uploadDir = '../uploads/';
    $imageName = uniqid('appliance_', true) . '_' . basename($_FILES['applianceImage']['name']);
    $imagePath = $uploadDir . $imageName;

    try {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['applianceImage']['type'], $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        if ($_FILES['applianceImage']['size'] > 2 * 1024 * 1024) {
            throw new Exception("File size exceeds the 2MB limit.");
        }

        if (!move_uploaded_file($_FILES['applianceImage']['tmp_name'], $imagePath)) {
            throw new Exception("Failed to upload image.");
        }

        //for saving rated data
        $stmt = $pdo->prepare("INSERT INTO appliances 
            (nameApp, 
             minVoltage, 
             minWatts, 
             minCurrent,
             serialNumber, 
             imagePath) 
            VALUES 
            (:nameApp, 
             :min_voltage,
             :min_watts,
             :min_current, 
             :serialNumber, 
             :imagePath)");

        $stmt->bindParam(':nameApp', $name);
        $stmt->bindParam(':min_voltage', $min_voltage);
        $stmt->bindParam(':min_watts', $min_watts);
        $stmt->bindParam(':min_current', $min_current);
        $stmt->bindParam(':serialNumber', $serialNumber);
        $stmt->bindParam(':imagePath', $imagePath);
        $stmt->execute();

        //adding device code to device_status, online | offline
        $device_statusSql = $pdo->prepare("INSERT INTO device_status (device_id) VALUES (:device_id)");
        $device_statusSql->bindParam(':device_id', $serialNumber);
        $device_statusSql->execute();

        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '_', $serialNumber); // Sanitize table name
        if (!empty($tableName)) {
            $createTableSQL = "CREATE TABLE IF NOT EXISTS `$tableName` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                powerReceived DECIMAL(6,2) NOT NULL,
                voltageReceived DECIMAL(6,2) NOT NULL,
                currentReceived DECIMAL(6,2) NOT NULL,
                deviceCondition VARCHAR(50) NOT NULL,
                timeReceived TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $pdo->exec($createTableSQL);
        }

        header('Location: ../index.php?success=Appliance ' . urlencode($tableName) . ' added successfully.');
    } catch (Exception $e) {
        header('Location: ../index.php?error=Something wrong happened, try again.');
    }
}
