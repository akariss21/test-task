<template>
  <form @submit.prevent="login">
    <input v-model="email" type="email" placeholder="Email" required />
    <input v-model="password" type="password" placeholder="Password" required />
    <button type="submit">Войти</button>
    <p v-if="error" style="color:red">{{ error }}</p>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router'; // Add this import
import api from '../api/axios';

const router = useRouter(); // Add this line

const email = ref('');
const password = ref('');
const error = ref('');

const login = async () => {
  error.value = '';
  try {
    const res = await api.post('/login', {
      email: email.value,
      password: password.value
    });
    console.log('Login success', res.data);
    
    // Сохраняем токен
    const token = res.data.token;
    localStorage.setItem('token', token);
    
    // Добавляем токен в axios для следующих запросов
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    
    // Можно перенаправить пользователя, например:
    router.push('/profile');
  } catch (e) {
    error.value = e.response?.data?.message || 'Ошибка входа';
    console.error('Login failed:', e.response?.data);
  }
};
</script>