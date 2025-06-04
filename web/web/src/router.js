// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue';
import Dashboard from './pages/Dashboard.vue';
import Registration from './pages/Registration.vue';
import Home from './pages/Home.vue';
import Profile from './pages/Profile.vue';
import Welcome from './pages/Welcome.vue';

const routes = [
  { path: '/', name: 'Welcome', component: Welcome }, // теперь '/' — выбор входа/регистрации
  { path: '/dashboard', name: 'Dashboard', component: Dashboard }, // поменял путь
  { path: '/login', name: 'Login', component: Login },
  { path: '/profile', name: 'Profile', component: Profile },
  { path: '/registration', name: 'Registration', component: Registration },
  { path: '/home', name: 'Home', component: Home }
];

// ✅ Сохраняем роутер в переменную
const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const publicPages = ['/', '/login', '/registration'];
  const authRequired = !publicPages.includes(to.path);

  if (authRequired && !token) {
    return next('/'); // Перекидываем неавторизованных на Welcome
  }

  if (to.path === '/' && token) {
    return next('/profile'); // Авторизованных с главной — на профиль
  }

  next();
});

// ✅ Экспортим только после настройки
export default router;
