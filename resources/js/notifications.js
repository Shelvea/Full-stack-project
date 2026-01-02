import axios from 'axios';

//auth token (shared)
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}//use Bearer token

async function fetchNotificationCount() {
    const badge = document.getElementById('notification-badge');
    if (!badge) return;

    try {
        const res = await axios.get('/api/notifications');
        const count = res.data.unread_count ?? res.data.notifications?.length ?? 0;

        badge.style.display = count > 0 ? 'inline-block' : 'none';
        badge.textContent = count;
        
    } catch (e) {
        console.error(e);
    }
}

// initial load
fetchNotificationCount();

// repeat every 15 seconds //polling
setInterval(fetchNotificationCount, 15000);