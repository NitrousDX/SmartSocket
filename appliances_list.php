<?php
require_once '../db/connection.php';

try {
    $stmt = $pdo->query("SELECT * FROM appliances");
    $appliances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching appliances: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appliances List</title>
    <link rel="stylesheet" href="../../styles/style.css">
</head>

<body>
    <div class="container">
        <h1>Appliances List</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Serial Number</th>
                    <th>Voltage (V)</th>
                    <th>Watts (W)</th>
                    <th>Current (A)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appliances as $appliance): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appliance['nameApp']); ?></td>
                        <td><?php echo htmlspecialchars($appliance['serialNumber']); ?></td>
                        <td><?php echo htmlspecialchars($appliance['voltage']); ?></td>
                        <td><?php echo htmlspecialchars($appliance['watts']); ?></td>
                        <td><?php echo htmlspecialchars($appliance['current']); ?></td>
                        <td>
                            <form action="../delete_appliance.php" method="POST" style="display:inline;">
                                <input type="hidden" name="appliance_id" value="<?php echo htmlspecialchars($appliance['appliance_id']); ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>