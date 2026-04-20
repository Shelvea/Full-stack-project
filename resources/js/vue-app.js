import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'; // Root component
import router from './router';
import './search';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap'

import { initSanctum } from './sanctum';
await initSanctum();

import Alert from '@/components/Alert.vue'// child component
//import Navbar from '@/components/layout/Navbar.vue'
//import Footer from '@/components/layout/Footer.vue'

const app = createApp(App);
app.use(createPinia());
app.use(router);

app.component('Alert', Alert) // register globally
//app.component('Navbar', Navbar)
//app.component('Footer', Footer)
app.mount('#vue-app');
