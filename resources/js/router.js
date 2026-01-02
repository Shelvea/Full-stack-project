import { createRouter, createWebHistory } from 'vue-router';
import Users from './components/User.vue';
import Notification from './components/Notification.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/app/users', component: Users },
        { path: '/app/notification', component: Notification },
    ]
});

export default router;