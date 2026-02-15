<template>
  <div class="min-h-screen flex flex-col selection:bg-indigo-100 selection:text-indigo-700 font-sans">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
      <div class="container mx-auto px-4 h-20 flex items-center justify-between max-w-6xl">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-100">
            <span class="text-white text-xl font-black">W</span>
          </div>
          <h1 class="text-xl font-black text-slate-800 tracking-tight">Warung<span class="text-indigo-600">Digi</span></h1>
        </div>
        
        <router-link 
            to="/login"
            class="px-6 py-2.5 rounded-xl font-bold text-sm bg-slate-900 text-white hover:bg-black transition-all shadow-lg hover:shadow-xl active:scale-95"
        >
            Login Admin
        </router-link>
      </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 max-w-6xl">
      <!-- Hero Section -->
      <div class="text-center mb-12 animate-fade-in-up">
        <div class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-6">
          Solusi Digital Terpercaya
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
          Bayar Tagihan & <br/>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Top Up Digital</span>
        </h1>
        <p class="text-slate-500 text-lg max-w-2xl mx-auto leading-relaxed">
          Platform pembayaran digital terlengkap untuk kebutuhan harianmu. 
          Aman, cepat, dan otomatis 24 jam.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-12">
        <!-- Main Transaction Panel -->
        <div class="lg:col-span-8 space-y-6">
          <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-slate-50/50 p-6 border-b border-slate-100 flex items-center justify-between">
              <h2 class="text-lg font-bold text-slate-800">Panel Pembayaran</h2>
              <div class="flex gap-2 items-center">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Online</span>
              </div>
            </div>

            <div class="p-6 md:p-8">
              <!-- Category Selector -->
              <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
                <button
                  v-for="cat in CATEGORIES"
                  :key="cat.id"
                  @click="selectCategory(cat.id)"
                  class="flex flex-col items-center justify-center p-5 rounded-2xl border transition-all duration-200"
                  :class="selectedCategory === cat.id 
                    ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg scale-105 ring-4 ring-indigo-50' 
                    : 'bg-white border-slate-100 text-slate-600 hover:border-indigo-300'"
                >
                  <span class="text-3xl mb-2">{{ cat.icon }}</span>
                  <span class="text-sm font-bold">{{ cat.id }}</span>
                </button>
              </div>

              <!-- Transaction Form -->
              <div class="mb-8">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                  {{ getLabel() }}
                </label>
                <div class="flex gap-2">
                  <input
                    type="text"
                    v-model="customerNumber"
                    class="flex-grow bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-lg font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                    :placeholder="getPlaceholder()"
                    @input="handleInput"
                  />
                  <button 
                    v-if="selectedCategory === ServiceCategory.APARTEMEN"
                    @click="handleCheckApartmentBill"
                    :disabled="!customerNumber || isLoadingBill"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 disabled:opacity-50 transition-all shadow-lg shadow-indigo-100"
                  >
                    {{ isLoadingBill ? '...' : 'Cek' }}
                  </button>
                </div>

                <!-- Bill Detail (Apartment) -->
                <div v-if="billData && selectedCategory === ServiceCategory.APARTEMEN" class="mt-4 p-5 bg-indigo-50 rounded-2xl border border-indigo-100 animate-fade-in-up">
                  <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3">Detail Tagihan</h4>
                  <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                      <span class="text-slate-500">Nama Penghuni</span>
                      <span class="font-bold text-slate-800">{{ billData.customerName }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-slate-500">Unit / Tower</span>
                      <span class="font-bold text-slate-800">{{ billData.unit }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-slate-500">Periode</span>
                      <span class="font-bold text-slate-800">{{ billData.period }}</span>
                    </div>
                    <div class="pt-2 border-t border-indigo-200 flex justify-between items-center">
                      <span class="text-indigo-600 font-semibold">Total Tagihan</span>
                      <span class="text-xl font-black text-indigo-700">
                        Rp {{ formatCurrency(billData.amount + billData.adminFee) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Product Selector -->
              <div v-if="selectedCategory !== ServiceCategory.APARTEMEN" class="mb-8">
                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button
                        v-for="product in filteredProducts"
                        :key="product.id"
                        @click="selectedProduct = product"
                        class="p-4 rounded-xl border text-left transition-all duration-200 relative overflow-hidden group"
                        :class="selectedProduct?.id === product.id
                            ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg ring-2 ring-indigo-100'
                            : 'bg-white border-slate-100 hover:border-indigo-300'"
                    >
                        <div class="relative z-10">
                            <h3 class="font-bold mb-1" :class="selectedProduct?.id === product.id ? 'text-white' : 'text-slate-800'">
                                {{ product.name }}
                            </h3>
                            <p class="text-xs mb-3" :class="selectedProduct?.id === product.id ? 'text-indigo-100' : 'text-slate-400'">
                                {{ product.description }}
                            </p>
                            <div class="font-black text-lg" :class="selectedProduct?.id === product.id ? 'text-white' : 'text-indigo-600'">
                                Rp {{ formatCurrency(product.price) }}
                            </div>
                        </div>
                    </button>
                 </div>
              </div>

              <!-- Checkout Action -->
              <div class="mt-8 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                  <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Total Bayar</p>
                  <p class="text-3xl font-black text-indigo-600">
                    Rp {{ formatCurrency(totalAmount) }}
                  </p>
                </div>
                <button
                  @click="handleCheckout"
                  :disabled="(!selectedProduct && !billData) || !customerNumber || isProcessing"
                  class="w-full sm:w-auto px-12 py-4 rounded-2xl font-black text-lg transition-all"
                  :class="(!selectedProduct && !billData) || !customerNumber || isProcessing
                    ? 'bg-slate-100 text-slate-300 cursor-not-allowed'
                    : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-xl shadow-indigo-200 active:scale-95'"
                >
                  {{ isProcessing ? 'PROCESSING...' : 'BAYAR SEKARANG' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar / History -->
        <div class="lg:col-span-4 space-y-6">
          <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center justify-between">
              History
              <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded">Terbaru</span>
            </h3>
            
            <div v-if="recentTransactions.length === 0" class="text-center py-12 border-2 border-dashed border-slate-50 rounded-2xl">
              <p class="text-slate-300 text-sm font-medium">Belum ada transaksi</p>
            </div>
            
            <div v-else class="space-y-4">
               <div v-for="tx in recentTransactions.slice(0, 5)" :key="tx.id" class="group relative flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-transparent hover:border-indigo-100 hover:bg-white transition-all">
                  <div class="w-10 h-10 bg-white shadow-sm text-green-500 rounded-full flex items-center justify-center font-bold">✓</div>
                  <div class="flex-grow">
                    <p class="text-sm font-bold text-slate-800">{{ tx.productName }}</p>
                    <p class="text-xs text-slate-400 font-mono tracking-tighter">{{ tx.customerNumber }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-xs font-bold text-slate-700">Rp {{ formatCurrency(tx.amount) }}</p>
                    <p class="text-[10px] text-slate-400 uppercase">{{ formatTime(tx.timestamp) }}</p>
                  </div>
               </div>
            </div>
          </div>

          <!-- Digi Assist Placeholder -->
           <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden">
               <div class="relative z-10">
                <h3 class="font-black text-xl mb-2">Digi Assist</h3>
                <p class="text-slate-400 text-sm mb-6 leading-relaxed">Tanya Digi AI tentang promo Apartemen Transit bulan ini!</p>
                <button 
                  class="w-full bg-indigo-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-indigo-500 transition-all shadow-lg"
                >
                  Chat Assistant
                </button>
              </div>
              <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/20 rounded-full -mr-16 -mt-16 blur-3xl"></div>
            </div>
        </div>
      </div>
    </main>
    
    <footer class="bg-white border-t border-slate-100 py-12 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-slate-400 text-sm">© 2024 WarungDigi. All rights reserved.</p>
        </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { ServiceCategory, CATEGORIES, MOCK_PRODUCTS } from '../constants';

// State
const selectedCategory = ref(ServiceCategory.PULSA);
const customerNumber = ref('');
const selectedProduct = ref(null);
const isLoadingBill = ref(false);
const billData = ref(null);
const isProcessing = ref(false);
const recentTransactions = ref([]);

// Computed
const filteredProducts = computed(() => {
    return MOCK_PRODUCTS.filter(p => p.category === selectedCategory.value);
});

const totalAmount = computed(() => {
    if (billData.value) {
        return billData.value.amount + billData.value.adminFee;
    }
    return selectedProduct.value?.price || 0;
});

// Methods
const selectCategory = (category) => {
    selectedCategory.value = category;
    selectedProduct.value = null;
    billData.value = null;
    customerNumber.value = '';
};

const handleInput = (e) => {
    customerNumber.value = e.target.value.toUpperCase();
};

const getLabel = () => {
    switch (selectedCategory.value) {
        case ServiceCategory.PLN: return 'Nomor Meter / ID Pelanggan';
        case ServiceCategory.PDAM: return 'Nomor Pelanggan PDAM';
        case ServiceCategory.APARTEMEN: return 'Kode Bayar Apartemen';
        default: return 'Nomor Handphone';
    }
};

const getPlaceholder = () => {
    switch (selectedCategory.value) {
        case ServiceCategory.APARTEMEN: return 'Contoh: APT-2024-XXXX';
        default: return 'Contoh: 08123456789';
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID').format(amount);
};

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
};

const handleCheckApartmentBill = async () => {
    if (!customerNumber.value) return;
    isLoadingBill.value = true;
    billData.value = null;

    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));

    billData.value = {
        customerName: "Budi Setiawan",
        unit: "TOWER A - 1205",
        period: "Maret 2024",
        amount: 450000,
        adminFee: 2500
    };
    isLoadingBill.value = false;
};

const handleCheckout = async () => {
    isProcessing.value = true;
    await new Promise(resolve => setTimeout(resolve, 2000));

    const productName = billData.value 
        ? `Sewa Apartemen - ${billData.value.period}` 
        : (selectedProduct.value?.name || '');

    const newTransaction = {
        id: Math.random().toString(36).substr(2, 9),
        customerNumber: customerNumber.value,
        productName,
        amount: totalAmount.value,
        timestamp: new Date()
    };

    recentTransactions.value.unshift(newTransaction);
    isProcessing.value = false;
    
    // Reset form but keep history
    selectedProduct.value = null;
    billData.value = null;
    customerNumber.value = '';
    
    alert('Pembayaran Berhasil! (Simulasi)');
};
</script>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
