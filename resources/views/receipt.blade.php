<!DOCTYPE html>
<html>

<head>
    <title>Struk Transaksi #{{ substr($transaction->id, 0, 8) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
            }

            .receipt {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="receipt bg-white p-8 rounded-none md:rounded-lg shadow-lg max-w-sm w-full font-mono text-sm relative">
        <div class="text-center mb-6 border-b-2 border-dashed border-gray-200 pb-6">
            <h1 class="text-xl font-bold uppercase tracking-wider mb-2">WarungDigi</h1>
            <p class="text-gray-500 text-xs">Jl. Digital No. 1, Jakarta</p>
            <p class="text-gray-500 text-xs">{{ now()->format('d M Y H:i') }}</p>
        </div>

        <div class="space-y-3 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-500">No. Transaksi</span>
                <span class="font-bold">{{ substr($transaction->id, 0, 8) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Customer</span>
                <span class="font-bold">{{ $transaction->customer_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Produk</span>
                <span class="font-bold">{{ $transaction->product_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status</span>
                <span class="font-bold uppercase">{{ $transaction->status }}</span>
            </div>
        </div>

        <div class="border-y-2 border-dashed border-gray-200 py-4 mb-6">
            <div class="flex justify-between items-center text-lg font-bold">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($transaction->sn_token)
        <div class="text-center mb-6 p-4 bg-gray-50 border border-gray-200">
            <p class="text-xs text-gray-500 mb-1">SERIAL NUMBER / TOKEN</p>
            <p class="text-xl font-bold select-all">{{ $transaction->sn_token }}</p>
        </div>
        @endif

        <div class="text-center text-xs text-gray-400">
            <p>Terima kasih atas kepercayaan Anda</p>
            <p>Simpan struk ini sebagai bukti pembayaran yang sah</p>
        </div>

        <div class="mt-8 text-center no-print">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">Print Struk</button>
        </div>
    </div>

</body>

</html>