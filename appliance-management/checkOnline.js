let globalDeviceIds = [];

function updateDeviceStatuses() {
  if (Array.isArray(globalDeviceIds) && globalDeviceIds.length > 0) {
    globalDeviceIds.forEach(deviceId => {
      fetch(`device_status.php?action=check_status&device_id=${deviceId}`)
        .then(response => response.json())
        .then(data => {
          const isOnline = data.online;
          const statusElement = document.getElementById(`status-${deviceId}`);
          if (statusElement) {
            statusElement.textContent = isOnline ? "Online" : "Offline";

            if (isOnline) {
              statusElement.classList.add('online');
              statusElement.classList.remove('offline');
            } else {
              statusElement.classList.add('offline');
              statusElement.classList.remove('online');
            }
          }
        })
        .catch(error => {
          console.error(`Error fetching status for device ${deviceId}:`, error);
        });
    });
  }
}

fetch('device_status.php?action=get_device_ids')
  .then(response => response.json())
  .then(deviceIds => {
    if (Array.isArray(deviceIds)) {
      globalDeviceIds = deviceIds;
      setInterval(updateDeviceStatuses, 2000);
    } else {
      console.error('Invalid device IDs received from the server.');
    }
  })
  .catch(error => {
    console.error('Error fetching device IDs:', error);
  });