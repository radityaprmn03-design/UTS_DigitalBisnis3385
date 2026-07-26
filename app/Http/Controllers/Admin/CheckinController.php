<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index()
    {
        return view('admin.checkin');
    }

    public function process(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $orderId = trim($request->order_id);

        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return back()->with('error', '❌ KODE TIKET TIDAK DITEMUKAN! Mohon periksa kembali QR / Order ID (' . $orderId . ').');
        }

        $currentStatus = strtolower($transaction->status);

        if ($currentStatus === 'used') {
            return back()->with('error', '⚠️ PERINGATAN DOUBLE ENTRY! Tiket ' . $orderId . ' atas nama ' . $transaction->customer_name . ' SUDAH PERNAH DIGUNAKAN untuk masuk!');
        }

        if (!in_array($currentStatus, ['success', 'settlement'])) {
            return back()->with('error', '⛔ TIKET TIDAK VALID / BELUM LUNAS! Status tiket saat ini: ' . strtoupper($transaction->status));
        }

        // Tandai tiket sebagai sudah digunakan
        $transaction->update(['status' => 'used']);

        return back()->with('success', '✅ CHECK-IN BERHASIL! Tiket atas nama ' . $transaction->customer_name . ' (' . ($transaction->event ? $transaction->event->title : 'Event') . ') Valid. Silakan masuk!');
    }
}
