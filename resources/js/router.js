import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from './stores/auth';

// Lazy loading components
const Transaction = () => import('./views/Transaction.vue');
const Dashboard = () => import('./views/Home.vue');
const Login = () => import('./views/Login.vue');

const routes = [
    {
        path: '/',
        component: Transaction,
        name: 'Transaction'
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        component: Login,
        name: 'Login',
        meta: { guest: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Check if user is authenticated (e.g., check for token in localStorage)
    // In a real app, you might want to validate the token with the backend here if it's not in the store yet
    if (!authStore.user && authStore.token) {
        await authStore.getUser();
    }

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'Login' });
    } else if (to.meta.guest && authStore.isAuthenticated) {
        next({ path: '/dashboard' });
    } else {
        next();
    }
});

export default router;
