<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSocket | Add Appliances</title>
    <link rel="stylesheet" href="../../styles/style.css">
    <link rel="icon" type="image/x-icon" href="../../../svg/outlet_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg">
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
                    <a href="../../../index.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
                    <a href="../../../appliance-management/index.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M460-200h40v-74l140-140v-186H320v186l140 140v74Zm-80 80v-120L240-380v-220q0-33 23.5-56.5T320-680h40l-40 40v-200h80v160h160v-160h80v200l-40-40h40q33 0 56.5 23.5T720-600v220L580-240v120H380Zm100-280Z" />
                        </svg>
                        <span>Appliances</span>
                    </a>
                </li>
                <li>
                    <a href="../../../ratedMeasured.php">

                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M360-82v-100q-106-37-173-129.5T120-522q0-75 28.5-140.5t77-114q48.5-48.5 114-77T479-882q74 0 140 28.5t115 77q49 48.5 77.5 114T840-522q0 118-67.5 209.5T600-183v101h-80v-82q-10 2-20 2h-21q-10 0-19.5-.5T440-164v82h-80Zm120-158q116 0 198-82t82-198q0-116-82-198t-198-82q-116 0-198 82t-82 198q0 116 82 198t198 82ZM320-600h320v-80H320v80Zm130 320 120-120-50-50 50-50-60-60-120 120 50 50-50 50 60 60Zm30-240Z" />
                        </svg>
                        <span>Rated Values</span>
                    </a>
                </li>
                <li>
                    <a href="../../../liveFeed.php">
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
                <p>Add Appliance</p>
            </div>
        </div>

        <div class="container">
            <div class="wrapper">
                <div class="add-appliance-title">
                    <div class="cont">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                        </svg>
                        <p>Appliance Details Form</p>
                    </div>

                    <div class="back"><a href="../../index.php">Back to Device Status</a></div>
                </div>

                <form action="../add_appliance.php" method="POST" enctype="multipart/form-data" class="add-appliance-form">
                    <div class="app-info">
                        <label for="name">Appliance Name:</label>
                        <input type="text" id="name" name="nameApp" placeholder="(ex. Daikin Inverter...)" required>

                        <label for="serial">Adaptor Serial Code:</label>
                        <input type="text" id="serial" name="serialNumber" placeholder="(ex. ESP32C300)" required>
                    </div>

                    <div class="divider"></div>

                    <div class="add-appliance-title-a">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                        </svg>
                        <p>Appliance Rated Values Form</p>
                    </div>

                    <div class="app-info">
                        <div class="app-info-wrapper">
                            <div class="infod">
                                <label for="voltage">Voltage:</label>
                                <input type="number" id="voltage" name="min-voltage" required>

                                <label for="watts">Wattage:</label>
                                <input type="number" id="watts" name="min-watts" required>

                                <label for="current">Current:</label>
                                <input type="number" id="current" name="min-current" required>
                            </div>
                            <div class="app-info">
                                <label for="image">Appliance Image:</label>
                                <input type="file" id="image" name="applianceImage" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit">Add Appliance</button>
                </form>
            </div>
            <!-- Wrapper -->
        </div>
    </div>
</body>

</html>