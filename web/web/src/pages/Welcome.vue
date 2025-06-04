<template>
  <div>
    <header>
      <h1>Добро пожаловать!</h1>
      <p>Пожалуйста, войдите или зарегистрируйтесь:</p>
      <button @click="router.push('/login')">Войти</button>
      <button @click="router.push('/registration')">Зарегистрироваться</button>
    </header>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();
</script>
