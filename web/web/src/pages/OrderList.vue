<template>
  <div>
    <h2>Список заказов</h2>
    <div v-if="orders.length === 0">Нет заказов для отображения.</div>
    <ul v-else>
      <li v-for="order in orders" :key="order.id">
        Заказ #{{ order.id }} от {{ order.order_date }} — {{ order.status }}
        <ul>
          <li v-for="product in order.products" :key="product.id">
            {{ product.name }} — {{ product.quantity }} шт. по {{ product.price }} руб.
          </li>
        </ul>
        Итого: {{ order.total_price }} руб.
      </li>
    </ul>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import api from '../api/axios';

const orders = ref([]);

const goToProfile = () => {
  router.push('/profile');
};

onMounted(async () => {
  try {
    const res = await api.get('/orders');
    orders.value = res.data.data;
  } catch (err) {
    console.error('Ошибка при получении заказов:', err);
  }
});
</script>
