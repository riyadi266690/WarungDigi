<template>
  <div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col transition-all duration-300">
      <div class="h-16 flex items-center justify-center border-b border-slate-800">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-600 bg-clip-text text-transparent">WarungDigi</h1>
      </div>

      <nav class="flex-1 p-4 space-y-2">
        <a href="#" class="flex items-center space-x-3 px-4 py-3 bg-purple-600/20 text-purple-400 rounded-lg border border-purple-600/30">
          <ChartBarIcon class="w-6 h-6" />
          <span class="font-medium">Dashboard</span>
        </a>
        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-400 hover:bg-slate-800 hover:text-white rounded-lg transition-colors">
          <UsersIcon class="w-6 h-6" />
          <span>Users</span>
        </a>
        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-400 hover:bg-slate-800 hover:text-white rounded-lg transition-colors">
          <ShoppingBagIcon class="w-6 h-6" />
          <span>Orders</span>
        </a>
        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-400 hover:bg-slate-800 hover:text-white rounded-lg transition-colors">
          <Cog6ToothIcon class="w-6 h-6" />
          <span>Settings</span>
        </a>
      </nav>

      <div class="p-4 border-t border-slate-800">
        <div class="flex items-center space-x-3 px-4 py-2">
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                {{ authStore.user?.name?.charAt(0) || 'A' }}
            </div>
            <div>
                <p class="text-sm font-medium text-white">{{ authStore.user?.name }}</p>
                <p class="text-xs text-gray-400">Administrator</p>
            </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center space-x-4">
             <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>
        </div>
        <div class="flex items-center space-x-4">
          <button @click="handleLogout" class="flex items-center space-x-2 text-gray-600 hover:text-red-500 transition-colors">
            <ArrowRightOnRectangleIcon class="w-5 h-5" />
            <span class="text-sm font-medium">Logout</span>
          </button>
        </div>
      </header>

      <!-- Content Scrollable -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Cards -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
                    <CurrencyDollarIcon class="w-8 h-8 text-green-500 bg-green-50 rounded-lg p-1" />
                </div>
                <p class="text-3xl font-bold text-gray-900">$45,231.89</p>
                <p class="text-xs text-green-500 flex items-center mt-2">
                    <ArrowTrendingUpIcon class="w-3 h-3 mr-1" />
                    +20.1% from last month
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Active Users</h3>
                    <UserGroupIcon class="w-8 h-8 text-blue-500 bg-blue-50 rounded-lg p-1" />
                </div>
                <p class="text-3xl font-bold text-gray-900">2,350</p>
                <p class="text-xs text-blue-500 flex items-center mt-2">
                    <ArrowTrendingUpIcon class="w-3 h-3 mr-1" />
                    +180 new this week
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Pending Orders</h3>
                    <ShoppingCartIcon class="w-8 h-8 text-orange-500 bg-orange-50 rounded-lg p-1" />
                </div>
                <p class="text-3xl font-bold text-gray-900">45</p>
                <p class="text-xs text-orange-500 flex items-center mt-2">
                    <ExclamationCircleIcon class="w-3 h-3 mr-1" />
                    Action required
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Sales Rate</h3>
                    <PresentationChartLineIcon class="w-8 h-8 text-purple-500 bg-purple-50 rounded-lg p-1" />
                </div>
                <p class="text-3xl font-bold text-gray-900">+573.00</p>
                <p class="text-xs text-purple-500 flex items-center mt-2">
                    <ArrowTrendingUpIcon class="w-3 h-3 mr-1" />
                    +201 since last hour
                </p>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
            </div>
            <div class="p-6">
                 <p class="text-gray-500 text-sm text-center py-8">No recent transactions to display.</p>
            </div>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup>
import { 
    ChartBarIcon, 
    UsersIcon, 
    ShoppingBagIcon, 
    Cog6ToothIcon, 
    ArrowRightOnRectangleIcon,
    CurrencyDollarIcon,
    ArrowTrendingUpIcon,
    UserGroupIcon,
    ShoppingCartIcon,
    ExclamationCircleIcon,
    PresentationChartLineIcon
} from '@heroicons/vue/24/outline';

import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();

const handleLogout = () => {
    authStore.logout();
};
</script>
