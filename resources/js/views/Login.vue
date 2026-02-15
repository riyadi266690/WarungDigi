<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-900 via-purple-900 to-black p-4 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
      <div class="px-8 py-10">
        <div class="mb-8 text-center">
          <h1 class="text-3xl font-bold text-white mb-2">WarungDigi Admin</h1>
          <p class="text-gray-300 text-sm">Sign in to access your dashboard</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          
          <div v-if="authStore.errors.email" class="bg-red-500/20 border border-red-500/50 text-red-200 text-sm rounded-lg p-3">
             {{ authStore.errors.email[0] }}
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email Address</label>
            <input 
              v-model="form.email"
              id="email" 
              type="email" 
              required
              class="w-full px-4 py-3 bg-black/20 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-white placeholder-gray-400 transition-all duration-200 outline-none"
              placeholder="admin@example.com"
            >
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
            <input 
              v-model="form.password"
              id="password" 
              type="password" 
              required
              class="w-full px-4 py-3 bg-black/20 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-white placeholder-gray-400 transition-all duration-200 outline-none"
              placeholder="••••••••"
            >
          </div>

          <button 
            type="submit" 
            :disabled="loading"
            class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-lg shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2"
          >
            <span v-if="loading" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
            <span v-else>Sign In</span>
          </button>
        </form>
      </div>
      <div class="px-8 py-4 bg-black/20 border-t border-white/10 text-center">
         <p class="text-xs text-gray-400">Restricted Access. Admins Only.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();
const form = ref({
  email: '',
  password: ''
});
const loading = ref(false);

const handleLogin = async () => {
    loading.value = true;
    await authStore.login(form.value);
    loading.value = false;
};
</script>

<style scoped>
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
</style>
