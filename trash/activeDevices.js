document.addEventListener('DOMContentLoaded', () => {
    const updateActiveNowCounter = async () => {
        try {
            const response = await fetch('./appliance-management/device_status.php?actioncount=count_online');
            if (!response.ok) {
                throw new Error('Failed to fetch active devices count');
            }
            const data = await response.json();
            const activeNowElement = document.querySelector('.reading.active-now');
            if (activeNowElement) {
                activeNowElement.textContent = data.online_count || 0;
            }
        } catch (error) {
            console.error('Error updating active devices count:', error);
        }
    };

    updateActiveNowCounter();
    setInterval(updateActiveNowCounter, 5000);
});