import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Laravel API
  timeout: 10000,
  headers: {
    Accept: 'application/json'
  }
});

// Добавляем токен при наличии
const token = localStorage.getItem('token');
if (token) {
  api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export default api;
