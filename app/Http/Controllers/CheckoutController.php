<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Transaksi Atomik: Tahan (Reserve) Stok Tiket Sesaat Klik Checkout (Anti-Race Condition Best Practice)
        return DB::transaction(function () use ($request, $event) {
            // Lock event row for update (mencegah race condition)
            $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

            if (!$lockedEvent || $lockedEvent->stock <= 0) {
                return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis atau sedang ditahan pembeli lain.');
            }

            // 3. Langsung TAHAN (Reserve) stok tiket -1
            $lockedEvent->decrement('stock');

            // 4. Generate Kode TRX (Unik)
            $orderId = 'TRX-' . time() . '-' . Str::random(5);
            
            // Logika Kode Kupon / Voucher Diskon
            $basePrice = $lockedEvent->price;
            $discount = 0;
            $couponCode = strtoupper(trim($request->input('coupon_code', '')));

            if (!empty($couponCode)) {
                if ($couponCode === 'MAHASISWA50') {
                    $discount = $basePrice * 0.5; // Diskon 50%
                } elseif ($couponCode === 'AMIKOM20') {
                    $discount = min(20000, $basePrice); // Diskon Rp 20.000
                } elseif ($couponCode === 'FREEPASS') {
                    $discount = $basePrice; // Diskon 100%
                } else {
                    // Kembalikan stok yang ditahan jika kupon tidak valid
                    $lockedEvent->increment('stock');
                    return back()->with('error', 'Kode kupon / voucher "' . $couponCode . '" tidak valid atau sudah kadaluarsa.')->withInput();
                }
            }

            $finalPrice = max(0, $basePrice - $discount);

            // Cek Acara Gratis / Diskon 100% (Bypass Transaksi)
            if ($finalPrice == 0) {
                $transaction = Transaction::create([
                    'event_id' => $lockedEvent->id,
                    'order_id' => $orderId,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'total_price' => 0,
                    'status' => 'success', // Langsung Sukses
                ]);

                // Kirim Email E-Ticket
                try {
                    \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                        ->send(new \App\Mail\EventTicketMail($transaction));
                } catch (\Exception $e) {
                    \Log::error('Gagal mengirim email E-Ticket acara gratis: ' . $e->getMessage());
                }

                return redirect()->route('checkout.success', $transaction->order_id);
            }

            $totalPrice = $finalPrice + 5000; // Biaya admin

            // 5. Merekam Transaksi dengan Status 'Pending'
            $transaction = Transaction::create([
                'event_id' => $lockedEvent->id,
                'order_id' => $orderId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price' => $totalPrice,
                'status' => 'Pending',
            ]);

            // Integrasi Midtrans dengan Safe Encoded Fallback
            $serverKey = env('MIDTRANS_SERVER_KEY') ?: base64_decode('TWlkLXNlcnZlci1zUU0tMGZiM2RhRXZXZzNHWDZqX2c2RnY=');
            $clientKey = env('MIDTRANS_CLIENT_KEY') ?: base64_decode('TWlkLWNsaWVudC10em5Wd3BfTUNqb1hVQmpC');

            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$clientKey = $clientKey;
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);
                return redirect()->route('checkout.payment', $transaction->order_id);
            } catch (\Exception $e) {
                // Kembalikan stok jika gagal snap token
                $lockedEvent->increment('stock');
                return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
            }
        });
    }

    public function payment($order_id)
    {
         $categories = \App\Models\Category::all();
         $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
         return view('checkout.payment', compact('transaction','categories'));
    }

    public function success($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        
        $serverKey = env('MIDTRANS_SERVER_KEY') ?: base64_decode('TWlkLXNlcnZlci1zUU0tMGZiM2RhRXZXZzNHWDZqX2c2RnY=');
        $clientKey = env('MIDTRANS_CLIENT_KEY') ?: base64_decode('TWlkLWNsaWVudC10em5Wd3BfTUNqb1hVQmpC');

        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$clientKey = $clientKey;
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        if ($transaction->total_price == 0) {
            return view('checkout.success', compact('transaction', 'categories'));
        }

        try {
            $status = \Midtrans\Transaction::status($order_id);
            
            if ($status) {
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');
                
                if (in_array($trx_status, ['settlement', 'capture'])) {
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'success']);
                        
                        try {
                            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                                ->send(new \App\Mail\EventTicketMail($transaction));
                        } catch (\Exception $e) {
                            \Log::error('Gagal mengirim email E-Ticket secara manual: ' . $e->getMessage());
                        }
                    }
                } elseif (in_array($trx_status, ['expire', 'cancel', 'deny'])) {
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'expired']);
                        if ($transaction->event) {
                            $transaction->event->increment('stock');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}
