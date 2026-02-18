<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - {{ config('app.name', 'WarungDigi') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons (Heroicons via CDN for simplicity in Blade) -->
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100 text-slate-800">
    <div class="flex h-screen bg-gray-100 font-sans" x-data="{ sidebarOpen: false, processModal: false, selectedTx: null }">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col transition-all duration-300 fixed md:relative z-30 h-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            <div class="h-16 flex items-center justify-center border-b border-slate-800">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-600 bg-clip-text text-transparent">WarungDigi</h1>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 bg-purple-600/20 text-purple-400 rounded-lg border border-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center space-x-3 px-4 py-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
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
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-red-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            <span class="text-sm font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content Scrollable -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Stat Cards -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Total Transaksi</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ count($transactions) }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Pending / Processing</h3>
                        <p class="text-3xl font-bold text-orange-600">{{ $transactions->whereIn('status', ['pending', 'processing'])->count() }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Success</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $transactions->where('status', 'success')->count() }}</p>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                        <button onclick="window.location.reload()" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold">Refresh</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-900 font-semibold border-b">
                                <tr>
                                    <th class="p-4">Date</th>
                                    <th class="p-4">Customer</th>
                                    <th class="p-4">Product</th>
                                    <th class="p-4">Amount</th>
                                    <th class="p-4">Payment Proof</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($transactions as $tx)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4 whitespace-nowrap">{{ $tx->created_at->format('d M H:i') }}</td>
                                    <td class="p-4 font-mono font-bold">{{ $tx->customer_number }}</td>
                                    <td class="p-4">{{ $tx->product_name }}</td>
                                    <td class="p-4 font-bold">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                    <td class="p-4 font-mono text-xs">{{ $tx->payment_proof ?? '-' }}</td>
                                    <td class="p-4">
                                        @if($tx->status == 'success')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">SUCCESS</span>
                                        @elseif($tx->status == 'processing')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-600 animate-pulse">PROCESSING</span>
                                        @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">PENDING</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        @if($tx->status == 'processing')
                                        <button
                                            @click="processModal = true; selectedTx = {{ $tx }}"
                                            class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-sm transition-all">
                                            Proses
                                        </button>
                                        @elseif($tx->status == 'success')
                                        <div class="flex flex-col gap-1">
                                            <span class="font-mono text-xs text-slate-400">{{ $tx->sn_token }}</span>
                                            <a href="{{ route('transaction.receipt.pos', $tx->id) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318-4.171a42.414 42.414 0 01-10.56 0m11.536 0c.208.019.416.04.624.062a1.125 1.125 0 01.99 1.124v4.5a1.125 1.125 0 01-1.125 1.125h-13.5A1.125 1.125 0 012.25 19.5v-4.5a1.125 1.125 0 01.99-1.124l.624-.062m15.75 0a1.125 1.125 0 00-1.12-1.227h-1.318c-.31 0-.54-.265-.54-.57l.084-2.292a.75.75 0 00-.75-.776h-8.25a.75.75 0 00-.75.776l.084 2.292c0 .305-.23.57-.54.57H5.62a1.125 1.125 0 00-1.12 1.227L4.47 13.5" />
                                                </svg>
                                                Print POS
                                            </a>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($transactions->isEmpty())
                        <div class="p-8 text-center text-gray-500">Belum ada transaksi</div>
                        @endif
                    </div>
                </div>

                <!-- Process Modal -->
                <div x-show="processModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="processModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="processModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Proses Transaksi
                                </h3>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Customer: <span class="font-bold text-gray-900" x-text="selectedTx?.customer_number"></span></p>
                                    <p class="text-sm text-gray-500">Product: <span class="font-bold text-gray-900" x-text="selectedTx?.product_name"></span></p>
                                    <p class="text-sm text-gray-500">Proof: <span class="font-mono text-gray-900 bg-gray-100 px-1" x-text="selectedTx?.payment_proof"></span></p>
                                </div>
                                <form :action="'/transaction/' + selectedTx?.id + '/token'" method="POST" id="processForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label for="sn_token" class="block text-sm font-medium text-gray-700">Input SN / Token</label>
                                            <input type="text" name="sn_token" id="sn_token" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border" placeholder="XXXX-XXXX-XXXX-XXXX" required>
                                        </div>
                                        <div>
                                            <label for="kwh" class="block text-sm font-medium text-gray-700">Jumlah KWh</label>
                                            <input type="number" step="0.01" name="kwh" id="kwh" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border" placeholder="Contoh: 294.2">
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                            Kirim Token
                                        </button>
                                        <button type="button" @click="processModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>

</html>