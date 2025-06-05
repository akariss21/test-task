<template>
  <div class="product-create">
    <h2>Создание товара</h2>

    <form @submit.prevent="submitProduct">
      <label>Название:</label>
      <input v-model="product.name" required />

      <label>Описание:</label>
      <textarea v-model="product.description"></textarea>

      <label>Цена:</label>
      <input type="number" step="0.01" v-model="product.price" required />

      <label>Количество:</label>
      <input type="number" v-model="product.quantity" required />

      <label>Категория:</label>
      <select id="category" v-model="product.category_id" required>
        <option disabled value="">Выберите категорию</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>

      <button type="submit">Создать</button>
    </form>

    <p v-if="message" style="color: green">{{ message }}</p>
    <p v-if="error" style="color: red">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../api/axios';
import { useRouter } from 'vue-router';

const product = ref({
  name: '',
  description: '',
  price: null,
  quantity: 0,
  category_id: null
});

const router = useRouter();
const message = ref('');
const error = ref('');
const categories = [
  { id: 1, name: 'Light' },
  { id: 2, name: 'Fragile' },
  { id: 3, name: 'Heavy' },
];

const submitProduct = async () => {
  try {
    const res = await api.post('/products', product.value);
    message.value = 'Товар успешно создан!';
    error.value = '';
    product.value = {
      name: '',
      description: '',
      price: null,
      quantity: 0,
      category_id: null
    };
    router.push('/profile');
  } catch (err) {
    error.value = 'Ошибка при создании товара.';
    message.value = '';
    console.error(err);
  }
};
</script>

<style scoped>
.product-create {
  max-width: 500px;
  margin: 2rem auto;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
}

form label {
  display: block;
  margin-top: 1rem;
}

input, textarea, select {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.25rem;
}
</style>
