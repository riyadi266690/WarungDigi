@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-slate-900 p-8 text-white flex justify-between items-center relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Riwayat Transaksi</h1>
                @if(request('customer_no'))
                <p class="text-slate-400">ID Pelanggan: <span class="font-mono text-white font-bold">{{ request('customer_no') }}</span></p>
                @endif
            </div>
            <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-600 rounded-full blur-3xl opacity-20 -mr-16 -mt-16 pointer-events-none"></div>
            <a href="/" class="relative z-10 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all backdrop-blur-sm">
                &larr; Kembali
            </a>
        </div>

        <div class="p-8">
            @if($transactions->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat</h3>
                <p class="text-slate-500">Belum ada transaksi yang tercatat untuk nomor ini.</p>
            </div>
            @else
            <div class="space-y-4">
                @foreach($transactions as $tx)
                <div class="bg-white border hover:border-indigo-300 transition-all rounded-xl p-5 flex flex-col md:flex-row items-center justify-between gap-4 group">
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg
                                    {{ $tx->status == 'success' ? 'bg-green-100 text-green-600' : 
                                       ($tx->status == 'processing' ? 'bg-yellow-100 text-yellow-600' : 'bg-slate-100 text-slate-500') }}">
                            @if($tx->status == 'success') ✓ @elseif($tx->status == 'processing') ⏳ @else • @endif
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $tx->product_name }}</p>
                            <p class="text-xs text-slate-500">{{ $tx->created_at->format('d M Y • H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-end md:items-center gap-4 w-full md:w-auto text-right md:text-left">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold">Total</p>
                            <p class="font-black text-slate-800">Rp {{ number_format($tx->amount, 0, ',', '.') }}</p>
                        </div>

                        @if($tx->status == 'success')
                        <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 text-right">
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Token / SN</p>
                            <p class="font-mono font-bold text-indigo-600 select-all">{{ $tx->sn_token }}</p>
                        </div>
                        <a href="{{ route('transaction.receipt', $tx->id) }}" target="_blank" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors" title="Cetak Struk">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                            </svg>
                        </a>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ $tx->status == 'processing' ? 'bg-yellow-100 text-yellow-600' : 'bg-slate-100 text-slate-500' }}">
                            {{ strtoupper($tx->status) }}
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection