import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue';
import Dashboard from './pages/Dashboard.vue';

const routes = [
  { path: '/', name: 'Dashboard', component: Dashboard },
  { path: '/login', name: 'Login', component: Login }
];

export default createRouter({
  history: createWebHistory(),
  routes
});

