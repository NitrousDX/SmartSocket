<?php
require_once 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['appliance_id'])) {
    $applianceId = $_GET['appliance_id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM appliances WHERE appliance_id = :id");
        $stmt->bindParam(':id', $applianceId, PDO::PARAM_INT);
        $stmt->execute();
        $appliance = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$appliance) {
            die("Appliance not found.");
        }
    } catch (PDOException $e) {
        die("Error fetching appliance: " . $e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applianceId = $_POST['appliance_id'];
    $name = trim($_POST['nameApp']);
    $serialNumber = trim($_POST['serialNumber']);
    $minVoltage = $_POST['minVoltage'] !== '' ? (float)$_POST['minVoltage'] : null;
    $maxVoltage = $_POST['maxVoltage'] !== '' ? (float)$_POST['maxVoltage'] : null;
    $minWatts = $_POST['minWatts'] !== '' ? (float)$_POST['minWatts'] : null;
    $maxWatts = $_POST['maxWatts'] !== '' ? (float)$_POST['maxWatts'] : null;
    $minCurrent = $_POST['minCurrent'] !== '' ? (float)$_POST['minCurrent'] : null;
    $maxCurrent = $_POST['maxCurrent'] !== '' ? (float)$_POST['maxCurrent'] : null;

    if (empty($name) || empty($serialNumber)) {
        die("Error: Appliance Name and Serial Number are required.");
    }

    $uploadDir = '../uploads/';
    $imagePath = null;

    if (!empty($_FILES['applianceImage']['name'])) {
        $imageName = basename($_FILES['applianceImage']['name']);
        $imagePath = $uploadDir . $imageName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['applianceImage']['type'], $allowedTypes)) {
            die("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        if ($_FILES['applianceImage']['size'] > 2 * 1024 * 1024) { // 2MB limit
            die("File size exceeds the 2MB limit.");
        }

        if (!move_uploaded_file($_FILES['applianceImage']['tmp_name'], $imagePath)) {
            die("Failed to upload image.");
        }
    }

    try {
        $sql = "UPDATE appliances SET 
                    nameApp = :nameApp, 
                    serialNumber = :serialNumber, 
                    minVoltage = :minVoltage, 
                    maxVoltage = :maxVoltage, 
                    minWatts = :minWatts, 
                    maxWatts = :maxWatts, 
                    minCurrent = :minCurrent, 
                    maxCurrent = :maxCurrent";
        if ($imagePath) {
            $sql .= ", imagePath = :imagePath";
        }
        $sql .= " WHERE appliance_id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nameApp', $name);
        $stmt->bindParam(':serialNumber', $serialNumber);
        $stmt->bindParam(':minVoltage', $minVoltage);
        $stmt->bindParam(':maxVoltage', $maxVoltage);
        $stmt->bindParam(':minWatts', $minWatts);
        $stmt->bindParam(':maxWatts', $maxWatts);
        $stmt->bindParam(':minCurrent', $minCurrent);
        $stmt->bindParam(':maxCurrent', $maxCurrent);
        if ($imagePath) {
            $stmt->bindParam(':imagePath', $imagePath);
        }
        $stmt->bindParam(':id', $applianceId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../src/views/appliance_list.php?success=Appliance $name updated successfully.");
        exit;
    } catch (PDOException $e) {
       header("Location: ../src/views/appliance_list.php?error=Something wrong happened, try again.");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appliance</title>
    <link rel="stylesheet" href="../styles/edit.css">
    <link rel="icon" href="../../svg/outlet_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg">
</head>

<body>
    <div class="tabs">
        <div class="tab-title">
            <p>Energy Monitor</p>
            <div class="tab-title-sub">
                <p>Smart Socket Device</p>
            </div>
        </div>

        <nav id="sidebar">
            <ul>
                <li>
                    <a href="../../index.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
                    <a href="../../appliance-management/index.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M460-200h40v-74l140-140v-186H320v186l140 140v74Zm-80 80v-120L240-380v-220q0-33 23.5-56.5T320-680h40l-40 40v-200h80v160h160v-160h80v200l-40-40h40q33 0 56.5 23.5T720-600v220L580-240v120H380Zm100-280Z" />
                        </svg>
                        <span>Appliances</span>
                    </a>
                </li>
                <li>
                    <a href="../../ratedMeasured.php">

                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M360-82v-100q-106-37-173-129.5T120-522q0-75 28.5-140.5t77-114q48.5-48.5 114-77T479-882q74 0 140 28.5t115 77q49 48.5 77.5 114T840-522q0 118-67.5 209.5T600-183v101h-80v-82q-10 2-20 2h-21q-10 0-19.5-.5T440-164v82h-80Zm120-158q116 0 198-82t82-198q0-116-82-198t-198-82q-116 0-198 82t-82 198q0 116 82 198t198 82ZM320-600h320v-80H320v80Zm130 320 120-120-50-50 50-50-60-60-120 120 50 50-50 50 60 60Zm30-240Z" />
                        </svg>
                        <span>Rated Values</span>
                    </a>
                </li>
                <li>
                    <a href="../../liveFeed.php">

                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="m380-340 280-180-280-180v360Zm-60 220v-80H160q-33 0-56.5-23.5T80-280v-480q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v480q0 33-23.5 56.5T800-200H640v80H320ZM160-280h640v-480H160v480Zm0 0v-480 480Z" />
                        </svg>
                        <span>Live Feed</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="logout-button">

        </div>
    </div>
    <div class="container">
        <div class="main-content-header">
            <div class="main-content-title">
                <p>Edit Panel</p>
            </div>
        </div>

        <div class="con-wrap">
            <div class="wrapper">
                <div class="add-appliance-title">
                    <div class="cont">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                        </svg>
                        <p>Edit Appliance (<?php echo htmlspecialchars($appliance['nameApp']); ?>) Details</p>
                    </div>

                    <div class="back"><a href="views/appliance_list.php">Back to Appliance List</a></div>
                </div>

                <form action="edit_appliance.php" method="POST" enctype="multipart/form-data">
                    <div class="grid-column-divider-edit">
                        <div class="app-info">
                            <input type="hidden" name="appliance_id" value="<?php echo htmlspecialchars($appliance['appliance_id']); ?>">

                            <label for="name">Appliance Name:</label>
                            <input type="text" id="name" name="nameApp" value="<?php echo htmlspecialchars($appliance['nameApp']); ?>" required>

                            <label for="serial">Serial Number:</label>
                            <input type="text" id="serial" name="serialNumber" value="<?php echo htmlspecialchars($appliance['serialNumber']); ?>" required>
                        </div>

                        <div class="divider"></div>

                        <div class="add-appliance-title-a">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                            </svg>
                            <p>Edit Appliance Rated Values Form</p>
                        </div>

                        <div class="app-info">
                            <label for="minVoltage">Min Voltage (V):</label>
                            <input type="number" id="minVoltage" name="minVoltage" step="0.01" min="0" max="9999.99" value="<?php echo htmlspecialchars($appliance['minVoltage']); ?>">

                            <label for="minWatts">Min Watts (W):</label>
                            <input type="number" id="minWatts" name="minWatts" step="0.01" min="0" max="9999.99" value="<?php echo htmlspecialchars($appliance['minWatts']); ?>">

                            <label for="minCurrent">Min Current (A):</label>
                            <input type="number" id="minCurrent" name="minCurrent" step="0.01" min="0" max="9999.99" value="<?php echo htmlspecialchars($appliance['minCurrent']); ?>">

                            <label for="image">Update Appliance Image:</label>
                            <input type="file" id="image" name="applianceImage" accept="image/*">
                        </div>
                    </div>

                    <button type="submit">Update Appliance</button>
                </form>
            </div>
        </div>

    </div>
</body>

</html>