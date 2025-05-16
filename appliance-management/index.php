<?php
require_once('./src/db/connection.php');

try {
    $stmt = $pdo->query("SELECT appliance_id, nameApp, minVoltage, minWatts, minCurrent, imagePath, serialNumber FROM appliances");
    $appliances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!-- Message Receiver -->
<?php
if (isset($_GET['message'])) {
    echo "<script>alert('" . htmlspecialchars($_GET['message']) . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSocket | Appliances Settings</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="checkOnline.js"></script>
    <link rel="icon" type="image/x-icon" href="../svg/outlet_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg">
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
                    <a href="../index.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
                    <a href="./index.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M460-200h40v-74l140-140v-186H320v186l140 140v74Zm-80 80v-120L240-380v-220q0-33 23.5-56.5T320-680h40l-40 40v-200h80v160h160v-160h80v200l-40-40h40q33 0 56.5 23.5T720-600v220L580-240v120H380Zm100-280Z" />
                        </svg>
                        <span>Appliances</span>
                    </a>
                </li>
                <li>
                    <a href="../ratedMeasured.php">

                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M360-82v-100q-106-37-173-129.5T120-522q0-75 28.5-140.5t77-114q48.5-48.5 114-77T479-882q74 0 140 28.5t115 77q49 48.5 77.5 114T840-522q0 118-67.5 209.5T600-183v101h-80v-82q-10 2-20 2h-21q-10 0-19.5-.5T440-164v82h-80Zm120-158q116 0 198-82t82-198q0-116-82-198t-198-82q-116 0-198 82t-82 198q0 116 82 198t198 82ZM320-600h320v-80H320v80Zm130 320 120-120-50-50 50-50-60-60-120 120 50 50-50 50 60 60Zm30-240Z" />
                        </svg>
                        <span>Rated Values</span>
                    </a>
                </li>
                <li>
                    <a href="../liveFeed.php">

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

    <div class="main-content">
        <div class="main-content-header">
            <div class="main-content-title">
                <p>Device Status</p>
            </div>
        </div>

        <div class="show-message">
            <p>
                <?php if (isset($_GET['success'])) { ?>
                    <span class="message-success">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </span>
                <?php } ?>
            </p>

            <p>
                <?php if (isset($_GET['error'])) { ?>
                    <span class="message-failed">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </span>
                <?php } ?>
            </p>
        </div>

        <header>
            <div class="main-content-title-sub">
                Select an option from the menu to manage your appliances.
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="src/views/add_appliance_form.php">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                            </svg>
                            <p>Add Appliance</p>
                        </a>
                    </li>
                    <li>
                        <a href="src/views/appliance_list.php">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                <path d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                            </svg>
                            View Appliances List
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        <div class="appliance-cubes">
            <?php if (!empty($appliances)): ?>
                <?php foreach ($appliances as $appliance): ?>
                    <div class="grid-hori">
                        <div class="appliance-cube">
                            <div class="appliance-image">
                                <img src="<?php echo htmlspecialchars('uploads/' . basename($appliance['imagePath'])); ?>"
                                    alt="<?php echo htmlspecialchars($appliance['nameApp']); ?>"
                                    style="width:200px; height:200px; border-radius: 8px; margin-right: 13px; display: flex;">
                            </div>

                            <div class="appliance-details">
                                <p class="device-name"><?php echo htmlspecialchars($appliance['nameApp']); ?></p>
                                <div class="rated-values">
                                    <div class="rated-values-title">
                                        <p>Rated Values:</p>
                                    </div>

                                    <div class="r-con">
                                        <div class="svg-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                <path d="m422-232 207-248H469l29-227-185 267h139l-30 208ZM320-80l40-280H160l360-520h80l-40 320h240L400-80h-80Zm151-390Z" />
                                            </svg>
                                        </div>
                                        <p class="r-reading"> <b style="padding-right: 5px;">Voltage: </b> <?php echo htmlspecialchars($appliance['minVoltage']); ?>V</p>
                                    </div>

                                    <div class="r-con">
                                        <div class="svg-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                <path d="M360-82v-100q-106-37-173-129.5T120-522q0-75 28.5-140.5t77-114q48.5-48.5 114-77T479-882q74 0 140 28.5t115 77q49 48.5 77.5 114T840-522q0 118-67.5 209.5T600-183v101h-80v-82q-10 2-20 2h-21q-10 0-19.5-.5T440-164v82h-80Zm120-158q116 0 198-82t82-198q0-116-82-198t-198-82q-116 0-198 82t-82 198q0 116 82 198t198 82ZM320-600h320v-80H320v80Zm130 320 120-120-50-50 50-50-60-60-120 120 50 50-50 50 60 60Zm30-240Z" />
                                            </svg>
                                        </div>
                                        <p class="r-reading"><b style="padding-right: 5px;">Watts: </b> <?php echo htmlspecialchars($appliance['minWatts']); ?>W</p>
                                    </div>

                                    <div class="r-con">
                                        <div class="svg-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                <path d="M720-360v-80h80q17 0 28.5 11.5T840-400q0 17-11.5 28.5T800-360h-80Zm0 160v-80h80q17 0 28.5 11.5T840-240q0 17-11.5 28.5T800-200h-80Zm-160 40q-33 0-56.5-23.5T480-240h-80v-160h80q0-33 23.5-56.5T560-480h120v320H560ZM280-280q-66 0-113-47t-47-113q0-66 47-113t113-47h60q25 0 42.5-17.5T400-660q0-25-17.5-42.5T340-720H200q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h140q58 0 99 41t41 99q0 58-41 99t-99 41h-60q-33 0-56.5 23.5T200-440q0 33 23.5 56.5T280-360h80v80h-80Z" />
                                            </svg>
                                        </div>
                                        <p class="r-reading"><b style="padding-right: 5px;">Current: </b> <?php echo htmlspecialchars($appliance['minCurrent']); ?>A</p>
                                    </div>


                                    <form action="../viewHistory.php" method="POST">
                                        <input type="hidden" name="serial" value="<?php echo htmlspecialchars($appliance['serialNumber']); ?>">
                                        <input type="hidden" name="deviceName" value="<?php echo htmlspecialchars($appliance['nameApp']); ?>">
                                        <input type="submit" name="toHistory" value="View History" class="history-device-list">
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div class="offline" id="status-<?php echo htmlspecialchars($appliance['serialNumber']); ?>">
                            Checking status...
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="not-found">No appliances found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>