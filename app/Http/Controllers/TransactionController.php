<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected $digiflazzService;

    public function __construct(\App\Services\DigiflazzService $digiflazzService)
    {
        $this->digiflazzService = $digiflazzService;
    }

    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('dashboard', compact('transactions'));
    }

    public function inquiry(Request $request)
    {
        $request->validate(['customer_no' => 'required']);
        $result = $this->digiflazzService->inquiryPln($request->customer_no);
        return response()->json($result);
    }

    public function history(Request $request)
    {
        $query = Transaction::latest();
        if ($request->has('customer_no')) {
            $query->where('customer_number', $request->customer_no);
        }
        $transactions = $query->get();
        return view('history', compact('transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_number' => 'required',
            'product_name' => 'required',
            'amount' => 'required|numeric',
        ]);

        $transaction = Transaction::create([
            'customer_number' => $validated['customer_number'],
            'product_name' => $validated['product_name'],
            'amount' => $validated['amount'],
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'transaction' => $transaction
        ]);
    }

    public function show(Transaction $transaction)
    {
        return response()->json([
            'status' => 'success',
            'transaction' => $transaction
        ]);
    }

    public function updatePayment(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'payment_proof' => 'required|string',
        ]);

        $transaction->update([
            'payment_proof' => $validated['payment_proof'],
            'status' => 'processing',
        ]);

        return response()->json([
            'status' => 'success',
            'redirect_url' => route('history', ['customer_no' => $transaction->customer_number]),
            'transaction' => $transaction
        ]);
    }

    public function updateToken(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'sn_token' => 'required|string',
            'kwh' => 'nullable|numeric',
        ]);

        $transaction->update([
            'sn_token' => $validated['sn_token'],
            'kwh' => $validated['kwh'],
            'status' => 'success',
        ]);

        return back()->with('success', 'Transaksi berhasil diproses!');
    }

    public function receipt(Transaction $transaction)
    {
        $customer = \App\Models\Customer::where('customer_no', $transaction->customer_number)->first();
        return view('receipt_pos', compact('transaction', 'customer'));
    }

    public function receiptPos(Transaction $transaction)
    {
        $customer = \App\Models\Customer::where('customer_no', $transaction->customer_number)->first();
        return view('receipt_pos', compact('transaction', 'customer'));
    }
}
