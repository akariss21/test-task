<template>
  <div class="dashboard">
    <header class="dashboard-header">
      <h1>Dashboard</h1>
      <button @click="logout" class="logout-btn">Logout</button>
    </header>
    
    <main class="dashboard-content">
      <div class="welcome-card">
        <h2>Welcome back!</h2>
        <p>Today is {{ currentDate }}</p>
      </div>
      
      <div class="stats-grid">
        <div class="stat-card">
          <h3>Users</h3>
          <p class="stat-number">{{ stats.users }}</p>
        </div>
        <div class="stat-card">
          <h3>Orders</h3>
          <p class="stat-number">{{ stats.orders }}</p>
        </div>
        <div class="stat-card">
          <h3>Revenue</h3>
          <p class="stat-number">${{ stats.revenue }}</p>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'Dashboard',
  setup() {
    const router = useRouter()
    
    const stats = ref({
      users: 1234,
      orders: 567,
      revenue: 12500
    })
    
    const currentDate = computed(() => {
      return new Date().toLocaleDateString()
    })
    
    const logout = () => {
      router.push('/login')
    }
    
    return {
      stats,
      currentDate,
      logout
    }
  }
}
</script>

<style scoped>
.dashboard {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid #eee;
}

.logout-btn {
  padding: 8px 16px;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.welcome-card {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.stat-card {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  text-align: center;
}

.stat-number {
  font-size: 2em;
  font-weight: bold;
  color: #007bff;
  margin: 10px 0;
}
</style>