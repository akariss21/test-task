<template>
  <div class="product-list">
    <h2>Список товаров</h2>

    <div v-if="products.length === 0">
      Нет товаров для отображения.
    </div>

    <ul v-else>
      <li v-for="product in products" :key="product.id">
        <strong>{{ product.name }}</strong> — {{ product.quantity }} шт. — {{ product.price }}₽  
        <em>({{ product.category.name }})</em>
        <button @click="editProduct(product.id)">Изменить</button>
      </li>
    </ul>
    <button @click="goToProfile">Выйти</button>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api/axios';

const products = ref([]);
const router = useRouter();

const fetchProducts = async () => {
  try {
    const res = await api.get('/products');
    products.value = res.data.data;
  } catch (err) {
    console.error('Ошибка при получении товаров:', err);
  }
};

const goToProfile = () => {
  router.push('/profile');
};

const editProduct = (id) => {
  router.push(`/products/${id}/edit`);
};

onMounted(() => {
  fetchProducts();
});
</script>
