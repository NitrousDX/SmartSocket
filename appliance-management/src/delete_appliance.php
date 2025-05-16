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


                header('Location: ../index.php?message=Appliance deleted successfully.');
            } else {
                header('Location: ../index.php?message=No appliance found with that ID.');
            }
        } catch (PDOException $e) {
            echo "Error deleting appliance: " . $e->getMessage();
        }
    } else {
        header('Location: ../index.php?message=Appliance ID cannot be empty.');
    }
}
