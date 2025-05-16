<?php
if ($online) {
    $sqlupdate = "UPDATE device_status SET devStatus = :dstatonline WHERE device_id = :dstatid";
    $stmt = $pdo->prepare($sqlupdate);
    $stmt->execute([
        ':dstatonline' => 'online',
        ':dstatid' => $device_id
    ]);
} else {
    $sqlupdate = "UPDATE device_status SET devStatus = :dstatonline WHERE device_id = :dstatid";
    $stmt = $pdo->prepare($sqlupdate);
    $stmt->execute([
        ':dstatonline' => 'offline',
        ':dstatid' => $device_id
    ]);
} ?>
