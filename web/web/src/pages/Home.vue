<template>
  <div style="text-align:center; margin-top:2rem;">
    <h2>Рады приветствовать вас на сайте!</h2>
    <button @click="logout">Выйти</button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
  localStorage.removeItem('token'); // удаляем токен
  router.push('/'); 
};
</script>
