<template>
  <div class="form-container">
    <h2>Оформление заказа</h2>
    <form @submit.prevent="submitOrder">
      <label>Имя покупателя:
        <input v-model="order.customer_name" type="text" required />
      </label>

      <label>Дата заказа:
        <input v-model="order.order_date" type="date" required />
      </label>

      <label>Комментарий:
        <textarea v-model="order.comment"></textarea>
      </label>

      <div v-for="(item, index) in order.products" :key="index" style="margin-top: 1rem;">
        <label>Выберите товар:</label>
        <select v-model="item.id" required>
          <option disabled value="">-- выберите товар --</option>
          <option v-for="product in products" :key="product.id" :value="product.id">
            {{ product.name }}
          </option>
        </select>

        <label>Количество:</label>
        <input 
          type="number" 
          v-model.number="item.quantity" 
          :min="1" 
          :max="getMaxQuantity(item.id)" 
          required
        />
        <small v-if="item.id">Максимум: {{ getMaxQuantity(item.id) }}</small>

        <button type="button" @click="removeProduct(index)">Удалить</button>
      </div>

      <button type="button" @click="addProduct" style="margin-top: 1rem;">Добавить товар</button>
      <button type="submit" style="margin-top: 1rem;">Оформить</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../api/axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const products = ref([]);
const order = ref({
  customer_name: '',
  order_date: '',
  comment: '',
  products: [
    { id: '', quantity: 1 }
  ]
});

const loadProducts = async () => {
  try {
    const response = await api.get('/products');
    products.value = response.data.data;
  } catch (e) {
    console.error('Ошибка загрузки товаров', e);
  }
};

loadProducts();

const addProduct = () => {
  order.value.products.push({ id: '', quantity: 1 });
};

const removeProduct = (index) => {
  order.value.products.splice(index, 1);
};

// Функция, возвращающая максимум по количеству для выбранного товара
const getMaxQuantity = (productId) => {
  if (!productId) return 0;
  const product = products.value.find(p => p.id === productId);
  return product ? product.quantity : 0;
};

const submitOrder = async () => {
  // Проверяем, что количество не больше доступного для каждого товара
  for (const item of order.value.products) {
    const maxQty = getMaxQuantity(item.id);
    if (item.quantity > maxQty) {
      alert(`Количество для товара с id ${item.id} не может быть больше ${maxQty}`);
      return;
    }
    if (item.quantity < 1) {
      alert('Количество должно быть минимум 1');
      return;
    }
  }

  try {
    const response = await api.post('/orders', {
      customer_name: order.value.customer_name,
      order_date: order.value.order_date,
      comment: order.value.comment,
      products: order.value.products
    });
    
    alert('Заказ успешно оформлен');

    // Берём id созданного заказа из ответа
    const orderId = response.data.data.id; // или response.data.id — проверь структуру ответа!

    // Переходим на страницу оплаты заказа
    router.push(`/orders/${orderId}/pay`);
  } catch (err) {
    console.error('Ошибка при оформлении заказа', err);
    alert('Ошибка при оформлении заказа');
  }
};
</script>

<style scoped>
.form-container {
  max-width: 600px;
  margin: auto;
  padding: 1rem;
}
form label {
  display: block;
  margin-top: 1rem;
}
</style>
