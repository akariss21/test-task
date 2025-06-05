<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const isAuthenticated = ref(false);

onMounted(() => {
  const token = localStorage.getItem('token');
  if (token) {
    isAuthenticated.value = true;
    if (route.path === '/') {
      router.push('/'); // или /dashboard
    }
  }
});

// реактивный путь (для шаблона)
const currentPath = computed(() => route.path);
</script>

<template>
  <div>
  <!-- Отображаем все остальное (логин, регистрация, dashboard, home) -->
    <router-view />
  </div>
</template>

<style scoped>
header {
  text-align: center;
  margin-top: 3rem;
}

button {
  margin: 0.5rem;
  padding: 0.5rem 1rem;
  font-size: 16px;
  cursor: pointer;
}
</style>