@extends('layouts.app')

@section('content')
<div x-data="transactionApp()" class="animate-fade-in-up">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <div class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-6">
            Solusi Digital Terpercaya
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
            Bayar Tagihan & <br />
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
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative">

                <!-- Loading Overlay -->
                <div x-show="isProcessing" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>

                <div class="bg-slate-50/50 p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800">Panel Pembayaran</h2>
                    <div class="flex gap-2 items-center">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Online</span>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <!-- Step 1: Selection -->
                    <div x-show="step === 1">
                        <!-- Category Selector -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
                            <template x-for="cat in categories" :key="cat.id">
                                <button
                                    @click="selectCategory(cat.id)"
                                    class="flex flex-col items-center justify-center p-5 rounded-2xl border transition-all duration-200"
                                    :class="selectedCategory === cat.id 
                                        ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg scale-105 ring-4 ring-indigo-50' 
                                        : 'bg-white border-slate-100 text-slate-600 hover:border-indigo-300'">
                                    <span class="text-3xl mb-2" x-text="cat.icon"></span>
                                    <span class="text-sm font-bold" x-text="cat.id"></span>
                                </button>
                            </template>
                        </div>

                        <!-- Form & Products -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-slate-700 mb-2" x-text="getLabel()"></label>
                            <div class="flex gap-2 mb-6">
                                <input
                                    type="text"
                                    x-model="customerNumber"
                                    class="flex-grow bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-lg font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                    :placeholder="getPlaceholder()"
                                    @input="customerNumber = customerNumber.toUpperCase()" />
                                <template x-if="selectedCategory === 'Apartemen Transit'">
                                    <button
                                        @click="checkApartmentBill()"
                                        :disabled="!customerNumber || isLoadingBill"
                                        class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 disabled:opacity-50 transition-all shadow-lg shadow-indigo-100"
                                        x-text="isLoadingBill ? '...' : 'Cek'">
                                    </button>
                                </template>
                                <template x-if="selectedCategory === 'Token PLN'">
                                    <button
                                        @click="checkPlnCustomer()"
                                        :disabled="!customerNumber || isLoadingBill"
                                        class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 disabled:opacity-50 transition-all shadow-lg shadow-indigo-100"
                                        x-text="isLoadingBill ? '...' : 'Cek ID'">
                                    </button>
                                </template>
                            </div>

                            <!-- Bill Detail (Apartment) -->
                            <template x-if="billData && selectedCategory === 'Apartemen Transit'">
                                <div class="mt-4 p-5 bg-indigo-50 rounded-2xl border border-indigo-100 animate-fade-in-up mb-6">
                                    <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3">Detail Tagihan</h4>
                                    <!-- ... Bill Details ... -->
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Nama Penghuni</span>
                                            <span class="font-bold text-slate-800" x-text="billData.customerName"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Unit / Tower</span>
                                            <span class="font-bold text-slate-800" x-text="billData.unit"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Periode</span>
                                            <span class="font-bold text-slate-800" x-text="billData.period"></span>
                                        </div>
                                        <div class="pt-2 border-t border-indigo-200 flex justify-between items-center">
                                            <span class="text-indigo-600 font-semibold">Total Tagihan</span>
                                            <span class="text-xl font-black text-indigo-700" x-text="'Rp ' + formatCurrency(billData.amount + billData.adminFee)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- PLN Inquiry Result -->
                            <template x-if="plnCustomer && selectedCategory === 'Token PLN'">
                                <div class="mt-4 p-5 bg-yellow-50 rounded-2xl border border-yellow-100 animate-fade-in-up mb-6">
                                    <h4 class="text-xs font-bold text-yellow-600 uppercase tracking-widest mb-3">Detail Pelanggan PLN</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Nama Pelanggan</span>
                                            <span class="font-bold text-slate-800" x-text="plnCustomer.name"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Daya / Segment</span>
                                            <span class="font-bold text-slate-800" x-text="plnCustomer.segment_power"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">ID Pelanggan</span>
                                            <span class="font-bold text-slate-800" x-text="plnCustomer.customer_no"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">No. Meter</span>
                                            <span class="font-bold text-slate-800" x-text="plnCustomer.meter_no"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Subscriber ID</span>
                                            <span class="font-bold text-slate-800" x-text="plnCustomer.subscriber_id"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Product Selector -->
                            <template x-if="selectedCategory !== 'Apartemen Transit'">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="product in filteredProducts()" :key="product.id">
                                        <button
                                            @click="selectedProduct = product"
                                            class="p-4 rounded-xl border text-left transition-all duration-200 relative overflow-hidden group"
                                            :class="selectedProduct?.id === product.id
                                                ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg ring-2 ring-indigo-100'
                                                : 'bg-white border-slate-100 hover:border-indigo-300'">
                                            <div class="relative z-10">
                                                <h3 class="font-bold mb-1" :class="selectedProduct?.id === product.id ? 'text-white' : 'text-slate-800'" x-text="product.name"></h3>
                                                <p class="text-xs mb-3" :class="selectedProduct?.id === product.id ? 'text-indigo-100' : 'text-slate-400'" x-text="product.description"></p>
                                                <div class="font-black text-lg" :class="selectedProduct?.id === product.id ? 'text-white' : 'text-indigo-600'" x-text="'Rp ' + formatCurrency(product.price)"></div>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Checkout Action -->
                        <div class="mt-8 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-center sm:text-left">
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Total Bayar</p>
                                <p class="text-3xl font-black text-indigo-600" x-text="'Rp ' + formatCurrency(totalAmount())"></p>
                            </div>
                            <button
                                @click="createTransaction()"
                                :disabled="(!selectedProduct && !billData) || !customerNumber || isProcessing || (selectedCategory === 'Token PLN' && !plnCustomer)"
                                class="w-full sm:w-auto px-12 py-4 rounded-2xl font-black text-lg transition-all"
                                :class="(!selectedProduct && !billData) || !customerNumber || isProcessing || (selectedCategory === 'Token PLN' && !plnCustomer)
                                    ? 'bg-slate-100 text-slate-300 cursor-not-allowed'
                                    : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-xl shadow-indigo-200 active:scale-95'">
                                BAYAR SEKARANG
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: QRIS Payment -->
                    <div x-show="step === 2" class="text-center">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Scan QRIS Untuk Membayar</h3>
                            <p class="text-slate-500 text-sm">Selesaikan pembayaran dalam 15:00 menit</p>
                        </div>

                        <div class="bg-white p-4 rounded-2xl border-2 border-slate-100 inline-block mb-6 shadow-sm">
                            <img src="/qris_payment.png" alt="QRIS Code" class="w-64 h-64 mx-auto" />
                            <div class="text-xs text-slate-400 mt-2">PIN: 010101</div>
                        </div>

                        <div class="mb-8">
                            <p class="text-slate-500 text-sm mb-1 uppercase tracking-widest font-bold">Total Pembayaran</p>
                            <p class="text-4xl font-black text-red-600" x-text="'Rp ' + formatCurrency(totalAmount())"></p>
                        </div>

                        <div class="max-w-md mx-auto space-y-4">
                            <div class="text-left">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Input Bukti / No. Referensi / RDN</label>
                                <input
                                    type="text"
                                    x-model="paymentProof"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                                    placeholder="Contoh: 129384812">
                            </div>
                            <button
                                @click="submitPayment()"
                                :disabled="!paymentProof"
                                class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                Sudah Bayar
                            </button>
                            <button @click="step = 1" class="text-slate-400 text-sm font-medium hover:text-slate-600">Batalkan Transaksi</button>
                        </div>
                    </div>

                    <!-- Step 3: Processing -->
                    <div x-show="step === 3" class="text-center py-12">
                        <div class="w-20 h-20 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Pembayaran Sedang Diproses</h3>
                        <p class="text-slate-500 mb-8 max-w-md mx-auto">
                            Sistem sedang memverifikasi pembayaran Anda. Mohon tunggu sebentar, halaman ini akan otomatis terupdate.
                        </p>
                        <button
                            @click="checkStatus()"
                            class="px-6 py-2 rounded-lg bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200">
                            Cek Status Manual
                        </button>
                    </div>

                    <!-- Step 4: Success -->
                    <div x-show="step === 4" class="text-center py-12">
                        <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Transaksi Berhasil!</h3>
                        <p class="text-slate-500 mb-8">
                            Terima kasih, transaksi Anda telah berhasil diproses.
                        </p>

                        <div class="bg-slate-50 rounded-xl p-6 max-w-md mx-auto mb-8 border border-slate-200">
                            <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-2">Serial Number / Token</p>
                            <p class="text-2xl font-mono font-bold text-slate-800 select-all" x-text="transaction?.transaction?.sn_token"></p>
                        </div>

                        <div class="flex gap-4 justify-center">
                            <a :href="'/transaction/' + transaction?.transaction?.id + '/receipt'" target="_blank" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50">
                                Cetak Struk
                            </a>
                            <button @click="reset()" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg">
                                Transaksi Baru
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Sidebar / History -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-800 mb-6 flex items-center justify-between">
                    Cek Riwayat
                    <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-1 rounded font-bold">Terbaru</span>
                </h3>

                <div class="space-y-4">
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        Masukkan nomor meter atau ID pelanggan untuk melihat riwayat transaksi Anda.
                    </p>
                    <div class="relative">
                        <input
                            type="text"
                            x-model="searchCustomerNo"
                            @keyup.enter="gotoHistory()"
                            placeholder="Contoh: 3290..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm font-bold">
                    </div>
                    <button
                        @click="gotoHistory()"
                        :disabled="!searchCustomerNo"
                        class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Tampilkan Riwayat
                    </button>
                </div>

                <div class="mt-8 pt-6 border-t border-dashed border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Aman & Terpercaya</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function transactionApp() {
        return {
            step: 1, // 1: Select, 2: QRIS, 3: Processing, 4: Success
            selectedCategory: 'Pulsa',
            customerNumber: '',
            searchCustomerNo: '',
            selectedProduct: null,
            isLoadingBill: false,
            billData: null,
            isProcessing: false,
            transaction: null,
            paymentProof: '',
            pollInterval: null,

            categories: [{
                    id: 'Pulsa',
                    icon: '📱'
                },
                {
                    id: 'Paket Data',
                    icon: '🌐'
                },
                {
                    id: 'Token PLN',
                    icon: '⚡'
                },
                {
                    id: 'PDAM',
                    icon: '💧'
                },
                {
                    id: 'Apartemen Transit',
                    icon: '🏢'
                },
                {
                    id: 'BPJS',
                    icon: '🏥'
                },
                {
                    id: 'Topup Game',
                    icon: '🎮'
                },
            ],

            products: [{
                    id: 'p1',
                    name: 'Pulsa 5.000',
                    price: 6200,
                    description: 'Masa aktif +7 hari',
                    category: 'Pulsa'
                },
                {
                    id: 'p2',
                    name: 'Pulsa 10.000',
                    price: 11200,
                    description: 'Masa aktif +15 hari',
                    category: 'Pulsa'
                },
                {
                    id: 'p3',
                    name: 'Pulsa 20.000',
                    price: 21200,
                    description: 'Masa aktif +30 hari',
                    category: 'Pulsa'
                },
                {
                    id: 'p4',
                    name: 'Pulsa 50.000',
                    price: 51200,
                    description: 'Masa aktif +45 hari',
                    category: 'Pulsa'
                },
                {
                    id: 'd1',
                    name: 'Data 1GB',
                    price: 10000,
                    description: '30 Hari',
                    category: 'Paket Data'
                },
                {
                    id: 'd2',
                    name: 'Data 5GB',
                    price: 45000,
                    description: '30 Hari',
                    category: 'Paket Data'
                },
                {
                    id: 'pln1',
                    name: 'Token PLN 20.000',
                    price: 23500,
                    description: 'Token Listrik Prabayar',
                    category: 'Token PLN'
                },
                {
                    id: 'pln2',
                    name: 'Token PLN 50.000',
                    price: 53500,
                    description: 'Token Listrik Prabayar',
                    category: 'Token PLN'
                },
                {
                    id: 'pln3',
                    name: 'Token PLN 100.000',
                    price: 103500,
                    description: 'Token Listrik Prabayar',
                    category: 'Token PLN'
                },
                {
                    id: 'pln4',
                    name: 'Token PLN 200.000',
                    price: 203500,
                    description: 'Token Listrik Prabayar',
                    category: 'Token PLN'
                },
                {
                    id: 'pln5',
                    name: 'Token PLN 500.000',
                    price: 503500,
                    description: 'Token Listrik Prabayar',
                    category: 'Token PLN'
                },
                {
                    id: 'pln6',
                    name: 'Token PLN 1.000.000',
                    price: 1003500,
                    description: 'Token Listrik Prabayar',
                    category: 'Token PLN'
                },
                {
                    id: 'pln7',
                    name: 'Token PLN 5.000.000',
                    price: 5003500,
                    description: 'Token Listrik Prabayar',
                    category: 'Token PLN'
                },
            ],

            plnCustomer: null,

            // ... (helper methods same as before: selectCategory, filteredProducts, totalAmount, getLabel, getPlaceholder, formatCurrency) ...
            selectCategory(id) {
                this.selectedCategory = id;
                this.selectedProduct = null;
                this.billData = null;
                this.plnCustomer = null;
                this.customerNumber = '';
            },

            filteredProducts() {
                return this.products.filter(p => p.category === this.selectedCategory);
            },

            totalAmount() {
                if (this.billData) {
                    return this.billData.amount + this.billData.adminFee;
                }
                return this.selectedProduct ? this.selectedProduct.price : 0;
            },

            getLabel() {
                switch (this.selectedCategory) {
                    case 'Token PLN':
                        return 'Nomor Meter / ID Pelanggan';
                    case 'PDAM':
                        return 'Nomor Pelanggan PDAM';
                    case 'Apartemen Transit':
                        return 'Kode Bayar Apartemen';
                    default:
                        return 'Nomor Handphone';
                }
            },

            getPlaceholder() {
                switch (this.selectedCategory) {
                    case 'Apartemen Transit':
                        return 'Contoh: APT-2024-XXXX';
                    default:
                        return 'Contoh: 08123456789';
                }
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            },

            async checkApartmentBill() {
                if (!this.customerNumber) return;
                this.isLoadingBill = true;
                this.billData = null;
                await new Promise(resolve => setTimeout(resolve, 1500));
                this.billData = {
                    customerName: "Budi Setiawan",
                    unit: "TOWER A - 1205",
                    period: "Maret 2024",
                    amount: 450000,
                    adminFee: 2500
                };
                this.isLoadingBill = false;
            },

            // PLN Inquiry
            async checkPlnCustomer() {
                if (!this.customerNumber) return;
                this.isLoadingBill = true;
                this.plnCustomer = null;
                try {
                    const response = await axios.post('/transaction/inquiry', {
                        customer_no: this.customerNumber
                    });

                    if (response.data.status === 'success') {
                        this.plnCustomer = response.data;
                    } else {
                        alert('ID Pelanggan tidak ditemukan!');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Gagal mengecek pelanggan.');
                } finally {
                    this.isLoadingBill = false;
                }
            },

            async createTransaction() {
                this.isProcessing = true;
                try {
                    let productName = '';
                    if (this.billData) {
                        productName = `Sewa Apartemen - ${this.billData.period}`;
                    } else if (this.selectedProduct) {
                        productName = this.selectedProduct.name;
                        if (this.plnCustomer) {
                            productName += ` - ${this.plnCustomer.name}`;
                        }
                    } else {
                        productName = 'Unknown Product';
                    }

                    const response = await axios.post('/transaction', {
                        customer_number: this.customerNumber,
                        product_name: productName,
                        amount: this.totalAmount()
                    });

                    this.transaction = response.data;
                    this.step = 2; // Move to QRIS
                } catch (error) {
                    alert('Gagal membuat transaksi. Silakan coba lagi.');
                    console.error(error);
                } finally {
                    this.isProcessing = false;
                }
            },

            async submitPayment() {
                this.isProcessing = true;
                try {
                    const response = await axios.post(`/transaction/${this.transaction.transaction.id}/payment`, {
                        payment_proof: this.paymentProof
                    });
                    this.step = 3; // Move to Processing

                    // Redirect to history
                    if (response.data.redirect_url) {
                        setTimeout(() => {
                            window.location.href = response.data.redirect_url;
                        }, 1500);
                    } else {
                        this.startPolling();
                    }
                } catch (error) {
                    alert('Gagal mengirim bukti pembayaran.');
                } finally {
                    this.isProcessing = false;
                }
            },

            startPolling() {
                this.pollInterval = setInterval(() => {
                    this.checkStatus();
                }, 5000); // Check every 5 seconds
            },

            async checkStatus() {
                try {
                    const response = await axios.get(`/transaction/${this.transaction.transaction.id}`);
                    const status = response.data.transaction.status;

                    if (status === 'success') {
                        this.transaction = response.data;
                        this.step = 4;
                        clearInterval(this.pollInterval);
                    }
                } catch (error) {
                    console.error('Error checking status', error);
                }
            },

            gotoHistory() {
                if (!this.searchCustomerNo) return;
                window.location.href = `/history?customer_no=${this.searchCustomerNo}`;
            },

            reset() {
                this.step = 1;
                this.transaction = null;
                this.paymentProof = '';
                this.selectedProduct = null;
                this.billData = null;
                this.customerNumber = '';
            }
        }
    }
</script>
@endsection