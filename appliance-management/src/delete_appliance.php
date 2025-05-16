<?php
require_once 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applianceId = $_POST['appliance_id'];

    if (!empty($applianceId)) {
        try {
            $stmt = $pdo->prepare("SELECT serialNumber FROM appliances WHERE appliance_id = :id");
            $stmt->bindParam(':id', $applianceId, PDO::PARAM_INT);
            $stmt->execute();
            $appliance = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($appliance) {
                $serialNumber = $appliance['serialNumber'];
                $tableName = preg_replace('/[^a-zA-Z0-9_]/', '_', $serialNumber);

                $deleteStmt = $pdo->prepare("DELETE FROM appliances WHERE appliance_id = :id");
                $deleteStmt->bindParam(':id', $applianceId, PDO::PARAM_INT);
                $deleteStmt->execute();

                $dropTableSQL = "DROP TABLE IF EXISTS `$tableName`";
                $pdo->exec($dropTableSQL);

                $deleteStatusSql = $pdo->prepare("DELETE FROM device_status WHERE device_id = :id");
                $deleteStatusSql->bindParam('id', $tableName);
                $deleteStatusSql->execute();

                header('Location: ../index.php?success=Appliance deleted successfully.');
            } else {
                header('Location: ../index.php?error=No appliance found with that ID.');
            }
        } catch (PDOException $e) {
            header("Location: ../index.php?error=Something wrong happened, try again");
        }
    } else {
        header('Location: ../index.php?error=Appliance ID cannot be empty.');
    }
}
