<template>
  <form @submit.prevent="register">
    <input v-model="name" type="text" placeholder="Name" required />
    <input v-model="email" type="email" placeholder="Email" required />
    <input v-model="password" type="password" placeholder="Password" required />

    <select v-model="gender" required>
      <option disabled value="">Select Gender</option>
      <option value="male">Male</option>
      <option value="female">Female</option>
    </select>

    <button type="submit">Register</button>

    <p v-if="error" style="color:red">{{ error }}</p>
    <p v-if="success" style="color:green">{{ success }}</p>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api/axios'; // Your configured Axios instance

const router = useRouter();

const name = ref('');
const email = ref('');
const password = ref('');
const gender = ref('male'); // или 'female', чтобы сразу был выбран вариант по умолчанию
const error = ref('');
const success = ref('');

const register = async () => {
  error.value = '';
  success.value = '';
  try {
    const res = await api.post('/registration', {
      name: name.value,
      email: email.value,
      password: password.value,
      gender: gender.value,
    });

    const token = res.data.token;
    localStorage.setItem('token', token);
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    success.value = 'Registration successful!';
    router.push('/profile'); // Redirect to homepage or dashboard
  } catch (e) {
    console.error('Registration error:', e);

    if (e.response && e.response.data) {
      // Если есть сообщение от сервера
      error.value = e.response.data.message || 'Ошибка регистрации';
      // Если есть ошибки валидации (например, Laravel возвращает errors)
      if (e.response.data.errors) {
        error.value += ': ' + Object.values(e.response.data.errors).flat().join(', ');
      }
    } else {
      error.value = 'Ошибка регистрации. Проверьте соединение.';
    }
  }
};
</script>
