import { defineStore } from 'pinia';
import axios from 'axios';
import router from '../router';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        errors: [],
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.is_admin,
    },
    actions: {
        async getUser() {
            if (this.token) {
                try {
                    const response = await axios.get('/api/user', {
                        headers: { Authorization: `Bearer ${this.token}` }
                    });
                    this.user = response.data;
                } catch (error) {
                    this.user = null;
                    this.token = null;
                    localStorage.removeItem('token');
                }
            }
        },
        async login(data) {
            this.errors = [];
            try {
                // Get CSRF cookie first
                await axios.get('/sanctum/csrf-cookie');

                const response = await axios.post('/api/login', data);
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('token', this.token);

                // Configure axios defaults for future requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

                await router.push('/dashboard');
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors;
                } else if (error.response?.status === 401) {
                    this.errors = { email: [error.response.data.message || 'Invalid credentials'] };
                } else {
                    console.error(error);
                }
            }
        },
        async logout() {
            try {
                await axios.post('/api/logout', {}, {
                    headers: { Authorization: `Bearer ${this.token}` }
                });
            } catch (error) {
                console.error(error);
            } finally {
                this.user = null;
                this.token = null;
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
                router.push('/login');
            }
        }
    }
});
