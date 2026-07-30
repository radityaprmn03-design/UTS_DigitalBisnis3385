<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventCertificateMail;

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

        // 1. Tandai tiket sebagai sudah digunakan
        $transaction->update(['status' => 'used']);

        // 2. Terbitkan & Kirim E-Certificate Kehadiran Otomatis ke Email Peserta
        try {
            Mail::to($transaction->customer_email)->send(new EventCertificateMail($transaction));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim E-Certificate: ' . $e->getMessage());
        }

        return back()->with('success', '✅ CHECK-IN BERHASIL! Tiket atas nama ' . $transaction->customer_name . ' (' . ($transaction->event ? $transaction->event->title : 'Event') . ') Valid. E-Certificate Kehadiran telah dilayangkan ke email peserta!');
    }
}
