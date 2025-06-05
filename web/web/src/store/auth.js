import { defineStore } from 'pinia';
import api from '../api/axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null
  }),
  actions: {
    async login(credentials) {
      const response = await api.post('/login', credentials);
      this.token = response.data.token;
      this.user = response.data.user;

      // Сохраняем в localStorage
      localStorage.setItem('token', this.token);
    },

    async fetchUser() {
      if (!this.token) return;

      const response = await api.get('/user', {
        headers: { Authorization: `Bearer ${this.token}` }
      });

      this.user = response.data;
    },

    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
    }
  }
});
