import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Laravel API
  timeout: 10000,
  headers: {
    Accept: 'application/json'
  }
});

// Добавляем токен из localStorage при каждом запросе (если есть)
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;