<template>
  <div v-if="product">
    <h2>Редактировать товар</h2>
    <form @submit.prevent="updateProduct">
      <input v-model="product.name" placeholder="Название" required />
      <input v-model="product.description" placeholder="Описание" />
      <input type="number" v-model.number="product.price" placeholder="Цена" required step="0.01" />
      <select v-model="product.category_id" required>
        <option value="1">Light</option>
        <option value="2">Fragile</option>
        <option value="3">Heavy</option>
      </select>
      <input type="number" v-model.number="product.quantity" placeholder="Количество" required min="0" />

      <button type="submit">Сохранить</button>
      <button type="button" @click="deleteProduct">Удалить</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api/axios';

const product = ref(null);
const route = useRoute();
const router = useRouter();

const fetchProduct = async () => {
  try {
    const res = await api.get(`/products/${route.params.id}`);
    product.value = res.data.data;
  } catch (err) {
    console.error('Ошибка загрузки товара:', err);
  }
};

const updateProduct = async () => {
  try {
    await api.patch(`/products/${route.params.id}`, product.value);
    alert('Товар обновлён!');
    router.push('/product');
  } catch (err) {
    console.error('Ошибка обновления:', err);
  }
};

const deleteProduct = async () => {
  if (!confirm('Вы уверены, что хотите удалить этот товар?')) return;
  try {
    await api.delete(`/products/${route.params.id}`);
    alert('Товар удалён');
    router.push('/product');
  } catch (err) {
    console.error('Ошибка удаления:', err);
  }
};

onMounted(fetchProduct);
</script>
