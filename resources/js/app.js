import './bootstrap';

import { createApp } from 'vue';
import DashboardComponent from './components/DashboardComponent.vue';

const app = createApp({});

app.component('dashboard-component', DashboardComponent);

app.mount('#app');