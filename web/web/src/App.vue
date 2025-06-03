<script setup>
import Login from './pages/Login.vue'
</script>

<template>
  <header>
    <img alt="Vue logo" class="logo" src="./assets/logo.svg" width="125" height="125" />
    <h1>Вход в систему</h1>
  </header>
  <router-view />
  <main>
    <Login />
  </main>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style scoped>
header {
  text-align: center;
  line-height: 1.5;
  margin-bottom: 2rem;
}

.logo {
  display: block;
  margin: 0 auto 1rem;
}

main {
  display: flex;
  justify-content: center;
}
</style>
