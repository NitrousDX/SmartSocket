function updateLiveData() {
    fetch('appliance-management/fetchLiveData.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const liveData = data.liveData;

                for (const [serialNumber, values] of Object.entries(liveData)) {
                    const power = values?.powerReceived ?? 'N/A';
                    const voltage = values?.voltageReceived ?? 'N/A';
                    const current = values?.currentReceived ?? 'N/A';

                    const row = document.querySelector(`[data-serial="${serialNumber}"]`);
                    if (row) {
                        row.querySelector('.live-w').textContent = power;
                        row.querySelector('.live-v').textContent = voltage;
                        row.querySelector('.live-a').textContent = current;
                    }
                }
            } else {
                console.error('Error fetching live data:', data.error);
            }
        })
        .catch(error => console.error('Fetch error:', error));
}

setInterval(updateLiveData, 5000);
updateLiveData();