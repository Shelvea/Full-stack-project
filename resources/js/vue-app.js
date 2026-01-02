import { createApp } from 'vue'
import App from './App.vue'; // Root component
import router from './router';
import './notifications';
import './search';

const app = createApp(App);
app.use(router);
app.mount('#vue-app');
