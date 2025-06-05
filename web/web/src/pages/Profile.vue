<template>
  <div v-if="user" class="profile">
    <h2>Профиль пользователя</h2>
    <p><strong>ID:</strong> {{ user.id }}</p>
    <p><strong>Имя:</strong> {{ user.name }}</p>
    <p><strong>Email:</strong> {{ user.email }}</p>
    <p><strong>Пол:</strong> {{ user.gender }}</p>
    <p><strong>Баланс:</strong> {{ user.balance }}</p>
    <p><strong>Дата регистрации:</strong> {{ user.created_at }}</p>
    <p><strong>Роль:</strong> {{ user.role }}</p>

    <!-- Кнопки -->
    <button v-if="user.role === 'customer'" @click="becomeSeller">Станьте продавцом!</button>
    <button @click="router.push('/orders/create')">Оформить заказ</button>
    <button v-if="user.role === 'seller'" @click="router.push('/products/create')">Создать товар</button>
    <button v-if="user.role === 'seller'" @click="router.push('/product')">Все товары</button>
    <button @click="router.push('/orders')">Все заказы</button>
    <button @click="showDeposit = true">Пополнить баланс</button>
    <button @click="showWithdraw = true">Вывести деньги</button>

    <!-- Кнопка выхода -->
    <button @click="logout">Выйти</button>
  </div>

  <!-- Модальные формы -->
  <div v-if="showDeposit">
    <input type="number" v-model="depositAmount" placeholder="Сумма пополнения" />
    <button @click="deposit">Пополнить</button>
  </div>

  <div v-if="showWithdraw">
    <input type="number" v-model="withdrawAmount" placeholder="Сумма вывода" />
    <button @click="withdraw">Вывести</button>
  </div>
  <p v-else-if="error" style="color: red;">{{ error }}</p>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api/axios';

const user = ref(null);
const error = ref('');
const router = useRouter();

const fetchProfile = async () => {
  try {
    const res = await api.get('/profile');
    user.value = res.data.user;
  } catch (err) {
    console.error('Ошибка загрузки профиля:', err);
    error.value = 'Не удалось загрузить профиль';
  }
};

onMounted(fetchProfile);

const logout = () => {
  localStorage.removeItem('token');
  delete api.defaults.headers.common['Authorization'];
  router.push('/login');
};

const showDeposit = ref(false);
const showWithdraw = ref(false);
const depositAmount = ref(0);
const withdrawAmount = ref(0);

const deposit = async () => {
  try {
    const res = await api.post('/transactions/deposit', { amount: depositAmount.value });
    alert(res.data.message);
    location.reload();
  } catch (err) {
    alert('Ошибка при пополнении');
  }
};

const withdraw = async () => {
  try {
    const res = await api.post('/transactions/withdraw', { amount: withdrawAmount.value });
    alert(res.data.message);
    location.reload();
  } catch (err) {
    alert(err.response?.data?.message || 'Ошибка при выводе');
  }
};

const becomeSeller = async () => {
  try {
    const res = await api.post('/user/become-seller');
    alert(res.data.message);
    fetchProfile(); // Обновить профиль после смены роли
  } catch (err) {
    console.error('Ошибка при попытке стать продавцом:', err);
    alert('Не удалось изменить роль.');
  }
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
