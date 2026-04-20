import axios from 'axios';
import { useAuthStore } from '@/components/pinia/authStore';
import { watch } from 'vue';
//import api from '@/axios'

//auth token (shared)
//const token = localStorage.getItem('token');
//if (token) {
//    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
//}//use Bearer token

function initNotifications() {

    const auth = useAuthStore();
     // Exit early if no user or not admin
    if (!auth.user || !auth.user.is_admin) return;

    const badge = document.getElementById('notification-badge');
    if (!badge) return;

    async function fetchCount() {
        try {
            const res = await axios.get('/api/notifications');
            //const res = await api.get('/notifications');
            const count = res.data.unread_count ?? res.data.notifications?.length ?? 0;

            badge.style.display = count > 0 ? 'inline-block' : 'none';
            badge.textContent = count;
        
        } catch (e) {
            console.error('Failed to fetch notifications', e);
        }
    }

    //initial load
    fetchCount();
    // repeat every 15 seconds //polling
    setInterval(fetchCount, 15000);    

}

export function startNotifications() {

    const auth = useAuthStore();

if(auth.user){
    initNotifications();
} else {
    //wait until user is loaded in App.vue
    const unwatch = watch(
        () => auth.user,
        (newUser) => {
            if(newUser) {
                initNotifications();
                unwatch();
            }
        }
    );
}

}