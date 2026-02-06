import { createApp } from 'vue'
import App from './App.vue'; // Root component
import router from './router';
import './notifications';
import './search';

import { initSanctum } from './sanctum';
await initSanctum();

import Alert from '@/components/Alert.vue'// child component

const app = createApp(App);
app.use(router);

app.component('Alert', Alert) // register globally
app.mount('#vue-app');
