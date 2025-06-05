<template>
  <div class="payment-container" v-if="order && user">
    <h2 class="text-xl font-bold mb-2">Оплата заказа #{{ order.id }}</h2>

    <p><strong>Покупатель:</strong> {{ order.customer_name }}</p>
    <p><strong>Дата заказа:</strong> {{ order.order_date }}</p>
    <p><strong>Комментарий:</strong> {{ order.comment || '—' }}</p>
    <p><strong>Статус:</strong> {{ order.status }}</p>

    <h3 class="font-semibold mt-4">Товары:</h3>
    <ul>
      <li v-for="product in order.products || []" :key="product.id">
        {{ product.name }} — {{ product.quantity || 0 }} × {{ product.price }} = {{ (product.price * (product.quantity || 0)).toFixed(2) }}₽
      </li>
    </ul>

    <p class="mt-4"><strong>Сумма к оплате:</strong> {{ Number(totalPrice).toFixed(2) }}₽</p>
    <p><strong>Ваш баланс:</strong> {{ Number(user.balance || 0).toFixed(2) }}₽</p>

    <div class="mt-4">
      <button
        :disabled="order.status === 'completed' || user.balance < totalPrice || isPaying"
        @click="payOrder"
        class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
      >
        {{ isPaying ? 'Обработка...' : 'Оплатить' }}
      </button>

      <p v-if="order.status === 'completed'" class="text-green-600 mt-2">
        Заказ уже оплачен
      </p>

      <p v-else-if="user.balance < totalPrice" class="text-red-600 mt-2">
        Недостаточно средств для оплаты
      </p>
    </div>
  </div>

  <div v-else>
    <p>Загрузка заказа...</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api/axios'

const route = useRoute()
const router = useRouter()

const order = ref(null)
const user = ref(null)
const isPaying = ref(false)

const totalPrice = computed(() => {
  if (!order.value || !order.value.products) return 0
  return order.value.products.reduce((sum, product) => {
    const quantity = product.quantity || 0
    return sum + Number(product.price) * quantity
  }, 0)
})

const fetchOrder = async () => {
  try {
    const { data } = await api.get(`/orders/${route.params.id}`)
    order.value = data.data
  } catch (e) {
    alert('Ошибка при загрузке заказа')
  }
}

const fetchUser = async () => {
  try {
    const response = await api.get('/profile')
    user.value = response.data.user
  } catch (e) {
    alert('Ошибка при загрузке пользователя')
  }
}

const payOrder = async () => {
  isPaying.value = true
  try {
    await api.post('/transactions/purchase', {
      order_id: order.value.id,
    })
    alert('Оплата прошла успешно')
    await fetchUser()
    await fetchOrder()
    router.push('/profile')
  } catch (err) {
    console.error('Ошибка при оплате', err)
    alert(err.response?.data?.message || 'Ошибка при оплате заказа')
  } finally {
    isPaying.value = false
  }
}

onMounted(() => {
  fetchOrder()
  fetchUser()
})
</script>

<style scoped>
.payment-container {
  max-width: 600px;
  margin: auto;
  padding: 1rem;
}
</style>
