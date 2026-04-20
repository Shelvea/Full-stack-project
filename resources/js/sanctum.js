import axios from 'axios';

export async function initSanctum() {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
}
