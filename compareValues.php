<?php
require_once('./appliance-management/src/db/connection.php');

try {
    $stmtRated = $pdo->query("SELECT serialNumber, minVoltage, minWatts, minCurrent FROM appliances");
    $ratedValues = $stmtRated->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rated values: " . $e->getMessage());
}

$liveData = [];
try {
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
} catch (PDOException $e) {
    die("Error fetching live feed values: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Values</title>
    <link rel="stylesheet" href="Styles/compareValues.css">
</head>
<body>
    <h1>Appliance Condition Comparison</h1>
    <table>
        <thead>
            <tr>
                <th>Appliance Serial No.</th>
                <th>Rated Voltage</th>
                <th>Live Voltage</th>
                <th>Rated Wattage</th>
                <th>Live Wattage</th>
                <th>Rated Current</th>
                <th>Live Current</th>
                <th>Condition</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ratedValues as $appliance): ?>
                <tr>
                    <td><?php echo htmlspecialchars($serialNumber); ?></td>
                    <td><?php echo htmlspecialchars($appliance['minVoltage']); ?></td>
                    <td><?php echo htmlspecialchars($live['voltageReceived'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($appliance['minWatts']); ?></td>
                    <td><?php echo htmlspecialchars($live['powerReceived'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($appliance['minCurrent']); ?></td>
                    <td><?php echo htmlspecialchars($live['currentReceived'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($condition); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        function fetchComparisonData() {
            fetch('appliance-management/fetchComparisonData.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.querySelector('table tbody');
                        tableBody.innerHTML = '';

                        data.data.forEach(appliance => {
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td>${appliance.serialNumber}</td>
                                <td>${appliance.ratedVoltage}</td>
                                <td>${appliance.liveVoltage}</td>
                                <td>${appliance.ratedWattage}</td>
                                <td>${appliance.liveWattage}</td>
                                <td>${appliance.ratedCurrent}</td>
                                <td>${appliance.liveCurrent}</td>
                                <td class="${appliance.condition.replace(/\s+/g, '-').toLowerCase()}">${appliance.condition}</td>
                            `;

                            tableBody.appendChild(row);
                        });
                    } else {
                        console.error('Error fetching comparison data:', data.error);
                    }
                })
                .catch(error => console.error('Fetch error:', error));
        }
        setInterval(fetchComparisonData, 5000);
        fetchComparisonData();
    </script>
</body>
</html>
