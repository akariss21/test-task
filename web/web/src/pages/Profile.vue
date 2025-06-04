<template>
  <div v-if="user" class="profile">
    <h2>Профиль пользователя</h2>
    <p><strong>ID:</strong> {{ user.id }}</p>
    <p><strong>Имя:</strong> {{ user.name }}</p>
    <p><strong>Email:</strong> {{ user.email }}</p>
    <p><strong>Пол:</strong> {{ user.gender }}</p>
    <p><strong>Баланс:</strong> {{ user.balance }}</p>
    <p><strong>Дата регистрации:</strong> {{ user.created_at }}</p>

    <button @click="logout">Выйти</button>
  </div>

  <p v-else-if="error" style="color: red;">{{ error }}</p>
  <p v-else>Загрузка...</p>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api/axios';

const user = ref(null);
const error = ref('');
const router = useRouter();

onMounted(async () => {
  try {
    const res = await api.get('/profile');
    console.log('Ответ от /profile:', res.data);
    user.value = res.data.user;  // <- вот тут ключ user
  } catch (err) {
    console.error('Ошибка загрузки профиля:', err);
    error.value = 'Не удалось загрузить профиль';
  }
});

const logout = () => {
  localStorage.removeItem('token');
  delete api.defaults.headers.common['Authorization'];
  router.push('/login');
};
</script>

<style scoped>
.profile {
  max-width: 500px;
  margin: 2rem auto;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
}

button {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background-color: #c00;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #900;
}
</style>
